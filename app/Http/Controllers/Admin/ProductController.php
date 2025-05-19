<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


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
        $letterSizes = Size::where('type', 'letter')->get();
        $numberSizes = Size::where('type', 'number')->get();
        

        return view('admin.products.create', compact('categories', 'letterSizes', 'numberSizes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'size_type' => 'required|in:huruf,angka',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'stocks' => 'required|array',
            'stocks.*' => 'required|integer|min:0',
        ]);

        // Hitung total stok
        $totalStock = array_sum($request->stocks);

        // Upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Simpan produk
        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'stock' => $totalStock,
        ]);

        // Simpan ukuran dan stok ke tabel pivot
        foreach ($request->sizes as $index => $sizeId) {
            $product->sizes()->attach($sizeId, [
                'stock' => $request->stocks[$index],
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        $categories = Category::all();
        $letterSizes = Size::whereHas('typeSize', fn ($q) => $q->where('name', 'huruf'))->get();
        $numberSizes = Size::whereHas('typeSize', fn ($q) => $q->where('name', 'angka'))->get();

        return view('admin.products.edit', compact('product', 'categories', 'letterSizes', 'numberSizes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
            'size_type' => 'required|in:huruf,angka',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'stocks' => 'required|array',
            'stocks.*' => 'required|integer|min:0',
        ]);

        // Hitung total stok
        $totalStock = array_sum($request->stocks);

        // Upload gambar baru jika ada
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // Update produk
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'stock' => $totalStock,
        ]);

        // Sync ukuran dan stok
        $syncData = [];
        foreach ($request->sizes as $index => $sizeId) {
            $syncData[$sizeId] = ['stock' => $request->stocks[$index]];
        }
        $product->sizes()->sync($syncData);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function show($id)
{
    $product = Product::with(['category', 'sizes'])->findOrFail($id);
    
    return view('admin.products.show', compact('product'));
}

}
