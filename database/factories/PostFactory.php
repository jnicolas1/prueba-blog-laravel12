<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->unique()->slug(),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraph(20,false),
            'is_published' => true,//$this->faker->boolean(),
            'published_at' => $this->faker->dateTime(),
            'user_id' => \App\Models\User::all()->random()->id,
            'category_id' => \App\Models\Category::all()->random()->id,
        ];
    }
}
