<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::dropIfExists('order_details');

    Schema::create('order_details', function (Blueprint $table) {
        $table->id('order_detail_id');
        $table->unsignedBigInteger('order_id');
        $table->unsignedBigInteger('menu_id');
        $table->integer('quantity')->default(1);
        $table->decimal('subtotal', 10, 2);
        $table->timestamps();

        $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        $table->foreign('menu_id')->references('id_menu')->on('menus')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('order_details');
}

};
