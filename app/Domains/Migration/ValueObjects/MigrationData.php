<?php

namespace App\Domains\Migration\ValueObjects;

use App\Domains\Migration\Interfaces\CachebleInterface;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use App\Support\CacheData;
use Illuminate\Support\Facades\Cache;

class MigrationData extends Data implements CachebleInterface
{
    function __construct(
        public string $migrationId,
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
            migrationId: $jsonData['migrationId'] ?? null,
            tablesFound: $jsonData['tablesFound'] ?? null,
            tables: collect($jsonData['tables']) ?? null,
            prefixes: collect($jsonData['prefixes']) ?? null
        );
    }

    function cache()
    {
        $cacheId = sprintf('%s_data', $this->migrationId);

        (new CacheData)->handle($cacheId, $this->toJson());
    }

    static function fromCache(string $migrationId)
    {
        $cacheId = sprintf('%s_data', $migrationId);
        $jsonString = Cache::get($cacheId);

        if (empty($jsonString)) {
            return null;
        }

        (new CacheData)->handle($cacheId, $jsonString);

        return self::fromJson($jsonString);
    }
}
