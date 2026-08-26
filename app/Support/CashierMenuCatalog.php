<?php

namespace App\Support;

use App\Models\MenuItem;
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

    public static function itemsKey(int $restaurantId): string
    {
        return "cashier-menu:{$restaurantId}:items";
    }

    public static function optionsKey(int $restaurantId): string
    {
        return "cashier-menu:{$restaurantId}:options";
    }

    public static function forget(?int $restaurantId): void
    {
        if (! $restaurantId) {
            return;
        }

        unset(self::$itemsMemo[$restaurantId], self::$optionsMemo[$restaurantId]);
        self::$modifierOptionsMemo = [];

        Cache::forget(self::itemsKey($restaurantId));
        Cache::forget(self::optionsKey($restaurantId));
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
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get([
                    'id',
                    'restaurant_id',
                    'name',
                    'price',
                    'discount_percent',
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
}
