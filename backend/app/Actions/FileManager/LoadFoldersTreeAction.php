<?php

namespace App\Actions\FileManager;

use App\Contracts\FileSystemInterface;

class LoadFoldersTreeAction
{
    public function __construct(
        private FileSystemInterface $fileSystem
    ) {}

    public function execute(string $path = ''): array
    {
        return $this->fileSystem->getFoldersTree($path);
    }
}
