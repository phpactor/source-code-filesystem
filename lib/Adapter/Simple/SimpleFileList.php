<?php

namespace DTL\ClassMover\Adapter\Simple;

use DTL\ClassMover\Finder\FileList;

class SimpleFileList implements FileList, \IteratorAggregate
{
    public function getIterator()
    {
        $directoryIterator = new \RecursiveDirectoryIterator($path->__toString());
        $iteratorIterator = new \RecursiveIteratorIterator($directoryIterator);
        $files = new \RegexIterator($iteratorIterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($files as $file) {
            yield FilePath::fromString($file[0]);
        }
    }
}
