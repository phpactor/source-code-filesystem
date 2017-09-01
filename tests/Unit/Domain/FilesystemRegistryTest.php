<?php

namespace Phpactor\Filesystem\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Phpactor\Filesystem\Domain\Filesystem;
use Phpactor\Filesystem\Domain\FilesystemRegistry;

class Domain extends TestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function setUp()
    {
        $this->filesystem = $this->prophesize(Filesystem::class);
    }

    public function testRetrievesFilesystems()
    {
        $registry = $this->createRegistry([
            'foobar' => $this->filesystem->reveal()
        ]);

        $filesystem = $registry->get('foobar');

        $this->assertEquals($this->filesystem->reveal(), $filesystem);
    }

    public function testExceptionOnNotFound()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown filesystem "barfoo"');
        $registry = $this->createRegistry([
            'foobar' => $this->filesystem->reveal()
        ]);

        $registry->get('barfoo');
    }

    private function createRegistry(array $filesystems)
    {
        return new FilesystemRegistry($filesystems);
    }
}

