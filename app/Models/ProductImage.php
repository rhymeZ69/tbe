<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'media_id', 'path', 'alt', 'is_primary', 'sort_order'];

    protected $casts = ['is_primary' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function getUrlAttribute(): string
    {
        if ($this->media) return $this->media->url;
        return $this->path ? asset($this->path) : '';
    }
}