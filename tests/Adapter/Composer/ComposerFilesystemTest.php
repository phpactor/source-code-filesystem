<?php

namespace DTL\Filesystem\Tests\Adapter\Composer;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Composer\ComposerFilesystem;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Tests\Adapter\AdapterTestCase;
use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\Cwd;

class ComposerFilesystemTest extends AdapterTestCase
{
    public function setUp()
    {
        parent::setUp();
        chdir($this->workspacePath());
        exec('composer dumpautoload  2> /dev/null');
    }

    protected function filesystem(): Filesystem
    {
        static $classLoader;

        if (!$classLoader) {
            $classLoader = require('vendor/autoload.php');
        }

        return new ComposerFilesystem(Cwd::fromCwd($this->workspacePath()), $classLoader);
    }
}
