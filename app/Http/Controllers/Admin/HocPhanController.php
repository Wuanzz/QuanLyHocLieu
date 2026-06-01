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

    public function edit($id)
    {
        // Tìm Học phần theo ID
        $hocphan = HocPhan::findOrFail($id);
        
        // Dò ngược tìm Khoa hiện tại thông qua Ngành của Học Phần
        $nganhHienTai = Nganh::find($hocphan->NganhID);
        $khoaIdHienTai = $nganhHienTai ? $nganhHienTai->KhoaID : null;

        // Lấy toàn bộ danh sách Khoa để đổ vào ô Dropdown 1
        $danhSachKhoa = Khoa::orderBy('TenKhoa', 'asc')->get();
        
        // Lấy danh sách Ngành thuộc Khoa hiện tại để nạp sẵn vào ô Dropdown 2
        $danhSachNganh = [];
        if ($khoaIdHienTai) {
            $danhSachNganh = Nganh::where('KhoaID', $khoaIdHienTai)->orderBy('TenNganh', 'asc')->get();
        }

        return view('admin.hocphan.edit', compact('hocphan', 'danhSachKhoa', 'danhSachNganh', 'khoaIdHienTai'));
    }

    public function update(Request $request, $id)
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

        $hocphan = HocPhan::findOrFail($id);

        $hocphan->update([
            'NganhID' => $request->NganhID,
            'TenHocPhan' => $request->TenHocPhan,
            'MoTa' => $request->MoTa
        ]);

        return redirect()->route('admin.hoc-phan.index')->with('success', 'Cập nhật thông tin học phần thành công!');
    }

    // Hàm nhận lệnh xóa từ Modal giao diện
    public function destroy($id)
    {
        $hocphan = HocPhan::findOrFail($id);
        $hocphan->delete();

        return redirect()->route('admin.hoc-phan.index')->with('success', 'Xóa học phần thành công!');
    }
}