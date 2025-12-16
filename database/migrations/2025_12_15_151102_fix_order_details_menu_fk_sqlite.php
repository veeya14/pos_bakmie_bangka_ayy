<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // MATIKAN FK CHECK (WAJIB DI SQLITE)
        DB::statement('PRAGMA foreign_keys=OFF');

        // HAPUS order_details_old KALAU ADA (biar gak error rename)
        Schema::dropIfExists('order_details_old');

        // rename tabel lama
        Schema::rename('order_details', 'order_details_old');

        // BUAT TABEL BARU DENGAN FK YANG BENAR
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('menu_id'); // FK KE id_menu
            $table->integer('quantity');
            $table->integer('subtotal');
            $table->text('notes')->nullable();
            $table->timestamps();

            // FK ORDER
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade');

            // 🔥 FK MENU (BENAR)
            $table->foreign('menu_id')
                ->references('id_menu')
                ->on('menus')
                ->onDelete('cascade');
        });

        // COPY DATA LAMA
        DB::statement("
            INSERT INTO order_details
            (order_id, menu_id, quantity, subtotal, notes, created_at, updated_at)
            SELECT
            order_id, menu_id, quantity, subtotal, notes, created_at, updated_at
            FROM order_details_old
        ");

        // HAPUS TABEL LAMA
        Schema::drop('order_details_old');

        // NYALAKAN FK LAGI
        DB::statement('PRAGMA foreign_keys=ON');
    }

    public function down(): void {}
};
