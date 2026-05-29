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
        Schema::create('Nganh', function (Blueprint $table) {
            $table->id('NganhID');
            $table->string('TenNganh', 255);
            $table->text('MoTa');
            $table->unsignedBigInteger('KhoaID');
            $table->foreign('KhoaID')->references('KhoaID')->on('Khoa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Nganh');
    }
};
