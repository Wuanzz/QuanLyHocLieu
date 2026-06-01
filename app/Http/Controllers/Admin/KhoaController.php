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

    // Các hàm trống tạm thời để không bị lỗi khi click nút Thêm/Sửa/Xóa
    public function create() { return "View Create đang được xây dựng"; }
    public function store(Request $request) {}
    public function edit($id) { return "View Edit đang được xây dựng"; }
    public function update(Request $request, $id) {}
    public function destroy($id) { return "View Delete đang được xây dựng"; }
}