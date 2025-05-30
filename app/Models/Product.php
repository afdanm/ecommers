<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category_id', 'price', 'description', 'images', 'size_type', 'stock',
    ];

    // Cast images sebagai array
    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
       
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

    public function sizes()
    {
        return $this->belongsToMany(Size::class)->withPivot('stock')->withTimestamps();
    }

    // Accessor for available sizes
    public function getAvailableSizesAttribute()
    {
        return $this->sizes->map(function($size) {
            return [
                'id' => $size->id,
                'name' => $size->name,
                'stock' => $size->pivot->stock,
                'available' => $size->pivot->stock > 0
            ];
        })->sortBy('name');
    }

    // Calculate total stock from all sizes
    public function getTotalStockAttribute()
    {
        if ($this->sizes->count() > 0) {
            return $this->sizes->sum('pivot.stock');
        }
        return $this->stock; // Fallback to single stock if no sizes
    }
}