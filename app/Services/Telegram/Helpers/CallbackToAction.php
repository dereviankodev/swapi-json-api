<?php

namespace App\Services\Telegram\Helpers;

use Illuminate\Support\Str;

class CallbackToAction
{
    private const ACTION_INDEX = 'Index';
    private const ACTION_READ = 'Read';

    public static function getAction(string $callbackQuery): string
    {
        $parseUrl = parse_url($callbackQuery);
        $path = Str::of($parseUrl['path'])->trim('/');
        $pathCollection = Str::of($path)->explode('/');

        return $pathCollection->count() === 1
            ? static::ACTION_INDEX
            : static::ACTION_READ;
    }
}