<?php

namespace DTL\Filesystem\Domain;

final class FileLocation
{
    private $path;

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function fromString(string $path)
    {
        return new self($path);
    }

    public function __toString()
    {
        return $this->path;
    }
}
