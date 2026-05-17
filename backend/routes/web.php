<?php

use Illuminate\Support\Facades\Route;
use App\Models\Material;
use App\Models\Category;

// Главная страница (берёт материал с is_home = true)
Route::get('/', function () {
    $homeMaterial = Material::where('is_home', true)->first();

    if ($homeMaterial) {
        return view('site.material', compact('homeMaterial'));
    }

    // Если нет главного материала — 404 или редирект
    abort(404, 'Главная страница не назначена');
})->name('home');

// Админка Filament
Route::get('/admin/file-manager', function () {
    return view('filament.pages.file-manager');
})->middleware(['auth'])->name('admin.file-manager');

// Материал в категории
Route::get('/{categorySlug}/{slug}', function ($categorySlug, $slug) {
    $category = Category::where('slug', $categorySlug)->firstOrFail();
    $material = Material::where('slug', $slug)
        ->where('category_id', $category->id)
        ->firstOrFail();
    return view('site.material', compact('material', 'category'));
})->where('categorySlug', '[a-z0-9-]+')->where('slug', '[a-z0-9-]+');

// Материал без категории или категория
Route::get('/{slug}', function ($slug) {
    // Материал без категории
    $material = Material::where('slug', $slug)->whereNull('category_id')->first();
    if ($material) {
        return view('site.material', compact('material'));
    }

    // Категория
    $category = Category::where('slug', $slug)->first();
    if ($category) {
        $materials = $category->materials()->paginate(20);
        return view('site.category', compact('category', 'materials'));
    }

    abort(404);
})->where('slug', '[a-z0-9-]+');
