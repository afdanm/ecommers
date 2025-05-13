<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'purchase_method',
        'delivery_address',
        'midtrans_order_id',
        'snap_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
// Transaction.php
public function products()
{
    return $this->belongsToMany(Product::class, 'transaction_items') // atau nama tabel pivot kamu
                ->withPivot('quantity', 'price')
                ->withTimestamps();
}
}