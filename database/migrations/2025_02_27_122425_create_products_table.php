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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('cost_price', 10, 2);
            $table->decimal('price', 10, 2);
            $table->string('barcode')->unique();
            $table->integer('stock_quantity')->default(0);
            $table->string('brand')->nullable();
            $table->string('image')->nullable();
            $table->decimal('discount', 5, 2)->default(0);
            $table->date('discount_start')->nullable();
            $table->date('discount_end')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('reviews_count')->default(0);
            $table->date('expiration_date')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('dimensions')->nullable();
            $table->string('aisle')->nullable();
            $table->string('section')->nullable();
            $table->integer('floor')->nullable();
            $table->softDeletes(); // Enables soft delete functionality
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
