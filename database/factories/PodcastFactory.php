<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'image' => 'podcasts/' . $this->faker->numberBetween(1, 20) . '.jpg',
            'category_id' => Category::factory(),
            'author' => $this->faker->name(),
            'language' => $this->faker->randomElement(['English', 'Spanish', 'French', 'German']),
            'featured' => $this->faker->boolean(20),
            'rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
