<?php

namespace App\Helpers\System;

use Illuminate\Support\Facades\Cache;

class MrCacheHelper extends Cache
{
    /**
     * Load objects array
     */
    public static function getCachedObjectList(string $key, string $className, callable $ids): array
    {
        $ids = Cache::rememberForever($key, function () use ($ids) {
            return $ids();
        });

        $out = [];

        $objectName = $className;

        foreach (array_chunk($ids, 500) as $idsChunk) {
            /** @var object $objectName */
            $collections = $objectName::whereIn('id', $idsChunk)->get();
            foreach ($collections as $item) {
                $out[] = $item;
            }
        }

        return $out;
    }

    public static function setCachedData(string $key, $value): void
    {
        Cache::rememberForever($key, function () use ($value) {
            return $value;
        });
    }

    public static function getCachedData(string $key, callable $object)
    {
        return Cache::rememberForever($key, function () use ($object) {
            return $object();
        });
    }
}
