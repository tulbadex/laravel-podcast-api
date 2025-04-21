<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PodcastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'author' => $this->author,
            'language' => $this->language,
            'featured' => (bool) $this->featured,
            'rating' => $this->rating,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'episodes_count' => $this->when($this->episodes_count !== null, $this->episodes_count),
            'episodes' => EpisodeResource::collection($this->whenLoaded('episodes')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
