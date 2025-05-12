<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validasi gambar
        ]);
    
        // Upload foto jika ada
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public'); // Menyimpan gambar di storage/app/public/products
        }
    
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'photo' => $photoPath, // Menyimpan path gambar di database
        ]);
    
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048', // Validasi gambar
        ]);
    
        // Upload foto jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($product->photo && file_exists(storage_path('app/public/' . $product->photo))) {
                unlink(storage_path('app/public/' . $product->photo));
            }
    
            // Simpan foto baru
            $photoPath = $request->file('photo')->store('products', 'public'); // Menyimpan gambar baru
        } else {
            $photoPath = $product->photo; // Jika tidak ada gambar baru, gunakan foto lama
        }
    
        // Update produk
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'photo' => $photoPath, // Menyimpan path gambar di database
        ]);
    
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        // Hapus foto jika ada
        if ($product->photo && file_exists(storage_path('app/public/' . $product->photo))) {
            unlink(storage_path('app/public/' . $product->photo));
        }

        // Hapus produk
        $product->delete();

        // Menambahkan notifikasi sukses
        Session::flash('success', 'Produk berhasil dihapus!');

        return redirect()->route('admin.products.index');
    }
}
