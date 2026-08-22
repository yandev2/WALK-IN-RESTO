<?php

namespace App\Http\Middleware;

use App\Filament\Pages\SubscriptionStatus;
use App\Models\Restaurant;
use App\Models\User;
use App\Support\SubscriptionGate;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Filament::getTenant();
        $user = $request->user();

        if (! $tenant instanceof Restaurant) {
            return $next($request);
        }

        if ($user instanceof User && $user->isPlatformOperator()) {
            return $next($request);
        }

        $gate = app(SubscriptionGate::class);

        if ($gate->canAccessPanel($tenant)) {
            if ($gate->isReadOnly($tenant) && $this->isBlockedMutation($request)) {
                abort(403, 'Masa langganan dalam mode lihat saja. Lunasi invoice untuk mengubah data.');
            }

            return $next($request);
        }

        if ($this->isSubscriptionStatusRequest($request)) {
            return $next($request);
        }

        return redirect()->to(SubscriptionStatus::getUrl(tenant: $tenant));
    }

    private function isSubscriptionStatusRequest(Request $request): bool
    {
        $routeName = (string) $request->route()?->getName();

        if (str_contains($routeName, 'subscription-status')) {
            return true;
        }

        if ($request->is('livewire/*') || str_contains($request->path(), 'livewire')) {
            $payload = json_encode($request->input('components', []));

            return is_string($payload) && str_contains($payload, 'SubscriptionStatus');
        }

        return false;
    }

    private function isBlockedMutation(Request $request): bool
    {
        if (in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
            return false;
        }

        $path = $request->path();

        if (str_contains($path, 'logout') || str_contains($path, 'livewire')) {
            return false;
        }

        if ($this->isSubscriptionStatusRequest($request)) {
            return false;
        }

        return true;
    }
}
