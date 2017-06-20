<?php

namespace DTL\ClassMover\Domain;

interface Filesystem
{
    public function fileList(): FileList;

    public function exists(string $srcPath);

    public function move(string $srcPath, string $destPath);
}
