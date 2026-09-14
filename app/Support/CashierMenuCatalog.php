<?php

namespace App\Support;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuVariant;
use App\Models\Modifier;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

final class CashierMenuCatalog
{
    public const TTL_SECONDS = 300;

    /**
     * @var array<int, Collection<int, MenuItem>>
     */
    private static array $itemsMemo = [];

    /**
     * @var array<int, array<int, string>>
     */
    private static array $optionsMemo = [];

    /**
     * @var array<int, array<int, string>>
     */
    private static array $modifierOptionsMemo = [];

    /**
     * @var array<int, array<int, string>>
     */
    private static array $variantOptionsMemo = [];

    /**
     * @var array<int, list<array<string, mixed>>>
     */
    private static array $posMemo = [];

    /**
     * @var array<int, list<array{id: int, name: string}>>
     */
    private static array $categoriesMemo = [];

    public static function itemsKey(int $restaurantId): string
    {
        return "cashier-menu:{$restaurantId}:items";
    }

    public static function optionsKey(int $restaurantId): string
    {
        return "cashier-menu:{$restaurantId}:options";
    }

    public static function posKey(int $restaurantId): string
    {
        return "cashier-menu:{$restaurantId}:pos-v2";
    }

    public static function categoriesKey(int $restaurantId): string
    {
        return "cashier-menu:{$restaurantId}:categories";
    }

    public static function forget(?int $restaurantId): void
    {
        if (! $restaurantId) {
            return;
        }

        unset(
            self::$itemsMemo[$restaurantId],
            self::$optionsMemo[$restaurantId],
            self::$posMemo[$restaurantId],
            self::$categoriesMemo[$restaurantId],
        );
        self::$modifierOptionsMemo = [];
        self::$variantOptionsMemo = [];

        Cache::forget(self::itemsKey($restaurantId));
        Cache::forget(self::optionsKey($restaurantId));
        Cache::forget(self::posKey($restaurantId));
        Cache::forget(self::categoriesKey($restaurantId));
    }

    /**
     * @return Collection<int, MenuItem>
     */
    public static function items(?int $restaurantId): Collection
    {
        if (! $restaurantId) {
            return new Collection;
        }

        if (isset(self::$itemsMemo[$restaurantId])) {
            return self::$itemsMemo[$restaurantId];
        }

        /** @var Collection<int, MenuItem> $items */
        $items = Cache::remember(
            self::itemsKey($restaurantId),
            self::TTL_SECONDS,
            fn (): Collection => MenuItem::query()
                ->where('restaurant_id', $restaurantId)
                ->where('is_active', true)
                ->where('is_out_of_stock', false)
                ->whereNotNull('station_id')
                ->orderByLandingPriority()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get([
                    'id',
                    'restaurant_id',
                    'name',
                    'price',
                    'discount_percent',
                    'is_best_seller',
                    'photo_path',
                    'sort_order',
                ]),
        );

        return self::$itemsMemo[$restaurantId] = $items;
    }

    /**
     * @return array<int, string>
     */
    public static function selectOptions(?int $restaurantId): array
    {
        if (! $restaurantId) {
            return [];
        }

        if (isset(self::$optionsMemo[$restaurantId])) {
            return self::$optionsMemo[$restaurantId];
        }

        /** @var array<int, string> $options */
        $options = Cache::remember(
            self::optionsKey($restaurantId),
            self::TTL_SECONDS,
            function () use ($restaurantId): array {
                return self::items($restaurantId)
                    ->mapWithKeys(fn (MenuItem $item): array => [
                        $item->id => view('filament.components.menu-item-select-option', [
                            'item' => $item,
                        ])->render(),
                    ])
                    ->all();
            },
        );

        return self::$optionsMemo[$restaurantId] = $options;
    }

    public static function optionLabel(?int $restaurantId, mixed $value): ?string
    {
        if (blank($value)) {
            return null;
        }

        $id = (int) $value;
        $cached = self::selectOptions($restaurantId)[$id] ?? null;

        if (is_string($cached)) {
            return $cached;
        }

        $item = self::items($restaurantId)->firstWhere('id', $id);

        if (! $item instanceof MenuItem) {
            $item = MenuItem::query()->find($id);
        }

        if (! $item instanceof MenuItem) {
            return null;
        }

        return $item->name.' — '.CmsMedia::formatIdr($item->effectivePrice());
    }

    public static function itemName(?int $restaurantId, mixed $value): string
    {
        if (blank($value)) {
            return 'Item baru';
        }

        $item = self::items($restaurantId)->firstWhere('id', (int) $value);

        return $item instanceof MenuItem ? $item->name : 'Item';
    }

    /**
     * @return array<int, string>
     */
    public static function modifierSelectOptions(mixed $menuItemId): array
    {
        $itemId = (int) $menuItemId;

        if ($itemId < 1) {
            return [];
        }

        if (isset(self::$modifierOptionsMemo[$itemId])) {
            return self::$modifierOptionsMemo[$itemId];
        }

        return self::$modifierOptionsMemo[$itemId] = Modifier::query()
            ->where('is_active', true)
            ->whereHas(
                'group.menuItems',
                fn ($query) => $query->whereKey($itemId),
            )
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (Modifier $modifier): array => [
                $modifier->id => $modifier->name.(
                    $modifier->price
                        ? ' (+'.CmsMedia::formatIdr((int) $modifier->price).')'
                        : ''
                ),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public static function variantSelectOptions(mixed $menuItemId): array
    {
        $itemId = (int) $menuItemId;

        if ($itemId < 1) {
            return [];
        }

        if (isset(self::$variantOptionsMemo[$itemId])) {
            return self::$variantOptionsMemo[$itemId];
        }

        return self::$variantOptionsMemo[$itemId] = MenuVariant::query()
            ->where('menu_item_id', $itemId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(fn (MenuVariant $variant): array => [
                $variant->id => $variant->name.(
                    $variant->price_delta
                        ? ' ('.($variant->price_delta > 0 ? '+' : '').CmsMedia::formatIdr((int) $variant->price_delta).')'
                        : ''
                ),
            ])
            ->all();
    }

    public static function hasVariants(mixed $menuItemId): bool
    {
        $itemId = (int) $menuItemId;

        if ($itemId < 1) {
            return false;
        }

        return self::variantSelectOptions($itemId) !== [];
    }

    /**
     * @return list<array{
     *     id: int,
     *     name: string,
     *     price: int,
     *     effective_price: int,
     *     discount_percent: int,
     *     photo_url: string|null,
     *     category_id: int|null,
     *     has_variants: bool,
     *     variants: list<array{id: int, name: string, price_delta: int, label: string}>,
     *     has_modifiers: bool,
     *     modifiers: list<array{id: int, label: string, price: int}>,
     *     has_options: bool
     * }>
     */
    public static function posPayload(?int $restaurantId): array
    {
        if (! $restaurantId) {
            return [];
        }

        if (isset(self::$posMemo[$restaurantId])) {
            return self::$posMemo[$restaurantId];
        }

        /** @var list<array<string, mixed>> $payload */
        $payload = Cache::remember(
            self::posKey($restaurantId),
            self::TTL_SECONDS,
            function () use ($restaurantId): array {
                return MenuItem::query()
                    ->where('restaurant_id', $restaurantId)
                    ->where('is_active', true)
                    ->where('is_out_of_stock', false)
                    ->whereNotNull('station_id')
                    ->with([
                        'variants' => fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('sort_order'),
                        'modifierGroups.modifiers' => fn ($query) => $query
                            ->where('is_active', true)
                            ->orderBy('sort_order'),
                    ])
                    ->orderByLandingPriority()
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get([
                        'id',
                        'name',
                        'price',
                        'discount_percent',
                        'is_best_seller',
                        'photo_path',
                        'category_id',
                        'sort_order',
                    ])
                    ->map(function (MenuItem $item): array {
                        $variants = $item->variants
                            ->map(fn (MenuVariant $variant): array => [
                                'id' => $variant->id,
                                'name' => $variant->name,
                                'price_delta' => (int) $variant->price_delta,
                                'label' => $variant->name.(
                                    $variant->price_delta
                                        ? ' ('.($variant->price_delta > 0 ? '+' : '').CmsMedia::formatIdr((int) $variant->price_delta).')'
                                        : ''
                                ),
                            ])
                            ->values()
                            ->all();

                        $modifiers = $item->modifierGroups
                            ->flatMap(fn ($group) => $group->modifiers)
                            ->unique('id')
                            ->map(fn (Modifier $modifier): array => [
                                'id' => $modifier->id,
                                'label' => $modifier->name.(
                                    $modifier->price
                                        ? ' (+'.CmsMedia::formatIdr((int) $modifier->price).')'
                                        : ''
                                ),
                                'price' => (int) $modifier->price,
                            ])
                            ->values()
                            ->all();

                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                            'price' => (int) $item->price,
                            'effective_price' => $item->effectivePrice(),
                            'discount_percent' => $item->hasDiscount() ? (int) $item->discount_percent : 0,
                            'is_best_seller' => (bool) $item->is_best_seller,
                            'photo_url' => CmsMedia::url($item->photo_path),
                            'category_id' => $item->category_id ? (int) $item->category_id : null,
                            'has_variants' => $variants !== [],
                            'variants' => $variants,
                            'has_modifiers' => $modifiers !== [],
                            'modifiers' => $modifiers,
                            'has_options' => $variants !== [] || $modifiers !== [],
                        ];
                    })
                    ->values()
                    ->all();
            },
        );

        return self::$posMemo[$restaurantId] = $payload;
    }

    /**
     * @return list<array{id: int, name: string}>
     */
    public static function categories(?int $restaurantId): array
    {
        if (! $restaurantId) {
            return [];
        }

        if (isset(self::$categoriesMemo[$restaurantId])) {
            return self::$categoriesMemo[$restaurantId];
        }

        /** @var list<array{id: int, name: string}> $categories */
        $categories = Cache::remember(
            self::categoriesKey($restaurantId),
            self::TTL_SECONDS,
            function () use ($restaurantId): array {
                return MenuCategory::query()
                    ->where('restaurant_id', $restaurantId)
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get(['id', 'name'])
                    ->map(fn (MenuCategory $category): array => [
                        'id' => $category->id,
                        'name' => $category->name,
                    ])
                    ->all();
            },
        );

        return self::$categoriesMemo[$restaurantId] = $categories;
    }
}
