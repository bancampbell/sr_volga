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
    public $showNewFolderModal = false;
    public $newFolderName = '';

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

        // Получаем ВСЕ папки
        $allFolders = $disk->directories($path);

        // Преобразуем в массив с понятным ключом
        $foldersList = [];
        foreach ($allFolders as $folder) {
            $name = basename($folder);

            // Вычисляем числовой ключ, если имя состоит из цифр
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

        // Жесткая сортировка
        usort($foldersList, function($a, $b) {
            // Если оба — числа
            if ($a['sort_number'] !== null && $b['sort_number'] !== null) {
                return $a['sort_number'] <=> $b['sort_number'];
            }

            // Если только a — число
            if ($a['sort_number'] !== null) {
                return -1;
            }

            // Если только b — число
            if ($b['sort_number'] !== null) {
                return 1;
            }

            // Иначе — по алфавиту
            return strcmp($a['original_name'], $b['original_name']);
        });

        // Сортируем файлы по имени
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

        \Log::info('Sorted folders:', array_column($foldersList, 'name'));


        // Объединяем папки и файлы
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



    public function render()
    {
        return view('livewire.file-manager');
    }



}
