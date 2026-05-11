<?php

namespace App\Livewire;

use Livewire\Component;

class LinkModal extends Component
{
    public $open = false;
    public $linkUrl = '';
    public $linkText = '';
    public $linkTarget = '';
    public $linkTitle = '';
    public $activeTab = 'link';
    public $showFileManagerModal = false;

    protected $listeners = ['file-selected' => 'onFileSelected'];

    public function openModal()
    {
        $this->open = true;
    }

    public function openModalWithSelectedText()
    {
        $this->dispatch('get-selected-text');
    }

    public function closeModal()
    {
        $this->open = false;
        $this->showFileManagerModal = false;
        $this->reset(['linkUrl', 'linkText', 'linkTarget', 'linkTitle']);
    }

    public function insertLink()
    {
        $this->dispatch('insert-link', url: $this->linkUrl, text: $this->linkText, target: $this->linkTarget, title: $this->linkTitle);
        $this->closeModal();
    }

    public function openFileManager()
    {
        $this->showFileManagerModal = true;
        // Передаём текущий URL файловому менеджеру
        if ($this->linkUrl) {
            $this->dispatch('set-file-manager-path', path: $this->linkUrl);
        }
    }

    public function closeFileManagerModal()
    {
        $this->showFileManagerModal = false;
    }

    public function onFileSelected($url)
    {
        $this->linkUrl = $url;
        $this->closeFileManagerModal();
    }

    public function render()
    {
        return view('livewire.link-modal', [
            'open' => $this->open,
        ]);
    }
}
