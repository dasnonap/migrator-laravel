<?php

namespace App\Domains\Migration\Actions;

use App\Domains\Migration\Services\FileReader;
use App\Domains\Migration\Services\FileWriter;
use App\Domains\Migration\ValueObjects\ImportedMigration;
use Carbon\Carbon;
use LogicException;
use Illuminate\Support\Facades\Storage;

class SearchReplaceAction
{
    function __construct()
    {
    }

    function handle(
        ImportedMigration $migration,
        string $search,
        string $replace
    ) {
        if (empty($migration)) {
            throw new LogicException("Migration data not found.");
        }

        $readerHandler = new FileReader($migration->filePath);
        $migrationName = 'uploads/migration_' . $migration->uuid . '.txt';
        Storage::disk('local')->put($migrationName, "");
        $writerHandler = new FileWriter($migrationName);

        $readerHandler->open();
        $writerHandler->open();

        while ($line = $readerHandler->nextLine()) {
            $writerHandler->writeLine($this->searchAndReplace($line, $search, $replace));
        }

        $writerHandler->close();
        $readerHandler->close();

        return ImportedMigration::from([
            'uuid' => $migration->uuid,
            'filePath' => $migrationName,
            'timestamp' => Carbon::now()
        ]);
    }

    function searchAndReplace($line, $search, $replace)
    {
        if (empty($line)) {
            return $line;
        }

        return preg_replace("/$search/", $replace, $line);
    }
}
