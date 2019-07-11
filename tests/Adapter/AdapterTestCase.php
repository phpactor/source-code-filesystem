<?php

namespace Phpactor\Filesystem\Tests\Adapter;

use Phpactor\Filesystem\Domain\Filesystem;

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
        $this->assertTrue($fileList->contains($this->filesystem()->createPath('src/Foobar.php')));

        $location = $this->filesystem()->createPath('src/Hello/Goodbye.php');
        $foo = $fileList->contains($location);
        $this->assertTrue($foo);
    }

    public function testRemove()
    {
        $file = $this->filesystem()->createPath('src/Hello/Goodbye.php');
        $this->assertTrue(file_exists($file->path()));
        $this->filesystem()->remove($file);
        $this->assertFalse(file_exists($file->path()));
    }

    public function testMove()
    {
        $srcLocation = $this->filesystem()->createPath('src/Hello/Goodbye.php');
        $destLocation = $this->filesystem()->createPath('src/Hello/Hello.php');

        $this->filesystem()->move($srcLocation, $destLocation);
        $this->assertTrue(file_exists($destLocation->path()));
        $this->assertFalse(file_exists($srcLocation->path()));
    }

    public function testCopy()
    {
        $srcLocation = $this->filesystem()->createPath('src/Hello/Goodbye.php');
        $destLocation = $this->filesystem()->createPath('src/Hello/Hello.php');

        $this->filesystem()->copy($srcLocation, $destLocation);
        $this->assertTrue(file_exists($destLocation->path()));
        $this->assertTrue(file_exists($srcLocation->path()));
    }

    public function testExists()
    {
        $path = $this->filesystem()->createPath('src/Hello/Goodbye.php');
        $this->assertTrue($this->filesystem()->exists($path));

        $path = $this->filesystem()->createPath('src/Hello/Plop.php');
        $this->assertFalse($this->filesystem()->exists($path));
    }

    public function testCopyRecursive()
    {
        $srcLocation = $this->filesystem()->createPath('src');
        $destLocation = $this->filesystem()->createPath('src/AAAn');

        $list = $this->filesystem()->copy($srcLocation, $destLocation);
        $this->assertTrue(file_exists($destLocation->path()));
        $this->assertTrue(file_exists($srcLocation->path()));
        $this->assertTrue(file_exists($srcLocation->path() . '/AAAn/Foobar.php'));
        $this->assertTrue(file_exists($srcLocation->path() . '/AAAn/Hello/Goodbye.php'));
        $this->assertCount(2, $list->srcFiles());
        $this->assertCount(2, $list->destFiles());
    }

    public function testWriteGet()
    {
        $path = $this->filesystem()->createPath('src/Hello/Goodbye.php');

        $this->filesystem()->writeContents($path, 'foo');
        $this->assertEquals('foo', $this->filesystem()->getContents($path));
    }
}
