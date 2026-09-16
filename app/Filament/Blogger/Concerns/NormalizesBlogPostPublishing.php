<?php

namespace App\Filament\Blogger\Concerns;

use App\Enums\BlogPostStatus;
use Carbon\Carbon;

trait NormalizesBlogPostPublishing
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function normalizePublishingData(array $data): array
    {
        $status = $data['status'] ?? null;

        if ($status instanceof BlogPostStatus) {
            $status = $status->value;
        }

        if ($status === BlogPostStatus::Published->value && blank($data['published_at'] ?? null)) {
            $data['published_at'] = now();
        }

        if ($status === BlogPostStatus::Scheduled->value && filled($data['published_at'] ?? null)) {
            if (Carbon::parse($data['published_at'])->lte(now())) {
                $data['status'] = BlogPostStatus::Published->value;
            }
        }

        return $data;
    }
}
