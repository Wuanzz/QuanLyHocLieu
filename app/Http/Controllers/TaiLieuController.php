<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaiLieu;
use App\Models\HocPhan;

class TaiLieuController extends Controller
{
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm và bộ lọc từ URL
        $timKiem = $request->input('timKiem');
        $locHocPhan = $request->input('locHocPhan');

        // Bắt đầu câu truy vấn, kết nối sẵn với bảng Học phần và Người dùng
        $query = TaiLieu::with(['HocPhan', 'NguoiDung']);

        // Xử lý logic Tìm kiếm theo tên
        if (!empty($timKiem)) {
            $query->where('TenTaiLieu', 'like', '%' . $timKiem . '%');
        }

        // Xử lý logic Lọc theo Học phần
        if (!empty($locHocPhan)) {
            $query->where('HocPhanID', $locHocPhan);
        }

        // Thực thi truy vấn, sắp xếp mới nhất và phân trang (ví dụ 10 item/trang)
        $taiLieus = $query->orderBy('NgayUpload', 'desc')->paginate(10);

        // Giữ lại các tham số lọc trên URL khi bấm sang trang khác
        $taiLieus->appends(['timKiem' => $timKiem, 'locHocPhan' => $locHocPhan]);

        // Lấy danh sách Học phần để đổ vào thẻ Select
        $danhSachHocPhan = HocPhan::orderBy('TenHocPhan', 'asc')->get();

        return view('tailieu.index', compact('taiLieus', 'danhSachHocPhan', 'timKiem', 'locHocPhan'));
    }
}