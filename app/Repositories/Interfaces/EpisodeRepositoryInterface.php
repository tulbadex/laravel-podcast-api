<?php

namespace App\Repositories\Interfaces;

interface EpisodeRepositoryInterface extends RepositoryInterface
{
    public function getFeatured();
    public function getByPodcast($podcastId);
    public function getLatest($limit = 10);
}