<?php

namespace DTL\Filesystem\Adapter\Composer;

use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use Composer\Autoload\ClassLoader;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\Cwd;
use DTL\Filesystem\Adapter\Simple\SimpleFileIterator;
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

        $multipleIterator = new \MultipleIterator();
        foreach ($prefixes as $paths) {
            foreach ($paths as $path) {
                if (!$path = realpath($path)) {
                    continue;
                }

                $iterator = new SimpleFileIterator(
                    $this->path->makeAbsoluteFromString($path)
                );

                return FileList::fromIterator($iterator);
            }
        }

        return FileList::fromIterator($multipleIterator);
    }
}
