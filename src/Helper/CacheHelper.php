<?php

namespace Pantono\Cache\Helper;

class CacheHelper
{
    public static function cleanCacheKey(string $key): string
    {
        if (strlen($key) > 64) {
            $key = substr($key, 0, 64);
        }
        $key = preg_replace('/[^A-Za-z0-9._]/', '_', $key);
        if (preg_match('/^[0-9.]/', $key)) {
            $key = '_' . $key;
        }
        return preg_replace('/_+/', '_', $key);
    }
}
