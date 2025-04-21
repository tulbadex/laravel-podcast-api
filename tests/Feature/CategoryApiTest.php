<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function CategoryApiTest()
    {
        // Create test categories
        Category::factory()->count(5)->create();
        
        // Make request
        $response = $this->getJson('/api/categories');
        
        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'image',
                        'description',
                        'featured',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links',
                'meta'
            ]);
    }
    
    public function test_can_get_featured_categories()
    {
        // Create non-featured categories
        Category::factory()->count(3)->create(['featured' => false]);
        
        // Create featured categories
        Category::factory()->count(2)->create(['featured' => true]);
        
        // Make request
        $response = $this->getJson('/api/categories/featured');
        
        // Assert response
        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
    
    public function test_can_get_single_category()
    {
        // Create test category
        $category = Category::factory()->create();
        
        // Make request
        $response = $this->getJson('/api/categories/' . $category->id);
        
        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug
                ]
            ]);
    }
    
    public function test_can_create_category()
    {
        $categoryData = [
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'This is a test category',
            'image' => 'categories/test.jpg',
            'featured' => true
        ];
        
        // Make request
        $response = $this->postJson('/api/categories', $categoryData);
        
        // Assert response
        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => 'Test Category',
                    'slug' => 'test-category',
                    'featured' => true
                ]
            ]);
            
        // Verify data was saved
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category'
        ]);
    }
    
    public function test_can_update_category()
    {
        // Create test category
        $category = Category::factory()->create();
        
        $updatedData = [
            'name' => 'Updated Category',
            'slug' => 'updated-category',
            'description' => 'This category has been updated',
            'featured' => true
        ];
        
        // Make request
        $response = $this->putJson('/api/categories/' . $category->id, $updatedData);
        
        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => 'Updated Category',
                    'slug' => 'updated-category',
                    'featured' => true
                ]
            ]);
            
        // Verify data was updated
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }
    
    public function test_can_delete_category()
    {
        // Create test category
        $category = Category::factory()->create();
        
        // Make request
        $response = $this->deleteJson('/api/categories/' . $category->id);
        
        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Category deleted successfully'
            ]);
            
        // Verify data was deleted
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }
    
    public function test_validates_required_fields_for_creation()
    {
        // Missing required fields
        $response = $this->postJson('/api/categories', []);
        
        // Assert validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'slug', 'image']);
    }
}