<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('po_images', function (Blueprint $table) {
            // Tambah info gambar diupload dari stage mana
            $table->string('uploaded_from_stage')->default('purchase_order')->after('po_id');
            $table->foreignId('uploaded_by')->nullable()->after('uploaded_from_stage')->constrained('users')->onDelete('set null');
            $table->text('description')->nullable()->after('original_name');
        });
    }

    public function down()
    {
        Schema::table('po_images', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropColumn(['uploaded_from_stage', 'uploaded_by', 'description']);
        });
    }
};