<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\Cwd;

class CwdTest extends TestCase
{
    /**
     * @testdox Throws an exception if not absolute path.
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cwd must be absolute
     */
    public function testCwdIsAbsolute()
    {
        Cwd::fromCwd('foobar');
    }

    /**
     * @testdox It relativizes a FilePath
     */
    public function testCreateWith()
    {
        $cwd = Cwd::fromCwd('/Foobar');
        $path = $cwd->createPathWith('barfoo');
        $this->assertEquals('barfoo', $path->__toString());
        $this->assertEquals('/Foobar/barfoo', $path->absolutePath());
    }
}
