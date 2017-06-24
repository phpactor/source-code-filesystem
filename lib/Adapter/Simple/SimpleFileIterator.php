<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Domain\Cwd;

class SimpleFileIterator implements \IteratorAggregate
{
    private $path;

    public function __construct(FilePath $path)
    {
        $this->path = $path;
    }

    public function getIterator()
    {
        $files = new \RecursiveDirectoryIterator($this->path->absolutePath());
        $files = new \RecursiveIteratorIterator($files);
        $files = new \CallbackFilterIterator($files, function ($file) {
            return $file->isFile();
        });

        foreach ($files as $file) {
            $path = $this->path->concatPath((string) $file);
            yield $path;
        }
    }
}
