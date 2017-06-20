<?php

namespace DTL\Filesystem\Domain;

use DTL\Filesystem\Domain\FileLocation;

interface FileFilter
{
    public function accepts(FileLocation $path);
}
