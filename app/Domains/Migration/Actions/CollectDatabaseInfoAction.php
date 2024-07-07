<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\Services\FileReader;
use App\Domains\Migration\ValueObjects\ImportedMigration;

class CollectDatabaseInfoAction
{
    private $disk;

    function __construct()
    {
    }

    function handle(ImportedMigration $importedMigration)
    {
        $reader = new FileReader($importedMigration->filePath);
        $reader->open();

        while ($line = $reader->nextLine()) {
            echo '<pre>';
            dump($line);
            echo '</pre>';
        }

        $reader->close();
    }
}
