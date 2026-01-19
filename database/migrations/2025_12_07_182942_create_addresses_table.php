<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id('id');

            $table->foreignId('userid')->unique()->constrained('users')->cascadeOnDelete();

            $table->string('name', 100);
            $table->string('phone', 20);
            $table->text('address');
            $table->string('city', 100);
            $table->string('ward', 100);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
