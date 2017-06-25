<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\Cwd;
use DTL\Filesystem\Domain\FilePath;

class FilePathTest extends TestCase
{
    /**
     * @testdox It should throw an exception if the path is not absolute
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage File path must be absolute
     */
    public function testNotAbsolute()
    {
        FilePath::fromString('foobar');
    }

    /**
     * @testdox It should provide the absolute path.
     */
    public function testAbsolute()
    {
        $path = FilePath::fromString('/path/to/something/else/yes');
        $this->assertEquals('/path/to/something/else/yes', $path->asString());
    }

    /**
     * @testdox It should return true if it is within another path
     */
    public function testWithin()
    {
        $path1 = FilePath::fromString('/else/yes');
        $path2 = FilePath::fromString('/else/yes/foobar');

        $this->assertTrue($path2->isWithin($path1));
    }

    /**
     * @testdox It returns the files extension.
     */
    public function itReturnsTheExtension()
    {
        $path = FilePath::fromString('/foobar.php');
        $this->assertEquals('php', $path->extension());
    }

    /**
     * @testdox It returns true or false if it is named a given name.
     */
    public function testIsNamed()
    {
        $path1 = FilePath::fromString('/else/foobar');
        $path2 = FilePath::fromString('/else/yes/foobar');
        $path3 = FilePath::fromString('/else/yes/brabar');

        $this->assertTrue($path1->isNamed('foobar'));
        $this->assertTrue($path2->isNamed('foobar'));
        $this->assertFalse($path3->isNamed('foobar'));
    }
}
