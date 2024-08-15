<?php

namespace App\Http\Migration\Controllers;

use App\Domains\Migration\Actions\CollectDatabaseInfoAction;
use App\Domains\Migration\Actions\ImportDatabaseMigrationAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MigrationRecordsController
{
    function __construct(
        public ImportDatabaseMigrationAction $importDatabaseAction,
        public CollectDatabaseInfoAction $collectInfoAction,
    ) {}

    // Handle Create Endpoint
    function create(Request $request)
    {
        $file = $request->file('file');
        $file = $request->file;

        Validator::validate($request->all(), [
            'file' => 'required|file',
        ]);

        // Validate Client MimeType is SQL 
        $clientMimeTypes = ['application/x-sql', 'sql'];
        if (!in_array($file->getClientMimeType(), $clientMimeTypes)) {
            return response()->json(['File type needs to be SQL', 422]);
        }

        $migration = $this->importDatabaseAction->handle($file);

        $migrationInfo = $this->collectInfoAction->handle($migration);

        // Cache Data
        $migration->cache();
        $migrationInfo->cache();

        return response()->json([
            'id' => $migration->uuid,
            'tableInfo' => $migrationInfo->toArray()
        ], 200);
    }
}
