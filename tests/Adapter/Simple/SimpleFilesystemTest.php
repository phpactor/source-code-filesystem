<?php

namespace DTL\Filesystem\Tests\Adapter\Simple;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use DTL\Filesystem\Domain\FilePath;

class SimpleFilesystemTest extends IntegrationTestCase
{
    private $filesystem;

    public function setUp()
    {
        $this->initWorkspace();
        $this->loadProject();
        $this->filesystem = new SimpleFilesystem(FilePath::fromExistingString($this->workspacePath() . '/src'));
    }

    public function testFind()
    {
        $fileList = $this->filesystem->fileList();
        $files = iterator_to_array($fileList);

        $this->assertEquals([
            FilePath::fromString('Hello/Goodbye.php'),
            FilePath::fromString('Foobar.php'),
        ], $files);
    }

    public function testRemove()
    {
        $file = 'Hello/Goodbye.php';
        $this->assertTrue(file_exists($file));
        $this->filesystem->remove('Hello/Goodbye.php');
        $this->assertFalse(file_exists($file));
    }

    public function testMove()
    {
        $srcFile = FilePath::fromString('Hello/Goodbye.php');
        $destFile = FilePath::fromString('Hello/Hello.php');
        $this->filesystem->move('Hello/Goodbye.php');
        $this->assertFalse(file_exists($file));
    }
}
