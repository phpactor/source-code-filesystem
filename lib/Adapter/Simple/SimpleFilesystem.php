<?php

namespace DTL\Filesystem\Adapter\Simple;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath; use Webmozart\PathUtil\Path;
use DTL\Filesystem\Domain\FileListProvider;
use DTL\Filesystem\Adapter\Simple\SimpleFileListProvider;

class SimpleFilesystem implements Filesystem
{
    private $path;

    public function __construct(FilePath $path, FileListProvider $fileListProvider = null)
    {
        $this->path = $path;
        $this->fileListProvider = $fileListProvider ?: new SimpleFileListProvider($path);
    }

    public function fileList(): FileList
    {
        return $this->fileListProvider->fileList();
    }

    public function remove(FilePath $path)
    {
        unlink($path->path());
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
        if (false === is_dir($srcLocation->path())) {
            copy(
                $srcLocation->path(),
                $destLocation->path()
            );
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcLocation->path(), \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            $destPath = $destLocation->path() . '/' . $iterator->getSubPathName();
            if ($file->isDir()) {
                continue;
            }

            if (!file_exists(dirname($destPath))) {
                mkdir(dirname($destPath), 0777, true);
            }

            copy($file, $destPath);
        }
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
