<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Domain\Cwd;

class SimpleFileIterator implements \IteratorAggregate
{
    private $path;

    public function __construct(Cwd $path)
    {
        $this->path = $path;
    }

    public function getIterator()
    {
        $files = new \RecursiveDirectoryIterator($this->path->__toString());
        $files = new \RecursiveIteratorIterator($files);

        foreach ($files as $file) {
            if (false === $file->isFile()) {
                continue;
            }

            $path = FilePath::fromCwdAndPath($this->path, (string) $file);
            yield $this->path->relativize($path);
        }
    }
}
