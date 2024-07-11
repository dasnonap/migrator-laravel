<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\ValueObjects\MigrationData;
use Illuminate\Support\Facades\Cache;

class GetCacheDataAction
{
    function handle(string $migrationId)
    {
        $storedData = Cache::get($migrationId);

        if (empty($storedData)) {
            return null;
        }

        return MigrationData::fromJson($storedData);
    }
}
