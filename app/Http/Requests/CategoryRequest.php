<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     title="Category Request",
 *     required={"name", "slug"},
 *     @OA\Property(property="name", type="string", example="Technology"),
 *     @OA\Property(property="slug", type="string", example="technology"),
 *     @OA\Property(property="description", type="string", example="Technology podcasts"),
 *     @OA\Property(property="image", type="string", example="categories/tech.jpg"),
 *     @OA\Property(property="featured", type="boolean", example=true)
 * )
 */
class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($this->category)
            ],
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'featured' => 'nullable|boolean',
        ];

        if ($this->isMethod('POST')) {
            $rules['image'] = 'required|string|max:255';
        }

        return $rules;
    }
}
