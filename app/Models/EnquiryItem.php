<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryItem extends Model
{
    protected $fillable = [
        'enquiry_id', 'product_id', 'category_id', 'item_description',
        'quantity', 'unit', 'packaging', 'notes',
    ];

    public function enquiry()
    {
        return $this->belongsTo(QuoteEnquiry::class, 'enquiry_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}