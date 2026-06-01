<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HocPhan;
use App\Models\Nganh;
use App\Models\Khoa;

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

    public function create()
    {
        // Lấy danh sách Khoa cho bước chọn số 1
        $danhSachKhoa = Khoa::orderBy('TenKhoa', 'asc')->get();
        return view('admin.hocphan.create', compact('danhSachKhoa'));
    }

    // Xử lý lưu Học Phần vào database
    public function store(Request $request)
    {
        $request->validate([
            'NganhID' => 'required',
            'TenHocPhan' => 'required|max:255',
            'MoTa' => 'nullable'
        ], [
            'NganhID.required' => 'Vui lòng chọn Ngành đào tạo cho học phần này.',
            'TenHocPhan.required' => 'Vui lòng nhập tên học phần.',
            'TenHocPhan.max' => 'Tên học phần không được vượt quá 255 ký tự.'
        ]);

        HocPhan::create([
            'NganhID' => $request->NganhID,
            'TenHocPhan' => $request->TenHocPhan,
            'MoTa' => $request->MoTa
        ]);

        return redirect()->route('admin.hoc-phan.index')->with('success', 'Thêm học phần mới thành công!');
    }

    // API xử lý truy vấn danh sách Ngành thuộc Khoa phục vụ AJAX
    public function getNganhByKhoa(Request $request)
    {
        $khoaId = $request->input('khoaId');
        
        // Truy vấn danh sách Ngành dựa trên KhoaID nhận được
        $danhSachNganh = Nganh::where('KhoaID', $khoaId)->orderBy('TenNganh', 'asc')->get();
        
        return response()->json($danhSachNganh);
    }

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