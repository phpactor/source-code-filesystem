<?php

namespace DTL\Filesystem\Domain;

final class FilePath
{
    private $path;

    private function __construct(string $path)
    {
        $this->path = $path;
    }

    public static function fromString(string $path)
    {
        return new self($path);
    }

    public function extension()
    {
        $info = pathinfo($this->path);
        return $info['extension'] ?? '';
    }

    public function concatPath(FilePath $path)
    {
        return new self($this->path . '/' . $path);
    }

    public function isAbsolute()
    {
        return substr($this->path, 0, 1) == '/';
    }

    public function relativize(FilePath $path)
    {
        // path is already relative
        if (false === $path->isAbsolute()) {
            return;
        }

        if (0 !== strpos($path, $this->path)) {
            throw new \InvalidArgumentException(sprintf(
                'Path "%s" is not an extension of "%s". Cannot relativize.',
                $path, $this->path
            ));
        }

        return substr($path, strlen($this->path) + 1);
    }

    public function __toString()
    {
        return $this->path;
    }
}
