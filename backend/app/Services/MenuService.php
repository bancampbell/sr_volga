<?php

namespace App\Services;

use App\Contracts\MenuRepositoryInterface;
use App\Models\Menu;
use Illuminate\Support\Collection;

class MenuService
{
    public function __construct(
        private MenuRepositoryInterface $menuRepository
    ) {}

    public function getTree(string $handle): Collection
    {
        return $this->menuRepository->getTree($handle);
    }

    public function create(array $data): Menu
    {
        return $this->menuRepository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->menuRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->menuRepository->delete($id);
    }

    public function reorder(int $id, int $position): bool
    {
        return $this->menuRepository->reorder($id, $position);
    }
}
