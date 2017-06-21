<?php

namespace DTL\Filesystem\Adapter\Git;

use DTL\Filesystem\Domain\Filesystem;
use DTL\Filesystem\Domain\FileList;
use DTL\Filesystem\Domain\FilePath;
use DTL\Filesystem\Domain\AbsoluteExistingPath;
use DTL\Filesystem\Adapter\Git\GitFileIterator;

class GitFilesystem implements Filesystem
{
    private $path;

    public function __construct(AbsoluteExistingPath $path)
    {
        $this->path = $path;
    }

    public static function fromRootPath(string $path)
    {
        return new self(AbsoluteExistingPath::fromString($path));
    }

    public function fileList(): FileList
    {
        $gitFiles = $this->exec('ls-files');
        $files = [];

        foreach ($gitFiles as $gitFile) {
            $files[] = FilePath::fromString($gitFile);
        }

        return FileList::fromIterator(new \ArrayIterator($files));
    }

    public function remove(FilePath $location)
    {
        $this->exec(sprintf('rm -f %s', $location->__toString()));
    }

    public function move(FilePath $srcPath, FilePath $destPath)
    {
        $this->exec(sprintf(
            'mv %s %s',
            $srcPath->__toString(),
            $destPath->__toString()
        ));
    }

    public function copy(FilePath $srcLocation, FilePath $destLocation)
    {
        copy(
            $srcLocation->__toString(),
            $destLocation->__toString()
        );
        $this->exec(sprintf('add %s', $destLocation->__toString()));
    }

    public function absolutePath(FilePath $location)
    {
        return $this->path->concatLocation($location);
    }

    private function exec($command)
    {
        $current = getcwd();
        chdir($this->path->__toString());
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

