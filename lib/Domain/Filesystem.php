<?php

namespace DTL\Filesystem\Domain;

use DTL\Filesystem\Domain\FileLocation;

interface Filesystem
{
    public function fileList(): FileList;

    public function move(FileLocation $srcLocation, FileLocation $destLocation);

    public function remove(FileLocation $location);

    public function copy(FileLocation $srcLocation, FileLocation $destLocation);
}
