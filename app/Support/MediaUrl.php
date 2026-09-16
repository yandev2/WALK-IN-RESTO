<?php

namespace App\Support;

class MediaUrl
{
    public static function public(?string $path): ?string
    {
        return CmsMedia::url($path);
    }
}
