<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariety extends Model
{
    protected $fillable = ['product_id', 'name', 'code', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}