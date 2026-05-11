<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FileManager extends Component
{
    use WithFileUploads;

    public $currentPath = '';
    public $files = [];
    public $foldersTree = [];
    public $selectedFile = null;
    public $showNewFolderModal = false;
    public $newFolderName = '';

    public $showUploadModal = false;
    public $uploadedFiles = [];

    protected $listeners = ['set-file-manager-path' => 'navigateToFile'];


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
        $this->files = [];

        $disk = Storage::disk('public');
        $path = $this->currentPath;

        $allFolders = $disk->directories($path);

        $foldersList = [];
        foreach ($allFolders as $folder) {
            $name = basename($folder);

            $sortNumber = null;
            if (preg_match('/^\d+$/', $name)) {
                $sortNumber = (int)$name;
            }

            $foldersList[] = [
                'original_name' => $name,
                'sort_number' => $sortNumber,
                'path' => $folder,
                'name' => $name,
                'type' => 'folder'
            ];
        }

        usort($foldersList, function($a, $b) {
            if ($a['sort_number'] !== null && $b['sort_number'] !== null) {
                return $a['sort_number'] <=> $b['sort_number'];
            }
            if ($a['sort_number'] !== null) {
                return -1;
            }
            if ($b['sort_number'] !== null) {
                return 1;
            }
            return strcmp($a['original_name'], $b['original_name']);
        });

        $allFiles = $disk->files($path);
        $filesList = [];
        foreach ($allFiles as $file) {
            $filesList[] = [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::url($file),
                'size' => $this->formatSize($disk->size($file)),
                'lastModified' => date('d/m/Y, H:i', $disk->lastModified($file)),
                'type' => 'file',
            ];
        }

        usort($filesList, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        $this->files = array_merge($foldersList, $filesList);
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

    public function openNewFolderModal()
    {
        $this->newFolderName = '';
        $this->showNewFolderModal = true;
    }

    public function closeNewFolderModal()
    {
        $this->showNewFolderModal = false;
        $this->newFolderName = '';
        $this->resetErrorBag();
    }

    public function createFolder()
    {
        $this->validate([
            'newFolderName' => 'required|string|max:255|regex:/^[a-zA-Zа-яА-Я0-9_\-\s]+$/u',
        ], [
            'newFolderName.required' => 'Введите название папки',
            'newFolderName.regex' => 'Название может содержать буквы, цифры, пробелы, дефис и нижнее подчёркивание',
        ]);

        $disk = Storage::disk('public');
        $path = $this->currentPath
            ? $this->currentPath . '/' . $this->newFolderName
            : $this->newFolderName;

        if (!$disk->exists($path)) {
            $disk->makeDirectory($path);
            $this->loadFiles();
            $this->loadFoldersTree();
            $this->closeNewFolderModal();
        } else {
            $this->addError('newFolderName', 'Папка с таким именем уже существует');
        }
    }

    public function createFolderInline()
    {
        $this->validate([
            'newFolderName' => 'required|string|max:255|regex:/^[a-zA-Zа-яА-Я0-9_\-\s]+$/u',
        ], [
            'newFolderName.required' => 'Введите название папки',
            'newFolderName.regex' => 'Название может содержать буквы, цифры, пробелы, дефис и нижнее подчёркивание',
        ]);

        $disk = Storage::disk('public');
        $path = $this->currentPath
            ? $this->currentPath . '/' . $this->newFolderName
            : $this->newFolderName;

        if (!$disk->exists($path)) {
            $disk->makeDirectory($path);
            $this->loadFiles();
            $this->loadFoldersTree();
            $this->newFolderName = '';
            $this->resetErrorBag();
        } else {
            $this->addError('newFolderName', 'Папка с таким именем уже существует');
        }
    }

    public function openUploadModal()
    {
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
        $this->reset(['uploadedFiles']);
    }

    public function uploadFiles()
    {
        $this->validate([
            'uploadedFiles.*' => 'required|file|max:102400',
        ]);

        $disk = Storage::disk('public');
        $path = $this->currentPath ?: '';

        $lastUploadedFile = null;

        foreach ($this->uploadedFiles as $file) {
            $originalName = $file->getClientOriginalName();
            $destination = $path ? $path . '/' . $originalName : $originalName;

            $counter = 1;
            $name = pathinfo($originalName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            while ($disk->exists($destination)) {
                $newName = $name . '_' . $counter . '.' . $extension;
                $destination = $path ? $path . '/' . $newName : $newName;
                $counter++;
            }

            $disk->putFileAs($path, $file, basename($destination));
            $lastUploadedFile = $destination;
        }

        $this->loadFiles();
        $this->closeUploadModal();

        // Выделяем последний загруженный файл
        if ($lastUploadedFile && $disk->exists($lastUploadedFile)) {
            $this->selectedFile = [
                'name' => basename($lastUploadedFile),
                'path' => $lastUploadedFile,
                'url' => Storage::url($lastUploadedFile),
                'size' => $this->formatSize($disk->size($lastUploadedFile)),
                'lastModified' => date('d/m/Y, H:i', $disk->lastModified($lastUploadedFile)),
            ];
        }
    }

    public function navigateToFile($path)
    {
        if (empty($path)) return;

        // Проверяем, что путь ведёт к файлу в storage
        if (!str_contains($path, '/storage/')) {
            return; // Игнорируем, если это не файл (например, ссылка на материал)
        }

        $relativePath = str_replace('/storage/', '', $path);

        $directory = dirname($relativePath);
        $filename = basename($relativePath);

        if ($directory !== '.' && $directory !== '/') {
            $this->currentPath = $directory;
            $this->loadFiles();
        } else {
            $this->currentPath = '';
            $this->loadFiles();
        }

        $disk = Storage::disk('public');
        $fullPath = ($this->currentPath ? $this->currentPath . '/' : '') . $filename;

        if ($disk->exists($fullPath)) {
            $this->selectedFile = [
                'name' => $filename,
                'path' => $fullPath,
                'url' => Storage::url($fullPath),
                'size' => $this->formatSize($disk->size($fullPath)),
                'lastModified' => date('d/m/Y, H:i', $disk->lastModified($fullPath)),
            ];
        }
    }

    public function toggleFileSelection($path)
    {
        $this->setSelectedFile($path);
    }

    private function setSelectedFile($path)
    {
        $disk = Storage::disk('public');

        if ($this->selectedFile && $this->selectedFile['path'] === $path) {
            $this->selectedFile = null;
        } else {
            $this->selectedFile = [
                'name' => basename($path),
                'path' => $path,
                'url' => Storage::url($path),
                'size' => $this->formatSize($disk->size($path)),
                'lastModified' => date('d/m/Y, H:i', $disk->lastModified($path)),
            ];
        }
    }

    public function render()
    {
        return view('livewire.file-manager');
    }
}
