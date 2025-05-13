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
    $query = Product::with('category', 'reviews');

    // Filter berdasarkan kategori jika ada
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // Filter berdasarkan nama produk
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    $products = $query->get();
    $categories = Category::all();

    return view('user.products.index', compact('products', 'categories'));
}

    public function show(Product $product)
    {
        return view('user.products.show', compact('product'));
    }
}