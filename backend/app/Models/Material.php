<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'is_active', 'category_id', 'is_home'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function getUrl(): string
    {
        if ($this->category) {
            return $this->category->getUrl() . '/' . $this->slug;
        }
        return '/' . $this->slug;
    }

    protected static function booted()
    {
        static::saving(function ($material) {
            if ($material->is_home) {
                static::where('is_home', true)
                    ->where('id', '!=', $material->id)
                    ->update(['is_home' => false]);
            }
        });
    }

    public function getBreadcrumbs(): array
    {
        $crumbs = [];

        if ($this->category) {
            $crumbs = $this->category->getBreadcrumbs();
        }

        $crumbs[] = [
            'name' => $this->title,
            'url' => $this->getUrl(),
        ];

        return $crumbs;
    }



}
