<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMessage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'is_read', 'ip_address',
    ];

    protected $casts = ['is_read' => 'boolean'];

    public function scopeUnread($q) { return $q->where('is_read', false); }
}