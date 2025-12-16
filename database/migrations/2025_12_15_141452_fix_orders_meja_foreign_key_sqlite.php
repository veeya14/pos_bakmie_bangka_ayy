<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Rename tabel orders lama
        Schema::rename('orders', 'orders_old');

        // 2. Buat tabel orders baru dengan FK yang BENAR (sesuai meja.meja_id)
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');

            $table->unsignedBigInteger('meja_id')->nullable();
            $table->foreign('meja_id')
                  ->references('meja_id')
                  ->on('meja')
                  ->onDelete('set null');

            $table->string('customer_name'); // NOT NULL
            $table->string('status_order');
            $table->string('status_bayar');
            $table->dateTime('order_datetime');

            $table->integer('total_bayar')->default(0);
            $table->string('payment_method')->nullable();

            $table->timestamps();
        });

        // 3. Copy data lama (AMAN: handle NULL customer_name)
        DB::statement("
            INSERT INTO orders (
                order_id,
                meja_id,
                customer_name,
                status_order,
                status_bayar,
                order_datetime,
                total_bayar,
                payment_method,
                created_at,
                updated_at
            )
            SELECT
                order_id,
                meja_id,
                COALESCE(customer_name, 'Guest'),
                status_order,
                status_bayar,
                order_datetime,
                total_bayar,
                payment_method,
                created_at,
                updated_at
            FROM orders_old
        ");

        // 4. Hapus tabel lama
        Schema::drop('orders_old');
    }

    public function down(): void
    {
        // Rollback tidak disarankan untuk SQLite (foreign key)
    }
};
