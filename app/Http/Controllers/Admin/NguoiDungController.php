<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoiDung;

class NguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $timKiem = $request->input('timKiem');
        $vaiTro = $request->input('vaiTro'); // Khai báo thêm biến nhận vai trò từ form

        $query = NguoiDung::query();

        // Lọc theo Tên hoặc Email (Dùng Closure function để nhóm 2 điều kiện OR lại với nhau)
        if (!empty($timKiem)) {
            $query->where(function($q) use ($timKiem) {
                $q->where('HoTen', 'like', '%' . $timKiem . '%')
                  ->orWhere('Email', 'like', '%' . $timKiem . '%');
            });
        }

        // Lọc theo Vai Trò
        if (!empty($vaiTro)) {
            $query->where('VaiTro', $vaiTro);
        }

        $tongSo = NguoiDung::count();

        // Phân trang và giữ lại cả 2 tham số trên URL khi chuyển trang
        $nguoidungs = $query->orderBy('NgayDangKy', 'desc')->paginate(10);
        $nguoidungs->appends(['timKiem' => $timKiem, 'vaiTro' => $vaiTro]);

        // Đẩy thêm biến $vaiTro ra view để giữ lại lựa chọn
        return view('admin.nguoidung.index', compact('nguoidungs', 'timKiem', 'vaiTro', 'tongSo'));
    }

    // Các hàm trống tạm thời
    public function create() { return "View Create đang được xây dựng"; }
    public function store(Request $request) {}

    public function edit($id)
    {
        return "Không dùng view Edit nữa vì đã dùng Modal";
    }

    public function update(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào hợp lệ
        $request->validate([
            'VaiTro' => 'required|in:SinhVien,GiangVien,Admin',
            'TrangThai' => 'required|in:HoatDong,Khoa',
        ], [
            'VaiTro.required' => 'Vui lòng chọn vai trò cho tài khoản.',
            'VaiTro.in' => 'Vai trò được chọn không hợp lệ.',
            'TrangThai.required' => 'Vui lòng chọn trạng thái cho tài khoản.',
            'TrangThai.in' => 'Trạng thái được chọn không hợp lệ.',
        ]);

        $nguoidung = NguoiDung::findOrFail($id);

        // Thực hiện cập nhật vào CSDL
        $nguoidung->update([
            'VaiTro' => $request->VaiTro,
            'TrangThai' => $request->TrangThai,
        ]);

        // Điều hướng về trang danh sách kèm thông báo thành công
        return redirect()->route('admin.nguoi-dung.index')->with('success', 'Cập nhật tài khoản thành công!');
    }

    public function destroy($id) {}
}