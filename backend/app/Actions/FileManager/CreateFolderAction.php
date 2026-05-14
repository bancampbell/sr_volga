<?php

namespace App\Actions\FileManager;

use App\Contracts\FileSystemInterface;

class CreateFolderAction
{
    public function __construct(
        private FileSystemInterface $fileSystem
    ) {}

    public function execute(string $currentPath, string $folderName): bool
    {
        return $this->fileSystem->createFolder($currentPath, $folderName);
    }
}
