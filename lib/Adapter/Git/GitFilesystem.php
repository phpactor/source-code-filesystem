<?php

namespace DTL\Filesystem\Adapter\Git;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Adapter\Git\GitFileIterator;
use DTL\Filesystem\Domain\Cwd;

class GitFilesystem implements Filesystem
{
    private $cwd;

    public function __construct(Cwd $cwd)
    {
        $this->cwd = $cwd;
    }

    public static function fromRootPath(string $path)
    {
        return new self($path);
    }

    public function fileList(): FileList
    {
        $gitFiles = $this->exec('ls-files');
        $files = [];

        foreach ($gitFiles as $gitFile) {
            $files[] = FilePath::fromCwdAndPath($this->cwd, $gitFile);
        }

        return FileList::fromIterator(new \ArrayIterator($files));
    }

    public function remove(FilePath $path)
    {
        $this->exec(sprintf('rm -f %s', $path->__toString()));
    }

    public function move(FilePath $srcPath, FilePath $destPath)
    {
        $this->exec(sprintf(
            'mv %s %s',
            $srcPath->__toString(),
            $destPath->__toString()
        ));
    }

    public function copy(FilePath $srcPath, FilePath $destPath)
    {
        copy(
            $srcPath->__toString(),
            $destPath->__toString()
        );
        $this->exec(sprintf('add %s', $destPath->__toString()));
    }

    private function exec($command)
    {
        $current = getcwd();
        chdir((string) $this->cwd);
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

