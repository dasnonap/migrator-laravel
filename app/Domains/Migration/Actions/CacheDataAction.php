<?php

namespace App\Domains\Migration\Actions;

use Illuminate\Support\Facades\Cache;

class CacheDataAction
{
    function handle($key, $data)
    {
        return Cache::put($key, $data, 600);
    }
}
