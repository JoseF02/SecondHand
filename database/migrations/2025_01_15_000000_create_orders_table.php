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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('cart_data'); // Datos del carrito al momento de la compra
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending'); // pending, processing, completed, failed, cancelled, refunded
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable(); // ID de la transacción en la pasarela
            $table->string('payment_status')->nullable(); // Estado del pago en la pasarela
            $table->json('billing_info')->nullable(); // Información de facturación
            $table->json('shipping_info')->nullable(); // Información de envío
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
