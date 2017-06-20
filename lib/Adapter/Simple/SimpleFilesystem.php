<?php

namespace DTL\Filesystem\SimpleFilesystem;

use DTL\ClassMover\Finder\SearchPath;
use DTL\ClassMover\Finder\FileList;
use DTL\ClassMover\Finder\Finder;
use DTL\ClassMover\Domain\Filesystem;

class SimpleFilesystem implements Filesystem
{
    public function findAll(): FileList
    {
        return new SimpleFileList($this->path);
    }
}
