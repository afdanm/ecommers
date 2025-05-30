<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationOption extends Model
{
    use HasFactory;

    protected $fillable = ['variation_id', 'option_name', 'image'];

    // Relasi ke variasi produk
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}