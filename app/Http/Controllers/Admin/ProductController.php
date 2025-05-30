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
public function index(Request $request)
{
    $query = Product::with('category');

    $category = null;
    if ($request->has('category_id')) {
        $query->where('category_id', $request->category_id);
        $category = Category::find($request->category_id);
    }

    $products = $query->get();

    return view('admin.products.index', compact('products', 'category'));
}



public function create(Request $request)
{
    $categories = Category::all();
    $letterSizes = Size::where('type', 'letter')->get();
    $numberSizes = Size::where('type', 'number')->get();
    $selectedCategoryId = $request->category_id; // untuk form

    return view('admin.products.create', compact('categories', 'letterSizes', 'numberSizes', 'selectedCategoryId'));
}



    // Dalam ProductController.php - method store()
// Dalam ProductController.php - method store()
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'images' => 'required|array|max:6',
        'size_type' => 'required|in:letter,number',
        'sizes' => 'required|array|min:1',
        'stocks' => 'required|array',
    ]);

    // Handle multiple image uploads
    $imagePaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $imagePaths[] = $imagePath;
        }
    }

    // Create product
    $product = Product::create([
        'name' => $request->name,
        'category_id' => $request->category_id,
        'price' => $request->price,
        'description' => $request->description,
        'images' => $imagePaths, // Simpan sebagai array
        'size_type' => $request->size_type,
        'stock' => 0, // Will be updated after attaching sizes
    ]);

    // Attach sizes with stock
    $totalStock = 0;
    foreach ($request->sizes as $index => $sizeId) {
        $stock = isset($request->stocks[$index]) ? (int)$request->stocks[$index] : 0;
        $product->sizes()->attach($sizeId, ['stock' => $stock]);
        $totalStock += $stock;
    }

    // Update total stock
    $product->update(['stock' => $totalStock]);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
}

// Method untuk update juga perlu disesuaikan
public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'images' => 'array|max:6',
        'size_type' => 'required|in:letter,number',
        'sizes' => 'required|array|min:1',
        'stocks' => 'required|array',
    ]);

    // Handle image updates
    $imagePaths = $product->images ?? []; // Keep existing images
    
    if ($request->hasFile('images')) {
        // Optionally delete old images
        if (!empty($product->images)) {
            foreach ($product->images as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }
        
        // Upload new images
        $imagePaths = [];
        foreach ($request->file('images') as $image) {
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $imagePaths[] = $imagePath;
        }
    }

    // Update product
    $product->update([
        'name' => $request->name,
        'category_id' => $request->category_id,
        'price' => $request->price,
        'description' => $request->description,
        'images' => $imagePaths,
        'size_type' => $request->size_type,
    ]);

    // Update sizes
    $product->sizes()->detach();
    $totalStock = 0;
    foreach ($request->sizes as $index => $sizeId) {
        $stock = isset($request->stocks[$index]) ? (int)$request->stocks[$index] : 0;
        $product->sizes()->attach($sizeId, ['stock' => $stock]);
        $totalStock += $stock;
    }

    $product->update(['stock' => $totalStock]);

    return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
}



    public function edit($id)
    {
        $product = Product::with('sizes')->findOrFail($id);
        $categories = Category::all();
$letterSizes = Size::where('type', 'letter')->get();
$numberSizes = Size::where('type', 'number')->get();


        return view('admin.products.edit', compact('product', 'categories', 'letterSizes', 'numberSizes'));
    }

   
public function destroy($id)
{
    $product = Product::findOrFail($id);
    $categoryId = $product->category_id;  // simpan dulu category_id sebelum dihapus
    
    if ($product->image) {
        Storage::disk('public')->delete($product->image);
    }
    $product->delete();
    
    return redirect()->route('admin.products.index', ['category_id' => $categoryId])
                 ->with('success', 'Produk berhasil dihapus.');

}


    public function show($id)
{
    $product = Product::with(['category', 'sizes'])->findOrFail($id);
    
    return view('admin.products.show', compact('product'));
}

}
