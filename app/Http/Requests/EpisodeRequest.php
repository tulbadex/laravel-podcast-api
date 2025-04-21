<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

 /**
  * @OA\Schema(
  *    schema="EpisodeRequest",
  *    title="Episode Request",
  *    required={"podcast_id", "title", "slug", "description", "audio_url", "duration_in_seconds", "published_at"},
  *    @OA\Property(property="podcast_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
  *    @OA\Property(property="title", type="string", example="Introduction to AI"),
  *    @OA\Property(property="slug", type="string", example="introduction-to-ai"),
  *    @OA\Property(property="description", type="string", example="An episode about artificial intelligence"),
  *    @OA\Property(property="audio_url", type="string", example="episodes/ai-intro.mp3"),
  *    @OA\Property(property="duration_in_seconds", type="integer", example=1800),
  *    @OA\Property(property="published_at", type="string", format="date", example="2023-01-15"),
  *    @OA\Property(property="featured", type="boolean", example=false),
  *    @OA\Property(property="guest_name", type="string", example="Jane Smith")
  * )
  */
class EpisodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'podcast_id' => 'required|string|exists:podcasts,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:episodes',
            'description' => 'nullable|string',
            'audio_url' => 'required|string|max:255',
            'duration_in_seconds' => 'required|integer|min:1',
            'published_at' => 'required|date',
            'featured' => 'nullable|boolean',
            'guest_name' => 'nullable|string|max:255',
        ];
    }
}
