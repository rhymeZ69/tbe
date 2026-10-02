<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'tagline', 'short_description', 'description',
        'icon', 'image', 'gradient_class', 'is_active', 'is_featured',
        'sort_order', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function ($cat) {
            if (empty($cat->slug)) $cat->slug = Str::slug($cat->name);
        });
    }

    /* ---------- Relationships ---------- */

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function activeProducts()
    {
        return $this->hasMany(Product::class, 'category_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order');
    }

    /* ---------- Scopes ---------- */

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true);
    }
}