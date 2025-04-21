<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Podcast extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'category_id',
        'author',
        'language',
        'featured',
        'rating'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
