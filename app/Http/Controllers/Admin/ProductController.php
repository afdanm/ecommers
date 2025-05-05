<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan semua produk
    public function index()
    {
        $products = Product::all(); // Ambil semua produk dari database
        return view('admin.products.index', compact('products'));
    }

    // Menampilkan form tambah produk
    public function create()
    {
        return view('admin.products.create');
    }

    // Menyimpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer',
            'status' => 'required|in:ready,habis',
            'image' => 'nullable|image|max:2048',
            'brand' => 'nullable|string|max:255', // Brand produk
        ]);

        // Menyimpan produk
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->brand = $request->brand;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        // Set rating ke null atau 0
        $product->rating = 0; // Mengatur rating ke 0 atau null jika Anda ingin nilai default

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // Menampilkan form edit produk
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // Mengupdate produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer',
            'status' => 'required|in:ready,habis',
            'image' => 'nullable|image|max:2048',
            'brand' => 'nullable|string|max:255', // Brand produk
        ]);

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                unlink(storage_path('app/public/' . $product->image));
            }

            $product->image = $request->file('image')->store('products', 'public');
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->brand = $request->brand; // Menyimpan brand produk

        // Set rating ke null atau 0
        $product->rating = $product->rating ?? 0; // Menetapkan nilai default rating 0 jika tidak ada rating

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    // Menghapus produk
    public function destroy(Product $product)
    {
        // Menghapus gambar jika ada
        if ($product->image) {
            unlink(storage_path('app/public/' . $product->image));
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }

    // Menampilkan detail produk
    public function show($id)
    {
        $product = Product::findOrFail($id); // Mengambil produk berdasarkan ID

        return view('products.show', compact('product')); // Menampilkan halaman produk
    }
}
