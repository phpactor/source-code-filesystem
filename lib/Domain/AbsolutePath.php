<?php

namespace DTL\Filesystem\Domain;

class AbsolutePath
{
    protected $path;

    protected function __construct(string $path)
    {
        $path = rtrim($path, '/');
        if (substr($path, 0, 1) !== '/') {
            throw new \InvalidArgumentException(sprintf(
                'File path "%s" is not absolute', $path
            ));
        }

        if (file_exists($path)) {
            $path = realpath($path);
        }

        $this->path = $path;
    }

    public static function fromString(string $path)
    {
        return new static($path);
    }

    public function relativizeToLocation(AbsolutePath $path)
    {
        if (false === $path->startsWith($this)) {
            throw new \InvalidArgumentException(sprintf(
                'Path "%s" must start with "%s" in order for it to be relativized',
                $path, $this
            ));
        }

        return FileLocation::fromString(substr($path, strlen($this) + 1));
    }

    public function __toString()
    {
        return $this->path;
    }

    public function startsWith(AbsolutePath $path)
    {
        return 0 === strpos($this->__toString(), $path->__toString());
    }
}
