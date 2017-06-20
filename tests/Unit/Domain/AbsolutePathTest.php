<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\AbsolutePath;
use DTL\Filesystem\Domain\FileLocation;

class AbsolutePathTest extends TestCase
{
    /**
     * @testdox Throws exception if not absolute path.
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage File path "foo/bar" is not absolute
     */
    public function testExceptionNotAbsolute()
    {
        AbsolutePath::fromString('foo/bar');
    }

    /**
     * @testdox It should trim any trailing slash
     */
    public function testTrimTrailing()
    {
        $path = AbsolutePath::fromString(__FILE__ . '/');
        $this->assertEquals(__FILE__, $path);
    }

    /**
     * @testdox It returns the file path as a string
     */
    public function testToString()
    {
        $this->assertEquals(
            __FILE__,
            AbsolutePath::fromString(__FILE__)->__toString()
        );
    }

    /**
     * @testdox It relativizes a given absolute path to a location using this path as a prefix.
     */
    public function testRelativizeToLocation()
    {
        $base = AbsolutePath::fromString(__DIR__ . '/../..');
        $file = AbsolutePath::fromString(__FILE__);

        $location = $base->relativizeToLocation($file);
        $this->assertInstanceOf(FileLocation::class, $location);
        $this->assertEquals('Unit/Domain/AbsolutePathTest.php', $location->__toString());
    }
}
