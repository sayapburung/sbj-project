<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->foreignId('jenis_po_id')
                ->constrained('jenis_pos')
                ->onDelete('restrict');
            $table->string('jenis_po');
            $table->string('file')->nullable();
            $table->integer('jumlah')->default(0);
            $table->decimal('meteran', 10, 2)->default(0);
            $table->date('tanggal_order');
            $table->date('deadline');
            $table->string('jenis_bahan');
            $table->string('current_stage')->default('waiting_list');
            $table->string('stage_status')->default('pending'); // pending, start, progress, selesai
            $table->tinyInteger('active')->default(1); // 1=belum selesai, 2=selesai
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};
