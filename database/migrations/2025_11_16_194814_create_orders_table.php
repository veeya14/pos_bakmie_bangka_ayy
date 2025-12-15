<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->enum('status_order', ['OPEN','CLOSE'])->default('OPEN');
            $table->enum('status_bayar', ['UNPAID','PAID'])->default('UNPAID');
            $table->dateTime('order_datetime')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
