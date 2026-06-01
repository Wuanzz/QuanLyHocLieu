<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HocPhan;

class HocPhanController extends Controller
{
    public function index(Request $request)
    {
        $timKiem = $request->input('timKiem');

        // Khởi tạo truy vấn, nạp sẵn thông tin Ngành để tối ưu hiệu suất
        $query = HocPhan::with('Nganh');

        // Tìm kiếm theo tên học phần nếu có
        if (!empty($timKiem)) {
            $query->where('TenHocPhan', 'like', '%' . $timKiem . '%');
        }

        // Phân trang và giữ lại tham số tìm kiếm trên thanh URL
        $hocphans = $query->orderBy('TenHocPhan', 'asc')->paginate(10);
        $hocphans->appends(['timKiem' => $timKiem]);

        return view('admin.hocphan.index', compact('hocphans', 'timKiem'));
    }

    // Các hàm này tạm để trống để tránh lỗi khi click
    public function create() { return "View Create đang được xây dựng"; }
    public function store(Request $request) {}
    public function edit($id) { return "View Edit đang được xây dựng"; }
    public function update(Request $request, $id) {}

    // Hàm nhận lệnh xóa từ Modal giao diện
    public function destroy($id)
    {
        $hocphan = HocPhan::findOrFail($id);
        $hocphan->delete();

        return redirect()->route('admin.hoc-phan.index')->with('success', 'Xóa học phần thành công!');
    }
}