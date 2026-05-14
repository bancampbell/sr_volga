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
        'roles',
    ];

    protected $casts = [
        'route_params' => 'array',
        'roles' => 'array',
        'is_active' => 'boolean',
        'is_new_tab' => 'boolean',
        'sort' => 'integer',
    ];

    public function linkable()
    {
        return $this->morphTo();
    }

    public function getUrlAttribute(): string
    {
        if ($this->external_url) {
            return $this->external_url;
        }

        if ($this->route_name) {
            return route($this->route_name, $this->route_params ?? []);
        }

        if ($this->linkable) {
            return $this->linkable->getUrl();
        }

        return $this->url ?? '#';
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

}
