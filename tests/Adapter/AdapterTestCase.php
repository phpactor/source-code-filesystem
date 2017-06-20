<?php

namespace DTL\Filesystem\Tests\Adapter;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use DTL\Filesystem\Domain\FileLocation;
use DTL\Filesystem\Domain\AbsoluteExistingPath;
use DTL\Filesystem\Domain\Filesystem;

abstract class AdapterTestCase extends IntegrationTestCase
{
    private $filesystem;

    public function setUp()
    {
        $this->initWorkspace();
        $this->loadProject();
    }

    abstract protected function filesystem(): Filesystem;

    protected function basePath()
    {
        return AbsoluteExistingPath::fromString($this->workspacePath());
    }

    public function testFind()
    {
        $fileList = $this->filesystem()->chdir(FileLocation::fromString('src'))->fileList();
        $files = iterator_to_array($fileList);

        $this->assertEquals([
            FileLocation::fromString('Hello/Goodbye.php'),
            FileLocation::fromString('Foobar.php'),
        ], $files);
    }

    public function testRemove()
    {
        $file = FileLocation::fromString('src/Hello/Goodbye.php');
        $absolutePath = $this->basePath()->concatExistingLocation($file);
        $this->assertTrue(file_exists($absolutePath));
        $this->filesystem()->remove($file);
        $this->assertFalse(file_exists($absolutePath));
    }

    public function testMove()
    {
        $srcLocation = FileLocation::fromString('src/Hello/Goodbye.php');
        $destLocation = FileLocation::fromString('src/Hello/Hello.php');

        $this->filesystem()->move($srcLocation, $destLocation);
        $this->assertTrue(file_exists($this->basePath()->concatExistingLocation($destLocation)));
        $this->assertFalse(file_exists($this->basePath()->concatNonExistingLocation($srcLocation)));
    }
}

