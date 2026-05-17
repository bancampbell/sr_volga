<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Menu extends Model
{
    use SoftDeletes, NodeTrait;

    protected $table = 'menus';

    protected $fillable = [
        'menu_category_id',
        'name',
        'handle',
        'description',
        'parent_id',
        'type',
        'url',
        'route_name',
        'route_params',
        'external_url',
        'target',
        'icon',
        'image',
        'is_active',
        'is_new_tab',
        'sort',
        'linkable_type',
        'linkable_id',
        'material_id',
        'category_id',
        'roles',
    ];

    protected $casts = [
        'route_params' => 'array',
        'roles' => 'array',
        'is_active' => 'boolean',
        'is_new_tab' => 'boolean',
        'sort' => 'integer',
        'url' => 'string',
    ];

    protected $appends = ['depth', 'material_id', 'category_id'];

    public function linkable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        if ($this->type === 'divider') {
            return '#';
        }
        return $this->attributes['url'] ?? '';
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    public function getDepthAttribute(): int
    {
        return $this->ancestors()->count();
    }

    public function setMaterialIdAttribute($value)
    {
        if ($value) {
            $this->attributes['material_id'] = $value;
            $this->attributes['linkable_id'] = $value;
            $this->attributes['linkable_type'] = Material::class;
            $this->attributes['url'] = Material::find($value)?->getUrl();
        }
    }

    public function setCategoryIdAttribute($value)
    {
        if ($value) {
            $this->attributes['category_id'] = $value;
            $this->attributes['linkable_id'] = $value;
            $this->attributes['linkable_type'] = Category::class;
            $this->attributes['url'] = Category::find($value)?->getUrl();
        }
    }

    public function setLinkableIdAttribute($value)
    {
        $this->attributes['linkable_id'] = $value;

        if ($value && $this->linkable_type === Material::class) {
            $this->attributes['url'] = Material::find($value)?->getUrl();
            $this->attributes['material_id'] = $value;
        }

        if ($value && $this->linkable_type === Category::class) {
            $this->attributes['url'] = Category::find($value)?->getUrl();
            $this->attributes['category_id'] = $value;
        }
    }

    public function getMaterialIdAttribute()
    {
        return $this->linkable_type === Material::class ? $this->linkable_id : null;
    }

    public function getCategoryIdAttribute()
    {
        return $this->linkable_type === Category::class ? $this->linkable_id : null;
    }

    public static function booted()
    {
        static::saving(function ($menu) {
            if ($menu->parent_id === null && !$menu->handle) {
                throw new \Exception('Для корневого пункта меню обязательно заполнение идентификатора');
            }
        });
    }
}
