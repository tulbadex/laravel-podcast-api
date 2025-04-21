<?php

namespace App\Repositories;

use App\Models\Podcast;
use App\Repositories\Interfaces\PodcastRepositoryInterface;

class PodcastRepository extends BaseRepository implements PodcastRepositoryInterface
{
    public function __construct(Podcast $podcast)
    {
        parent::__construct($podcast);
    }
    
    public function getFeatured()
    {
        return $this->model->where('featured', true)
            ->orderBy('rating', 'desc')
            ->with('category')
            ->get();
    }
    
    public function getByCategory($categoryId)
    {
        return $this->model->where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
    
    /* public function search($query)
    {
        return $this->model->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(15);
    } */

    public function search($query)
    {
        return $this->model->with(['category', 'episodes'])
            ->where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->paginate(10);
    }
}