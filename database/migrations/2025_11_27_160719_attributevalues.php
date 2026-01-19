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
        Schema::create('attributevalues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productid');
            $table->unsignedBigInteger('attrid');
            $table->text('value')->nullable();
            $table->timestamps();

            $table->foreign('productid') ->references('id')->on('products') ->onDelete('cascade');
            $table->foreign('attrid') ->references('id')->on('attributes') ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributevalues');
    }
};
