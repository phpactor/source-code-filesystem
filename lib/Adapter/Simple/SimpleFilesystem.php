<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FileLocation;
use DTL\Filesystem\Domain\AbsoluteExistingPath;

class SimpleFilesystem implements Filesystem
{
    private $path;

    public function __construct(AbsoluteExistingPath $path)
    {
        $this->path = $path;
    }

    public function fileList(): FileList
    {
        return new SimpleFileList($this->path);
    }

    public function chdir(FileLocation $location)
    {
        return new self($this->path->concatExistingLocation($location));
    }

    public function remove(FileLocation $location)
    {
        $absolutePath = $this->path->concatExistingLocation($location);
        unlink($absolutePath);
    }

    public function move(FileLocation $srcPath, FileLocation $destPath)
    {
        rename(
            $this->path->concatExistingLocation($srcPath),
            $this->path->concatNonExistingLocation($destPath)
        );
    }
}
