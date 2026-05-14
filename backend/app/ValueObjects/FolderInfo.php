<?php

namespace App\ValueObjects;

class FolderInfo
{
    public function __construct(
        public readonly string $name,
        public readonly string $path,
        public readonly array $children = [],
        public readonly ?int $sortNumber = null,
        public readonly string $type = 'folder'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            path: $data['path'],
            children: $data['children'] ?? [],
            sortNumber: $data['sort_number'] ?? null,
            type: $data['type'] ?? 'folder'
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'path' => $this->path,
            'children' => $this->children,
            'sort_number' => $this->sortNumber,
            'type' => $this->type,
        ];
    }
}
