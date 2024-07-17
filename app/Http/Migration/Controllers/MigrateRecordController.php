<?php

namespace App\Http\Migration\Controllers;

use App\Domains\Migration\ValueObjects\ImportedMigration;
use App\Domains\Migration\ValueObjects\MigrationData;
use Illuminate\Http\Request;
use LogicException;

class MigrateRecordController
{
    function __construct()
    {
    }

    function migrate(Request $request)
    {
        $migrationId = $request->id;

        if (empty($migrationId)) {
            throw new LogicException('Migration ID is empty.');
        }

        $migration = ImportedMigration::fromCache($migrationId);
        $migrationData = MigrationData::fromCache($migrationId);

        if (empty($migration) || empty($migrationData)) {
            response()->json([
                'error' => "Persisted Migration data lost. Please re-upload file."
            ], 500);
        }
        dd($migration, $migrationData);
    }
}
