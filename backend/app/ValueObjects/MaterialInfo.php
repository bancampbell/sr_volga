<?php

namespace App\ValueObjects;

use App\Models\Material;

class MaterialInfo
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly string $title,
        public readonly ?int $categoryId,
        public readonly string $url,
    ) {}

    public static function fromModel(Material $material): self
    {
        return new self(
            id: $material->id,
            slug: $material->slug,
            title: $material->title,
            categoryId: $material->category_id,
            url: url('/materials/' . $material->slug),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'category_id' => $this->categoryId,
            'url' => $this->url,
        ];
    }
}
