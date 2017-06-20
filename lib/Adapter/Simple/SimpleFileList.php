<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FileLocation;
use DTL\Filesystem\Domain\AbsoluteExistingPath;

class SimpleFileList implements FileList
{
    private $path;

    public function __construct(AbsoluteExistingPath $path)
    {
        $this->path = $path;
    }

    public function getIterator()
    {
        $directoryIterator = new \RecursiveDirectoryIterator($this->path->__toString());
        $iteratorIterator = new \RecursiveIteratorIterator($directoryIterator);
        $files = new \RegexIterator($iteratorIterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($files as $file) {
            $path = AbsoluteExistingPath::fromString($file[0]);
            yield $this->path->relativizeToLocation($path);
        }
    }
}
