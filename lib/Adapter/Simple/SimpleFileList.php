<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;

class SimpleFileList implements FileList
{
    private $path;

    public function __construct(FilePath $path)
    {
        $this->path = $path;
    }

    public function getIterator()
    {
        $directoryIterator = new \RecursiveDirectoryIterator($this->path->__toString());
        $iteratorIterator = new \RecursiveIteratorIterator($directoryIterator);
        $files = new \RegexIterator($iteratorIterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($files as $file) {
            yield FilePath::fromExistingString($file[0]);
        }
    }
}
