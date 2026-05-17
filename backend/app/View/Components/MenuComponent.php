<?php

namespace App\View\Components;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\View\Component;
use App\Services\MenuService;

class MenuComponent extends Component
{
    public function __construct(
        private MenuService $menuService,
        public string $handle
    ) {}

    public function render()
    {
        $category = MenuCategory::where('handle', $this->handle)->first();

        if (!$category) {
            return view('components.menu', ['items' => collect()]);
        }

        $items = Menu::where('menu_category_id', $category->id)
            ->where('is_active', true)
            ->orderBy('_lft')
            ->get()
            ->toTree();

        // Логирование
        \Log::info('Menu category: ' . $category->name);
        \Log::info('Items count: ' . $items->count());
        foreach ($items as $item) {
            \Log::info($item->name . ' children: ' . $item->children->count());
        }

        return view('components.menu', ['items' => $items]);
    }
}
