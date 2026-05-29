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
        Schema::create('BinhLuan', function (Blueprint $table) {
            $table->id('BinhLuanID');
            $table->text('NoiDung');
            $table->dateTime('NgayDang');
            $table->string('TrangThaiDuyet', 50);
            $table->unsignedBigInteger('NguoiDungID');
            $table->unsignedBigInteger('ParentID')->nullable();
            $table->unsignedBigInteger('ReviewID')->nullable();
            $table->unsignedBigInteger('TaiLieuID')->nullable();
            $table->foreign('NguoiDungID')->references('NguoiDungID')->on('NguoiDung')->onDelete('cascade');
            $table->foreign('ParentID')->references('BinhLuanID')->on('BinhLuan')->onDelete('cascade');
            $table->foreign('ReviewID')->references('ReviewID')->on('Review')->onDelete('cascade');
            $table->foreign('TaiLieuID')->references('TaiLieuID')->on('TaiLieu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('BinhLuan');
    }
};
