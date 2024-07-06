<?php

namespace App\Http\Controllers;

use App\Models\MigrationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MigrationRecordsController extends Controller
{
    //
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

        $filePath = app('App\Actions\Domain\ImportDatabaseMigrationAction')->handle($file);

        dd($filePath);

        // response()->stream(function(){

        // });
    }
}
