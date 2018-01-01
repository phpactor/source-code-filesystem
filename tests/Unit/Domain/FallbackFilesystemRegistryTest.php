<?php

namespace Phpactor\Filesystem\Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use Phpactor\Filesystem\Domain\FilesystemRegistry;
use Phpactor\Filesystem\Domain\Filesystem;
use Phpactor\Filesystem\Domain\FallbackFilesystemRegistry;

class FallbackFilesystemRegistryTest extends TestCase
{
    /**
     * @var ObjectProphecy|FilesystemRegistry
     */
    private $innerRegistry;

    /**
     * @var FallbackFilesystemRegistry
     */
    private $registry;

    public function setUp()
    {
        $this->innerRegistry = $this->prophesize(FilesystemRegistry::class);
        $this->registry = new FallbackFilesystemRegistry($this->innerRegistry->reveal(), 'bar');
        $this->filesystem1 = $this->prophesize(Filesystem::class);
    }

    public function testDecoration()
    {
        $this->innerRegistry->names()->willReturn([ 'one' ]);
        $this->innerRegistry->has('foo')->willReturn(true);
        $this->innerRegistry->get('foo')->willReturn($this->filesystem1->reveal());

        $this->assertEquals([ 'one' ], $this->registry->names());
        $this->assertTrue($this->registry->has('foo'));
        $this->assertSame($this->filesystem1->reveal(), $this->registry->get('foo'));
    }

    public function testFallback()
    {
        $this->innerRegistry->has('foo')->willReturn(false);
        $this->innerRegistry->get('bar')->willReturn($this->filesystem1->reveal());

        $filesystem = $this->registry->get('foo');
        $this->assertSame($this->filesystem1->reveal(), $filesystem);
    }
}
