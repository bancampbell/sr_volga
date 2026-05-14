<?php

namespace App\Repositories;

use App\Models\Material;
use App\Contracts\MaterialRepositoryInterface;
use App\ValueObjects\MaterialInfo;
use Illuminate\Database\Eloquent\Collection;

class MaterialRepository implements MaterialRepositoryInterface
{
    public function search(string $searchTerm = '', int $limit = 50): Collection
    {
        $query = Material::query();

        if (!empty($searchTerm)) {
            $query->where('title', 'like', '%' . $searchTerm . '%');
        }

        return $query->limit($limit)->get();
    }

    public function findBySlug(string $slug): ?MaterialInfo
    {
        $material = Material::where('slug', $slug)->first();

        return $material ? MaterialInfo::fromModel($material) : null;
    }
}
