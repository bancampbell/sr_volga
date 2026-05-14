<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAllWithMaterials(): Collection;

    public function findWithParents(int $categoryId): array;
}
