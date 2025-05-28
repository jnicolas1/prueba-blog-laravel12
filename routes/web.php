<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');*/

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/prueba/{post}', function (Post $post) {
    return Storage::download($post->image_path);
})->name('prueba');

/*Route::get('/probando', function () {
    //copiar una imagen de una ruta a otra
    $path = 'posts/dolorem-mollitia-et-rerum-consequatur-eos-repellat-nisi3.png';// ruta de la imagen que se va a copiar
    $target = 'articles/imagen1.jpg';// ruta donde se va a copiar la imagen
    Storage::copy($path, $target);// copiar la imagen
    //Storage::move($path, $target);// mover la imagen
    return 'Imagen copiada';
});*/

require __DIR__.'/auth.php';
//require __DIR__.'/admin.php';
