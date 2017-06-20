<?php

namespace DTL\Filesystem\Tests\Adapter\Simple;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use DTL\Filesystem\Domain\FileLocation;
use DTL\Filesystem\Domain\AbsoluteExistingPath;
use DTL\Filesystem\Tests\Adapter\AdapterTestCase;
use DTL\Filesystem\Domain\Filesystem;

class SimpleFilesystemTest extends AdapterTestCase
{
    protected function filesystem(): Filesystem
    {
        $basePath = AbsoluteExistingPath::fromString($this->workspacePath());
        return new SimpleFilesystem($basePath);
    }
}
