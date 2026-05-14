<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Contracts\FileSystemInterface;
use App\Traits\WithFileSize;
use App\ValueObjects\FileInfo;
use App\ValueObjects\FolderInfo;

class FileSystemService implements FileSystemInterface
{
    use WithFileSize;

    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('public');
    }

    public function getFoldersTree(string $path = ''): array
    {
        return $this->buildTree($path);
    }

    protected function buildTree(string $path): array
    {
        $result = [];
        foreach ($this->disk->directories($path) as $dir) {
            $result[] = [
                'name' => basename($dir),
                'path' => $dir,
                'children' => $this->buildTree($dir),
            ];
        }
        return $result;
    }

    public function getFilesList(string $path): array
    {
        return array_merge(
            $this->getSortedFolders($path),
            $this->getSortedFiles($path)
        );
    }

    public function getSortedFolders(string $path): array
    {
        $folders = [];
        foreach ($this->disk->directories($path) as $folder) {
            $name = basename($folder);
            $folders[] = [
                'original_name' => $name,
                'sort_number' => is_numeric($name) ? (int)$name : null,
                'path' => $folder,
                'name' => $name,
                'type' => 'folder'
            ];
        }

        usort($folders, function ($a, $b) {
            if ($a['sort_number'] !== null && $b['sort_number'] !== null) {
                return $a['sort_number'] <=> $b['sort_number'];
            }
            if ($a['sort_number'] !== null) return -1;
            if ($b['sort_number'] !== null) return 1;
            return strcmp($a['original_name'], $b['original_name']);
        });

        return $folders;
    }

    public function getSortedFiles(string $path): array
    {
        $files = [];
        foreach ($this->disk->files($path) as $file) {
            $files[] = [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::url($file),
                'size' => $this->formatSize($this->disk->size($file)),
                'lastModified' => date('d/m/Y, H:i', $this->disk->lastModified($file)),
                'type' => 'file',
            ];
        }

        usort($files, fn($a, $b) => strcmp($a['name'], $b['name']));
        return $files;
    }

    public function createFolder(string $path, string $name): bool
    {
        $fullPath = $path ? $path . '/' . $name : $name;

        if ($this->disk->exists($fullPath)) {
            return false;
        }

        return $this->disk->makeDirectory($fullPath);
    }

    public function uploadFile(string $path, $file): string
    {
        $originalName = $file->getClientOriginalName();
        $destination = $path ? $path . '/' . $originalName : $originalName;
        $counter = 1;
        $name = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();

        while ($this->disk->exists($destination)) {
            $newName = $name . '_' . $counter . '.' . $extension;
            $destination = $path ? $path . '/' . $newName : $newName;
            $counter++;
        }

        $this->disk->putFileAs($path, $file, basename($destination));
        return $destination;
    }

    public function deleteFile(string $path): bool
    {
        if (!$this->disk->exists($path)) {
            return false;
        }
        return $this->disk->delete($path);
    }

    public function getFileInfo(string $path): ?FileInfo
    {
        if (!$this->disk->exists($path)) {
            return null;
        }

        return new FileInfo(
            name: basename($path),
            path: $path,
            url: Storage::url($path),
            size: $this->formatSize($this->disk->size($path)),
            lastModified: date('d/m/Y, H:i', $this->disk->lastModified($path)),
        );
    }

    public function fileExists(string $path): bool
    {
        return $this->disk->exists($path);
    }
}
