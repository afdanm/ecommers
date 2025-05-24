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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->enum('purchase_method', ['pickup', 'delivery'])->default('pickup');
            $table->text('delivery_address')->nullable();
            $table->string('midtrans_order_id')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_status')->default('pending');
            $table->string('shipping_status')->nullable()->default('diproses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
