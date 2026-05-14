<?php

namespace App\Contracts;

use App\ValueObjects\MaterialInfo;
use Illuminate\Database\Eloquent\Collection;

interface MaterialRepositoryInterface
{
    public function search(string $searchTerm = '', int $limit = 50): Collection;

    public function findBySlug(string $slug): ?MaterialInfo;
}
