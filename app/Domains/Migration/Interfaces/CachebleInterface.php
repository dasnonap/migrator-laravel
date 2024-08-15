<?php

namespace App\Domains\Migration\Interfaces;

interface CachebleInterface
{
    /**
     * Method for caching
     */
    public function cache();

    /**
     * Method for building from cacheId
     */
    public static function fromCache(string $cacheId);
}
