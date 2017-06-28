<?php

namespace DTL\Filesystem\Adapter\Composer;

use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use Composer\Autoload\ClassLoader;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;

class ComposerFilesystem extends SimpleFilesystem
{
    public function __construct(FilePath $path, ClassLoader $classLoader)
    {
        parent::__construct($path, new ComposerFileListProvider($path, $classLoader));
    }
}
