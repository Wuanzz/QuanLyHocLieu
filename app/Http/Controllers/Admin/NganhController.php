<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nganh;

class NganhController extends Controller
{
    public function index(Request $request)
    {
        $timKiem = $request->input('timKiem');

        // Bắt đầu truy vấn, nạp sẵn quan hệ với bảng Khoa (Eager Loading) để tối ưu tốc độ
        $query = Nganh::with('Khoa');

        // Nếu có từ khóa tìm kiếm, lọc theo Tên Ngành
        if (!empty($timKiem)) {
            $query->where('TenNganh', 'like', '%' . $timKiem . '%');
        }

        // Phân trang 10 record/trang và giữ lại tham số tìm kiếm trên URL
        $nganhs = $query->orderBy('TenNganh', 'asc')->paginate(10);
        $nganhs->appends(['timKiem' => $timKiem]);

        return view('admin.nganh.index', compact('nganhs', 'timKiem'));
    }

    public function create()
    {
        // Lấy danh sách Khoa để đưa vào Dropdown (sắp xếp theo tên cho dễ tìm)
        $danhSachKhoa = \App\Models\Khoa::orderBy('TenKhoa', 'asc')->get();
        
        return view('admin.nganh.create', compact('danhSachKhoa'));
    }

    public function store(Request $request)
    {
        // Kiểm tra tính hợp lệ của dữ liệu
        $request->validate([
            'KhoaID' => 'required',
            'TenNganh' => 'required|max:255',
            'MoTa' => 'nullable'
        ], [
            'KhoaID.required' => 'Vui lòng chọn Khoa trực thuộc.',
            'TenNganh.required' => 'Vui lòng nhập tên ngành đào tạo.',
            'TenNganh.max' => 'Tên ngành không được vượt quá 255 ký tự.'
        ]);

        // Thêm mới vào CSDL
        \App\Models\Nganh::create([
            'KhoaID' => $request->KhoaID,
            'TenNganh' => $request->TenNganh,
            'MoTa' => $request->MoTa
        ]);

        // Quay lại trang danh sách kèm thông báo
        return redirect()->route('admin.nganh.index')->with('success', 'Thêm ngành đào tạo mới thành công!');
    }

    public function edit($id)
    {
        // Tìm Ngành theo ID, nếu không thấy tự động trả về lỗi 404
        $nganh = \App\Models\Nganh::findOrFail($id);
        
        // Lấy danh sách Khoa để người dùng có thể chọn lại Khoa nếu muốn đổi
        $danhSachKhoa = \App\Models\Khoa::orderBy('TenKhoa', 'asc')->get();
        
        return view('admin.nganh.edit', compact('nganh', 'danhSachKhoa'));
    }

    public function update(Request $request, $id)
    {
        // Kiểm tra dữ liệu đầu vào
        $request->validate([
            'KhoaID' => 'required',
            'TenNganh' => 'required|max:255',
            'MoTa' => 'nullable'
        ], [
            'KhoaID.required' => 'Vui lòng chọn Khoa trực thuộc.',
            'TenNganh.required' => 'Vui lòng nhập tên ngành đào tạo.',
            'TenNganh.max' => 'Tên ngành không được vượt quá 255 ký tự.'
        ]);

        $nganh = \App\Models\Nganh::findOrFail($id);

        // Tiến hành cập nhật vào CSDL
        $nganh->update([
            'KhoaID' => $request->KhoaID,
            'TenNganh' => $request->TenNganh,
            'MoTa' => $request->MoTa
        ]);

        // Chuyển hướng về danh sách kèm thông báo thành công
        return redirect()->route('admin.nganh.index')->with('success', 'Cập nhật thông tin ngành thành công!');
    }

    public function destroy($id)
    {
        $nganh = Nganh::findOrFail($id);
        $nganh->delete();

        return redirect()->route('admin.nganh.index')->with('success', 'Xóa ngành thành công!');
    }
}