<?php

namespace App\Repositories\Interfaces;

interface PodcastRepositoryInterface extends RepositoryInterface
{
    public function getFeatured();
    public function getByCategory($categoryId);
    public function search($query);
}