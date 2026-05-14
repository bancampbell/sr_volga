<?php

namespace App\Services;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\MaterialRepositoryInterface;
use App\ValueObjects\MaterialInfo;
use Illuminate\Database\Eloquent\Collection;

class CategoryMaterialService
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
        private MaterialRepositoryInterface $materialRepository
    ) {}

    public function getCategoriesWithMaterials(): Collection
    {
        return $this->categoryRepository->getAllWithMaterials();
    }

    public function searchMaterials(string $searchTerm = ''): Collection
    {
        return $this->materialRepository->search($searchTerm);
    }

    public function findMaterialBySlug(string $slug): ?MaterialInfo
    {
        return $this->materialRepository->findBySlug($slug);
    }

    public function getCategoryWithParents(int $categoryId): array
    {
        return $this->categoryRepository->findWithParents($categoryId);
    }
}
