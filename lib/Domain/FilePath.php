<?php

namespace DTL\Filesystem\Domain;

use Webmozart\PathUtil\Path;

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

    public static function fromParts(array $parts)
    {
        $path = Path::join($parts);
        if (substr($path, 0, 1) !== '/') {
            $path = '/'.$path;
        }

        return new self($path);
    }

    public static function fromSplFileInfo(\SplFileInfo $fileInfo)
    {
        return new self((string) $fileInfo);
    }

    public function asSplFileInfo()
    {
        return new \SplFileInfo($this->path());
    }

    public function makeAbsoluteFromString(string $path)
    {
        if (Path::isAbsolute($path)) {
            $path = self::fromString($path);

            if (!$path->isWithin($this)) {
                throw new \RuntimeException(sprintf(
                    'Trying to create descendant from absolute path "%s" that does not lie within context path "%s"',
                    (string) $path,
                    (string) $this
                ));
            }

            return $path;
        }

        return self::fromParts([(string) $this, $path]);
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
        return 0 === strpos($this->path(), $path->path().'/');
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
