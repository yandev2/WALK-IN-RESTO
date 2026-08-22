<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Support\GuestContext;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class SessionController extends Controller
{
    public function store(): JsonResponse
    {
        $token = GuestContext::deviceToken() ?: Str::random(64);

        return response()->json([
            'data' => [
                'device_token' => $token,
            ],
        ]);
    }
}
