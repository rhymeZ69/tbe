<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'email', 'name', 'is_active', 'token',
        'subscribed_at', 'unsubscribed_at', 'ip_address',
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'subscribed_at'   => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($s) {
            $s->token = $s->token ?: Str::random(64);
            $s->subscribed_at = $s->subscribed_at ?: now();
        });
    }

    public function scopeActive($q) { return $q->where('is_active', true); }
}