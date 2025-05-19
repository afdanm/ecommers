<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizeTypes = [
            'letter' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            'number' => range(35, 50)
        ];

        foreach ($sizeTypes as $type => $sizes) {
            foreach ($sizes as $size) {
                DB::table('sizes')->insert([
                    'name' => $size,
                    'type' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
