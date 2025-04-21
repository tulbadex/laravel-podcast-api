<?php

namespace App\Repositories;

use App\Models\Episode;
use App\Repositories\Interfaces\EpisodeRepositoryInterface;

class EpisodeRepository extends BaseRepository implements EpisodeRepositoryInterface
{
    public function __construct(Episode $episode)
    {
        parent::__construct($episode);
    }
    
    public function getByPodcast($podcastId)
    {
        return $this->model->where('podcast_id', $podcastId)
            ->orderBy('published_at', 'desc')
            ->paginate(15);
    }
    
    public function getFeatured()
    {
        return $this->model->where('featured', true)
            ->with('podcast')
            ->orderBy('published_at', 'desc')
            ->get();
    }
    
    public function getLatest($limit = 10)
    {
        return $this->model->with('podcast')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();
    }
}