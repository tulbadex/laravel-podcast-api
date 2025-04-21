<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="PodcastRequest",
 *     title="Podcast Request",
 *     required={"title", "slug", "description", "category_id", "author"},
 *     @OA\Property(property="title", type="string", example="Tech Talk"),
 *     @OA\Property(property="slug", type="string", example="tech-talk"),
 *     @OA\Property(property="description", type="string", example="A podcast about technology"),
 *     @OA\Property(property="image", type="string", example="podcasts/tech-talk.jpg"),
 *     @OA\Property(property="category_id", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="author", type="string", example="John Doe"),
 *     @OA\Property(property="language", type="string", example="English"),
 *     @OA\Property(property="featured", type="boolean", example=false),
 *     @OA\Property(property="rating", type="integer", example=4)
 * )
 */
class PodcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('podcasts')->ignore($this->podcast),
            ],
            'description' => 'required|string',
            'image' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author' => 'required|string|max:255',
            'language' => 'nullable|string|max:50',
            'featured' => 'nullable|boolean',
            'rating' => 'nullable|integer|min:0|max:5',
        ];

        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|string|max:255';
        }

        return $rules;
    }
}