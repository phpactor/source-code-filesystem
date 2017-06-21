<?php

namespace DTL\Filesystem\Tests\Adapter\Git;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Git\GitFilesystem;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Domain\AbsoluteExistingPath;
use DTL\Filesystem\Tests\Adapter\AdapterTestCase;
use DTL\Filesystem\Domain\Filesystem;

class GitFilesystemTest extends AdapterTestCase
{
    public function setUp()
    {
        parent::setUp();
        chdir($this->workspacePath());
        exec('git init');
        exec('git add *');
    }
    protected function filesystem(): Filesystem
    {
        $basePath = AbsoluteExistingPath::fromString($this->workspacePath());
        return new GitFilesystem($basePath);
    }
}
