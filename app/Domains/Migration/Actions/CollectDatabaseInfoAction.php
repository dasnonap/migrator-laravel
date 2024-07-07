<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\Services\FileReader;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use Ramsey\Collection\Collection;
use App\Domains\Migration\ValueObjects\MigrationTable;
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
        $tableCollection = new Collection(MigrationTable::class);
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

        return $this->filterPrefixes($migrationData);
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
            return;
        }

        $prefixes = $migration->tables->map(function (MigrationTable $table) {
            return $table->prefix;
        });

        if ($prefixes->count() <= 2) {
            return $migration;
        }

        // To DO test with multiple tables
        // logic select unique prefixes only 
        dd($prefixes);
    }
}
