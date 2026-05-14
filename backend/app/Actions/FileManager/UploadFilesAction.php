<?php

namespace App\Actions\FileManager;

use App\Contracts\FileSystemInterface;
use App\ValueObjects\FileInfo;

class UploadFilesAction
{
    public function __construct(
        private FileSystemInterface $fileSystem
    ) {}

    public function execute(string $path, array $uploadedFiles): ?FileInfo
    {
        $lastUploadedPath = null;

        foreach ($uploadedFiles as $file) {
            $lastUploadedPath = $this->fileSystem->uploadFile($path, $file);
        }

        if ($lastUploadedPath) {
            return $this->fileSystem->getFileInfo($lastUploadedPath);
        }

        return null;
    }
}
