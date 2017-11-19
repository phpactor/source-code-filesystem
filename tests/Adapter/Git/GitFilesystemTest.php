<?php

namespace Phpactor\Filesystem\Tests\Adapter\Git;

use Phpactor\Filesystem\Adapter\Git\GitFilesystem;
use Phpactor\Filesystem\Domain\FilePath;
use Phpactor\Filesystem\Tests\Adapter\AdapterTestCase;
use Phpactor\Filesystem\Domain\Filesystem;
use Phpactor\Filesystem\Domain\Cwd;

class GitFilesystemTest extends AdapterTestCase
{
    public function setUp()
    {
        parent::setUp();
        chdir($this->workspacePath());
        exec('git init');
        exec('git add *');
    }

    protected function filesystem(): Filesystem
    {
        return new GitFilesystem(FilePath::fromString($this->workspacePath()));
    }

    /**
     * It sohuld throw an exception if the cwd does not have a .git folder.
     *
     * @expectedException RuntimeException
     * @expectedExceptionMessage The cwd does not seem to be
     */
    public function testNoGitFolder()
    {
        return new GitFilesystem(FilePath::fromString(__DIR__));
    }

    /**
     * It should fallback to simple filesystem if file is not under VC.
     */
    public function testMoveNonVersionedFile()
    {
        touch($this->workspacePath() . '/Test.php');
        $this->filesystem()->move(FilePath::fromString($this->workspacePath() . '/Test.php'), FilePath::fromString($this->workspacePath() . '/Foobar.php'));
    }

    public function testMoveNonVersionedFileToNonExistingDirectory()
    {
        touch($this->workspacePath() . '/Test.php');
        $this->filesystem()->move(FilePath::fromString($this->workspacePath() . '/Test.php'), FilePath::fromString($this->workspacePath() . '/NotExisting/Foobar.php'));
    }
    /**
     * It should fallback to simple filesystem if file is not under VC.
     */
    public function testRemoveNonVersionedFile()
    {
        touch($this->workspacePath() . '/Test.php');
        $this->filesystem()->remove(FilePath::fromString($this->workspacePath() . '/Test.php'));
    }

    /**
     * It lists untracked files
     */
    public function testListUntracked()
    {
        $path = $this->workspacePath() . '/Test.php';
        touch($path);
        $this->assertTrue($this->filesystem()->fileList()->contains(FilePath::fromString($path)));
    }
}
