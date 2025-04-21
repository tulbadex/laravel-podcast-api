<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Episode extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'podcast_id',
        'title',
        'slug',
        'description',
        'audio_url',
        'duration_in_seconds',
        'published_at',
        'featured',
        'guest_name'
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }
    
    public function getDurationAttribute()
    {
        $minutes = floor($this->duration_in_seconds / 60);
        $seconds = $this->duration_in_seconds % 60;
        
        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
