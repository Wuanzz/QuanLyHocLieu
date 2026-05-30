<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaiLieu;
use App\Models\Review;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 4 tài liệu mới nhất (kèm thông tin Người đăng và Học phần)
        $taiLieuMoi = TaiLieu::with(['NguoiDung', 'HocPhan'])
            ->orderBy('NgayUpload', 'desc')
            ->take(4)
            ->get();

        // Lấy 4 đánh giá mới nhất (kèm thông tin Người đăng và Học phần)
        $reviewMoi = Review::with(['NguoiDung', 'HocPhan'])
            ->orderBy('NgayDang', 'desc')
            ->take(5)
            ->get();

        // Ném dữ liệu sang view 'home'
        return view('home', compact('taiLieuMoi', 'reviewMoi'));
    }
}