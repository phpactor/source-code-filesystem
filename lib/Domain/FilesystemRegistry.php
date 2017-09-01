<?php

namespace Phpactor\Filesystem\Domain;

final class FilesystemRegistry
{
    private $filesystems = [];

    public function __construct(array $filesystemMap)
    {
        foreach ($filesystemMap as $name => $filesystem) {
            $this->add($name, $filesystem);
        }
    }

    public function get(string $name): Filesystem
    {
        if (!isset($this->filesystems[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown filesystem "%s", known filesystems "%s"',
                $name, implode('", "', array_keys($this->filesystems))
            ));
        }

        return $this->filesystems[$name];
    }

    private function add(string $name, Filesystem $filesystem)
    {
        $this->filesystems[$name] = $filesystem;
    }
}
