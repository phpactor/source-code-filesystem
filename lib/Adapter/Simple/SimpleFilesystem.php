<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;

class SimpleFilesystem implements Filesystem
{
    private $path;

    public function __construct(FilePath $path)
    {
        $this->path = $path;
    }

    public function fileList(): FileList
    {
        return new SimpleFileList($this->path);
    }

    public function move(FilePath $srcPath, FilePath $destPath)
    {
    }
}
