<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Contracts\MenuRepositoryInterface;
use Illuminate\Support\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function findById(int $id): ?Menu
    {
        return Menu::find($id);
    }

    public function findByHandle(string $handle): Collection
    {
        return Menu::where('handle', $handle)
            ->where('is_active', true)
            ->orderBy('sort')
            ->get();
    }

    public function getTree(string $handle): Collection
    {
        return Menu::where('handle', $handle)
            ->where('is_active', true)
            ->orderBy('sort')
            ->get()
            ->toTree();
    }

    public function getAllActive(): Collection
    {
        return Menu::where('is_active', true)
            ->orderBy('sort')
            ->get();
    }

    public function create(array $data): Menu
    {
        return Menu::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $menu = $this->findById($id);

        if (!$menu) {
            return false;
        }

        return $menu->update($data);
    }

    public function delete(int $id): bool
    {
        $menu = $this->findById($id);

        if (!$menu) {
            return false;
        }

        return (bool) $menu->delete();
    }

    public function reorder(int $id, int $position): bool
    {
        $menu = $this->findById($id);

        if (!$menu) {
            return false;
        }

        $menu->sort = $position;

        return $menu->save();
    }
}
