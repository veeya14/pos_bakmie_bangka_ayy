<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id('cart_id');
            $table->unsignedBigInteger('menu_id');   // menu yg dimasukkan ke cart
            $table->integer('quantity')->default(1); // jumlah
            $table->decimal('subtotal', 10, 2);      // harga x qty
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
