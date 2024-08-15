<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\Services\FileReader;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use App\Domains\Migration\ValueObjects\MigrationTable;
use Illuminate\Support\Collection;
use App\Domains\Migration\ValueObjects\MigrationData;
use App\Domains\Migration\ValueObjects\TableLocation;
use Illuminate\Support\Str;

class CollectDatabaseInfoAction
{
    function __construct() {}

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
            $line = trim($line);

            // If Line starts with comment or empty skip
            if (empty($line) || Str::startsWith('/*', $line)) {
                continue;
            }

            if (Str::contains($line, "INSERT INTO")) {
                $lastTable = $tableCollection->pop();
                $lastTable->hasContent = true;
                $tableCollection->push($lastTable);
            }

            $table = $this->getTable($line);

            if (empty($table)) {
                continue;
            }

            $isTableCollected = $tableCollection->contains(function (MigrationTable $collectedTable, int $key) use ($table) {
                return $collectedTable->name === $table->name;
            });

            if (! $isTableCollected) {
                // Collect The begining of the table
                $startIndexLine = $reader->getLineStartIndex();
                $table->location->startByte = $startIndexLine;

                // If the collection is not empty - make the current start line index the endByteIndex of the previous table
                if (! $tableCollection->isEmpty()) {
                    $lastTable = $tableCollection->pop();
                    $lastTable->location->endByte = $startIndexLine;
                    $tableCollection->push($lastTable);
                }

                $tableCollection->add($table);
            }
        }

        $reader->close();

        $migrationData = MigrationData::from([
            'migrationId' => $importedMigration->uuid,
            'tablesFound' => $tableCollection->count(),
            'tables' => $tableCollection
        ]);

        $migrationData->prefixes = $this->filterPrefixes($migrationData);

        return $migrationData;
    }

    function getTable($text)
    {
        $tableName = '';
        $tablePrefix = '';

        switch ($text) {
            case strpos($text, 'Table structure') !== false:
            case strpos($text, 'CREATE TABLE') !== false:
            case strpos($text, 'DROP TABLE') !== false:
                preg_match("/`(?<table>.*)`/", $text, $matches);
                $tableName = $matches['table'];
                $tablePrefix = Str::of($matches['table'])->split('/_/')->first();
                break;

            default:
                return null;
        }

        if (empty($tableName) || empty($tablePrefix)) {
            return null;
        }

        return new MigrationTable(
            name: $tableName,
            prefix: $tablePrefix,
            location: new TableLocation(null, null),
            hasContent: false
        );
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

        return $migration->tables->pluck('prefix')->unique();
    }
}
