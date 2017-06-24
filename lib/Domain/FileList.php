<?php

namespace DTL\Filesystem\Domain;

use DTL\Filesystem\Domain\FilePath;

class FileList implements \IteratorAggregate
{
    private $iterator;

    private function __construct(\Traversable $iterator)
    {
        $this->iterator = $iterator;
    }

    public static function fromIterator(\Traversable $iterator)
    {
        return new self($iterator);
    }

    public static function fromFilePaths(array $filePaths)
    {
        return new self(new \ArrayIterator($filePaths));
    }

    public function getIterator()
    {
        return $this->iterator;
    }

    public function contains(FilePath $path)
    {
        foreach ($this->iterator as $filePath) {
            if ($path == $filePath) {
                return true;
            }
        }

        return false;
    }

    public function phpFiles(): FileList
    {
        return new self(function () {
            foreach ($this->iterator as $filePath) {
                if ($filePath->extension() !== 'php') {
                    continue;
                }

                yield($filePath);
            }
        });
    }

    public function within(FilePath $path): FileList
    {
        return new self((function () use ($path) {
            foreach ($this->iterator as $filePath) {
                if ($filePath->isWithin($path)) {
                    yield($filePath);
                }
            }
        })());
    }

    public function named(string $name): FileList
    {
        return new self((function () use ($name) {
            foreach ($this->iterator as $filePath) {
                if ($filePath->isNamed($name)) {
                    yield($filePath);
                }
            }
        })());
    }
}
