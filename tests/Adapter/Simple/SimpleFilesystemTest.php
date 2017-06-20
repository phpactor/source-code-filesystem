<?php

namespace DTL\Filesystem\Tests\Adapter\Simple;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use DTL\Filesystem\Domain\FileLocation;
use DTL\Filesystem\Domain\AbsoluteExistingPath;

class SimpleFilesystemTest extends IntegrationTestCase
{
    private $filesystem;
    private $basePath;

    public function setUp()
    {
        $this->initWorkspace();
        $this->loadProject();
        $this->basePath = AbsoluteExistingPath::fromString($this->workspacePath() . '/src');
        $this->filesystem = new SimpleFilesystem($this->basePath);
    }

    public function testFind()
    {
        $fileList = $this->filesystem->fileList();
        $files = iterator_to_array($fileList);

        $this->assertEquals([
            FileLocation::fromString('Hello/Goodbye.php'),
            FileLocation::fromString('Foobar.php'),
        ], $files);
    }

    public function testRemove()
    {
        $file = FileLocation::fromString('Hello/Goodbye.php');
        $absolutePath = $this->basePath->concatExistingLocation($file);
        $this->assertTrue(file_exists($absolutePath));
        $this->filesystem->remove($file);
        $this->assertFalse(file_exists($absolutePath));
    }

    public function testMove()
    {
        $srcLocation = FileLocation::fromString('Hello/Goodbye.php');
        $destLocation = FileLocation::fromString('Hello/Hello.php');

        $this->filesystem->move($srcLocation, $destLocation);
        $this->assertTrue(file_exists($this->basePath->concatExistingLocation($destLocation)));
        $this->assertFalse(file_exists($this->basePath->concatNonExistingLocation($srcLocation)));
    }
}
