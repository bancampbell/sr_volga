<?php

namespace App\Actions\FileManager;

use App\Contracts\FileSystemInterface;
use App\ValueObjects\FileInfo;

class GetFileInfoAction
{
    public function __construct(
        private FileSystemInterface $fileSystem
    ) {}

    public function execute(string $path): ?FileInfo
    {
        return $this->fileSystem->getFileInfo($path);
    }
}
