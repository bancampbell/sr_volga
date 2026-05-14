<?php

namespace App\Providers;

use App\Contracts\CategoryRepositoryInterface;
use App\Contracts\FileSystemInterface;
use App\Contracts\MaterialRepositoryInterface;
use App\Livewire\FileManager;
use App\Repositories\CategoryRepository;
use App\Repositories\MaterialRepository;
use App\Services\FileSystemService;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\LinkModal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileSystemInterface::class, FileSystemService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(MaterialRepositoryInterface::class, MaterialRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Livewire::component('link-modal', LinkModal::class);
        Livewire::component('file-manager', FileManager::class);

    }
}
