<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil beberapa produk unggulan (misalnya yang stoknya masih tersedia)
        $products = Product::where('status', 'ready')->take(4)->get();  // Ambil 4 produk unggulan

        // Kirim data produk ke view beranda
        return view('home', compact('products'));
    }

    public function listProducts()
{
    // Ambil semua produk yang ready
    $products = Product::where('status', 'ready')->get();

    // Kirim ke view 'products.index'
    return view('products.index', compact('products'));
}
}
