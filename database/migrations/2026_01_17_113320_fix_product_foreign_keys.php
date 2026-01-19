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
        Schema::table('products', function (Blueprint $table) {

            $table->dropForeign(['categoryid']);
            $table->dropForeign(['brandid']);

            $table->unsignedBigInteger('categoryid')->nullable()->change();
            $table->unsignedBigInteger('brandid')->nullable()->change();

            $table->foreign('categoryid')->references('id')->on('categories')->nullOnDelete();

            $table->foreign('brandid')->references('id') ->on('brands')->nullOnDelete();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
