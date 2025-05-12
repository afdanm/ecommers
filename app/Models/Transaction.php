<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Tambahkan kolom yang mau diisi otomatis (mass assignment)
    protected $fillable = [
        'user_id',
        'total_price',    // contoh kolom lain kalau ada
        'status',
        'payment_url',   // contoh kolom lain kalau ada
        // tambahkan kolom lain kalau perlu
    ];
}
