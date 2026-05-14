<?php

namespace App\Actions\LinkModal;

use App\Contracts\CategoryRepositoryInterface;

class ExpandCategoryTreeAction
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(?int $categoryId, array $currentlyExpanded): array
    {
        if (empty($categoryId) || in_array($categoryId, $currentlyExpanded)) {
            return $currentlyExpanded;
        }

        $expanded = [...$currentlyExpanded, $categoryId];
        $parents = $this->categoryRepository->findWithParents($categoryId);

        return array_unique([...$expanded, ...$parents]);
    }
}
