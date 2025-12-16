<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 🔴 MATIKAN FK (KUNCI UTAMA SQLITE)
        DB::statement('PRAGMA foreign_keys = OFF');

        // 1. Rename tabel lama
        Schema::rename('order_details', 'order_details_old');

        // 2. Buat tabel baru (FK SUDAH BENAR)
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')
                  ->references('order_id')
                  ->on('orders')
                  ->onDelete('cascade');

            // PK menus kamu = menu_id (BUKAN id)
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')
                  ->references('menu_id')
                  ->on('menus')
                  ->onDelete('cascade');

            $table->integer('quantity');
            $table->integer('subtotal');
            $table->text('notes')->nullable();

            $table->timestamps();
        });

        // 3. COPY DATA LAMA (FK MATI → TIDAK ERROR)
        DB::statement("
            INSERT INTO order_details (
                order_id,
                menu_id,
                quantity,
                subtotal,
                notes,
                created_at,
                updated_at
            )
            SELECT
                order_id,
                menu_id,
                quantity,
                subtotal,
                notes,
                created_at,
                updated_at
            FROM order_details_old
        ");

        // 4. HAPUS TABEL LAMA
        Schema::drop('order_details_old');

        // 🟢 AKTIFKAN FK LAGI
        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // rollback tidak disarankan untuk SQLite
    }
};
