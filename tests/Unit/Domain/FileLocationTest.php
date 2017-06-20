<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\FileLocation;

class FileLocationTest extends TestCase
{
    /**
     * @testdox It returns its extension.
     */
    public function testExtension()
    {
        $this->assertEquals('php', FileLocation::fromString('foo/bar.inc.php')->extension());
    }
}
