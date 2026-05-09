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


    public function openModal()
    {
        $this->open = true;
        $this->render();
    }

    public function openModalWithSelectedText()
    {
        $this->dispatch('get-selected-text');
    }

    public function closeModal()
    {
        $this->open = false;
        $this->reset(['linkUrl', 'linkText', 'linkTarget']);
        $this->render();
    }

    public function insertLink()
    {
        $this->dispatch('insert-link', url: $this->linkUrl, text: $this->linkText, target: $this->linkTarget, title: $this->linkTitle);
        $this->closeModal();
    }
    public function openFileManager()
    {
        $this->dispatch('open-filemanager');
    }


    public function render()
    {
        return view('livewire.link-modal', [
            'open' => $this->open,
        ]);
    }
}
