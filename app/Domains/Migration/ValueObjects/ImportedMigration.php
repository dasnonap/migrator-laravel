<?php

namespace App\Domains\Migration\ValueObjects;

use App\Domains\Migration\Models\MigrationRecord;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ImportedMigration extends Data
{
    function __construct(
        public string $uuid,
        public string $filePath,
        public Carbon $timestamp
    ) {
    }

    static function fromModel(MigrationRecord $record, string $path)
    {
        return new self($record->id, $path, $record->created_at);
    }
}
