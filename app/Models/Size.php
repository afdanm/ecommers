<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'type'];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('stock')->withTimestamps();
    }
}
