<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fashion',
                'foto' => 'categories/Q31Vyu6wSom5bs3tDZIAbVX125orpZBAdklCUP3B.jpg',
            ],
            [
                'name' => 'Shoes',
                'foto' => 'categories/0Je7zpORceaGNR7eYUL63ypAlwAB0JZhgJ5kgjmU.jpg',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],    // kondisi pencarian
                ['foto' => $category['foto']]     // data yang akan diupdate jika create baru
            );
        }
    }
}
