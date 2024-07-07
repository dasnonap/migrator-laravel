<?php

namespace App\Domains\Migration\Http\Controllers;

use App\Domains\Migration\Actions\ImportDatabaseMigrationAction;
use App\Domains\Migration\Models\MigrationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MigrationRecordsController
{
    function __construct(public ImportDatabaseMigrationAction $importDatabaseAction)
    {
    }

    // Handle Create Endpoint
    function create(Request $request)
    {
        $file = $request->file('file');
        $file = $request->file;

        Validator::validate($request->all(), [
            'file' => 'required|file|mimes:txt',
        ]);

        // Validate Client MimeType is SQL 
        $clientMimeTypes = ['application/x-sql', 'sql'];
        if (!in_array($file->getClientMimeType(), $clientMimeTypes)) {
            return response()->json(['File type needs to be SQL', 422]);
        }

        $migrationRecord = new MigrationRecord([]);

        $migrationRecord->save();

        $filePath = $this->importDatabaseAction->handle($file);

        dd($filePath);

        // response()->stream(function(){

        // });
    }
}
