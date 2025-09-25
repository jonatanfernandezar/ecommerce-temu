<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0);

            // Relaciones (foreignUuid asegura compatibilidad exacta con las tablas padre)
            $table->foreignUuid('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();

            $table->foreignUuid('brand_id')
                  ->nullable()
                  ->constrained('brands')
                  ->cascadeOnDelete();

            $table->string('status')->default('active');
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
