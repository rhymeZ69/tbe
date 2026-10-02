<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoTour extends Model
{
    protected $fillable = [
        'stage_number', 'title', 'stage_tag', 'description',
        'video_source', 'video_type', 'poster_image', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('stage_number');
    }

    /**
     * Convert to embed URL for iframe (YouTube / Vimeo) — used by frontend.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        $url = $this->video_source;

        if ($this->video_type === 'youtube') {
            if (preg_match('/(?:v=|youtu\.be\/|shorts\/|embed\/)([\w-]{11})/', $url, $m)) {
                return 'https://www.youtube-nocookie.com/embed/' . $m[1]
                     . '?autoplay=1&rel=0&modestbranding=1&playsinline=1';
            }
        }

        if ($this->video_type === 'vimeo') {
            if (preg_match('/vimeo\.com\/(?:video\/)?(\d+)/', $url, $m)) {
                return 'https://player.vimeo.com/video/' . $m[1] . '?autoplay=1';
            }
        }

        return null; // mp4 / webm → use <video> tag directly
    }
}