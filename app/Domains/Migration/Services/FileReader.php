<?php

namespace App\Domains\Migration\Services;

use App\Domains\Migration\Exceptions\FileNotInitializedException;
use Illuminate\Support\Facades\Storage;

class FileReader
{
    private $fileHandler;
    private $storagePath;
    private $fullFilePath;

    function __construct(
        public string $filePath
    ) {
        $this->storagePath = Storage::disk('local')->path('/');
        $this->fullFilePath = $this->storagePath . $this->filePath;
    }

    // Open File for manipulation
    function open()
    {
        $this->fileHandler = fopen($this->fullFilePath, 'r');
    }

    // Get next Line
    function nextLine()
    {
        if (empty($this->fileHandler)) {
            throw new FileNotInitializedException("File couldn't be opened properly.");
        }

        return fgets($this->fileHandler);
    }

    // Close file 
    function close()
    {
        fclose($this->fileHandler);
    }
}
