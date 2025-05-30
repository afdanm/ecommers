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
        $table->json('images')->nullable(); // <-- update dari 'image' jadi 'images'
        $table->enum('size_type', ['letter', 'number']);
        $table->integer('stock')->default(0);
        $table->timestamps();
    });
    
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
