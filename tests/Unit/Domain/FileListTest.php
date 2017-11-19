<?php

namespace Phpactor\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Phpactor\Filesystem\Domain\FileList;
use Phpactor\Filesystem\Domain\FilePath;

class FileListTest extends TestCase
{
    /**
     * @testdox It returns true if it contains a file path.
     */
    public function testContains()
    {
        $list = FileList::fromFilePaths([
            FilePath::fromString('/Foo/Bar.php'),
            FilePath::fromString('/Foo/Foo.php'),
        ]);

        $this->assertTrue($list->contains(FilePath::fromString('/Foo/Bar.php')));
    }

    /**
     * @testdox It returns files within a path
     */
    public function testWithin()
    {
        $list = FileList::fromFilePaths([
            FilePath::fromString('/Foo/Bar.php'),
            FilePath::fromString('/Foo/Foo.php'),
            FilePath::fromString('/Boo/Bar.php'),
            FilePath::fromString('/Foo.php'),
        ]);
        $expected = FileList::fromFilePaths([
            FilePath::fromString('/Foo/Bar.php'),
            FilePath::fromString('/Foo/Foo.php'),
        ]);

        $this->assertEquals(
            iterator_to_array($expected),
            iterator_to_array($list->within(FilePath::fromString('/Foo')))
        );
    }

    /**
     * It returns all files with given name (including extension).
     */
    public function testNamed()
    {
        $list = FileList::fromFilePaths([
            FilePath::fromString('/Foo/Bar.php'),
            FilePath::fromString('/Foo/Foo.php'),
            FilePath::fromString('/Boo/Bar.php'),
            FilePath::fromString('/Foo.php'),
        ]);
        $expected = FileList::fromFilePaths([
            FilePath::fromString('/Foo/Bar.php'),
            FilePath::fromString('/Boo/Bar.php'),
        ]);

        $this->assertEquals(
            array_values(iterator_to_array($expected)),
            array_values(iterator_to_array($list->named('Bar.php')))
        );
    }

    public function testCallback()
    {
        $list = FileList::fromFilePaths([
            FilePath::fromString('/Foo/Bar.php'),
            FilePath::fromString('/Foo/Foo.php'),
            FilePath::fromString('/Boo/Bar.php'),
            FilePath::fromString('/Foo.php'),
        ]);
        $expected = FileList::fromFilePaths([
            FilePath::fromString('/Foo/Bar.php'),
            FilePath::fromString('/Boo/Bar.php'),
        ]);

        $this->assertEquals(
            array_values(iterator_to_array($expected)),
            array_values(iterator_to_array($list->filter(function (\SplFileInfo $file) {
                return $file->getFileName() == 'Bar.php';
            })))
        );
    }

    public function testExisting()
    {
        $list = FileList::fromFilePaths([
            FilePath::fromString(__FILE__),
            FilePath::fromString('/Foo.php'),
        ]);
        $expected = FileList::fromFilePaths([
            FilePath::fromString(__FILE__),
        ]);

        $this->assertEquals(
            array_values(iterator_to_array($expected)),
            array_values(iterator_to_array($list->existing()))
        );
    }
}
