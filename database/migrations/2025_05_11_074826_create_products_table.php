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
            $table->text('description');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete(); // pastikan ada tabel categories
            $table->boolean('use_variant')->default(false); // aktifkan varian?
            $table->string('variant_name_1')->nullable(); // contoh: Warna
            $table->string('variant_name_2')->nullable(); // contoh: Ukuran
            $table->integer('price')->nullable(); // jika tidak pakai varian
            $table->integer('stock')->nullable(); // jika tidak pakai varian
            $table->float('weight')->nullable(); // gram
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->timestamps();
        });
    
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
