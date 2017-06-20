<?php

namespace DTL\Filesystem\Domain;

interface Filesystem
{
    public function fileList(): FileList;

    public function move(FilePath $srcPath, FilePath $destPath);
}
