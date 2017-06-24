<?php

namespace DTL\Filesystem\Domain;

final class FileQueryBuilder
{
    private $name;

    public function name(string $name)
    {
        $this->name = $name;
        return $this;
    }
}
