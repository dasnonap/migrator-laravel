<?php

namespace App\Domains\Migration\ValueObjects;

use App\Domains\Migration\Interfaces\CachebleInterface;
use App\Domains\Migration\Models\MigrationRecord;
use App\Support\CacheData;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Cache;

class ImportedMigration extends Data implements CachebleInterface
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

    static function fromJson(string $json)
    {
        $jsonData = json_decode($json, true);

        return self::from($jsonData);
    }

    function cache()
    {
        (new CacheData)->handle($this->uuid, $this->toJson());
    }

    static function fromCache(string $cacheId)
    {
        $jsonString = Cache::get($cacheId);

        if (empty($jsonString)) {
            return null;
        }

        (new CacheData)->handle($cacheId, $jsonString);

        return self::fromJson($jsonString);
    }
}
