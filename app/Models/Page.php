<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'is_published', 'published_at', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($p) {
            if (empty($p->slug)) $p->slug = Str::slug($p->title);
        });
    }

    public function scopePublished($q)
    {
        return $q->where('is_published', true)
                 ->where(function ($w) {
                     $w->whereNull('published_at')->orWhere('published_at', '<=', now());
                 });
    }
}