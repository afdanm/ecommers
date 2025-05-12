<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
    public function run(): void
    {
        User::create([
            'username' => 'admin123',
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '08123456789',
            'alamat' => 'Jl. Admin No. 1',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
    
}
