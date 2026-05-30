<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\HocPhan;
use App\Models\BinhLuan;
use App\Models\DanhGiaReview;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index()
    {
        // Eager load quan hệ 'danhGias' để tính điểm trung bình
        $reviews = Review::with(['HocPhan', 'NguoiDung', 'danhGias'])
            ->orderBy('NgayDang', 'desc')
            ->get();

        return view('review.index', compact('reviews'));
    }

    public function create()
    {
        $danhSachKhoa = Khoa::orderBy('TenKhoa', 'asc')->get();
        return view('review.create', compact('danhSachKhoa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'HocPhanID' => 'required',
            'NoiDung' => 'required|min:10',
        ], [
            'HocPhanID.required' => 'Vui lòng chọn môn học cần đánh giá.',
            'NoiDung.required' => 'Bạn chưa nhập nội dung đánh giá.',
            'NoiDung.min' => 'Nội dung đánh giá quá ngắn (tối thiểu 10 ký tự).'
        ]);

        // Tạo bài review 
        Review::create([
            'HocPhanID' => $request->HocPhanID,
            'NguoiDungID' => Auth::id(),
            'NoiDung' => $request->NoiDung,
            'NgayDang' => now(),
            'TrangThaiDuyet' => 'HopLe'
        ]);

        return redirect()->route('review.index')->with('success', 'Đã đăng bài review thành công! Hãy chờ cộng đồng đánh giá điểm số.');
    }

    public function show($id)
    {
        $review = Review::with(['HocPhan', 'NguoiDung', 'danhGias'])->findOrFail($id);
        
        $danhSachBinhLuan = BinhLuan::with('NguoiDung')
            ->where('ReviewID', $id)
            ->where('TrangThaiDuyet', 'HopLe')
            ->orderBy('NgayDang', 'asc')
            ->get();

        // Kiểm tra xem người dùng hiện tại đã từng vote bài này chưa
        $userVote = 0;
        if (Auth::check()) {
            $daVote = DanhGiaReview::where('ReviewID', $id)->where('NguoiDungID', Auth::id())->first();
            if ($daVote) {
                $userVote = $daVote->SoSao;
            }
        }

        return view('review.show', compact('review', 'danhSachBinhLuan', 'userVote'));
    }

    // HÀM MỚI: Xử lý AJAX chấm điểm sao từ cộng đồng
    public function rate(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để đánh giá.'], 401);
        }

        $request->validate([
            'ReviewID' => 'required',
            'SoSao' => 'required|integer|min:1|max:5'
        ]);

        $review = Review::findOrFail($request->ReviewID);

        // Chặn không cho tác giả tự vote bài của mình
        if ($review->NguoiDungID == Auth::id()) {
            return response()->json(['error' => 'Bạn không thể tự chấm điểm bài viết của chính mình!'], 400);
        }

        // Lưu hoặc cập nhật lại nếu người này đổi ý muốn vote số sao khác
        DanhGiaReview::updateOrCreate(
            ['ReviewID' => $request->ReviewID, 'NguoiDungID' => Auth::id()],
            ['SoSao' => $request->SoSao, 'NgayDanhGia' => now()]
        );

        // Lấy ra điểm số trung bình mới cập nhật và tổng số lượt vote
        $diemMoi = $review->sao_trung_binh;
        $luotVote = $review->danhGias()->count();

        return response()->json([
            'success' => 'Cảm ơn bạn đã đánh giá bài viết này!',
            'saoTrungBinh' => $diemMoi,
            'luotVote' => $luotVote
        ]);
    }

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

    public function addComment(Request $request)
    {
        $request->validate([
            'ReviewID' => 'required',
            'NoiDung' => 'required|max:500'
        ]);

        BinhLuan::create([
            'ReviewID' => $request->ReviewID,
            'ParentID' => $request->ParentID,
            'NguoiDungID' => Auth::id(),
            'NoiDung' => $request->NoiDung,
            'NgayDang' => now(),
            'TrangThaiDuyet' => 'HopLe'
        ]);

        return back()->with('ThongBaoBinhLuan', 'Đã thêm bình luận thành công.');
    }
}