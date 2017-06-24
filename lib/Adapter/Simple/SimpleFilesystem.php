<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Adapter\Simple\SimpleFileIterator;
use DTL\Filesystem\Domain\Cwd;

class SimpleFilesystem implements Filesystem
{
    private $path;

    public function __construct(Cwd $path)
    {
        $this->path = $path;
    }

    public function fileList(): FileList
    {
        return FileList::fromIterator(new SimpleFileIterator($this->path));
    }

    public function remove(FilePath $path)
    {
        unlink($path->absolutePath());
    }

    public function move(FilePath $srcLocation, FilePath $destLocation)
    {
        rename(
            $srcLocation->absolutePath(),
            $destLocation->absolutePath()
        );
    }

    public function copy(FilePath $srcLocation, FilePath $destLocation)
    {
        copy(
            $srcLocation->absolutePath(),
            $destLocation->absolutePath()
        );
    }

    public function createPath(string $path): FilePath
    {
        return FilePath::fromCwdAndPath($this->path, $path);
    }

    public function getContents(FilePath $path): string
    {
        return file_get_contents($path->absolutePath());
    }

    public function writeContents(FilePath $path, string $contents)
    {
        file_put_contents($path->absolutePath(), $contents);
    }
}
