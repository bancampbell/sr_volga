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
    public $categories = [];
    public $expandedCategories = []; // массив раскрытых категорий

    protected $listeners = ['file-selected' => 'onFileSelected'];

    public $selectedMaterialId = null;

    public function mount()
    {
        $this->loadCategories();
        $this->loadMaterials();
        if ($this->linkUrl) {
            $this->findAndSelectMaterialByUrl($this->linkUrl);
        }
    }

    public function loadCategories()
    {
        $this->categories = \App\Models\Category::with('children')->whereNull('parent_id')->get();
    }

    public function loadMaterials()
    {
        $query = \App\Models\Material::query();

        if (!empty($this->searchTerm)) {
            $query->where('title', 'like', '%' . $this->searchTerm . '%');
        }

        $this->materials = $query->limit(50)->get();
    }

    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->expandedCategories)) {
            $this->expandedCategories = array_diff($this->expandedCategories, [$categoryId]);
        } else {
            $this->expandedCategories[] = $categoryId;
        }
    }

    public function selectMaterial($slug)
    {
        $material = \App\Models\Material::where('slug', $slug)->first();
        if ($material) {
            if ($this->selectedMaterialId === $material->id) {
                $this->selectedMaterialId = null;
                $this->linkUrl = '';
                $this->linkText = '';
            } else {
                $this->selectedMaterialId = $material->id;
                $this->linkUrl = url('/materials/' . $material->slug);
                $this->linkText = $this->linkText ?: $material->title;

                // Раскрываем категорию при выборе материала
                if ($material->category_id && !in_array($material->category_id, $this->expandedCategories)) {
                    $this->expandedCategories[] = $material->category_id;
                    $category = $material->category;
                    while ($category && $category->parent_id) {
                        $category = $category->parent;
                        if ($category && !in_array($category->id, $this->expandedCategories)) {
                            $this->expandedCategories[] = $category->id;
                        }
                    }
                }
            }
        }
    }

    public function updatedSearchTerm()
    {
        $this->loadMaterials();
        $this->loadCategories(); // обновляем категории (для счётчиков)
    }

    public function openModal()
    {
        $this->open = true;

        // Если есть linkUrl, пытаемся найти материал и раскрыть категорию
        if ($this->linkUrl) {
            $this->findAndSelectMaterialByUrl($this->linkUrl);
        }
    }

    public function findAndSelectMaterialByUrl($url)
    {
        // Извлекаем slug из URL /materials/slug
        if (preg_match('/\/materials\/([^\/]+)/', $url, $matches)) {
            $slug = $matches[1];
            $material = \App\Models\Material::where('slug', $slug)->first();
            if ($material) {
                $this->selectedMaterialId = $material->id;
                $this->linkText = $material->title;

                // Раскрываем категорию и родительские категории
                if ($material->category_id) {
                    $this->expandedCategories[] = $material->category_id;
                    $category = \App\Models\Category::find($material->category_id);
                    while ($category && $category->parent_id) {
                        $category = $category->parent;
                        if ($category && !in_array($category->id, $this->expandedCategories)) {
                            $this->expandedCategories[] = $category->id;
                        }
                    }
                }
            }
        }
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
        $this->loadCategories();
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
        $this->selectedMaterialId = null;
        $this->closeFileManagerModal();
    }



    public function expandCategoryForMaterial($materialId)
    {
        $material = \App\Models\Material::find($materialId);
        if ($material && $material->category_id) {
            // Раскрываем родительские категории
            $category = $material->category;
            $this->expandedCategories[] = $category->id;

            while ($category->parent_id) {
                $category = $category->parent;
                if (!in_array($category->id, $this->expandedCategories)) {
                    $this->expandedCategories[] = $category->id;
                }
            }
        }
    }





    public function render()
    {
        return view('livewire.link-modal', [
            'open' => $this->open,
        ]);
    }
}
