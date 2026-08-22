<?php

namespace App\Livewire\Auth;

use App\Enums\PlanCode;
use App\Enums\SubscriptionStatus;
use App\Models\PlatformSetting;
use App\Models\Restaurant;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Services\RestaurantProvisioner;
use App\Services\SubscriptionPlanSync;
use App\Support\ReservedSlugs;
use App\Support\RestaurantTheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\PermissionRegistrar;

#[Layout('layouts.directory', ['title' => 'Daftarkan restoran'])]
class RegisterRestaurant extends Component
{
    public int $step = 1;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $restaurant_name = '';

    public string $slug = '';

    public string $plan_code = '';

    public function updatedRestaurantName(): void
    {
        if (filled($this->slug)) {
            return;
        }

        $this->slug = str($this->restaurant_name)->slug()->toString();
    }

    public function nextFromAccount(): void
    {
        $this->validate($this->accountRules());
        $this->step = 2;
    }

    public function nextFromRestaurant(): void
    {
        $this->validate($this->restaurantRules());

        if (blank($this->plan_code)) {
            $this->plan_code = (string) (SubscriptionPlan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->value('code') ?? '');
        }

        $this->step = 3;
    }

    public function selectPlan(string $planCode): void
    {
        $this->plan_code = $planCode;
    }

    public function back(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function register(RestaurantProvisioner $provisioner, SubscriptionPlanSync $planSync)
    {
        $this->validate([
            ...$this->accountRules(),
            ...$this->restaurantRules(),
            ...$this->planRules(),
        ]);

        $restaurant = DB::transaction(function () use ($provisioner, $planSync): Restaurant {
            $user = User::query()->create([
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->uniqueUsername(),
                'password' => $this->password,
                'is_active' => true,
            ]);

            $restaurant = Restaurant::query()->create([
                'name' => $this->restaurant_name,
                'slug' => $this->slug,
                'timezone' => 'Asia/Jakarta',
                'currency' => 'IDR',
                'is_active' => true,
                'listed_in_directory' => true,
                'landing_enabled' => true,
                'plan_code' => $this->plan_code,
                'subscription_status' => SubscriptionStatus::Trial,
                'trial_ends_at' => now()->addDays(PlatformSetting::trialDays()),
            ]);

            $restaurant->users()->attach($user->id, ['is_active' => true]);

            $provisioner->provision($restaurant, $this->plan_code);
            $planSync->syncOwnerPermissions($restaurant, $this->plan_code);

            $outlet = $restaurant->fresh()->defaultOutlet;
            if ($outlet) {
                DB::table('outlet_users')->insert([
                    'restaurant_id' => $restaurant->id,
                    'outlet_id' => $outlet->id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                ]);
            }

            $registrar = app(PermissionRegistrar::class);
            $previousTeamId = $registrar->getPermissionsTeamId();
            $registrar->setPermissionsTeamId($restaurant->id);

            try {
                $user->assignRole('owner');
            } finally {
                $registrar->setPermissionsTeamId($previousTeamId);
            }

            Auth::login($user);

            return $restaurant;
        });

        return redirect()->to(url('/admin/'.$restaurant->slug));
    }

    public function render()
    {
        return view('livewire.auth.register-restaurant', [
            'plans' => SubscriptionPlan::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'theme' => RestaurantTheme::for(null),
            'trialDays' => PlatformSetting::trialDays(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function accountRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function restaurantRules(): array
    {
        return [
            'restaurant_name' => ['required', 'string', 'max:120'],
            'slug' => [
                'required',
                'string',
                'max:80',
                'alpha_dash',
                'unique:restaurants,slug',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (ReservedSlugs::isReserved((string) $value)) {
                        $fail('Slug ini tidak bisa dipakai.');
                    }
                },
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function planRules(): array
    {
        return [
            'plan_code' => ['required', Rule::in(PlanCode::values())],
        ];
    }

    private function uniqueUsername(): string
    {
        $base = str($this->email)->before('@')->slug('_')->limit(40, '')->toString() ?: 'owner';
        $username = $base;
        $i = 1;

        while (User::query()->where('username', $username)->exists()) {
            $username = $base.$i;
            $i++;
        }

        return $username;
    }
}
