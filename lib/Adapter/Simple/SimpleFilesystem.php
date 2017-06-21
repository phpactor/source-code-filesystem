<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Adapter\Simple\SimpleFileIterator;

class SimpleFilesystem implements Filesystem
{
    private $path;

    public function __construct(FilePath $path)
    {
        $this->path = $path;
    }

    public function fileList(): FileList
    {
        return FileList::fromIterator(new SimpleFileIterator($this->path));
    }

    public function chdir(FilePath $location): SimpleFileSystem
    {
        return new self($this->path->concatPath($location));
    }

    public function remove(FilePath $location)
    {
        $absolutePath = $this->path->concatPath($location);
        unlink($absolutePath);
    }

    public function move(FilePath $srcLocation, FilePath $destLocation)
    {
        rename(
            $this->path->concatPath($srcLocation),
            $this->path->concatPath($destLocation)
        );
    }

    public function copy(FilePath $srcLocation, FilePath $destLocation)
    {
        copy(
            $this->path->concatPath($srcLocation),
            $this->path->concatPath($destLocation)
        );
    }

    public function absolutePath(FilePath $location)
    {
        return $this->path->concatPath($location);
    }
}
