<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants']); // Eager loading
    
        $category = null; // 设置默认值
    
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
            $category = Category::find($request->category_id);
        }
    
        // 添加分页逻辑
        $perPage = 10; // 每页显示的记录数
        $products = $query->paginate($perPage);
    
        // 如果需要，可以在视图中使用 `$products->appends(request()->query())` 来保持查询参数
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
public function store(Request $request)
{
    try {
        // Debug: Log incoming request data
        \Log::info('Product store request:', $request->all());

        // Validasi dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'required|array|max:6',
            'has_variants' => 'boolean',
        ]);

        // Konversi has_variants ke boolean
        $hasVariants = $request->has('has_variants') || $request->has_variants == '1' || $request->has_variants === true;

        // Validasi kondisional berdasarkan varian
        if (!$hasVariants) {
            $request->validate([
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
            ]);
        } else {
            $request->validate([
                'variant_names' => 'required|array|min:1',
                'variant_names.*' => 'required|string|max:255',
                'variant_options' => 'required|array|min:1',
                'variant_prices' => 'required|array|min:1',
                'variant_prices.*' => 'required|numeric|min:0',
                'variant_stocks' => 'required|array',
                'variant_stocks.*' => 'required|integer|min:0',
                'variant_weights' => 'nullable|array',
                'variant_weights.*' => 'nullable|numeric|min:0',
                'variant_lengths' => 'nullable|array',
                'variant_lengths.*' => 'nullable|numeric|min:0',
                'variant_widths' => 'nullable|array',
                'variant_widths.*' => 'nullable|numeric|min:0',
                'variant_heights' => 'nullable|array',
                'variant_heights.*' => 'nullable|numeric|min:0',
                'variant_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        }

        // Mulai database transaction
        \DB::beginTransaction();

        // Handle image uploads untuk produk utama
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if ($image && $image->isValid()) {
                    $imageName = time() . '_' . uniqid() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products', $imageName, 'public');
                    $imagePaths[] = $imagePath;
                }
            }
        }

        // Pastikan ada minimal 1 gambar
        if (empty($imagePaths)) {
            throw new \Exception('Minimal 1 gambar produk harus diupload.');
        }

        // Create product data
        $productData = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'images' => json_encode($imagePaths),
            'has_variants' => $hasVariants,
        ];

        // Jika produk tanpa varian, tambahkan data langsung ke produk
        if (!$hasVariants) {
            $productData = array_merge($productData, [
                'price' => $request->price,
                'stock' => $request->stock ?? 0,
                'weight' => $request->weight ?? 0,
                'length' => $request->length ?? 0,
                'width' => $request->width ?? 0,
                'height' => $request->height ?? 0,
            ]);
        }

        // Create product
        $product = Product::create($productData);

        if (!$product) {
            throw new \Exception('Gagal membuat produk.');
        }

        // Handle variants jika ada
        if ($hasVariants) {
            // Simpan data varian ke kolom variant_data
            $variantData = [
                'variant_names' => array_filter($request->variant_names ?? []),
                'variant_options' => array_filter($request->variant_options ?? [], function($options) {
                    return is_array($options) && !empty($options);
                }),
            ];
            
            $product->update(['variant_data' => json_encode($variantData)]);

            // Simpan setiap kombinasi varian
            $variantPrices = $request->variant_prices ?? [];
            $variantStocks = $request->variant_stocks ?? [];
            $variantWeights = $request->variant_weights ?? [];
            $variantLengths = $request->variant_lengths ?? [];
            $variantWidths = $request->variant_widths ?? [];
            $variantHeights = $request->variant_heights ?? [];
            $variantCombinations = $request->variant_combinations ?? [];

            foreach ($variantPrices as $index => $price) {
                // Handle variant image upload
                $variantImagePath = null;
                if ($request->hasFile('variant_images') && 
                    isset($request->file('variant_images')[$index]) && 
                    $request->file('variant_images')[$index]->isValid()) {
                    
                    $image = $request->file('variant_images')[$index];
                    $imageName = time() . '_' . uniqid() . '_variant_' . $index . '.' . $image->getClientOriginalExtension();
                    $variantImagePath = $image->storeAs('products/variants', $imageName, 'public');
                }

                // Ambil kombinasi varian
                $variant1 = isset($variantCombinations[$index][0]) ? $variantCombinations[$index][0] : null;
                $variant2 = isset($variantCombinations[$index][1]) ? $variantCombinations[$index][1] : null;

                // Create product variant
                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_1' => $variant1,
                    'variant_2' => $variant2,
                    'price' => $price,
                    'stock' => $variantStocks[$index] ?? 0,
                    'weight' => $variantWeights[$index] ?? 0,
                    'length' => $variantLengths[$index] ?? 0,
                    'width' => $variantWidths[$index] ?? 0,
                    'height' => $variantHeights[$index] ?? 0,
                    'image' => $variantImagePath,
                ]);
            }
        }

        // Commit transaction jika semua berhasil
        \DB::commit();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        \DB::rollback();
        \Log::error('Validation error:', $e->errors());
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        \DB::rollback();
        \Log::error('Product creation error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
}

    public function edit($id)
    {
        $product = Product::with(['category', 'variants'])->findOrFail($id);
        $categories = Category::all();
       
        $variantData = json_decode($product->variant_data, true) ?? [];
        $variantNames = $variantData['variant_names'] ?? [];
        $variantOptions = $variantData['variant_options'] ?? [];

        return view('admin.products.edit', compact(
            'product', 
            'categories', 
            'variantNames',
            'variantOptions'
        ));
    }

    public function update(Request $request, Product $product)
{
    try {
        // Debug: Log incoming request data
        \Log::info('Product update request:', $request->all());

        // Validasi dasar
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'array|max:6',
            'has_variants' => 'boolean',
        ]);

        // Konversi has_variants ke boolean
        $hasVariants = $request->has('has_variants') || $request->has_variants == '1' || $request->has_variants === true;

        // Validasi kondisional berdasarkan varian
        if (!$hasVariants) {
            $request->validate([
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'weight' => 'nullable|numeric|min:0',
                'length' => 'nullable|numeric|min:0',
                'width' => 'nullable|numeric|min:0',
                'height' => 'nullable|numeric|min:0',
            ]);
        } else {
            // Perbaikan validasi untuk struktur data varian yang benar
            $request->validate([
                'variant_names' => 'required|array|min:1',
                'variant_names.*' => 'required|string|max:255',
                'variant_options' => 'required|array|min:1',
                'variants' => 'required|array|min:1',
                'variants.*.price' => 'required|numeric|min:0',
                'variants.*.stock' => 'required|integer|min:0',
                'variants.*.weight' => 'nullable|numeric|min:0',
                'variants.*.length' => 'nullable|numeric|min:0',
                'variants.*.width' => 'nullable|numeric|min:0',
                'variants.*.height' => 'nullable|numeric|min:0',
                'variant_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        }

        // Mulai database transaction
        DB::beginTransaction();

        // Handle image uploads untuk produk utama
        // PERBAIKAN: Pastikan kita menghandle data images dengan benar
        $imagePaths = [];
        if ($product->images) {
            // Jika $product->images sudah berupa array, gunakan langsung
            // Jika masih string JSON, decode dulu
            if (is_string($product->images)) {
                $imagePaths = json_decode($product->images, true) ?? [];
            } else {
                $imagePaths = $product->images ?? [];
            }
        }
        
        // Handle image deletion if requested
        if ($request->has('deleted_images')) {
            foreach ($request->deleted_images as $deletedImage) {
                if (($key = array_search($deletedImage, $imagePaths)) !== false) {
                    Storage::disk('public')->delete($deletedImage);
                    unset($imagePaths[$key]);
                }
            }
            $imagePaths = array_values($imagePaths); // Reindex array
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if ($image && $image->isValid()) {
                    $imageName = time() . '_' . uniqid() . '_' . $index . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('products', $imageName, 'public');
                    $imagePaths[] = $imagePath;
                }
            }
        }

        // Batasi maksimal 6 gambar
        if (count($imagePaths) > 6) {
            $imagePaths = array_slice($imagePaths, 0, 6);
        }

        // Pastikan ada minimal 1 gambar
        if (empty($imagePaths)) {
            throw new \Exception('Minimal 1 gambar produk harus diupload.');
        }

        // Update product data
        $productData = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'images' => json_encode($imagePaths),
            'has_variants' => $hasVariants,
        ];

        // Jika produk tanpa varian, tambahkan data langsung ke produk
        if (!$hasVariants) {
            $productData = array_merge($productData, [
                'price' => $request->price,
                'stock' => $request->stock ?? 0,
                'weight' => $request->weight ?? 0,
                'length' => $request->length ?? 0,
                'width' => $request->width ?? 0,
                'height' => $request->height ?? 0,
            ]);
        } else {
            // Reset product-level price and stock if switching to variants
            $productData['price'] = null;
            $productData['stock'] = null;
        }

        // Update product
        $product->update($productData);

        if (!$product) {
            throw new \Exception('Gagal mengupdate produk.');
        }

        // Handle variants jika ada
        if ($hasVariants) {
            // PERBAIKAN: Simpan data varian ke kolom variant_data dengan struktur yang benar
            $variantData = [
                'variant_names' => array_filter($request->variant_names ?? []),
                'variant_options' => array_filter($request->variant_options ?? [], function($options) {
                    return is_array($options) && !empty($options);
                }),
            ];
            
            $product->update(['variant_data' => json_encode($variantData)]);

            // Delete existing variants
            $product->variants()->delete();

            // PERBAIKAN: Simpan setiap kombinasi varian dengan struktur data yang benar
            $variants = $request->variants ?? [];

            foreach ($variants as $index => $variantData) {
                // Handle variant image upload
                $variantImagePath = null;
                if ($request->hasFile('variant_images') && 
                    isset($request->file('variant_images')[$index]) && 
                    $request->file('variant_images')[$index]->isValid()) {
                    
                    $image = $request->file('variant_images')[$index];
                    $imageName = time() . '_' . uniqid() . '_variant_' . $index . '.' . $image->getClientOriginalExtension();
                    $variantImagePath = $image->storeAs('products/variants', $imageName, 'public');
                } else {
                    // Keep existing image if updating existing variant
                    if (isset($variantData['id'])) {
                        $existingVariant = ProductVariant::find($variantData['id']);
                        if ($existingVariant && $existingVariant->image) {
                            $variantImagePath = $existingVariant->image;
                        }
                    }
                }

                // Ambil kombinasi varian dari data yang dikirim
                $variant1 = $variantData['variant_1'] ?? null;
                $variant2 = $variantData['variant_2'] ?? null;

                // Create product variant
                ProductVariant::create([
                    'product_id' => $product->id,
                    'variant_1' => $variant1,
                    'variant_2' => $variant2,
                    'price' => $variantData['price'] ?? 0,
                    'stock' => $variantData['stock'] ?? 0,
                    'weight' => $variantData['weight'] ?? 0,
                    'length' => $variantData['length'] ?? 0,
                    'width' => $variantData['width'] ?? 0,
                    'height' => $variantData['height'] ?? 0,
                    'image' => $variantImagePath,
                ]);
            }
        } else {
            // Jika produk diubah dari punya varian ke tidak punya varian
            $product->variants()->delete();
            $product->update(['variant_data' => null]);
        }

        // Commit transaction jika semua berhasil
        DB::commit();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();
        \Log::error('Validation error:', $e->errors());
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Product update error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
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



}
