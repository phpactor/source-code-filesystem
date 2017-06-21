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
        return new self($this->phpFileGenerator());
    }

    private function phpFileGenerator(): \Iterator
    {
        foreach ($this->iterator as $filePath) {
            if ($filePath->extension() !== 'php') {
                continue;
            }

            yield($filePath);
        }
    }

    public function within(FilePath $path): FileList
    {
        return new self($this->withinGenerator($path));
    }

    private function withinGenerator(FilePath $path): \Iterator
    {
        foreach ($this->iterator as $filePath) {
            if ($filePath->isWithin($path)) {
                yield($filePath);
            }
        }
    }
}
