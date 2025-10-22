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

            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('manufacturer_id')->constrained('manufacturers')->nullOnDelete();

            $table->string('name');
            $table->string('model_code')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_uah', 12, 2)->nullable();
            $table->unsignedSmallInteger('warranty_months')->default(0);
            $table->boolean('is_available')->nullable();

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
