<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\Exceptions\EmptyFileException;
use App\Domains\Migration\Models\MigrationRecord;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImportDatabaseMigrationAction
{
    function __construct() {}

    function handle(UploadedFile $file)
    {
        if (empty($file)) {
            throw new EmptyFileException("File must be provided.");
        }

        $migrationRecord = new MigrationRecord([]);

        $migrationRecord->save();

        $path = Storage::disk('local')->put('/uploads', $file);

        return ImportedMigration::fromModel($migrationRecord, $path);
    }
}
