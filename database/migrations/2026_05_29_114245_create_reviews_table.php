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
        Schema::create('Review', function (Blueprint $table) {
            $table->id('ReviewID');
            $table->text('NoiDung');
            $table->integer('SoSao');
            $table->dateTime('NgayDang');
            $table->string('TrangThaiDuyet', 50);
            $table->unsignedBigInteger('NguoiDungID');
            $table->unsignedBigInteger('HocPhanID');
            $table->foreign('NguoiDungID')->references('NguoiDungID')->on('NguoiDung')->onDelete('cascade');
            $table->foreign('HocPhanID')->references('HocPhanID')->on('HocPhan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Review');
    }
};
