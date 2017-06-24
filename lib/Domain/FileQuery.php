<?php

namespace DTL\Filesystem\Domain;

final class FileQuery
{
    private $pattern;

    public static function fromPattern(string $pattern)
    {
        $this->name = $name;
        return $this;
    }

    public function create(): FileQuery
}
