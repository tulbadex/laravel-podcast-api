<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\Interfaces\PodcastRepositoryInterface;
use App\Repositories\PodcastRepository;
use App\Repositories\Interfaces\EpisodeRepositoryInterface;
use App\Repositories\EpisodeRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(PodcastRepositoryInterface::class, PodcastRepository::class);
        $this->app->bind(EpisodeRepositoryInterface::class, EpisodeRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /* RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(1200)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many requests'
                    ], 429, $headers);
                });
        }); */

        RateLimiter::for('api', function (Request $request) {
            return Limit::perSecond(20)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response()->json([
                        'message' => 'Too many requests'
                    ], 429, $headers);
                });
        });        
    }
}
