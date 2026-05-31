<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaiLieuController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HoSoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KhoaController;
use App\Http\Controllers\Admin\NganhController;
use App\Http\Controllers\Admin\HocPhanController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\GiangVien\KiemDuyetController;
use App\Http\Controllers\BaoCaoController;

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
Route::get('/kho-tai-lieu/them-moi', [TaiLieuController::class, 'create'])->name('tailieu.create');
Route::post('/kho-tai-lieu/them-moi', [TaiLieuController::class, 'store'])->name('tailieu.store');
Route::get('/api/get-nganh', [TaiLieuController::class, 'getNganh'])->name('api.getNganh');
Route::get('/api/get-hoc-phan', [TaiLieuController::class, 'getHocPhan'])->name('api.getHocPhan');
Route::get('/kho-tai-lieu/chi-tiet/{id}', [TaiLieuController::class, 'show'])->name('tailieu.show');
Route::get('/kho-tai-lieu/tai-ve/{id}', [TaiLieuController::class, 'download'])->name('tailieu.download');
Route::post('/kho-tai-lieu/binh-luan', [TaiLieuController::class, 'addComment'])->name('tailieu.addComment');

Route::get('/cong-dong-review', [ReviewController::class, 'index'])->name('review.index');
Route::get('/cong-dong-review/them-moi', [ReviewController::class, 'create'])->name('review.create');
Route::post('/cong-dong-review/them-moi', [ReviewController::class, 'store'])->name('review.store');
Route::get('/cong-dong-review/chi-tiet/{id}', [ReviewController::class, 'show'])->name('review.show');
Route::get('/api/review/get-nganh', [ReviewController::class, 'getNganh'])->name('api.review.getNganh');
Route::get('/api/review/get-hoc-phan', [ReviewController::class, 'getHocPhan'])->name('api.review.getHocPhan');
Route::post('/cong-dong-review/binh-luan', [ReviewController::class, 'addComment'])->name('review.addComment');
Route::post('/cong-dong-review/cham-diem', [ReviewController::class, 'rate'])->name('review.rate');


Route::middleware(['auth'])->group(function () {
    Route::get('/ho-so', [HoSoController::class, 'index'])->name('hoso.index');
    Route::post('/ho-so/cap-nhat-anh', [HoSoController::class, 'capNhatAnhDaiDien'])->name('hoso.capNhatAnhDaiDien');
    Route::get('/ho-so/doi-mat-khau', [\App\Http\Controllers\HoSoController::class, 'doiMatKhau'])->name('hoso.doiMatKhau');
    Route::post('/ho-so/doi-mat-khau', [\App\Http\Controllers\HoSoController::class, 'xuLyDoiMatKhau'])->name('hoso.xuLyDoiMatKhau');
});

Route::post('/bao-cao/gui-bao-cao', [BaoCaoController::class, 'store'])->name('baocao.store');
// ==========================================
// PHÂN HỆ ADMIN (Chỉ Quản trị viên)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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