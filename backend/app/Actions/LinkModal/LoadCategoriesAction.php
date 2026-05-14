<?php

namespace App\Actions\LinkModal;

use App\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LoadCategoriesAction
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(): Collection
    {
        return $this->categoryRepository->getAllWithMaterials();
    }
}
