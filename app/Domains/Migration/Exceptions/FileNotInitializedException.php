<?php

namespace App\Domains\Migration\Exceptions;

use Exception;
use Throwable;

class FileNotInitializedException extends Exception
{
    function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
