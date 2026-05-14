<?php

namespace App\Contracts;

use App\ValueObjects\FileInfo;
use App\ValueObjects\FolderInfo;

interface FileSystemInterface
{
    public function getFoldersTree(string $path = ''): array;

    public function getFilesList(string $path): array;

    public function createFolder(string $path, string $name): bool;

    public function uploadFile(string $path, $file): string;

    public function deleteFile(string $path): bool;

    public function getFileInfo(string $path): ?FileInfo;

    public function fileExists(string $path): bool;

    public function getSortedFolders(string $path): array;

    public function getSortedFiles(string $path): array;
}
