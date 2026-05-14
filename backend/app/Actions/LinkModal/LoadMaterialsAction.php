<?php

namespace App\Actions\LinkModal;

use App\Contracts\MaterialRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class LoadMaterialsAction
{
    public function __construct(
        private MaterialRepositoryInterface $materialRepository
    ) {}

    public function execute(string $searchTerm = ''): Collection
    {
        return $this->materialRepository->search($searchTerm);
    }
}
