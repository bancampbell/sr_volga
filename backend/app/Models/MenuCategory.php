<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuCategory extends Model
{
    protected $table = 'menu_categories';

    protected $fillable = [
        'name',
        'handle',
        'description',
        'sort',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort' => 'integer',
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'menu_category_id');
    }

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }
}
