<?php

namespace DTL\Filesystem\Tests\Adapter\Simple;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Tests\Adapter\AdapterTestCase;
use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\Cwd;

class SimpleFilesystemTest extends AdapterTestCase
{
    protected function filesystem(): Filesystem
    {
        return new SimpleFilesystem(Cwd::fromCwd($this->workspacePath()));
    }
}
