<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'parent_id'];

    protected $with = ['materials', 'children'];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getUrl(): string
    {
        if ($this->parent) {
            return $this->parent->getUrl() . '/' . $this->slug;
        }
        return '/' . $this->slug;
    }

    public function getBreadcrumbs(): array
    {
        $crumbs = [];
        $current = $this;

        while ($current) {
            array_unshift($crumbs, [
                'name' => $current->name,
                'url' => $current->getUrl(),
            ]);
            $current = $current->parent;
        }

        return $crumbs;
    }

}
