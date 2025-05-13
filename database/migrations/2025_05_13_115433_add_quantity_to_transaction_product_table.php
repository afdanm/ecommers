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
        Schema::table('transaction_product', function (Blueprint $table) {

            $table->integer('quantity')->after('product_id')->default(1); // Tambahkan kolom quantity
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_product', function (Blueprint $table) {
            $table->dropColumn('quantity');
        });
    }
};
