<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
        
        // Prepare availableSizes array with stock info for the view
        $availableSizes = $product->sizes->map(function ($size) use ($product) {
            return [
                'id' => $size->id,
                'name' => $size->name,
                'stock' => $size->pivot->stock,
                'available' => $size->pivot->stock > 0,
            ];
        });

        // Total stock if product has no size
        $total_stock = $product->sizes->sum('pivot.stock') ?: $product->stock;

        return view('user.products.show', compact('product', 'availableSizes', 'total_stock'));
    }
}
