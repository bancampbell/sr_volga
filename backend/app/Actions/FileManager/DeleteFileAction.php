<?php

namespace App\Actions\FileManager;

use App\Contracts\FileSystemInterface;

class DeleteFileAction
{
    public function __construct(
        private FileSystemInterface $fileSystem
    ) {}

    public function execute(string $path): bool
    {
        return $this->fileSystem->deleteFile($path);
    }
}
