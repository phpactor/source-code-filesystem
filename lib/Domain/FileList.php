<?php

namespace DTL\Filesystem\Domain;

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

    public function getIterator()
    {
        return $this->iterator;
    }
}
