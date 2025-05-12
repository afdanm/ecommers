<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(8);
        return view('user.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        return view('user.products.show', compact('product'));
    }
}