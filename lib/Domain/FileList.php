<?php

namespace DTL\Filesystem\Domain;

class FileList implements \Iterator
{
    private $iterator;
    private $key = 0;

    private function __construct(\Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    public static function fromIterator(\Iterator $iterator)
    {
        return new self($iterator);
    }

    public static function fromFilePaths(array $filePaths)
    {
        $files = [];
        foreach ($filePaths as $filePath) {
            $files[] = new \SplFileInfo($filePath);
        }

        return new self(new \ArrayIterator($files));
    }

    public function getIterator()
    {
        return $this->iterator;
    }

    public function contains(FilePath $path)
    {
        foreach ($this as $filePath) {
            if ($path == $filePath) {
                return true;
            }
        }

        return false;
    }

    public function phpFiles(): FileList
    {
        return new self((function () {
            foreach ($this as $filePath) {
                if ($filePath->extension() !== 'php') {
                    continue;
                }

                yield $filePath->asSplFileInfo();
            }
        })());
    }

    public function within(FilePath $path): FileList
    {
        return new self(new \RegexIterator($this->iterator, sprintf(
            '{^%s/.*}', (string) $path
        )));
    }

    public function named(string $name): FileList
    {
        return new self(new \RegexIterator($this->iterator, sprintf(
            '{/%s.*$}', $name
        )));
    }

    public function rewind()
    {
        $this->iterator->rewind();
    }

    public function current()
    {
        $current = $this->iterator->current();

        return FilePath::fromSplFileInfo($current);
    }

    public function key()
    {
        return $this->key++;
    }

    public function next()
    {
        $this->iterator->next();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }
}
