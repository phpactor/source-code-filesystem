<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FileLocation;
use DTL\Filesystem\Domain\AbsoluteExistingPath;

class SimpleFileIterator implements \IteratorAggregate
{
    private $path;

    public function __construct(AbsoluteExistingPath $path)
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

            $path = AbsoluteExistingPath::fromString((string) $file);
            yield $this->path->relativizeToLocation($path);
        }
    }
}
