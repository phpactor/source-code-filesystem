<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\Cwd;
use DTL\Filesystem\Domain\FilePath;

class FilePathTest extends TestCase
{
    /**
     * @testdox It should throw an exception if the path is outside of the CWD
     * @expectedException OutOfBoundsException
     * @expectedExceptionMessage Absolute path "/foobar" is
     */
    public function testExceptionOutside()
    {
        FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/something'), '/foobar');
    }

    /**
     * @testdox It should provide the relative path for an absolute path.
     */
    public function testRelativeForAbsolute()
    {
        $relative = FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/something'), '/path/to/something/else/yes');
        $this->assertEquals('else/yes', $relative->relativePath());
    }

    /**
     * @testdox It should provide the absolute path.
     */
    public function testAbsolute()
    {
        $relative = FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/something'), '/path/to/something/else/yes');
        $this->assertEquals('/path/to/something/else/yes', $relative->absolutePath());
    }

    /**
     * @testdox It should return true if it is within another path
     */
    public function testWithin()
    {
        $path1 = FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/something'), 'else/yes');
        $path2 = FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/something'), 'else/yes/foobar');

        $this->assertTrue($path2->isWithin($path1));
    }

    /**
     * @testdox It returns true or false if it is named a given name.
     */
    public function testIsNamed()
    {
        $path1 = FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/something'), 'else/foobar');
        $path2 = FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/foo'), 'else/yes/foobar');
        $path3 = FilePath::fromCwdAndPath(Cwd::fromCwd('/path/to/barbar'), 'else/yes/brabar');

        $this->assertTrue($path1->isNamed('foobar'));
        $this->assertTrue($path2->isNamed('foobar'));
        $this->assertFalse($path3->isNamed('foobar'));
    }
}
