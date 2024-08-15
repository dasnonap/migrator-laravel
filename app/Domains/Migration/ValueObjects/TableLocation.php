<?php

namespace App\Domains\Migration\ValueObjects;

use Spatie\LaravelData\Data;

class TableLocation extends Data
{
    function __construct(
        public int|null $startByte,
        public int|null $endByte,
    ) {}
}
