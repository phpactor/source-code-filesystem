<?php
    ;

namespace DTL\Filesystem\Domain;

use DTL\Filesystem\Domain\FilePath;

interface FilesystemDriver
{
    public function fileList(): FileList;

    public function move(FilePath $srcLocation, FilePath $destLocation);

    public function remove(FilePath $location);

    public function copy(FilePath $srcLocation, FilePath $destLocation);

    public function absolutePath(FilePath $location);
}
