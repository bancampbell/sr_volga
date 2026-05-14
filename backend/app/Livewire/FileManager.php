<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Actions\FileManager\LoadFilesAction;
use App\Actions\FileManager\LoadFoldersTreeAction;
use App\Actions\FileManager\CreateFolderAction;
use App\Actions\FileManager\UploadFilesAction;
use App\Actions\FileManager\DeleteFileAction;
use App\Actions\FileManager\GetFileInfoAction;
use App\Actions\FileManager\NavigateToFileAction;

class FileManager extends Component
{
    use WithFileUploads;

    public string $currentPath = '';
    public array $files = [];
    public array $foldersTree = [];
    public ?array $selectedFile = null;
    public string $newFolderName = '';
    public bool $showUploadModal = false;
    public array $uploadedFiles = [];

    protected $listeners = ['set-file-manager-path' => 'navigateToFile'];

    public function mount(): void
    {
        $this->refresh();
    }

    protected function refresh(): void
    {
        $loadFoldersTree = app(LoadFoldersTreeAction::class);
        $loadFiles = app(LoadFilesAction::class);

        $this->foldersTree = $loadFoldersTree->execute();
        $this->files = $loadFiles->execute($this->currentPath);
    }

    public function selectFolder(string $path): void
    {
        $this->currentPath = $path;

        $loadFoldersTree = app(LoadFoldersTreeAction::class);
        $loadFiles = app(LoadFilesAction::class);

        $this->foldersTree = $loadFoldersTree->execute();
        $this->files = $loadFiles->execute($this->currentPath);
        $this->selectedFile = null;
    }

    public function goBack(): void
    {
        if ($this->currentPath === '') {
            return;
        }

        $this->currentPath = dirname($this->currentPath);
        if ($this->currentPath === '.') {
            $this->currentPath = '';
        }

        $loadFoldersTree = app(LoadFoldersTreeAction::class);
        $loadFiles = app(LoadFilesAction::class);

        $this->foldersTree = $loadFoldersTree->execute();
        $this->files = $loadFiles->execute($this->currentPath);
        $this->selectedFile = null;
    }

    public function createFolderInline(): void
    {
        $this->validate([
            'newFolderName' => 'required|string|max:255|regex:/^[a-zA-Zа-яА-Я0-9_\-\s]+$/u',
        ]);

        $action = app(CreateFolderAction::class);

        if ($action->execute($this->currentPath, $this->newFolderName)) {
            $this->refresh();
            $this->newFolderName = '';
            $this->resetErrorBag();
        } else {
            $this->addError('newFolderName', 'Папка с таким именем уже существует');
        }
    }

    public function uploadFiles(): void
    {
        $this->validate([
            'uploadedFiles.*' => 'required|file|max:102400',
        ]);

        $action = app(UploadFilesAction::class);
        $uploadedFile = $action->execute($this->currentPath, $this->uploadedFiles);

        $this->refresh();
        $this->closeUploadModal();

        if ($uploadedFile) {
            $this->selectedFile = $uploadedFile->toArray();
        }
    }

    public function deleteSelectedFile(): void
    {
        if (!$this->selectedFile) {
            return;
        }

        $action = app(DeleteFileAction::class);
        $action->execute($this->selectedFile['path']);
        $this->selectedFile = null;
        $this->refresh();
    }

    public function toggleFileSelection(string $path): void
    {
        $isSameFile = $this->selectedFile && $this->selectedFile['path'] === $path;

        if ($isSameFile) {
            $this->selectedFile = null;
        } else {
            $action = app(GetFileInfoAction::class);
            $fileInfo = $action->execute($path);
            $this->selectedFile = $fileInfo?->toArray();
        }
    }

    public function insertFileUrl(string $url): void
    {
        $this->dispatch('file-selected', url: $url);
        $this->selectedFile = null;
    }

    public function navigateToFile(string $path): void
    {
        $action = app(NavigateToFileAction::class);
        $result = $action->execute($path);

        if (empty($result['fullPath'])) {
            return;
        }

        $this->currentPath = $result['currentPath'];
        $this->refresh();

        $getFileInfo = app(GetFileInfoAction::class);
        $fileInfo = $getFileInfo->execute($result['fullPath']);
        $this->selectedFile = $fileInfo?->toArray();
    }

    public function openUploadModal(): void
    {
        $this->showUploadModal = true;
    }

    public function closeUploadModal(): void
    {
        $this->showUploadModal = false;
        $this->reset(['uploadedFiles']);
    }

    public function closeFileInfo(): void
    {
        $this->selectedFile = null;
    }

    public function render()
    {
        return view('livewire.file-manager');
    }
}
