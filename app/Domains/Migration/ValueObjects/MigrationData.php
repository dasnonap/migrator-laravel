<?php

namespace App\Domains\Migration\ValueObjects;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

class MigrationData extends Data
{
    function __construct(
        public int $tablesFound,
        public Collection|null $tables,
        public Collection|null $prefixes,
    ) {
    }
}
