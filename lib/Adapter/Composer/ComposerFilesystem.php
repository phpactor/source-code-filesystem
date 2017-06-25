<?php

namespace DTL\Filesystem\Adapter\Composer;

use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use Composer\Autoload\ClassLoader;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;

class ComposerFilesystem extends SimpleFilesystem
{
    private $classLoader;
    private $path;

    public function __construct(FilePath $path, ClassLoader $classLoader)
    {
        parent::__construct($path);
        $this->path = $path;
        $this->classLoader = $classLoader;
    }

    public function fileList(): FileList
    {
        $prefixes = array_merge(
            $this->classLoader->getPrefixes(),
            $this->classLoader->getPrefixesPsr4(),
            $this->classLoader->getClassMap()
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
                    $files[] = $path;
                    continue;
                }

                $iterator = $this->createFileIterator(
                    $this->path->makeAbsoluteFromString($path)
                );

                $appendIterator->append($iterator);
            }
        }

        if ($paths) {
            $appendIterator->append(new \ArrayIterator($paths));
        }

        return FileList::fromIterator($appendIterator);
    }
}
