<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\VisitLog;
use App\Support\VisitorHash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordVisitService
{
    private const DEBOUNCE_MINUTES = 30;

    public function record(Request $request, string $pageKey, ?Model $visitable = null, ?string $locale = null): bool
    {
        if ($this->shouldSkip($request)) {
            return false;
        }

        $path = '/'.ltrim($request->path(), '/');
        $sessionId = $request->session()->getId();
        $visitorHash = VisitorHash::fromRequest($request);

        if ($this->isDebounced($pageKey, $sessionId, $visitorHash, $visitable)) {
            return false;
        }

        DB::transaction(function () use ($request, $pageKey, $visitable, $locale, $path, $sessionId, $visitorHash) {
            VisitLog::query()->create([
                'page_key' => $pageKey,
                'visitable_type' => $visitable?->getMorphClass(),
                'visitable_id' => $visitable?->getKey(),
                'locale' => $locale,
                'path' => $path,
                'ip_address' => $request->ip() ?? '0.0.0.0',
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
                'visitor_hash' => $visitorHash,
                'session_id' => $sessionId,
                'created_at' => now(),
            ]);

            if ($pageKey === 'blog_post' && $visitable instanceof BlogPost) {
                $visitable->increment('views_count');
            }
        });

        return true;
    }

    private function shouldSkip(Request $request): bool
    {
        if (! $request->isMethod('GET')) {
            return true;
        }

        if ($request->is('admin', 'admin/*', 'founder', 'founder/*', 'blogger', 'blogger/*', 'livewire', 'livewire/*')) {
            return true;
        }

        $ua = strtolower($request->userAgent() ?? '');

        foreach (['bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit', 'headlesschrome', 'googlebot'] as $pattern) {
            if (str_contains($ua, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function isDebounced(string $pageKey, string $sessionId, string $visitorHash, ?Model $visitable): bool
    {
        $query = VisitLog::query()
            ->where('page_key', $pageKey)
            ->where(function ($q) use ($sessionId, $visitorHash) {
                if (filled($sessionId)) {
                    $q->where('session_id', $sessionId);
                }
                if (filled($visitorHash)) {
                    $q->orWhere('visitor_hash', $visitorHash);
                }
            })
            ->where('created_at', '>=', now()->subMinutes(self::DEBOUNCE_MINUTES));

        if ($visitable) {
            $query->where('visitable_type', $visitable->getMorphClass())
                ->where('visitable_id', $visitable->getKey());
        } else {
            $query->whereNull('visitable_type')->whereNull('visitable_id');
        }

        return $query->exists();
    }
}
