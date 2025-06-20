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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('variant_option_1'); // contoh: Putih
            $table->string('variant_option_2')->nullable(); // contoh: XXL (jika tidak pakai varian 2, bisa null)
            $table->string('variant_image')->nullable(); // gambar untuk opsi varian 1
            $table->integer('price');
            $table->integer('stock');
            $table->float('weight')->nullable(); // gram
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
