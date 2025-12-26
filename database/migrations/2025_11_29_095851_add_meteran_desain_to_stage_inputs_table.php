<?php

// database/migrations/2024_01_01_000009_add_meteran_desain_to_stage_inputs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stage_inputs', function (Blueprint $table) {
            // Tambah kolom meteran untuk desain
            $table->decimal('meteran_desain', 10, 2)->nullable()->after('stage_name');
        });
    }

    public function down()
    {
        Schema::table('stage_inputs', function (Blueprint $table) {
            $table->dropColumn('meteran_desain');
        });
    }
};