<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number', 30)
                ->unique();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Customer information snapshot.
            $table->string('customer_name', 150);

            $table->string('customer_phone', 30);

            $table->string('delivery_phone', 30);

            $table->text('delivery_address');

            $table->string('delivery_province', 100);

            $table->boolean('contact_via_telegram')
                ->default(false);

            // Payment.
            $table->string('payment_method', 50);

            // Amounts.
            $table->decimal('subtotal', 10, 2);

            $table->decimal('shipping_fee', 10, 2)
                ->default(0);

            $table->decimal('discount', 10, 2)
                ->default(0);

            $table->decimal('total', 10, 2);

            // Order status.
            $table->string('status', 30)
                ->default('pending');

            $table->timestamp('ordered_at')
                ->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('ordered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};