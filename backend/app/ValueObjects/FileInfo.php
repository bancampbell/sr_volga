<?php

namespace App\ValueObjects;

class FileInfo
{
    public function __construct(
        public readonly string $name,
        public readonly string $path,
        public readonly string $url,
        public readonly string $size,
        public readonly string $lastModified,
        public readonly string $type = 'file'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            path: $data['path'],
            url: $data['url'],
            size: $data['size'],
            lastModified: $data['lastModified'],
            type: $data['type'] ?? 'file'
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'path' => $this->path,
            'url' => $this->url,
            'size' => $this->size,
            'lastModified' => $this->lastModified,
            'type' => $this->type,
        ];
    }
}
