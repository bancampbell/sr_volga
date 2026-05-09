<?php

namespace App\Providers;

use App\Livewire\FileManager;
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
        //
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
