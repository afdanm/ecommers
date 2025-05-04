<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // pastikan kolom name ada di sini
            $table->text('description');
            $table->decimal('price', 10, 2);  // harga produk
            $table->string('category');  // kategori produk
            $table->integer('stock');  // stok produk
            $table->string('status');  // status produk, misalnya 'ready' atau 'not ready'
            $table->string('image');  // gambar produk
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
