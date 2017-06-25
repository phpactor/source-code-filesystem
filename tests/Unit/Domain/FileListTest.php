<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;

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
            iterator_to_array($expected),
            iterator_to_array($list->named('Bar.php'))
        );
    }
}
