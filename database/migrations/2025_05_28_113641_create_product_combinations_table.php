<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('product_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variation_option_1_id')->constrained('variation_options')->onDelete('cascade');
            $table->foreignId('variation_option_2_id')->nullable()->constrained('variation_options')->onDelete('cascade');
            $table->decimal('price', 12, 2);
            $table->integer('stock');
            $table->timestamps();
        });
    }
     
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_combinations');
    }
};
