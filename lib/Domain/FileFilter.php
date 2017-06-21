<?php

namespace DTL\Filesystem\Domain;

use DTL\Filesystem\Domain\FilePath;

interface FileFilter
{
    public function accepts(FilePath $path);
}
