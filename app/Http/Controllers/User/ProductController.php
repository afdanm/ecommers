<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'reviews', 'variants']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Add sorting options
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderByRaw('COALESCE(price, (SELECT MIN(price) FROM product_variants WHERE product_id = products.id)) ASC');
                    break;
                case 'price_high':
                    $query->orderByRaw('COALESCE(price, (SELECT MAX(price) FROM product_variants WHERE product_id = products.id)) DESC');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('user.products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user', 'variants']);

        // Handle product variants
        $availableVariants = [];
        $total_stock = 0;
        $minPrice = null;
        $maxPrice = null;
        $variantData = null;

        if ($product->has_variants && $product->variants->count() > 0) {
            // Product has variants
            $availableVariants = $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'variant_1' => $variant->variant_1,
                    'variant_2' => $variant->variant_2,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'available' => $variant->stock > 0,
                    'weight' => $variant->weight,
                    'image' => $variant->image,
                ];
            })->toArray();

            $total_stock = $product->variants->sum('stock');
            $minPrice = $product->variants->min('price');
            $maxPrice = $product->variants->max('price');

            // Decode variant data for frontend
            if ($product->variant_data) {
                $variantData = json_decode($product->variant_data, true);
            }
        } else {
            // Product without variants
            $total_stock = $product->stock ?? 0;
            $minPrice = $maxPrice = $product->price ?? 0;
        }

        // Handle product images
        $productImages = [];
        if ($product->images) {
            $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
            $productImages = is_array($images) ? $images : [];
        }

        // Check if review mode is active
        $canReview = false;
        $transactionId = null;
        $rating = null;

        if (request()->has('review') && request()->has('transaction_id') && auth()->check()) {
            $transaction = Transaction::where('id', request('transaction_id'))
                ->where('user_id', auth()->id())
                ->first();

            if ($transaction && $transaction->products->contains($product->id)) {
                $canReview = true;
                $transactionId = $transaction->id;

                // Check if user already reviewed this product from this transaction
                $existingReview = $product->reviews
                    ->where('user_id', auth()->id())
                    ->where('transaction_id', $transaction->id)
                    ->first();

                if ($existingReview) {
                    $canReview = false;
                }
            }
        }

        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['variants', 'reviews'])
            ->limit(4)
            ->get();

        return view('user.products.show', compact(
            'product',
            'availableVariants',
            'total_stock',
            'minPrice',
            'maxPrice',
            'productImages',
            'variantData',
            'canReview',
            'transactionId',
            'rating',
            'relatedProducts'
        ));
    }

    public function getVariants(Product $product)
    {
        if (!$product->has_variants) {
            return response()->json(['error' => 'Product does not have variants'], 400);
        }

        $variants = $product->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'variant_1' => $variant->variant_1,
                'variant_2' => $variant->variant_2,
                'price' => $variant->price,
                'stock' => $variant->stock,
                'weight' => $variant->weight,
                'image' => $variant->image,
                'available' => $variant->stock > 0,
            ];
        });

        return response()->json([
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
            ],
            'variants' => $variants
        ]);
    }

    public function getProductDetails(Product $product)
    {
        $product->load(['variants', 'reviews']);

        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'has_variants' => $product->has_variants,
            'price' => $product->price,
            'stock' => $product->stock,
            'images' => is_string($product->images) ? json_decode($product->images, true) : $product->images,
        ];

        if ($product->has_variants) {
            $data['variants'] = $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'variant_1' => $variant->variant_1,
                    'variant_2' => $variant->variant_2,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'weight' => $variant->weight,
                    'image' => $variant->image,
                ];
            });

            $data['variant_data'] = $product->variant_data ? json_decode($product->variant_data, true) : null;
        }

        return response()->json($data);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', '%' . $query . '%')
            ->with(['variants'])
            ->limit(10)
            ->get()
            ->map(function ($product) {
                $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
                $firstImage = is_array($images) && !empty($images) ? $images[0] : null;

                $price = $product->price;
                if ($product->has_variants && $product->variants->count() > 0) {
                    $minPrice = $product->variants->min('price');
                    $maxPrice = $product->variants->max('price');
                    $price = $minPrice == $maxPrice ? $minPrice : $minPrice . ' - ' . $maxPrice;
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'image' => $firstImage,
                    'url' => route('products.show', $product->id),
                ];
            });

        return response()->json($products);
    }

    public function byCategory(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->with(['variants', 'reviews'])
            ->paginate(12);

        $categories = Category::all();

        return view('user.products.index', compact('products', 'categories', 'category'));
    }
}