<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\HocPhan;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['HocPhan', 'NguoiDung'])
            ->orderBy('NgayDang', 'desc')
            ->get();

        return view('review.index', compact('reviews'));
    }

    // 1. Hiển thị form tạo mới
    public function create()
    {
        $danhSachKhoa = Khoa::orderBy('TenKhoa', 'asc')->get();
        return view('review.create', compact('danhSachKhoa'));
    }

    // 2. Xử lý lưu Review
    public function store(Request $request)
    {
        $request->validate([
            'HocPhanID' => 'required',
            'SoSao' => 'required|integer|min:1|max:5',
            'NoiDung' => 'required|min:10',
        ], [
            'HocPhanID.required' => 'Vui lòng chọn môn học cần đánh giá.',
            'NoiDung.required' => 'Bạn chưa nhập nội dung đánh giá.',
            'NoiDung.min' => 'Nội dung đánh giá quá ngắn (tối thiểu 10 ký tự).'
        ]);

        Review::create([
            'HocPhanID' => $request->HocPhanID,
            'NguoiDungID' => Auth::id(),
            'NoiDung' => $request->NoiDung,
            'SoSao' => $request->SoSao,
            'NgayDang' => now(),
            'TrangThaiDuyet' => 'HopLe' // Hoặc 'ChoDuyet' nếu muốn kiểm duyệt
        ]);

        return redirect()->route('review.index')->with('success', 'Đã đăng bài đánh giá thành công!');
    }

    // 3. Các hàm API phục vụ AJAX dropdown
    public function getNganh(Request $request)
    {
        $nganhs = Nganh::where('KhoaID', $request->khoaId)->get();
        return response()->json($nganhs);
    }

    public function getHocPhan(Request $request)
    {
        $hocphans = HocPhan::where('NganhID', $request->nganhId)->get();
        return response()->json($hocphans);
    }
    
    public function show($id)
    {
        $review = Review::with(['HocPhan', 'NguoiDung'])->findOrFail($id);
        return view('review.show', compact('review'));
    }
}