<?php

namespace Phpactor\Filesystem\Adapter\Simple;

use Phpactor\Filesystem\Domain\Filesystem;
use Phpactor\Filesystem\Domain\FileList;
use Phpactor\Filesystem\Domain\FilePath;
use RuntimeException;
use Webmozart\PathUtil\Path;
use Phpactor\Filesystem\Domain\FileListProvider;
use Phpactor\Filesystem\Domain\CopyReport;

class SimpleFilesystem implements Filesystem
{
    private $path;

    /**
     * @var FileListProvider
     */
    private $fileListProvider;


    public function __construct($path, FileListProvider $fileListProvider = null)
    {
        $path = FilePath::fromUnknown($path);
        $this->path = $path;
        $this->fileListProvider = $fileListProvider ?: new SimpleFileListProvider($path);
    }

    public function fileList(): FileList
    {
        return $this->fileListProvider->fileList();
    }

    public function remove($path)
    {
        $path = FilePath::fromUnknown($path);
        unlink($path->path());
    }

    public function move($srcLocation, $destPath)
    {
        $srcLocation = FilePath::fromUnknown($srcLocation);
        $destPath = FilePath::fromUnknown($destPath);

        $this->makeDirectoryIfNotExists((string) $destPath);
        rename(
            $srcLocation->path(),
            $destPath->path()
        );
    }

    public function copy($srcLocation, $destPath): CopyReport
    {
        $srcLocation = FilePath::fromUnknown($srcLocation);
        $destPath = FilePath::fromUnknown($destPath);

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

    public function getContents($path): string
    {
        $path = FilePath::fromUnknown($path);
        $contents = file_get_contents($path->path());

        if (false === $contents) {
            throw new RuntimeException('Could not file_get_contents');
        }

        return $contents;
    }

    public function writeContents($path, string $contents)
    {
        $path = FilePath::fromUnknown($path);
        file_put_contents($path->path(), $contents);
    }

    public function exists($path): bool
    {
        $path = FilePath::fromUnknown($path);
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
            $destFiles[] = FilePath::fromString($filePath);
        }

        return CopyReport::fromSrcAndDestFiles(FileList::fromFilePaths($srcFiles), FileList::fromFilePaths($destFiles));
    }
}
