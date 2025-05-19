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
            $table->decimal('price', 12, 2);
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->enum('size_type', ['letter', 'number']); // tipe size produk
            $table->integer('stock')->default(0);// total stok dari semua size

            $table->timestamps();
        });

        
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
