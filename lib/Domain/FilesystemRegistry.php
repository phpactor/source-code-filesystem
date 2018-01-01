<?php

namespace Phpactor\Filesystem\Domain;

use Phpactor\Filesystem\Domain\Filesystem;

interface FilesystemRegistry
{
    public function get(string $name): Filesystem;

    public function has(string $name);

    public function names(): array;
}
