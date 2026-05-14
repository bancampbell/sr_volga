<?php

namespace App\Repositories;

use App\Models\Category;
use App\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllWithMaterials(): Collection
    {
        return Category::with('materials')->get();
    }

    public function findWithParents(int $categoryId): array
    {
        $category = Category::find($categoryId);
        $parents = [];

        while ($category && $category->parent_id) {
            $category = $category->parent;
            if ($category) {
                $parents[] = $category->id;
            }
        }

        return $parents;
    }
}
