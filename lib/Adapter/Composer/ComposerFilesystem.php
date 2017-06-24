<?php

namespace DTL\Filesystem\Adapter\Composer;

use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use Composer\Autoload\ClassLoader;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\Cwd;
use DTL\Filesystem\Adapter\Simple\SimpleFileIterator;

class ComposerFilesystem extends SimpleFilesystem
{
    private $classLoader;
    private $cwd;

    public function __construct(Cwd $cwd, ClassLoader $classLoader)
    {
        parent::__construct($cwd);
        $this->cwd = $cwd;
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
                    $this->cwd->createPathWith($path)
                );
                return FileList::fromIterator($iterator);
                $multipleIterator->attachIterator(new \CachingIterator($iterator->getIterator()));
            }
        }

        return FileList::fromIterator($multipleIterator);
    }
}
