<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class CacheData
{
    function __construct()
    {
    }

    function handle($key, $data)
    {
        return Cache::put($key, $data, 600);
    }
}
