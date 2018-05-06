<?php

namespace Phpactor\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Phpactor\Filesystem\Domain\FilePath;
use RuntimeException;
use SplFileInfo;
use stdClass;

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

    public function testFromParts()
    {
        $path = FilePath::fromParts(['Hello', 'Goodbye']);
        $this->assertEquals('/Hello/Goodbye', $path->path());
    }

    /**
     * @dataProvider provideUnknown
     */
    public function testFromUnknownWith($path, string $expectedPath)
    {
        $filePath = FilePath::fromUnknown($path);
        $this->assertInstanceOf(FilePath::class, $filePath);
        $this->assertEquals($expectedPath, (string) $filePath);
    }

    public function provideUnknown()
    {
        yield 'FilePath instance' => [
            FilePath::fromString('/foo.php'),
            '/foo.php'
        ];

        yield 'string' => [
            '/foo.php',
            '/foo.php'
        ];

        yield 'array' => [
            [ 'one', 'two' ],
            '/one/two'
        ];

        yield 'SplFileInfo' => [
            new SplFileInfo(__FILE__),
            __FILE__
        ];
    }

    public function testThrowExceptionOnUnknowableType()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Do not know');
        FilePath::fromUnknown(new stdClass());
    }

    /**
     * @testdox It generates an absolute path from a relative.
     */
    public function testAbsoluteFromString()
    {
        $base = FilePath::fromString('/path/to/something');
        $new = $base->makeAbsoluteFromString('else/yes');
        $this->assertEquals('/path/to/something/else/yes', $new->path());
    }

    /**
     * @testdox If creating a descendant file and the path is absolute and NOT in th
     *          current branch, an exception should be thrown.
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage Trying to create descendant
     */
    public function testDescendantOutsideOfBranchException()
    {
        $base = FilePath::fromString('/path/to/something');
        $base->makeAbsoluteFromString('/else/yes');
    }

    /**
     * @testdox If given an absolute path and it lies within the current branch, return new file path.
     */
    public function testDescendantInsideOfBranchException()
    {
        $base = FilePath::fromString('/path/to/something');
        $path = $base->makeAbsoluteFromString('/path/to/something/yes');
        $this->assertEquals('/path/to/something/yes', (string) $path);
    }

    /**
     * @testdox It should provide the absolute path.
     */
    public function testAbsolute()
    {
        $path = FilePath::fromString('/path/to/something/else/yes');
        $this->assertEquals('/path/to/something/else/yes', $path->path());
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
