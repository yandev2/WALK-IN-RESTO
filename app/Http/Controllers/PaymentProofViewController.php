<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Support\GuestContext;
use App\Support\PermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PaymentProofViewController extends Controller
{
    public function __invoke(Request $request, Order $order, Payment $payment): BinaryFileResponse|StreamedResponse
    {
        // 1. Verify that the payment belongs to the specified order
        abort_unless((int) $payment->order_id === (int) $order->id, 404);
        abort_unless(filled($payment->proof_image_path), 404);

        // 2. Authorize requester
        $this->authorizeRequester($request, $order);

        // 3. Locate file on disk (check private 'local' disk first, fallback to legacy 'public' if migrating)
        $path = $payment->proof_image_path;

        // Path must stay under tenant prefix and contain no directory traversal
        $expectedPrefix = 'payment-proofs/'.$order->restaurant_id.'/';
        abort_unless(str_starts_with($path, $expectedPrefix) && ! str_contains($path, '..'), 404);

        $disk = Storage::disk('local');

        if (! $disk->exists($path)) {
            if (Storage::disk('public')->exists($path)) {
                $disk = Storage::disk('public');
            } else {
                abort(404);
            }
        }

        $fullPath = $disk->path($path);
        $mimeType = is_file($fullPath)
            ? (mime_content_type($fullPath) ?: 'application/octet-stream')
            : 'application/octet-stream';

        $response = response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
        ]);

        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    private function authorizeRequester(Request $request, Order $order): void
    {
        // A. Valid Temporary Signed URL
        if ($request->hasValidSignature()) {
            return;
        }

        // B. Active Guest Visit owning the order
        $guestVisit = GuestContext::visit();
        if ($guestVisit && (int) $guestVisit->id === (int) $order->visit_id) {
            return;
        }

        // C. Authenticated Staff / Operator
        $user = $request->user();
        if ($user instanceof User) {
            $restaurant = $order->restaurant;

            if ($restaurant && ($user->isPlatformOperator() || $user->canAccessTenant($restaurant))) {
                app(PermissionRegistrar::class)->setPermissionsTeamId($order->restaurant_id);
                $user->unsetRelation('roles');
                $user->unsetRelation('permissions');

                if (
                    $user->isPlatformOperator()
                    || $user->hasRole('owner')
                    || PermissionCheck::allowsAny($user, ['order.view', 'order.verify_payment', 'receipt.print'])
                ) {
                    return;
                }
            }
        }

        abort(403, 'Akses bukti transfer ditolak.');
    }
}
