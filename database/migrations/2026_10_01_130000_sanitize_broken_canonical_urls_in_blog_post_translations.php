<?php

use App\Models\BlogPostTranslation;
use App\Models\PlatformSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $translations = BlogPostTranslation::query()
            ->whereNotNull('canonical_url')
            ->get();

        foreach ($translations as $translation) {
            $raw = trim((string) $translation->canonical_url);
            if (empty($raw)) {
                continue;
            }

            // If the canonical URL is invalid or malformed (e.g. https://citarasakita/blog/...)
            if (! PlatformSetting::isValidExternalCanonical($raw) || str_contains($raw, 'citarasakita/blog')) {
                $translation->updateQuietly(['canonical_url' => null]);
            }
        }
    }

    public function down(): void
    {
        // Data sanitization migration, no rollback needed.
    }
};
