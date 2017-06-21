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
            FilePath::fromString('Foo/Bar.php'),
            FilePath::fromString('Foo/Foo.php'),
        ]);

        $this->assertTrue($list->contains(FilePath::fromString('Foo/Bar.php')));
    }
}
