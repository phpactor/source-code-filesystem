<?php

namespace DTL\Filesystem\Domain;

interface FileFilter
{
    public function accepts(FilePath $path);
}
