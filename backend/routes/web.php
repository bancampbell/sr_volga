<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/file-manager', function () {
    return view('filament.pages.file-manager');
})->middleware(['auth'])->name('admin.file-manager');
