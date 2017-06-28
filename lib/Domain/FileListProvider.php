<?php

namespace DTL\Filesystem\Domain;

interface FileListProvider
{
    public function fileList(): FileList;
}
