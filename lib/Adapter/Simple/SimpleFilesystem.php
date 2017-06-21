<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FileLocation;
use DTL\Filesystem\Domain\AbsoluteExistingPath;
use DTL\Filesystem\Adapter\Simple\SimpleFileIterator;

class SimpleFilesystem implements Filesystem
{
    private $path;

    public function __construct(AbsoluteExistingPath $path)
    {
        $this->path = $path;
    }

    public function fileList(): FileList
    {
        return FileList::fromIterator(new SimpleFileIterator($this->path));
    }

    public function chdir(FileLocation $location): SimpleFileSystem
    {
        return new self($this->path->concatExistingLocation($location));
    }

    public function remove(FileLocation $location)
    {
        $absolutePath = $this->path->concatExistingLocation($location);
        unlink($absolutePath);
    }

    public function move(FileLocation $srcLocation, FileLocation $destLocation)
    {
        rename(
            $this->path->concatExistingLocation($srcLocation),
            $this->path->concatNonExistingLocation($destLocation)
        );
    }

    public function copy(FileLocation $srcLocation, FileLocation $destLocation)
    {
        copy(
            $this->path->concatExistingLocation($srcLocation),
            $this->path->concatNonExistingLocation($destLocation)
        );
    }

    public function absolutePath(FileLocation $location)
    {
        return $this->path->concatLocation($location);
    }
}
