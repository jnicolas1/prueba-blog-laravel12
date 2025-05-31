<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        //call(CategorySeeder::class);
        
        //sirve para eliminar la carpeta posts y su contenido
        Storage::deleteDirectory('posts');
        Storage::makeDirectory('posts');//crea la carpeta posts

        User::factory()->create([
            'name' => 'Josue Nicolás',
            'email' => 'nicolas.jmn.mn@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
        User::factory(5)->create();
        
        Category::factory(10)->create();
        Post::factory(100)->create();

        $this->call([
            PermissionSeeder::class
        ]);
    }
}
