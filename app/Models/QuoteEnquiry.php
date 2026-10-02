<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuoteEnquiry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference', 'name', 'company', 'email', 'phone', 'country_id',
        'destination_port', 'product_interest', 'quantity_mt', 'packaging_notes',
        'message', 'status', 'admin_notes', 'source', 'ip_address', 'user_agent',
        'contacted_at', 'quoted_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
        'quoted_at'    => 'datetime',
        'quantity_mt'  => 'decimal:2',
    ];

    public static function generateReference(): string
    {
        $year = now()->format('Y');
        $last = static::withTrashed()->whereYear('created_at', $year)->count() + 1;
        return 'TBE-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    protected static function booted(): void
    {
        static::creating(function ($e) {
            if (empty($e->reference)) $e->reference = static::generateReference();
        });
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function items()
    {
        return $this->hasMany(EnquiryItem::class, 'enquiry_id');
    }

    public function scopeNew($q)       { return $q->where('status', 'new'); }
    public function scopeUnread($q)    { return $q->whereNull('contacted_at'); }
}