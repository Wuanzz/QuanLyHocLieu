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
        Schema::create('nguoi_dungs', function (Blueprint $table) {
            $table->id('NguoiDungID');
            $table->string('HoTen', 255);
            $table->string('Email', 255)->unique();
            $table->string('MatKhau', 255);
            $table->string('AnhDaiDien', 500)->nullable();
            $table->dateTime('NgayDangKy');
            $table->string('TrangThai', 50);
            $table->string('VaiTro', 50);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoi_dungs');
    }
};
