<?php

namespace App\Http\Migration\Controllers;

use App\Domains\Migration\Actions\SearchReplaceAction;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use App\Domains\Migration\ValueObjects\MigrationData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use LogicException;

class MigrateRecordController
{
    function __construct(
        public SearchReplaceAction $searchReplaceAction
    ) {
    }

    function migrate(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'type' => 'required|string'
        ]);
        $migrationId = $request->id;
        $search = $request->search;
        $replace = $request->replace;

        if (empty($migrationId)) {
            throw new LogicException('Migration ID is empty.');
        }

        $migration = ImportedMigration::fromCache($migrationId);
        $migrationData = MigrationData::fromCache($migrationId);

        if (empty($migration) || empty($migrationData)) {
            return response()->json([
                'error' => "Persisted Migration data lost. Please re-upload file."
            ], 422);
        }

        $migratedRecord = $this->searchReplaceAction->handle($migration, $search, $replace);
        return Storage::download($migratedRecord->filePath, 'migration', [
            'Content-Type' => 'application/sql',
        ]);
    }
}
