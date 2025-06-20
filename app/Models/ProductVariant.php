<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'variant_option_1', 'variant_option_2',
        'variant_image', 'price', 'stock', 'weight', 'length', 'width', 'height'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
