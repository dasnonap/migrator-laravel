<?php

namespace App\Domains\Migration\ValueObjects;

use Spatie\LaravelData\Data;

class MigrationTable extends Data
{
    function __construct(
        // Table Name
        public string $name,
        // Table Prefix
        public string|null $prefix,
        // Table Location indexes see TableLocation
        public TableLocation $location,
        // Whether Table has Content
        public bool $hasContent,
    ) {}
}
