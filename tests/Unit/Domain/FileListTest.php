<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FileLocation;

class FileListTest extends TestCase
{
    /**
     * @testdox It returns true if it contains a file path.
     */
    public function testContains()
    {
        $list = FileList::fromFilePaths([
            FileLocation::fromString('Foo/Bar.php'),
            FileLocation::fromString('Foo/Foo.php'),
        ]);

        $this->assertTrue($list->contains(FileLocation::fromString('Foo/Bar.php')));
    }
}
