<?php

namespace App\View\Components;

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
        $items = $this->menuService->getTree($this->handle);

        return view('components.menu', [
            'items' => $items
        ]);
    }
}
