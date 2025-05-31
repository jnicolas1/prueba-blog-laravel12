<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        //llamaremos Gate para verificar si el usuario autenticado es administrador
        Gate::define('admin', function ($user) {
            return $user->is_admin;       
        });
        /*Gate::define('author', function ($user, $post) {
            return $user->id === $post->user_id;       
        });*/
    }
}
