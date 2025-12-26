<?php

// database/migrations/2024_01_01_000008_create_order_histories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('from_stage')->nullable(); // Stage sebelumnya (null jika baru dibuat)
            $table->string('to_stage'); // Stage tujuan
            $table->string('from_status')->nullable(); // Status sebelumnya
            $table->string('to_status'); // Status baru
            $table->text('notes')->nullable(); // Catatan/komentar saat transisi
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User yang melakukan aksi
            $table->timestamp('created_at'); // Waktu transisi
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_histories');
    }
};