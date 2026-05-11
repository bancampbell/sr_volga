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

    public $materials = [];
    public $searchTerm = '';

    protected $listeners = ['file-selected' => 'onFileSelected'];

    public $selectedMaterialId = null;

    public function mount()
    {
        $this->loadMaterials();
    }

    public function loadMaterials()
    {
        $query = \App\Models\Material::query();

        if (!empty($this->searchTerm)) {
            $query->where('title', 'like', '%' . $this->searchTerm . '%');
        }

        $this->materials = $query->orderBy('title')->get(); // или ->paginate(100)
    }

    public function updatedSearchTerm()
    {
        $this->loadMaterials();
    }

    public function selectMaterial($slug)
    {
        $material = \App\Models\Material::where('slug', $slug)->first();
        if ($material) {
            // Если выбран тот же материал — снимаем выделение
            if ($this->selectedMaterialId === $material->id) {
                $this->selectedMaterialId = null;
                $this->linkUrl = '';
                $this->linkText = '';
            } else {
                $this->selectedMaterialId = $material->id;
                $this->linkUrl = url('/materials/' . $material->slug);
                $this->linkText = $this->linkText ?: $material->title;
            }
        }
    }

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
        $this->reset(['linkUrl', 'linkText', 'linkTarget', 'linkTitle', 'searchTerm']);
        $this->loadMaterials();
    }

    public function insertLink()
    {
        $this->dispatch('insert-link', url: $this->linkUrl, text: $this->linkText, target: $this->linkTarget, title: $this->linkTitle);
        $this->closeModal();
    }

    public function openFileManager()
    {
        $this->showFileManagerModal = true;
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
        $this->selectedMaterialId = null; // Сбрасываем выделение материала
        $this->closeFileManagerModal();
    }

    public function render()
    {
        return view('livewire.link-modal', [
            'open' => $this->open,
        ]);
    }
}
