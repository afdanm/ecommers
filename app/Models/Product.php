<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'images',
        'has_variants',
        'weight',
        'length',
        'width',
        'height',
        'stock',
        'variant_data'
    ];

    // Cast images sebagai array
    protected $casts = [
        'images' => 'array',
        'variant_data' => 'array',
        'has_variants' => 'boolean',
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2'
    ];

    public function getImagesAttribute($value)
    {
        if (empty($value)) return [];
        return is_array($value) ? $value : json_decode($value, true) ?? [$value];
    }

    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = is_array($value) ? json_encode($value) : $value;
    }

    // Helper method untuk get image count
    public function getImageCountAttribute()
    {
        return count($this->images);
    }

    // Helper method untuk get first image
    public function getFirstImageAttribute()
    {
        $images = $this->images;
        return !empty($images) ? $images[0] : null;
    }

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }
    
    public function totalReviews()
    {
        return $this->reviews()->count();
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    
    

    // Helper untuk mendapatkan nama varian
    public function getVariant1NameAttribute()
    {
        return $this->variant_data['variant_names'][0] ?? 'Varian 1';
    }

    public function getVariant2NameAttribute()
    {
        return $this->variant_data['variant_names'][1] ?? 'Varian 2';
    }

    // Calculate total stock from all sizes
    public function getTotalStockAttribute()
    {
        if ($this->has_variants) {
            return $this->variants->sum('stock');
        }
        return $this->stock;
    }
}