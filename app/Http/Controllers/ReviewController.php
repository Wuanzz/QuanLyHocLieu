<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // Hàm hiển thị danh sách Review
    public function index()
    {
        // Lấy toàn bộ review đã được duyệt hoặc không cần duyệt (tạm thời lấy hết, sắp xếp mới nhất)
        // Kết nối sẵn bảng HocPhan và NguoiDung để hiển thị tên
        $reviews = Review::with(['HocPhan', 'NguoiDung'])
            ->orderBy('NgayDang', 'desc')
            ->get();

        return view('review.index', compact('reviews'));
    }

    // Tạm thời tạo sẵn hàm để sau này gọi view form tạo mới
    public function create()
    {
        return "Chức năng Thêm mới Review sẽ được làm ở bước tiếp theo.";
    }

    // Tạm thời tạo sẵn hàm để sau này gọi view chi tiết
    public function show($id)
    {
        return "Chức năng Xem chi tiết Review $id sẽ được làm ở bước tiếp theo.";
    }
}