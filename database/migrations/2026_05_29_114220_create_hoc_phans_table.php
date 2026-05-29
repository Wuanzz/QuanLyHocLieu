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
        Schema::create('hoc_phans', function (Blueprint $table) {
            $table->id('HocPhanID');
            $table->string('TenHocPhan', 255);
            $table->text('MoTa');
            $table->unsignedBigInteger('NganhID');
            $table->foreign('NganhID')->references('NganhID')->on('Nganh')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoc_phans');
    }
};
