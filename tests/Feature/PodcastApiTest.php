<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PodcastApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_podcasts()
    {
        // Create test podcasts
        $category = Category::factory()->create();
        Podcast::factory()->count(5)->create(['category_id' => $category->id]);
        
        // Make request
        $response = $this->getJson('/api/podcasts');
        
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
                        'image',
                        'author',
                        'language',
                        'featured',
                        'rating',
                        'episodes_count',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links',
                'meta'
            ]);
    }
    
    public function test_can_get_podcasts_by_category()
    {
        // Create categories and podcasts
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        
        Podcast::factory()->count(3)->create(['category_id' => $category1->id]);
        Podcast::factory()->count(2)->create(['category_id' => $category2->id]);
        
        // Make request
        $response = $this->getJson('/api/podcasts/category/' . $category1->id);
        
        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
    
    public function test_can_search_podcasts()
    {
        // Create podcasts
        $category = Category::factory()->create();
        Podcast::factory()->create([
            'category_id' => $category->id,
            'title' => 'Tech Talk'
        ]);
        Podcast::factory()->create([
            'category_id' => $category->id,
            'title' => 'History Hour'
        ]);
        
        // Make request
        $response = $this->getJson('/api/podcasts/search?q=Tech');
        
        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Tech Talk');
    }
    
    public function test_can_get_single_podcast_with_episodes()
    {
        // Create test podcast
        $category = Category::factory()->create();
        $podcast = Podcast::factory()
            ->has(\App\Models\Episode::factory()->count(3))
            ->create(['category_id' => $category->id]);
        
        // Make request
        $response = $this->getJson('/api/podcasts/' . $podcast->id);
        
        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $podcast->id,
                    'title' => $podcast->title
                ]
            ])
            ->assertJsonCount(3, 'data.episodes');
    }
}