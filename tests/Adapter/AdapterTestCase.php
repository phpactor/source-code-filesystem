<?php

namespace DTL\Filesystem\Tests\Adapter;

use DTL\Filesystem\Tests\Adapter\IntegrationTestCase;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\Cwd;

abstract class AdapterTestCase extends IntegrationTestCase
{
    private $filesystem;

    public function setUp()
    {
        $this->initWorkspace();
        $this->loadProject();
    }

    abstract protected function filesystem(): Filesystem;

    public function testFind()
    {
        $fileList = $this->filesystem()->fileList();

        $location = $this->createPath('src/Hello/Goodbye.php');
        $foo = $fileList->contains($location);
        $this->assertTrue($fileList->contains(FilePath::fromPathInCurrentCwd('src/Foobar.php')));
        $this->assertTrue($foo);
    }

    public function testRemove()
    {
        $file = $this->createPath('src/Hello/Goodbye.php');
        $this->assertTrue(file_exists($file->absolutePath()));
        $this->filesystem()->remove($file);
        $this->assertFalse(file_exists($file->absolutePath()));
    }

    public function testMove()
    {
        $srcLocation = $this->createPath('src/Hello/Goodbye.php');
        $destLocation = $this->createPath('src/Hello/Hello.php');

        $this->filesystem()->move($srcLocation, $destLocation);
        $this->assertTrue(file_exists($destLocation->absolutePath()));
        $this->assertFalse(file_exists($srcLocation->absolutePath()));
    }

    public function testCopy()
    {
        $srcLocation = $this->createPath('src/Hello/Goodbye.php');
        $destLocation = $this->createPath('src/Hello/Hello.php');

        $this->filesystem()->copy($srcLocation, $destLocation);
        $this->assertTrue(file_exists($destLocation->absolutePath()));
        $this->assertTrue(file_exists($srcLocation->absolutePath()));
    }

    public function testWriteGet()
    {
        $path = $this->createPath('src/Hello/Goodbye.php');

        $this->filesystem()->writeContents($path, 'foo');
        $this->assertEquals('foo', $this->filesystem()->getContents($path));
    }

    private function createPath(string $path)
    {
        return FilePath::fromCwdAndPath(Cwd::fromCwd($this->workspacePath()), $path);
    }
}
