<?php

namespace DTL\Filesystem\Domain;

interface Filesystem
{
    public function fileList(): FileList;

    public function move(FilePath $srcLocation, FilePath $destLocation);

    public function remove(FilePath $location);

    public function copy(FilePath $srcLocation, FilePath $destLocation): FileList;

    public function createPath(string $path): FilePath;

    public function writeContents(FilePath $path, string $contents);

    public function getContents(FilePath $path): string;
}
