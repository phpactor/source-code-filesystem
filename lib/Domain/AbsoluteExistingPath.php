<?php

namespace DTL\Filesystem\Domain;

final class AbsoluteExistingPath extends AbsolutePath
{
    protected function __construct(string $path)
    {
        parent::__construct($path);

        if (!file_exists($this->path)) {
            throw new \InvalidArgumentException(sprintf(
                'File "%s" does not exist', $path
            ));
        }
    }

    public function concatExistingLocation(FileLocation $location)
    {
        return new self($this->__toString() . '/' . $location->__toString());
    }

    public function concatNonExistingLocation(FileLocation $location)
    {
        return AbsoluteNonExistingPath::fromString($this->__toString() . '/' . $location->__toString());
    }
}
