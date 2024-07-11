<?php

namespace App\Http\Migration\Controllers;

use App\Domains\Migration\Actions\GetCacheDataAction;
use Illuminate\Http\Request;
use LogicException;

class MigrateRecordController
{
    function __construct(
        public GetCacheDataAction $searchAction,
    ) {
    }

    function migrate(Request $request)
    {
        $migrationId = $request->id;

        if (empty($migrationId)) {
            throw new LogicException('Migration ID is empty.');
        }

        $migrationInfo = $this->searchAction->handle($migrationId);

        if (empty($migrationInfo)) {
            response()->json([
                'error' => "Persisted Migration data lost. Please re-upload file."
            ], 500);
        }

        dd($migrationInfo);
    }
}
