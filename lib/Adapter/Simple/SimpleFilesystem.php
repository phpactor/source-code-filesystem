<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Adapter\Simple\SimpleFileIterator;
use Webmozart\PathUtil\Path;

class SimpleFilesystem implements Filesystem
{
    private $path;

    public function __construct(FilePath $path)
    {
        $this->path = $path;
    }

    public function fileList(): FileList
    {
        return FileList::fromIterator(
            $this->createFileIterator(
                (string) $this->path
            )
        );
    }

    public function remove(FilePath $path)
    {
        unlink($path->path());
    }

    protected function createFileIterator(): \Iterator
    {
        $files = new \RecursiveDirectoryIterator($this->path->path());
        $files = new \RecursiveIteratorIterator($files);
        $files = new \CallbackFilterIterator($files, function ($file) {
            return $file->isFile();
        });

        return $files;
    }

    public function move(FilePath $srcLocation, FilePath $destLocation)
    {
        rename(
            $srcLocation->path(),
            $destLocation->path()
        );
    }

    public function copy(FilePath $srcLocation, FilePath $destLocation)
    {
        copy(
            $srcLocation->path(),
            $destLocation->path()
        );
    }

    public function createPath(string $path): FilePath
    {
        if (Path::isRelative($path)) {
            return FilePath::fromParts([$this->path->path(), $path]);
        }

        return FilePath::fromString($path);
    }

    public function getContents(FilePath $path): string
    {
        return file_get_contents($path->path());
    }

    public function writeContents(FilePath $path, string $contents)
    {
        file_put_contents($path->path(), $contents);
    }
}
