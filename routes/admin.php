<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);

    // Configurar posts para usar slug
    Route::resource('posts', PostController::class)->parameters([
        'posts' => 'post:slug'
    ]);
    
});