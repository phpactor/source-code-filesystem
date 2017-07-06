<?php

namespace Phpactor\Filesystem\Tests\Adapter\Composer;

use Phpactor\Filesystem\Adapter\Composer\ComposerFilesystem;
use Phpactor\Filesystem\Domain\FilePath;
use Phpactor\Filesystem\Tests\Adapter\AdapterTestCase;
use Phpactor\Filesystem\Domain\Filesystem;

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
            $classLoader = require 'vendor/autoload.php';
        }

        return new ComposerFilesystem(FilePath::fromString($this->workspacePath()), $classLoader);
    }
}
