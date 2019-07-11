<?php

namespace Phpactor\Filesystem\Adapter\Git;

use Phpactor\Filesystem\Domain\FileList;
use Phpactor\Filesystem\Domain\FileListProvider;
use Phpactor\Filesystem\Domain\FilePath;
use Phpactor\Filesystem\Adapter\Simple\SimpleFilesystem;
use Phpactor\Filesystem\Domain\CopyReport;
use Phpactor\Filesystem\Domain\Exception\NotSupported;
use RuntimeException;

class GitFilesystem extends SimpleFilesystem
{
    private $path;

    public function __construct($path, FileListProvider $fileListProvider = null)
    {
        $path = FilePath::fromUnknown($path);
        parent::__construct($path, $fileListProvider);
        $this->path = $path;

        if (false === file_exists($path->__toString().'/.git')) {
            throw new NotSupported(
                'The cwd does not seem to be a git repository root (could not find .git folder)'
            );
        }
    }

    public function fileList(): FileList
    {
        // list all files (tracked and non-tracked) but ignore those in .gitignore
        $gitFiles = $this->exec('ls-files --cached --others --exclude-standard');
        $files = [];

        foreach ($gitFiles as $gitFile) {
            $files[] = new \SplFileInfo((string) $this->path->makeAbsoluteFromString($gitFile));
        }

        return FileList::fromIterator(new \ArrayIterator($files));
    }

    public function remove($path)
    {
        $path = FilePath::fromUnknown($path);
        if (false === $this->trackedByGit($path)) {
            return parent::remove($path);
        }

        $this->exec(sprintf('rm -f %s', $path->path()));
    }

    public function move($srcPath, $destPath)
    {
        $srcPath = FilePath::fromUnknown($srcPath);
        $destPath = FilePath::fromUnknown($destPath);

        if (false === $this->trackedByGit($srcPath)) {
            return parent::move($srcPath, $destPath);
        }

        $this->exec(sprintf(
            'mv %s %s',
            $srcPath->path(),
            $destPath->path()
        ));
    }

    public function copy($srcPath, $destPath): CopyReport
    {
        $srcPath = FilePath::fromUnknown($srcPath);
        $destPath = FilePath::fromUnknown($destPath);
        $list = parent::copy($srcPath, $destPath);
        $this->exec(sprintf('add %s', $destPath->__toString()));

        return $list;
    }

    public function createPath(string $path): FilePath
    {
        return $this->path->makeAbsoluteFromString($path);
    }

    private function exec($command)
    {
        $current = getcwd();

        if (false === $current) {
            throw new RuntimeException('Could not determine cwd');
        }

        chdir((string) $this->path);
        exec(sprintf('git %s 2>&1', $command), $output, $return);
        chdir($current);

        if ($return !== 0) {
            throw new \InvalidArgumentException(sprintf(
                'Could not execute git command "git %s", exit code "%s", output "%s"',
                $command,
                $return,
                implode(PHP_EOL, $output)
            ));
        }

        return $output;
    }

    private function trackedByGit(FilePath $file)
    {
        $out = $this->exec(sprintf('ls-files %s', (string) $file));

        return false === empty($out);
    }
}
