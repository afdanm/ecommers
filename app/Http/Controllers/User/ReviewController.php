<?php

// app/Http/Controllers/User/ReviewController.php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
            'transaction_id' => 'required|exists:transactions,id,user_id,'.Auth::id()
        ]);

        // Cek apakah user sudah memberikan review untuk transaksi ini
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('transaction_id', $request->transaction_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk transaksi ini.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'transaction_id' => $request->transaction_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->route('products.show', $product)
            ->with('success', 'Ulasan berhasil ditambahkan!');
    }

    public function index(Product $product)
    {
        $reviews = $product->reviews()->with('user')->latest()->paginate(10);
        return view('user.reviews.index', compact('product', 'reviews'));
    }
}