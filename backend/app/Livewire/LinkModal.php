<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\CategoryMaterialService;
use App\Actions\LinkModal\ExpandCategoryTreeAction;
use App\ValueObjects\MaterialInfo;

class LinkModal extends Component
{
    public bool $open = false;
    public string $linkUrl = '';
    public string $linkText = '';
    public string $linkTarget = '';
    public string $linkTitle = '';
    public string $activeTab = 'link';
    public bool $showFileManagerModal = false;

    public $materials = [];
    public string $searchTerm = '';
    public $categories = [];
    public array $expandedCategories = [];
    public ?int $selectedMaterialId = null;

    protected CategoryMaterialService $categoryService;

    protected $listeners = [
        'file-selected' => 'onFileSelected',
        'set-link-text' => 'setLinkText',
        'set-link-url' => 'setLinkUrl',
        'update-link-text' => 'updateLinkText',
        'update-link-url' => 'updateLinkUrl',
    ];

    public function boot(CategoryMaterialService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function mount(): void
    {
        $this->loadCategories();
        $this->loadMaterials();
        $this->restoreMaterialFromUrl();
    }

    public function loadCategories(): void
    {
        $this->categories = $this->categoryService->getCategoriesWithMaterials();
    }

    public function loadMaterials(): void
    {
        $this->materials = $this->categoryService->searchMaterials($this->searchTerm);
    }

    public function toggleCategory(int $categoryId): void
    {
        $this->expandedCategories = in_array($categoryId, $this->expandedCategories)
            ? array_diff($this->expandedCategories, [$categoryId])
            : [...$this->expandedCategories, $categoryId];
    }

    public function selectMaterial(string $slug): void
    {
        $material = $this->categoryService->findMaterialBySlug($slug);

        if (!$material) {
            return;
        }

        $isDeselecting = $this->selectedMaterialId === $material->id;

        if ($isDeselecting) {
            $this->clearSelectedMaterial();
        } else {
            $expandAction = app(ExpandCategoryTreeAction::class);
            $this->setSelectedMaterial($material, $expandAction);
        }
    }

    protected function clearSelectedMaterial(): void
    {
        $this->selectedMaterialId = null;
        $this->linkUrl = '';
        $this->linkText = '';
    }

    protected function setSelectedMaterial(MaterialInfo $material, ExpandCategoryTreeAction $expandAction): void
    {
        $this->selectedMaterialId = $material->id;
        $this->linkUrl = $material->url;
        $this->linkText = $this->linkText ?: $material->title;

        $this->expandedCategories = $expandAction->execute(
            $material->categoryId,
            $this->expandedCategories
        );
    }

    public function updatedSearchTerm(): void
    {
        $this->loadMaterials();
        $this->loadCategories();
    }

    public function openModal(): void
    {
        $this->open = true;
        $this->restoreMaterialFromUrl();
    }

    protected function restoreMaterialFromUrl(): void
    {
        if (empty($this->linkUrl)) {
            return;
        }

        $material = $this->findMaterialByUrl($this->linkUrl);

        if ($material) {
            $this->selectedMaterialId = $material->id;
            $this->linkText = $material->title;

            $expandAction = app(ExpandCategoryTreeAction::class);
            $this->expandedCategories = $expandAction->execute(
                $material->categoryId,
                $this->expandedCategories
            );
        }
    }

    protected function findMaterialByUrl(string $url): ?MaterialInfo
    {
        if (!preg_match('/\/materials\/([^\/]+)/', $url, $matches)) {
            return null;
        }

        return $this->categoryService->findMaterialBySlug($matches[1]);
    }

    public function openModalWithSelectedText(): void
    {
        $this->dispatch('get-selected-text');
        $this->openModal();
    }

    public function closeModal(): void
    {
        $this->open = false;
        $this->showFileManagerModal = false;
        $this->reset(['linkUrl', 'linkText', 'linkTarget', 'linkTitle', 'searchTerm', 'selectedMaterialId']);
        $this->loadCategories();
        $this->loadMaterials();
    }

    public function insertLink(): void
    {
        $this->dispatch('insert-link', url: $this->linkUrl, text: $this->linkText, target: $this->linkTarget, title: $this->linkTitle);
        $this->closeModal();
    }

    public function openFileManager(): void
    {
        $this->showFileManagerModal = true;

        if ($this->linkUrl) {
            $this->dispatch('set-file-manager-path', path: $this->linkUrl);
        }
    }

    public function closeFileManagerModal(): void
    {
        $this->showFileManagerModal = false;
    }

    public function onFileSelected(string $url): void
    {
        $this->linkUrl = $url;
        $this->selectedMaterialId = null;
        $this->closeFileManagerModal();
    }

    public function setLinkText(string $text): void
    {
        $this->linkText = $text;
    }

    public function setLinkUrl(string $url): void
    {
        $this->linkUrl = $url;
    }

    public function updateLinkText(string $text): void
    {
        $this->linkText = $text;
    }

    public function updateLinkUrl(string $url): void
    {
        $this->linkUrl = $url;
    }

    public function render()
    {
        return view('livewire.link-modal', [
            'open' => $this->open,
        ]);
    }
}
