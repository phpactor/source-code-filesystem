<?php

namespace Phpactor\Filesystem\Domain;

interface Filesystem
{
    public function fileList(): FileList;

    public function move(FilePath $srcLocation, FilePath $destLocation);

    public function remove(FilePath $location);

    public function copy(FilePath $srcLocation, FilePath $destLocation): CopyReport;

    public function createPath(string $path): FilePath;

    public function writeContents(FilePath $path, string $contents);

    public function getContents(FilePath $path): string;
}
