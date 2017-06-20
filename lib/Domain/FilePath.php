<?php

namespace DTL\ClassMover\Finder;

final class FilePath
{
    const PATH_NONE = '_|__<transient>__|_';

    private $path;

    public static function fromString(string $path)
    {
        $new = new self();
        $real = realpath($path);

        if (!$real) {
            throw new \RuntimeException(sprintf(
                'Could not determine realpath for "%s"', $path
            ));
        }

        $new->path = $real;

        return $new;
    }

    public function __toString()
    {
        return $this->path;
    }
}
