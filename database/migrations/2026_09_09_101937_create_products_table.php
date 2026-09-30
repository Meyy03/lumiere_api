<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name', 255);

            $table->string('image')->nullable();

            $table->string('size', 50);

            $table->json('available_sizes')->nullable();

            $table->decimal('price', 10, 2);

            $table->decimal('rating', 3, 2)
                ->default(0);

            $table->unsignedInteger('stock')
                ->default(0);

            $table->text('description')
                ->nullable();

            $table->string('skin_type', 255)
                ->default('Suitable for all skin types');

            $table->text('product_details')
                ->nullable();

            $table->text('ingredients')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->index('name');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};