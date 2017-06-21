<?php

namespace DTL\Filesystem\Domain;

use Webmozart\PathUtil\Path;

final class FilePath
{
    private $cwd;
    private $path;

    private function __construct(Cwd $cwd, string $path)
    {
        $this->cwd = $cwd;
        $this->path = $path;
    }

    public static function fromCwdAndPath(Cwd $cwd, string $path)
    {
        return new self($cwd, $path);
    }

    public static function fromPathInCurrentCwd(string $path)
    {
        return new self(Cwd::fromCurrent(), $path);
    }

    public function extension(): string
    {
        return Path::getExtension($this->path);
    }

    public function concatPath(FilePath $path)
    {
        return new self($this->cwd, Path::join($this->path, $path));
    }

    public function absolutePath()
    {
        if ($this->isAbsolute()) {
            return $this->path;
        }

        return Path::join($this->cwd->__toString(), $this->path);
    }

    public function isAbsolute()
    {
        return Path::isAbsolute($this->path);
    }

    public function relativize(FilePath $path)
    {
        return new self($this->cwd, Path::makeRelative($path, $this->path));
    }

    public function __toString()
    {
        return $this->path;
    }
}
