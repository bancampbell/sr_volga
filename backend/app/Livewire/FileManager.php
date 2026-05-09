<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class FileManager extends Component
{
    public $currentPath = '';
    public $files = [];
    public $foldersTree = [];
    public $selectedFile = null;

    public function mount()
    {
        $this->loadFoldersTree();
        $this->loadFiles();
    }

    public function loadFoldersTree()
    {
        $disk = Storage::disk('public');
        $this->foldersTree = $this->buildTree($disk, '');
    }

    private function buildTree($disk, $path)
    {
        $result = [];
        $directories = $disk->directories($path);
        foreach ($directories as $dir) {
            $name = basename($dir);
            $result[] = [
                'name' => $name,
                'path' => $dir,
                'children' => $this->buildTree($disk, $dir),
            ];
        }
        return $result;
    }

    public function loadFiles()
    {
        $disk = Storage::disk('public');
        $path = $this->currentPath;

        $subFolders = collect($disk->directories($path))
            ->map(fn($dir) => [
                'name' => basename($dir),
                'path' => $dir,
                'type' => 'folder',
            ])
            ->values()
            ->toArray();

        $files = collect($disk->files($path))
            ->map(fn($file) => [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::url($file),
                'size' => $this->formatSize($disk->size($file)),
                'lastModified' => date('d/m/Y, H:i', $disk->lastModified($file)),
                'type' => 'file',
            ])
            ->values()
            ->toArray();

        $this->files = array_merge($subFolders, $files);
    }

    public function selectFolder($path)
    {
        $this->currentPath = $path;
        $this->loadFiles();
        $this->selectedFile = null;
    }

    public function goBack()
    {
        if ($this->currentPath === '') return;
        $this->currentPath = dirname($this->currentPath);
        if ($this->currentPath === '.') $this->currentPath = '';
        $this->loadFiles();
        $this->selectedFile = null;
    }

    public function showFileDetails($path)
    {
        $disk = Storage::disk('public');
        $this->selectedFile = [
            'name' => basename($path),
            'path' => $path,
            'url' => Storage::url($path),
            'size' => $this->formatSize($disk->size($path)),
            'lastModified' => date('d/m/Y, H:i', $disk->lastModified($path)),
        ];
    }

    public function closeFileInfo()
    {
        $this->selectedFile = null;
    }

    public function insertFileUrl($url)
    {
        $this->dispatch('file-selected', url: $url);
        $this->closeFileInfo();
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' ГБ';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' МБ';
        if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' КБ';
        return $bytes . ' Б';
    }

    public function render()
    {
        return view('livewire.file-manager');
    }
}
