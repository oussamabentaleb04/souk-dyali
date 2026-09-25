<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('seller_profile_id')->constrained();
            $table->string('status')->default('pending'); // pending, confirmed, shipped, delivered, cancelled
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('shipping_address');
            $table->string('phone');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};