<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method', 20)->change();
            $table->string('payment_status', 20)->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('payment_method', ['COD', 'BANK'])->default('COD')->change();
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid')->change();
        });
    }
};
