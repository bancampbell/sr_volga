<?php

namespace App\Actions\LinkModal;

use App\Contracts\MaterialRepositoryInterface;
use App\ValueObjects\MaterialInfo;

class SelectMaterialAction
{
    public function __construct(
        private MaterialRepositoryInterface $materialRepository
    ) {}

    public function execute(string $slug): ?MaterialInfo
    {
        return $this->materialRepository->findBySlug($slug);
    }
}
