<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\Services\FileReader;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use App\Domains\Migration\ValueObjects\MigrationTable;
use Illuminate\Support\Collection;
use App\Domains\Migration\ValueObjects\MigrationData;
use Illuminate\Support\Str;

class CollectDatabaseInfoAction
{
    function __construct()
    {
    }

    /**
     * Collect all needed data for the migration 
     * @param ImportedMigration $migration instance of recently imported migration
     * @return MigrationData collected data 
     */
    function handle(ImportedMigration $importedMigration)
    {
        $reader = new FileReader($importedMigration->filePath);
        $tableCollection = new Collection();
        $reader->open();

        while ($line = $reader->nextLine()) {
            $table = $this->getTable($line);

            if (empty($table)) {
                continue;
            }

            $tableCollection->add($table);
        }

        $reader->close();

        $migrationData = MigrationData::from([
            'tablesFound' => $tableCollection->count(),
            'tables' => $tableCollection
        ]);

        $migrationData->prefixes = $this->filterPrefixes($migrationData);

        return $migrationData;
    }

    function getTable($text)
    {
        if (empty($text) || strpos($text, 'CREATE') === false) {
            return false;
        }

        preg_match("/`(?<table>.*)`/", $text, $matches);

        return MigrationTable::from([
            'name' => $matches['table'],
            'prefix' => Str::of($matches['table'])->split('/_/')->first()
        ]);
    }

    function filterPrefixes(MigrationData $migration)
    {
        if (empty($migration->tables)) {
            return null;
        }

        // If table count is smaller than 2 -- Skip
        if ($migration->tables->count() <= 2) {
            return null;
        }

        return collect($migration->tables->toArray())->pluck('prefix')->unique();
    }
}
