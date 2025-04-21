<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PodcastController;
use App\Http\Controllers\Api\EpisodeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Apply rate limiting to all API routes
Route::middleware('throttle:api')->group(function () {
    // Categories
    Route::get('/categories/featured', [CategoryController::class, 'featured']);
    Route::apiResource('categories', CategoryController::class);
    
    // Podcasts
    Route::get('/podcasts/featured', [PodcastController::class, 'featured']);
    Route::get('/podcasts/category/{categoryId}', [PodcastController::class, 'byCategory']);
    Route::get('/podcasts/search', [PodcastController::class, 'search']);
    Route::apiResource('podcasts', PodcastController::class);
    
    // Episodes
    Route::get('/episodes/featured', [EpisodeController::class, 'featured']);
    Route::get('/episodes/latest', [EpisodeController::class, 'latest']);
    Route::get('/episodes/podcast/{podcastId}', [EpisodeController::class, 'byPodcast']);
    Route::apiResource('episodes', EpisodeController::class);
});