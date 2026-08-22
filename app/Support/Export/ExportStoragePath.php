<?php

namespace App\Support\Export;

final class ExportStoragePath
{
    public static function forRestaurant(int $restaurantId, string $fileName): string
    {
        $safeName = ltrim(str_replace('\\', '/', $fileName), '/');

        return 'restaurants/'.$restaurantId.'/export/'.$safeName;
    }
}
