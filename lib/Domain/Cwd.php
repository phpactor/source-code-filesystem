<?php

namespace DTL\Filesystem\Domain;

use Webmozart\PathUtil\Path;

class Cwd
{
    private $cwd;

    private function __construct(string $cwd)
    {
        if (false === Path::isAbsolute($cwd)) {
            throw new \InvalidArgumentException(sprintf(
                'Cwd must be absolute, got "%s"',
                $cwd
            ));
        }

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

    public function createPathWith(string $path)
    {
        if (Path::isAbsolute($path)) {
            $path = Path::makeRelative($path, $this->cwd);
        }

        return FilePath::fromCwdAndPath($this, $path);
    }

    public function __toString()
    {
        return $this->cwd;
    }
}
