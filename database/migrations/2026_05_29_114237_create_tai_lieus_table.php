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
        Schema::create('TaiLieu', function (Blueprint $table) {
            $table->id('TaiLieuID');
            $table->string('TenTaiLieu', 255);
            $table->string('DuongDanFile', 500);
            $table->string('LoaiTaiLieu', 50);
            $table->float('KichThuoc');
            $table->dateTime('NgayUpload');
            $table->string('TrangThaiDuyet', 50);
            $table->integer('LuotTai')->default(0);
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
        Schema::dropIfExists('TaiLieu');
    }
};
