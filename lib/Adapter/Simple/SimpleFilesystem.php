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

    public function move(FilePath $srcLocation, FilePath $destPath)
    {
        $this->makeDirectoryIfNotExists((string) $destPath);
        rename(
            $srcLocation->path(),
            $destPath->path()
        );
    }

    public function copy(FilePath $srcLocation, FilePath $destPath): CopyReport
    {
        if ($srcLocation->isDirectory()) {
            return $this->copyDirectory($srcLocation, $destPath);
        }

        $this->makeDirectoryIfNotExists((string) $destPath);

        copy(
            $srcLocation->path(),
            $destPath->path()
        );

        return CopyReport::fromSrcAndDestFiles(
            FileList::fromFilePaths([ $srcLocation ]),
            FileList::fromFilePaths([ $destPath ])
        );

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

    public function exists(FilePath $path): bool
    {
        return file_exists($path);
    }

    private function makeDirectoryIfNotExists($destPath)
    {
        if (file_exists(dirname($destPath))) {
            return;
        }

        mkdir(dirname($destPath), 0777, true);
    }

    private function copyDirectory(FilePath $srcLocation, FilePath $destPath): CopyReport
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($srcLocation->path(), \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $destFiles = [];
        $srcFiles = [];
        foreach ($iterator as $file) {
            $filePath = $destPath->path() . '/' . $iterator->getSubPathName();
            if ($file->isDir()) {
                continue;
            }

            $this->makeDirectoryIfNotExists($filePath);

            copy($file, $filePath);
            $srcFiles[] = FilePath::fromString($file);
            $destFiles[] = FilePath::fromString($destPath);
        }

        return CopyReport::fromSrcAndDestFiles(FileList::fromFilePaths($srcFiles), FileList::fromFilePaths($destFiles));
    }
}
