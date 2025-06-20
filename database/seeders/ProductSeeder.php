<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Data produk tanpa varian
        $productWithoutVariant = [
            'name' => 'Laptop Gaming Asus ROG',
            'category_id' => 1, // Pastikan category dengan ID 1 ada di tabel categories
            'description' => 'Laptop gaming berkualitas tinggi dengan performa maksimal untuk gaming dan produktivitas. Dilengkapi dengan processor terbaru dan kartu grafis dedicated.',
            'price' => 15000000.00,
            'stock' => 25,
            'weight' => 2.5,
            'length' => 35.0,
            'width' => 25.0,
            'height' => 3.0,
            'has_variants' => false,
            'images' => json_encode([
                'products/laptop-gaming-1.jpg',
                'products/laptop-gaming-2.jpg',
                'products/laptop-gaming-3.jpg'
            ]),
            'variant_data' => null,
           
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Data produk dengan varian
        $productWithVariant = [
            'name' => 'Kaos Polo Casual',
            'category_id' => 2, // Pastikan category dengan ID 2 ada di tabel categories
            'description' => 'Kaos polo casual yang nyaman untuk aktivitas sehari-hari. Terbuat dari bahan katun berkualitas tinggi dengan berbagai pilihan warna dan ukuran.',
            'price' => null, // Null karena menggunakan varian
            'stock' => null, // Null karena menggunakan varian
            'weight' => null,
            'length' => null,
            'width' => null,
            'height' => null,
            'has_variants' => true,
            'images' => json_encode([
                'products/kaos-polo-1.jpg',
                'products/kaos-polo-2.jpg',
                'products/kaos-polo-3.jpg',
                'products/kaos-polo-4.jpg'
            ]),
            'variant_data' => json_encode([
                'variant_names' => ['Warna', 'Ukuran'],
                'variant_options' => [
                    ['Merah', 'Biru', 'Hijau', 'Hitam'],
                    ['S', 'M', 'L', 'XL']
                ],
                'combinations' => [
                    // Merah
                    ['Merah', 'S'],
                    ['Merah', 'M'],
                    ['Merah', 'L'],
                    ['Merah', 'XL'],
                    // Biru
                    ['Biru', 'S'],
                    ['Biru', 'M'],
                    ['Biru', 'L'],
                    ['Biru', 'XL'],
                    // Hijau
                    ['Hijau', 'S'],
                    ['Hijau', 'M'],
                    ['Hijau', 'L'],
                    ['Hijau', 'XL'],
                    // Hitam
                    ['Hitam', 'S'],
                    ['Hitam', 'M'],
                    ['Hitam', 'L'],
                    ['Hitam', 'XL'],
                ],
                'variant_prices' => [
                    150000, 150000, 150000, 160000, // Merah S,M,L,XL
                    150000, 150000, 150000, 160000, // Biru S,M,L,XL
                    150000, 150000, 150000, 160000, // Hijau S,M,L,XL
                    155000, 155000, 155000, 165000, // Hitam S,M,L,XL (lebih mahal)
                ],
                'variant_stocks' => [
                    10, 15, 20, 8,  // Merah S,M,L,XL
                    12, 18, 22, 10, // Biru S,M,L,XL
                    8, 12, 15, 6,   // Hijau S,M,L,XL
                    15, 25, 30, 12, // Hitam S,M,L,XL
                ],
                'variant_weights' => [
                    0.3, 0.35, 0.4, 0.45, // Merah S,M,L,XL
                    0.3, 0.35, 0.4, 0.45, // Biru S,M,L,XL
                    0.3, 0.35, 0.4, 0.45, // Hijau S,M,L,XL
                    0.3, 0.35, 0.4, 0.45, // Hitam S,M,L,XL
                ],
                'variant_lengths' => [
                    25, 27, 29, 31, // Merah S,M,L,XL
                    25, 27, 29, 31, // Biru S,M,L,XL
                    25, 27, 29, 31, // Hijau S,M,L,XL
                    25, 27, 29, 31, // Hitam S,M,L,XL
                ],
                'variant_widths' => [
                    20, 22, 24, 26, // Merah S,M,L,XL
                    20, 22, 24, 26, // Biru S,M,L,XL
                    20, 22, 24, 26, // Hijau S,M,L,XL
                    20, 22, 24, 26, // Hitam S,M,L,XL
                ],
                'variant_heights' => [
                    1, 1, 1, 1, // Merah S,M,L,XL
                    1, 1, 1, 1, // Biru S,M,L,XL
                    1, 1, 1, 1, // Hijau S,M,L,XL
                    1, 1, 1, 1, // Hitam S,M,L,XL
                ],
            ]),
           
            'created_at' => $now,
            'updated_at' => $now,
        ];

        // Insert data ke database
        DB::table('products')->insert([
            $productWithoutVariant,
            $productWithVariant
        ]);

        $this->command->info('Product seeder completed: 2 products created (1 without variants, 1 with variants)');
    }
}