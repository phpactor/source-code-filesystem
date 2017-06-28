<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\FileListProvider;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;

final class SimpleFileListProvider implements FileListProvider
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

    private function createFileIterator(string $path): \Iterator
    {
        $path = $path ? $this->path->makeAbsoluteFromString($path) : $this->path->path();
        $files = new \RecursiveDirectoryIterator($path);
        $files = new \RecursiveIteratorIterator($files);

        return $files;
    }
}
