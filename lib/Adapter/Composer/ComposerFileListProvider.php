<?php

namespace Phpactor\Filesystem\Adapter\Composer;

use Phpactor\Filesystem\Domain\FileList;
use Phpactor\Filesystem\Domain\FilePath;
use Composer\Autoload\ClassLoader;
use Phpactor\Filesystem\Domain\FileListProvider;

class ComposerFileListProvider implements FileListProvider
{
    private $classLoader;
    private $path;

    public function __construct(FilePath $path, ClassLoader $classLoader)
    {
        $this->path = $path;
        $this->classLoader = $classLoader;
    }

    public function fileList(): FileList
    {
        $prefixes = array_merge(
            $this->classLoader->getPrefixes(),
            $this->classLoader->getPrefixesPsr4(),
            $this->classLoader->getClassMap(),
            $this->classLoader->getFallbackDirs(),
            $this->classLoader->getFallbackDirsPsr4()
        );

        $appendIterator = new \AppendIterator();
        $files = [];
        foreach ($prefixes as $paths) {
            $paths = (array) $paths;
            foreach ($paths as $path) {
                if (!$path = realpath($path)) {
                    continue;
                }

                if (is_file($path)) {
                    $files[] = new \SplFileInfo($path);
                    continue;
                }

                $iterator = $this->createFileIterator(
                    $this->path->makeAbsoluteFromString($path)
                );

                $appendIterator->append($iterator);
            }
        }

        if ($files) {
            $appendIterator->append(new \ArrayIterator($files));
        }

        return FileList::fromIterator($appendIterator);
    }

    private function createFileIterator(string $path): \Iterator
    {
        $path = $path ? $this->path->makeAbsoluteFromString($path) : $this->path->path();
        $files = new \RecursiveDirectoryIterator($path);
        $files = new \RecursiveIteratorIterator($files);

        return $files;
    }
}
