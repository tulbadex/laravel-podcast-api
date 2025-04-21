<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'image' => 'categories/' . $this->faker->numberBetween(1, 10) . '.jpg',
            'description' => $this->faker->sentence(),
            'featured' => $this->faker->boolean(20),
        ];
    }
}
