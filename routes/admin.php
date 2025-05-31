<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;


// Agregar el prefijo 'admin' y nombre 'admin.'
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    Route::get('/', function () {
        return view('admin.dashboard');
    })
    //->middleware(['admin'])
    ->name('dashboard');

    Route::resource('categories', CategoryController::class);

    Route::resource('posts', PostController::class)->parameters([
        'posts' => 'post:slug'
    ]);
    
    Route::resource('permissions', PermissionController::class);
});