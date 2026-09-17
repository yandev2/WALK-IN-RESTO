<?php

namespace App\Filament\Blogger\Pages;

use App\Filament\Blogger\Pages\Schemas\BlogHeroFormSchema;
use App\Filament\Concerns\HandlesTranslatableForm;
use App\Filament\Concerns\HasSingletonForm;
use App\Models\BlogHeroSetting;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class ManageBlogHero extends Page
{
    use HandlesTranslatableForm;
    use HasSingletonForm;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Landing Blog';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Pengaturan Landing Blog';

    protected static ?string $slug = 'blog-hero';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user instanceof \App\Models\User && $user->isPlatformOperator();
    }

    protected function resolveRecord(): Model
    {
        return BlogHeroSetting::getSingleton();
    }

    public function form(Schema $schema): Schema
    {
        return BlogHeroFormSchema::configure($schema);
    }

    /**
     * For the blog hero banner, custom text translations are optional.
     * If left blank, the public layout falls back to default app localization strings.
     */
    protected function ensureAtLeastOneTranslation(): void
    {
        // No-op: Allow saving hero banner appearance without requiring custom text translations.
    }
}
