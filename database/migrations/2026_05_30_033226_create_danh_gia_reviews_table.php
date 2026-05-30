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
        Schema::create('DanhGiaReview', function (Blueprint $table) {
            $table->id('DanhGiaID');
            $table->unsignedBigInteger('ReviewID');
            $table->unsignedBigInteger('NguoiDungID');
            $table->integer('SoSao');
            $table->timestamp('NgayDanhGia')->useCurrent();

            // Khai báo các khóa ngoại kết nối sang bảng Review và NguoiDung của cậu
            $table->foreign('ReviewID')->references('ReviewID')->on('Review')->onDelete('cascade');
            $table->foreign('NguoiDungID')->references('NguoiDungID')->on('NguoiDung')->onDelete('cascade');
            
            // Ràng buộc UNIQUE để một người dùng chỉ được vote đúng 1 lần trên 1 bài review
            $table->unique(['ReviewID', 'NguoiDungID']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DanhGiaReview');
    }
};
