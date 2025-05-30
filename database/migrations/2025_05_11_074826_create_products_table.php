<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 12, 2)->nullable(); // Nullable untuk produk dengan varian
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->boolean('has_variants')->default(false);
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->integer('stock')->default(0)->nullable(); // Nullable untuk produk dengan varian
            $table->json('variant_data')->nullable(); // Untuk menyimpan nama varian dan opsi
            $table->timestamps();
        });
    
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
