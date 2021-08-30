<?php

namespace App\Services\Telegram\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CallbackToUri
{
    public static function getUri(string $callbackQuery): string
    {
        $parseUrl = parse_url($callbackQuery);
        $path = Str::of($parseUrl['path'])->trim('/');
        $pathCollection = Str::of($path)->explode('/');
        $query = $parseUrl['query'] ?? [];
        empty($query) ?: parse_str($parseUrl['query'], $query);

        if ($pathCollection->count() === 1) {
            return static::getIndexUri($pathCollection, $query);
        }

        return static::getReadUri($pathCollection, $query);
    }

    private static function getIndexUri(Collection $path, array $query = []): string
    {
        return json_api()->url()->index($path->get(0), $query);
    }

    private static function getReadUri(Collection $path, array $query = []): string
    {
        $resourceType = $path->get(0);
        $id = $path->get(1);

        return json_api()->url()->read($resourceType, $id, $query);
    }
}