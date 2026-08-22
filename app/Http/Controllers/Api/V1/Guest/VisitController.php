<?php

namespace App\Http\Controllers\Api\V1\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\VisitResource;
use App\Support\GuestContext;
use Illuminate\Http\JsonResponse;

class VisitController extends Controller
{
    public function show(): JsonResponse
    {
        return (new VisitResource(GuestContext::visit()))->response();
    }
}
