<?php

namespace DTL\Filesystem\Adapter\Git;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Adapter\Git\GitFileIterator;
use DTL\Filesystem\Adapter\Simple\SimpleFilesystem;

class GitFilesystem extends SimpleFilesystem
{
    private $path;

    public function __construct(FilePath $path)
    {
        $this->path = $path;

        if (false === file_exists($path->__toString() . '/.git')) {
            throw new \RuntimeException(
                'The cwd does not seem to be a git repository root (could not find .git folder)'
            );
        }
    }

    public function fileList(): FileList
    {
        $gitFiles = $this->exec('ls-files');
        $files = [];

        foreach ($gitFiles as $gitFile) {
            $files[] = $this->path->makeAbsoluteFromString($gitFile);
        }

        return FileList::fromIterator(new \ArrayIterator($files));
    }

    public function remove(FilePath $path)
    {
        $this->exec(sprintf('rm -f %s', $path->path()));
    }

    public function move(FilePath $srcPath, FilePath $destPath)
    {
        $this->exec(sprintf(
            'mv %s %s',
            $srcPath->path(),
            $destPath->path()
        ));
    }

    public function copy(FilePath $srcPath, FilePath $destPath)
    {
        copy(
            $srcPath->path(),
            $destPath->path()
        );
        $this->exec(sprintf('add %s', $destPath->__toString()));
    }

    public function createPath(string $path): FilePath
    {
        return $this->path->makeAbsoluteFromString($path);
    }

    private function exec($command)
    {
        $current = getcwd();
        chdir((string) $this->path);
        exec(sprintf('git %s 2>&1', $command), $output, $return);
        chdir($current);

        if ($return !== 0) {
            throw new \InvalidArgumentException(sprintf(
                'Could not execute git command "git %s", exit code "%s", output "%s"',
                $command, $return, implode(PHP_EOL, $output)
            ));
        }

        return $output;
    }
}

