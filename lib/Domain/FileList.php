<?php

namespace Phpactor\Filesystem\Domain;

use CallbackFilterIterator;
use RegexIterator;
use SplFileInfo;

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
        return new self(new RegexIterator($this->iterator, sprintf(
            '{^%s/.*}',
            (string) preg_quote($path)
        )));
    }

    public function named(string $name): FileList
    {
        return new self(new RegexIterator($this->iterator, sprintf(
            '{/%s.*$}',
            preg_quote($name)
        )));
    }

    public function filter(\Closure $closure)
    {
        return new self(new CallbackFilterIterator($this->iterator, $closure));
    }

    public function existing()
    {
        return new self(new CallbackFilterIterator($this->iterator, function (SplFileInfo $file) {
            return file_exists($file->__toString());
        }));
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
