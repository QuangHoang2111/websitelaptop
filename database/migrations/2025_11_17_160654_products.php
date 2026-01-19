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
            $table->string('slug')->unique();
            $table->string('shortdescription')->nullable();
            $table->text('description')->nullable();
            $table->decimal('costprice', 12, 2)->default(0);
            $table->decimal('regularprice', 12, 2)->default(0);
            $table->decimal('saleprice', 12, 2)->default(0);
            $table->string('sku')->nullable();
            $table->integer('stocks')->default(0);
            $table->boolean('isfeatured')->default(false);
            $table->string('image')->nullable();
            $table->text('images')->nullable();
            $table->unsignedBigInteger('categoryid')->index();
            $table->unsignedBigInteger('brandid')->index();
            $table->timestamps();

            $table->foreign('categoryid')->references('id')->on('categories')->onDelete('cascade');
            
            $table->foreign('brandid')->references('id')->on('brands')->onDelete('cascade');
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
