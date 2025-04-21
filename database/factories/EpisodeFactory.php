<?php

namespace Database\Factories;

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Episode>
 */
class EpisodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        return [
            'podcast_id' => Podcast::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'audio_url' => 'episodes/' . $this->faker->uuid() . '.mp3',
            'duration_in_seconds' => $this->faker->numberBetween(300, 3600),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'featured' => $this->faker->boolean(20),
        ];
    }
}
