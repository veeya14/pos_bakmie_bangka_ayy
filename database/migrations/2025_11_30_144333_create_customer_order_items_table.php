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
    Schema::create('customer_order_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_order_id')->constrained('customer_orders')->onDelete('cascade');
    $table->foreignId('menu_id')->constrained('menus', 'id_menu');
    $table->string('menu_name');
    $table->integer('qty');
    $table->integer('price');
    $table->integer('subtotal');
    $table->text('note')->nullable();
    $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_order_items');
    }
};
