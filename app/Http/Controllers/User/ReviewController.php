<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Pastikan user sudah membeli produk dan transaksinya sudah dibayar
        $hasPurchased = Auth::user()->transactions()
            ->where('status', 'paid') // Hanya transaksi dengan status 'paid'
            ->whereHas('products', fn($query) => $query->where('product_id', $product->id))
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang telah dibayar.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->back()->with('success', 'Ulasan Anda telah disimpan.');
    }
}
