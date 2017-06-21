<?php

namespace DTL\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use DTL\Filesystem\Domain\FilePath;

class FileLocationTest extends TestCase
{
    /**
     * @testdox It returns its extension.
     */
    public function testExtension()
    {
        $this->assertEquals('php', FilePath::fromPathInCurrentCwd('foo/bar.inc.php')->extension());
    }
}
