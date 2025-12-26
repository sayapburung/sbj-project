<?php

// database/migrations/2024_01_01_000011_create_qc_rejections_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qc_rejections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('rejected_from_stage'); // Stage mana yang direject (desain, printing, press)
            $table->text('rejection_reason'); // Alasan reject
            $table->text('rejection_notes')->nullable(); // Catatan detail
            $table->string('severity')->default('medium'); // low, medium, high, critical
            $table->foreignId('rejected_by')->constrained('users')->onDelete('cascade'); // QC user
            $table->timestamp('rejected_at'); // Kapan direject
            $table->boolean('is_resolved')->default(false); // Sudah diperbaiki atau belum
            $table->timestamp('resolved_at')->nullable(); // Kapan selesai diperbaiki
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['po_id', 'rejected_at']);
            $table->index(['is_resolved']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('qc_rejections');
    }
};