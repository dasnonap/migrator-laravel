<?php

namespace App\Actions\Domain;

use App\Exceptions\EmptyFileException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImportDatabaseMigrationAction
{
    function __construct()
    {
    }

    function handle(UploadedFile $file)
    {
        if (empty($file)) {
            throw new EmptyFileException("File must be provided.");
        }

        return Storage::disk('local')->put('/uploads', $file);
    }
}
