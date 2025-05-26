<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'reviews', 'sizes');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();
        $categories = Category::all();

        return view('user.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
{
    $product->load(['category', 'sizes', 'reviews.user']);
    
    $availableSizes = $product->sizes->map(function ($size) use ($product) {
        return [
            'id' => $size->id,
            'name' => $size->name,
            'stock' => $size->pivot->stock,
            'available' => $size->pivot->stock > 0,
        ];
    });

    $total_stock = $product->sizes->sum('pivot.stock') ?: $product->stock;

    // Check if review mode is active
    $canReview = false;
    $transactionId = null;
    $rating = null;
    
    if(request()->has('review') && request()->has('transaction_id')) {
        $transaction = Transaction::where('id', request('transaction_id'))
            ->where('user_id', auth()->id())
            ->first();
            
        if($transaction && $transaction->products->contains($product->id)) {
            $canReview = true;
            $transactionId = $transaction->id;
            
            // Check if user already reviewed this product from this transaction
            $existingReview = $product->reviews
                ->where('user_id', auth()->id())
                ->where('transaction_id', $transaction->id)
                ->first();
                
            if($existingReview) {
                $canReview = false;
            }
        }
    }

    return view('user.products.show', compact(
        'product', 
        'availableSizes', 
        'total_stock',
        'canReview',
        'transactionId',
        'rating'
    ));
}
}
