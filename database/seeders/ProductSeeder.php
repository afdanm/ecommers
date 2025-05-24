<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Size;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori sudah ada
        $fashionCategory = Category::firstOrCreate(['name' => 'Fashion']);
        $shoesCategory = Category::firstOrCreate(['name' => 'Shoes']);

        // Ambil semua ukuran letter dan number dari DB
        $letterSizes = Size::where('type', 'letter')->get();
        $numberSizes = Size::where('type', 'number')->get();

        // Produk 1: Kaos dengan ukuran letter
        $product1 = Product::firstOrCreate([
            'name' => 'Kaos Batik Lurik',
            'category_id' => $fashionCategory->id,
        ], [
            'price' => 150000,
            'description' => 'Kaos batik khas Indonesia, cocok untuk acara santai.',
            'image' => null,
            'stock' => 0,
        ]);

        // Hapus relasi size lama sebelum attach baru
        $product1->sizes()->detach();

        $totalStock1 = 0;
        foreach ($letterSizes as $size) {
            $stock = rand(5, 20);
            $product1->sizes()->attach($size->id, ['stock' => $stock]);
            $totalStock1 += $stock;
        }
        $product1->update(['stock' => $totalStock1]);

        // Produk 2: Sepatu dengan ukuran number
        $product2 = Product::firstOrCreate([
            'name' => 'Sepatu Kulit Handmade',
            'category_id' => $shoesCategory->id,
        ], [
            'price' => 275000,
            'description' => 'Sepatu kulit asli buatan lokal dengan kualitas premium.',
            'image' => null,
            'stock' => 0,
        ]);

        // Hapus relasi size lama sebelum attach baru
        $product2->sizes()->detach();

        $totalStock2 = 0;
        foreach ($numberSizes as $size) {
            $stock = rand(3, 15);
            $product2->sizes()->attach($size->id, ['stock' => $stock]);
            $totalStock2 += $stock;
        }
        $product2->update(['stock' => $totalStock2]);
    }
}
