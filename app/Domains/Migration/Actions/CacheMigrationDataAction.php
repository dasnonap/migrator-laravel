<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\ValueObjects\MigrationData;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use App\Support\CacheData;

class CacheMigrationDataAction
{
    function __construct()
    {
    }

    function handle(
        ImportedMigration $migration,
        MigrationData $migrationData
    ) {
        $cache = new CacheData();

        $cache->handle($migration->uuid, $migration->toJson());

        $cache->handle($migration->uuid . '_data', $migrationData->toJson());
    }
}
