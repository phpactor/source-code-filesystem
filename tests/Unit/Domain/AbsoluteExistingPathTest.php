<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\AbsoluteExistingPath;
use DTL\Filesystem\Domain\FileLocation;

class AbsoluteExistingPathTest extends AbsolutePathTest
{
    /**
     * @testdox Throws exception if not existing.
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage does not exist
     */
    public function testExceptionNotExisting()
    {
        AbsoluteExistingPath::fromString(__DIR__ . '/barbar.boo.baz');
    }
}
