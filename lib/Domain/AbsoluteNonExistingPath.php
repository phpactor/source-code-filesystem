<?php

namespace DTL\Filesystem\Domain;

final class AbsoluteNonExistingPath extends AbsolutePath
{
    protected function __construct(string $path)
    {
        if (file_exists($path)) {
            throw new \InvalidArgumentException(sprintf(
                'File "%s" already exists', $path
            ));
        }

        parent::__construct($path);
    }
}
