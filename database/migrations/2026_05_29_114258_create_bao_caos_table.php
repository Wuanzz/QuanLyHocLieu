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
        Schema::create('bao_caos', function (Blueprint $table) {
            $table->id('BaoCaoID');
            $table->string('LyDo', 500);
            $table->dateTime('NgayBaoCao');
            $table->string('TrangThaiXuLy', 50);
            $table->unsignedBigInteger('NguoiDungID');
            $table->unsignedBigInteger('ReviewID')->nullable();
            $table->unsignedBigInteger('TaiLieuID')->nullable();
            $table->unsignedBigInteger('BinhLuanID')->nullable();
            $table->foreign('NguoiDungID')->references('NguoiDungID')->on('NguoiDung')->onDelete('cascade');
            $table->foreign('ReviewID')->references('ReviewID')->on('Review')->onDelete('cascade');
            $table->foreign('TaiLieuID')->references('TaiLieuID')->on('TaiLieu')->onDelete('cascade');
            $table->foreign('BinhLuanID')->references('BinhLuanID')->on('BinhLuan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bao_caos');
    }
};
