<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('userid')->constrained('users')->cascadeOnDelete();

            $table->foreignId('voucherid')->constrained('vouchers') ->cascadeOnDelete();

            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->unique(['userid', 'voucherid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_vouchers');
    }
};
