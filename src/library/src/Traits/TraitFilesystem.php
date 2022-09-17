<?php

namespace Library\Traits;

trait TraitFilesystem
{
    public static function checkOrCreate(string $storageDir): bool
    {
        if (is_dir($storageDir)) {
            return true;
        }
        if (! @mkdir($storageDir, 0777, true) && ! is_dir($storageDir)) {
            throw new \RuntimeException(sprintf('[%s] could not be created', $storageDir));
        }
        return true;
    }
}
