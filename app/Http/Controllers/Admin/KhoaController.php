<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Khoa;

class KhoaController extends Controller
{
    public function index(Request $request)
    {
        $timKiem = $request->input('timKiem');

        // Bắt đầu truy vấn
        $query = Khoa::query();

        // Nếu có từ khóa tìm kiếm, lọc theo Tên Khoa
        if (!empty($timKiem)) {
            $query->where('TenKhoa', 'like', '%' . $timKiem . '%');
        }

        // Phân trang 10 record/trang và giữ lại tham số tìm kiếm trên URL
        $khoas = $query->orderBy('TenKhoa', 'asc')->paginate(10);
        $khoas->appends(['timKiem' => $timKiem]);

        return view('admin.khoa.index', compact('khoas', 'timKiem'));
    }

    public function create()
    {
        return view('admin.khoa.create');
    }

    public function store(Request $request)
    {
        // Kiểm tra tính hợp lệ của dữ liệu
        $request->validate([
            'TenKhoa' => 'required|max:255',
            'MoTa' => 'nullable'
        ], [
            'TenKhoa.required' => 'Vui lòng nhập tên khoa.',
            'TenKhoa.max' => 'Tên khoa không được vượt quá 255 ký tự.'
        ]);

        // Thêm mới vào CSDL
        Khoa::create([
            'TenKhoa' => $request->TenKhoa,
            'MoTa' => $request->MoTa
        ]);

        // Quay lại trang danh sách kèm thông báo thành công
        return redirect()->route('admin.khoa.index')->with('success', 'Thêm khoa mới thành công!');
    }

    public function edit($id)
    {
        // Tìm Khoa theo KhoaID, nếu không thấy sẽ tự động quăng lỗi 404
        $khoa = Khoa::findOrFail($id);
        
        return view('admin.khoa.edit', compact('khoa'));
    }

    public function update(Request $request, $id)
    {
        // Kiểm tra tính hợp lệ của dữ liệu đầu vào
        $request->validate([
            'TenKhoa' => 'required|max:255',
            'MoTa' => 'nullable'
        ], [
            'TenKhoa.required' => 'Vui lòng nhập tên khoa.',
            'TenKhoa.max' => 'Tên khoa không được vượt quá 255 ký tự.'
        ]);

        $khoa = Khoa::findOrFail($id);

        // Cập nhật dữ liệu vào cơ sở dữ liệu
        $khoa->update([
            'TenKhoa' => $request->TenKhoa,
            'MoTa' => $request->MoTa
        ]);

        // Điều hướng về trang danh sách kèm thông báo thành công
        return redirect()->route('admin.khoa.index')->with('success', 'Cập nhật thông tin khoa thành công!');
    }

    public function destroy($id)
    {
        $khoa = Khoa::findOrFail($id);
        $khoa->delete();

        return redirect()->route('admin.khoa.index')->with('success', 'Xóa khoa thành công!');
    }
}