<?php

namespace App\Support;

use Illuminate\Http\Request;

class VisitorHash
{
    public static function fromRequest(Request $request): string
    {
        $visitorId = $request->session()->get('blog_visitor_id');
        if (filled($visitorId)) {
            return hash('sha256', (string) $visitorId);
        }

        return hash('sha256', implode('|', [
            $request->ip() ?? '',
            $request->userAgent() ?? '',
            $request->session()->getId(),
        ]));
    }
}
