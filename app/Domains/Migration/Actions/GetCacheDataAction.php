<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\ValueObjects\ImportedMigration;
use App\Domains\Migration\ValueObjects\MigrationData;
use Illuminate\Support\Facades\Cache;

class GetCacheDataAction
{
    function handle(string $migrationId)
    {
        $migration = Cache::get($migrationId);
        $migrationData = Cache::get($migrationId . '_data');

        if (empty($migrationData)) {
            return null;
        }

        return [
            'migration' => ImportedMigration::fromJson($migration),
            'migrationData'  => MigrationData::fromJson($migrationData)
        ];
    }
}
