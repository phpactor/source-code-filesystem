<?php

namespace DTL\Filesystem\Tests\Adapter\Git;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Git\GitFilesystem;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Tests\Adapter\AdapterTestCase;
use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\Cwd;

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
        return new GitFilesystem(FilePath::fromString($this->workspacePath()));
    }

    /**
     * It sohuld throw an exception if the cwd does not have a .git folder.
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage The cwd does not seem to be
     */
    public function testNoGitFolder()
    {
        return new GitFilesystem(FilePath::fromString(__DIR__));
    }

}
