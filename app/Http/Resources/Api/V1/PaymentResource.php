<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Payment;
use App\Support\CmsMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Payment */
class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'method' => $this->method,
            'status' => $this->status,
            'amount' => (int) $this->amount,
            'unique_add' => (int) $this->unique_add,
            'qris_image_url' => CmsMedia::url($this->qris_image_path_snapshot),
            'proof_image_url' => CmsMedia::url($this->proof_image_path),
            'gps_status' => $this->gps_status,
            'reject_reason' => $this->reject_reason,
            'awaiting_expires_at' => $this->awaiting_expires_at?->toIso8601String(),
        ];
    }
}
