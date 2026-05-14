<?php

namespace App\Actions\FileManager;

use App\Contracts\FileSystemInterface;

class LoadFilesAction
{
    public function __construct(
        private FileSystemInterface $fileSystem
    ) {}

    public function execute(string $path): array
    {
        return $this->fileSystem->getFilesList($path);
    }
}
