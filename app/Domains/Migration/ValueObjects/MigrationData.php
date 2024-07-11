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

    /**
     * Initialize using Json data
     * @param string $jsonData 
     * @return MigrationData instance
     */
    static function fromJson(string $jsonData)
    {
        $jsonData = json_decode($jsonData, true);

        return new self(
            tablesFound: $jsonData['tablesFound'] ?? null,
            tables: collect($jsonData['tables']) ?? null,
            prefixes: collect($jsonData['prefixes']) ?? null
        );
    }
}
