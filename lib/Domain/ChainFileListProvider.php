<?php

namespace Phpactor\Filesystem\Domain;

use Phpactor\Filesystem\Domain\FileList;

class ChainFileListProvider implements FileListProvider
{
    private $providers;

    public function __construct(array $providers)
    {
        foreach ($providers as $provider) {
            $this->add($provider);
        }
    }

    public function fileList(): FileList
    {
        $appendIterator = new \AppendIterator();
        foreach ($this->providers as $provider) {
            $iterator = $provider->fileList()->getIterator();

            if ($iterator instanceof AppendIterator) {
                foreach ($iterator as $subIterator) {
                    $appendIterator->append($subIterator);
                }
                continue;
            }
            $appendIterator->append($iterator);
        }

        return FileList::fromIterator($iterator);
    }

    private function add(FileListProvider $provider)
    {
        $this->providers[] = $provider;
    }
}
