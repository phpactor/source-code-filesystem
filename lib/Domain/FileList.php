<?php

namespace DTL\ClassMover\Finder;

interface FileList extends \Iterator
{
    public function filter(FileFilter $filter);
}
