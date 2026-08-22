<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ClaimTableRequest;
use App\Http\Requests\Api\V1\JoinTableRequest;
use App\Http\Resources\Api\V1\TableScanResource;
use App\Http\Resources\Api\V1\VisitResource;
use App\Services\TableScanService;
use App\Services\VisitClaimService;
use App\Support\GuestContext;
use App\Support\TableQrToken;
use Illuminate\Http\JsonResponse;

class TableController extends Controller
{
    public function show(string $token, TableScanService $scan): JsonResponse
    {
        return (new TableScanResource($scan->inspect($token)))->response();
    }

    public function claim(ClaimTableRequest $request, string $token, VisitClaimService $claims): JsonResponse
    {
        $table = TableQrToken::resolve($token);

        if (! $table) {
            return response()->json([
                'message' => 'Stiker tidak valid atau sudah diganti. Hubungi kasir.',
                'errors' => ['token' => ['Stiker tidak valid.']],
            ], 422);
        }

        $visit = $claims->claim(
            $table,
            (string) GuestContext::deviceToken(),
            (string) $request->validated('customer_wa'),
            $request->validated('customer_name'),
            (string) $request->userAgent(),
        );

        return (new VisitResource($visit))->response()->setStatusCode(201);
    }

    public function join(JoinTableRequest $request, string $token, VisitClaimService $claims): JsonResponse
    {
        $table = TableQrToken::resolve($token);

        if (! $table) {
            return response()->json([
                'message' => 'Stiker tidak valid atau sudah diganti. Hubungi kasir.',
                'errors' => ['token' => ['Stiker tidak valid.']],
            ], 422);
        }

        $visit = $claims->join(
            $table,
            (string) GuestContext::deviceToken(),
            (string) $request->validated('pin'),
            (string) $request->userAgent(),
        );

        return (new VisitResource($visit))->response();
    }
}
