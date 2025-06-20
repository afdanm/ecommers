<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images', 'variants')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'images' => 'required|array|min:1|max:6',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'use_variant' => $request->has('use_variant'),
                'variant_name_1' => $request->variant_name_1,
                'variant_name_2' => $request->variant_name_2,
                'price' => $request->price,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
            ]);

            // Simpan foto produk utama (1–6)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create(['image_path' => $path]);
                }
            }

            // Jika pakai varian, simpan variannya
            if ($product->use_variant) {
                foreach ($request->variants as $variant) {
                    $imagePath = null;

                    if (isset($variant['variant_image']) && $variant['variant_image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $variant['variant_image']->store('variants', 'public');
                    }

                    $product->variants()->create([
                        'variant_option_1' => $variant['variant_option_1'],
                        'variant_option_2' => $variant['variant_option_2'] ?? null,
                        'variant_image' => $imagePath,
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'weight' => $variant['weight'],
                        'length' => $variant['length'],
                        'width' => $variant['width'],
                        'height' => $variant['height'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors('Gagal menyimpan produk: ' . $e->getMessage())->withInput();
        }
    }

    // public function show(Product $product)
    // {
    //     return view('products.show', compact('product'));
    // }

    public function edit(Product $product)
    {
        $categories = \App\Models\Category::all();
        $product->load('images', 'variants');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'images' => 'nullable|array|max:6',
        ]);
    
        DB::beginTransaction();
    
        try {
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'use_variant' => $request->has('use_variant'),
                'variant_name_1' => $request->variant_name_1,
                'variant_name_2' => $request->variant_name_2,
                'price' => $request->price,
                'stock' => $request->stock,
                'weight' => $request->weight,
                'length' => $request->length,
                'width' => $request->width,
                'height' => $request->height,
            ]);
    
            // Jika ada gambar baru, hapus gambar lama dan simpan yang baru
            if ($request->hasFile('images')) {
                foreach ($product->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage->image_path);
                    $oldImage->delete();
                }
    
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    $product->images()->create(['image_path' => $path]);
                }
            }
    
            // Hapus semua varian lama jika pakai varian
            if ($product->use_variant) {
                foreach ($product->variants as $variant) {
                    if ($variant->variant_image) {
                        Storage::disk('public')->delete($variant->variant_image);
                    }
                    $variant->delete();
                }
    
                // Tambahkan varian baru
                if (is_array($request->variants)) {
                    foreach ($request->variants as $variant) {
                        $imagePath = null;
    
                        if (isset($variant['variant_image']) && $variant['variant_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $imagePath = $variant['variant_image']->store('variants', 'public');
                        }
    
                        $product->variants()->create([
                            'variant_option_1' => $variant['variant_option_1'],
                            'variant_option_2' => $variant['variant_option_2'] ?? null,
                            'variant_image' => $imagePath,
                            'price' => $variant['price'],
                            'stock' => $variant['stock'],
                            'weight' => $variant['weight'],
                            'length' => $variant['length'],
                            'width' => $variant['width'],
                            'height' => $variant['height'],
                        ]);
                    }
                }
            } else {
                // Jika sebelumnya pakai varian dan sekarang tidak, hapus semua varian
                foreach ($product->variants as $variant) {
                    if ($variant->variant_image) {
                        Storage::disk('public')->delete($variant->variant_image);
                    }
                    $variant->delete();
                }
            }
    
            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal memperbarui produk: ' . $e->getMessage())->withInput();
        }
    }
    
    public function destroy(Product $product)
    {
        // Hapus semua varian dan gambar terkait
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        foreach ($product->variants as $variant) {
            if ($variant->variant_image) {
                Storage::disk('public')->delete($variant->variant_image);
            }
            $variant->delete();
        }

        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }
}