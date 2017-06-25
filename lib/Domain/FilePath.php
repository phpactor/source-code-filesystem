<?php

namespace DTL\Filesystem\Domain;

use Webmozart\PathUtil\Path;
use DTL\Filesystem\Domain\FilePath;

final class FilePath
{
    private $path;

    private function __construct(string $path = null)
    {
        if (false === Path::isAbsolute($path)) {
            throw new \InvalidArgumentException(sprintf(
                'File path must be absolute'
            ));
        }

        $this->path = $path;
    }

    public static function fromString(string $path)
    {
        return new self($path);
    }

    public function extension(): string
    {
        return Path::getExtension($this->path);
    }

    public function concatPath(FilePath $path)
    {
        return new self(Path::join($this->path(), (string) $path));
    }

    public function isWithin(FilePath $path)
    {
        return 0 === strpos($this->path(), $path->path() . '/');
    }

    public function isNamed(string $name): bool
    {
        return basename($this->path()) == $name;
    }

    public function path()
    {
        return $this->path;
    }

    public function __toString()
    {
        return $this->path;
    }
}
