<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('po_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->string('image_path');
            $table->string('original_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('po_images');
    }
};
