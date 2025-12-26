<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stage_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('stage_name');
            $table->decimal('kiloan', 10, 2)->nullable();
            $table->decimal('meteran_press', 10, 2)->nullable();
            $table->decimal('meteran_printing', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stage_inputs');
    }
};
