<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Podcast;
use App\Models\Episode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpisodeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_episodes()
    {
        // Create test episodes
        $category = Category::factory()->create();
        $podcast = Podcast::factory()->create(['category_id' => $category->id]);
        Episode::factory()->count(5)->create(['podcast_id' => $podcast->id]);
        
        // Make request
        $response = $this->getJson('/api/episodes');
        
        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'description',
                        'audio_url',
                        'duration_in_seconds',
                        'duration_formatted',
                        'published_at',
                        'featured',
                        'guest_name',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links',
                'meta'
            ]);
    }
    
    public function test_can_get_episodes_by_podcast()
    {
        // Create podcasts and episodes
        $category = Category::factory()->create();
        $podcast1 = Podcast::factory()->create(['category_id' => $category->id]);
        $podcast2 = Podcast::factory()->create(['category_id' => $category->id]);
        
        Episode::factory()->count(3)->create(['podcast_id' => $podcast1->id]);
        Episode::factory()->count(2)->create(['podcast_id' => $podcast2->id]);
        
        // Make request
        $response = $this->getJson('/api/episodes/podcast/' . $podcast1->id);
        
        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
    
    public function test_can_get_latest_episodes()
    {
        // Create test episodes with different dates
        $category = Category::factory()->create();
        $podcast = Podcast::factory()->create(['category_id' => $category->id]);
        
        // Older episodes
        Episode::factory()->count(3)->create([
            'podcast_id' => $podcast->id,
            'published_at' => now()->subDays(30)
        ]);
        
        // Newer episodes
        Episode::factory()->count(2)->create([
            'podcast_id' => $podcast->id,
            'published_at' => now()->subDays(1)
        ]);
        
        // Make request
        $response = $this->getJson('/api/episodes/latest?limit=2');
        
        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
    
    public function test_can_get_single_episode()
    {
        // Create test episode
        $category = Category::factory()->create();
        $podcast = Podcast::factory()->create(['category_id' => $category->id]);
        $episode = Episode::factory()->create(['podcast_id' => $podcast->id]);
        
        // Make request
        $response = $this->getJson('/api/episodes/' . $episode->id);
        
        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $episode->id,
                    'title' => $episode->title,
                    'slug' => $episode->slug
                ]
            ]);
    }
}