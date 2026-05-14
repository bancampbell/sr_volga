<?php

namespace App\Contracts;

use App\Models\Menu;
use Illuminate\Support\Collection;

interface MenuRepositoryInterface
{
    public function findById(int $id): ?Menu;

    public function findByHandle(string $handle): Collection;

    public function getTree(string $handle): Collection;

    public function getAllActive(): Collection;

    public function create(array $data): Menu;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function reorder(int $id, int $position): bool;
}
