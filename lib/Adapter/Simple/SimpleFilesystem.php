<?php

namespace Phpactor\Filesystem\Adapter\Simple;

use Phpactor\Filesystem\Domain\Filesystem;
use Phpactor\Filesystem\Domain\FileList;
use Phpactor\Filesystem\Domain\FilePath; 
use Webmozart\PathUtil\Path;
use Phpactor\Filesystem\Domain\FileListProvider;
use Phpactor\Filesystem\Adapter\Simple\SimpleFileListProvider;
use Phpactor\Filesystem\Domain\CopyReport;

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

    public function copy(FilePath $srcLocation, FilePath $destLocation): CopyReport
    {
        if (false === is_dir($srcLocation->path())) {
            if (!file_exists(dirname($destLocation))) {
                mkdir(dirname($destLocation), 0777, true);
            }
            copy(
                $srcLocation->path(),
                $destLocation->path()
            );
            return CopyReport::fromSrcAndDestFiles(
                FileList::fromFilePaths([ $srcLocation ]),
                FileList::fromFilePaths([ $destLocation ])
            );
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcLocation->path(), \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $destFiles = [];
        $srcFiles = [];
        foreach ($iterator as $file) {
            $destPath = $destLocation->path() . '/' . $iterator->getSubPathName();
            if ($file->isDir()) {
                continue;
            }

            if (!file_exists(dirname($destPath))) {
                mkdir(dirname($destPath), 0777, true);
            }

            copy($file, $destPath);
            $srcFiles[] = FilePath::fromString($file);
            $destFiles[] = FilePath::fromString($destPath);
        }

        return CopyReport::fromSrcAndDestFiles(FileList::fromFilePaths($srcFiles), FileList::fromFilePaths($destFiles));
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
