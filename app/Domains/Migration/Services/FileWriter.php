<?php

namespace App\Domains\Migration\Services;

use Illuminate\Support\Facades\Storage;

class FileWriter
{
    private $handler;
    private $storagePath;
    private $fullFilePath;

    function __construct(
        public string $filePath
    ) {
        $this->storagePath = Storage::disk('local')->path('/');
        $this->fullFilePath = $this->storagePath . $this->filePath;
    }

    function open()
    {
        $this->handler = fopen($this->fullFilePath, 'a+');
    }

    function writeLine($line)
    {
        // dd($line, 'writing');
        return fwrite($this->handler, $line);
    }

    function close()
    {
        fclose($this->handler);
    }
}
