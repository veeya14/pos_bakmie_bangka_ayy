<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->unsignedBigInteger('id_category')->nullable();
            $table->string('menu_name');
            $table->text('menu_description')->nullable();
            $table->decimal('menu_price', 10, 2);
            $table->enum('menu_status', ['available','sold_out'])->default('available');
            $table->boolean('menu_active')->default(true);
            $table->string('menu_image')->nullable();
            $table->timestamps();

            // Foreign key MySQL only, SQLite ignore
            if (config('database.default') !== 'sqlite') {
                $table->foreign('id_category')
                      ->references('id_category')
                      ->on('categories')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
