<?php

namespace App\Domains\Migration\ValueObjects;

use Ramsey\Collection\Collection;
use Spatie\LaravelData\Data;

class MigrationData extends Data
{
    function __construct(
        public int $tablesFound,
        public Collection|null $tables,
    ) {
    }
}
