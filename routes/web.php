<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaiLieuController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HoSoController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\KhoaController;
use App\Http\Controllers\Admin\NganhController;
use App\Http\Controllers\Admin\HocPhanController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\GiangVien\KiemDuyetController;

// ==========================================
// XÁC THỰC (Đăng nhập, Đăng xuất)
// ==========================================
Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/dang-nhap', [AuthController::class, 'login']);
Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// PHÂN HỆ USER (Mọi đối tượng đều vào được)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/kho-tai-lieu', [TaiLieuController::class, 'index'])->name('tailieu.index');
Route::get('/cong-dong-review', [ReviewController::class, 'index'])->name('review.index');
Route::get('/ho-so-ca-nhan', [HoSoController::class, 'index'])->name('hoso.index');

// ==========================================
// PHÂN HỆ ADMIN (Chỉ Quản trị viên)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('khoa', KhoaController::class);
    Route::resource('nganh', NganhController::class);
    Route::resource('hoc-phan', HocPhanController::class);
    Route::resource('nguoi-dung', NguoiDungController::class);
});

// ==========================================
// PHÂN HỆ GIẢNG VIÊN (Khu vực kiểm duyệt)
// ==========================================
Route::prefix('giang-vien')->name('giangvien.')->middleware(['auth', 'giangvien'])->group(function () {
    Route::get('/kiem-duyet', [KiemDuyetController::class, 'index'])->name('kiemduyet.index');
});