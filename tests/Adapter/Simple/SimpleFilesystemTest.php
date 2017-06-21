<?php

namespace DTL\Filesystem\Tests\Adapter\Simple;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Tests\Adapter\AdapterTestCase;
use DTL\Filesystem\Domain\Filesystem;

class SimpleFilesystemTest extends AdapterTestCase
{
    protected function filesystem(): Filesystem
    {
        $basePath = FilePath::fromString($this->workspacePath());
        return new SimpleFilesystem($basePath);
    }
}
