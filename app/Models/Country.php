<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'code', 'code3', 'flag_emoji', 'short_label',
        'is_gcc', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_gcc'      => 'boolean',
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
    ];

    public function scopeGcc($q)  { return $q->where('is_gcc', true); }
    public function scopeActive($q) { return $q->where('is_active', true); }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function enquiries()
    {
        return $this->hasMany(QuoteEnquiry::class);
    }
}