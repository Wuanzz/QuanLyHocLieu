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

    // Các hàm trống tạm thời để chờ làm ở bước tiếp theo
    public function create() { return "View Create đang được xây dựng"; }
    public function store(Request $request) {}
    public function edit($id) { return "View Edit đang được xây dựng"; }
    public function update(Request $request, $id) {}

    // Hàm xử lý Xóa dữ liệu nhận từ Modal
    public function destroy($id)
    {
        $nganh = Nganh::findOrFail($id);
        $nganh->delete();

        return redirect()->route('admin.nganh.index')->with('success', 'Xóa ngành thành công!');
    }
}