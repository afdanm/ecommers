<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCombination extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'variation_option_1_id', 'variation_option_2_id', 'price', 'stock'];

    // Relasi ke produk
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke opsi variasi pertama
    public function variationOption1()
    {
        return $this->belongsTo(VariationOption::class, 'variation_option_1_id');
    }

    // Relasi ke opsi variasi kedua
    public function variationOption2()
    {
        return $this->belongsTo(VariationOption::class, 'variation_option_2_id');
    }
}