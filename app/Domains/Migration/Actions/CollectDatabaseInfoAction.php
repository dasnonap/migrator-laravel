<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\Services\FileReader;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use Ramsey\Collection\Collection;
use App\Domains\Migration\ValueObjects\MigrationTable;
use App\Domains\Migration\ValueObjects\MigrationData;

class CollectDatabaseInfoAction
{
    function __construct()
    {
    }

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

            $tableCollection->add(MigrationTable::from([
                'name' => $table,
                'fileIndex' => $reader->getFilePointerIndex()
            ]));
        }

        $reader->close();

        return MigrationData::from([
            'tablesFound' => $tableCollection->count(),
            'tables' => $tableCollection
        ]);
    }

    function getTable($text)
    {
        if (empty($text) || strpos($text, 'CREATE') === false) {
            return false;
        }

        preg_match("/`(?<table>.*)`/", $text, $matches);

        return $matches['table'];
    }
}
