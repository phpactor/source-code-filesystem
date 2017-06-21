<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Domain\Cwd;

class SimpleFileIterator implements \IteratorAggregate
{
    private $cwd;

    public function __construct(Cwd $cwd)
    {
        $this->cwd = $cwd;
    }

    public function getIterator()
    {
        $files = new \RecursiveDirectoryIterator($this->cwd->__toString());
        $files = new \RecursiveIteratorIterator($files);

        foreach ($files as $file) {
            if (false === $file->isFile()) {
                continue;
            }

            yield $this->cwd->createPathWith((string) $file);
        }
    }
}
