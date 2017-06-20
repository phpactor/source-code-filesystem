<?php

namespace DTL\ClassMover\Domain;

use DTL\ClassMover\Finder\FilePath;

interface FileFilter
{
    public function accepts(FilePath $path);
}
