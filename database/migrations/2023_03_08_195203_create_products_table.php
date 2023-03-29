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
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->integer('stock')->unsigned();
            $table->string('image');
            $table->string('is_new')->default('no');
            $table->string('is_featured')->default('no');
            $table->string('is_popular')->default('no');
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();

            // add indexes for faster queries
            $table->index('category_id');
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
