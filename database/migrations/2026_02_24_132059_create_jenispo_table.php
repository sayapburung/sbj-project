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
            Schema::create('jenis_pos', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');       // Full Order / Printing
            $table->string('kode');           // FO / PRT
            $table->string('nama');           // Full Order Jaket / Print Only
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('jenis_pos');
    }
};
