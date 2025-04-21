<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Episode;
use App\Models\Podcast;

class PodcastPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = Category::factory(10)->create();
        
        // Create podcasts
        $categories->each(function ($category) {
            Podcast::factory(rand(3, 8))
                ->create(['category_id' => $category->id])
                ->each(function ($podcast) {
                    // Create episodes for each podcast
                    Episode::factory(rand(5, 15))
                        ->create(['podcast_id' => $podcast->id]);
                });
        });
        
        // Ensure we have some featured content
        Category::inRandomOrder()->limit(2)->update(['featured' => true]);
        Podcast::inRandomOrder()->limit(5)->update(['featured' => true]);
        Episode::inRandomOrder()->limit(10)->update(['featured' => true]);
    }
}
