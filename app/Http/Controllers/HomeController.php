<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil 6 kategori, kalau kosong aman
        $categories = Category::take(6)->get();

        // Ambil 8 produk terbaru, kalau kosong aman
        $latestProducts = Product::latest()->take(8)->get();
        

        return view('home', compact('categories', 'latestProducts'));
        
    }

    // Buat list semua produk (halaman /products)
    public function listProducts()
    {
        $products = Product::latest()->paginate(12); // Pagination biar rapi

        return view('user.products.index', compact('products'));
    }
}
