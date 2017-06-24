<?php

namespace DTL\Filesystem\Domain;

use Webmozart\PathUtil\Path;

final class FilePath
{
    private $cwd;
    private $path;

    private function __construct(Cwd $cwd, string $path = null)
    {
        if ($path && Path::isAbsolute($path) && 0 !== strpos($path, (string) $cwd)) {
            throw new \OutOfBoundsException(sprintf(
                'Absolute path "%s" is not part of working directory "%s"',
                $path, $cwd->__toString()
            ));
        }

        if ($path && Path::isAbsolute($path)) {
            $path = Path::makeRelative($path, (string) $cwd);
        }

        $this->cwd = $cwd;
        $this->path = $path;
    }

    public static function fromCwdAndPath(Cwd $cwd, string $path)
    {
        return new self($cwd, $path);
    }

    public static function fromCwd(Cwd $cwd)
    {
        return new self($cwd, '');
    }

    public static function fromPathInCurrentCwd(string $path)
    {
        return new self(Cwd::fromCurrent(), $path);
    }

    public function extension(): string
    {
        return Path::getExtension($this->path);
    }

    public function concatPath(string $path)
    {
        if (Path::isAbsolute($path)) {
            $path = Path::makeRelative($path, $this->absolutePath());
        }
        return new self($this->cwd, Path::join($this->absolutePath(), $path));
    }

    public function isWithin(FilePath $path)
    {
        return 0 === strpos($this->absolutePath(), $path->absolutePath() . '/');
    }

    public function isNamed(string $name): bool
    {
        return basename($this->absolutePath()) == $name;
    }

    public function absolutePath()
    {
        return Path::join($this->cwd->__toString(), $this->path);
    }

    public function relativePath()
    {
        return $this->path;
    }

    public function __toString()
    {
        return $this->path;
    }
}
