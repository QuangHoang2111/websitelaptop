<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();;

            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('total', 15, 2);

            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->string('city');
            $table->string('ward');

            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');

            $table->enum('payment_method', ['COD', 'BANK'])->default('COD');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid');

            $table->date('delivered_date')->nullable();
            $table->date('cancelled_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
