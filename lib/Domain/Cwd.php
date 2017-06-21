<?php

namespace DTL\Filesystem\Domain;

use Webmozart\PathUtil\Path;

class Cwd
{
    private $cwd;

    private function __construct(string $cwd)
    {
        $this->cwd = $cwd;
    }

    public static function fromCwd(string $cwd)
    {
        return new self($cwd);
    }

    public static function fromCurrent()
    {
        return new self(getcwd());
    }

    public function relativize(FilePath $path)
    {
        return FilePath::fromCwdAndPath($this, Path::makeRelative((string) $path, $this->cwd));
    }

    public function __toString()
    {
        return $this->cwd;
    }
}
