<?php

namespace App\Domains\Migration\ValueObjects;

use Spatie\LaravelData\Data;

class MigrationTable extends Data
{
    function __construct(
        public string $name,
        public int $fileIndex,
    ) {
    }
}
