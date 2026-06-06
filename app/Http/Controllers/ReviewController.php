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
use App\Services\GeminiService;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Nhận tham số tìm kiếm và bộ lọc từ URL
        $timKiem = $request->input('timKiem');
        $locHocPhan = $request->input('locHocPhan');
        $locSao = $request->input('locSao');

        // Khởi tạo câu truy vấn gốc
        $query = Review::with(['HocPhan', 'NguoiDung', 'danhGias'])
            ->where('TrangThaiDuyet', 'HopLe');

        // Logic tìm kiếm chuỗi (Không phân biệt dấu và hoa thường)
        if (!empty($timKiem)) {
            $query->where(function($q) use ($timKiem) {
                // Quét trong nội dung Review
                $q->whereRaw("NoiDung COLLATE utf8mb4_general_ci LIKE ?", ['%' . $timKiem . '%'])
                  // Hoặc quét trong tên Môn Học
                  ->orWhereHas('HocPhan', function($q2) use ($timKiem) {
                      $q2->whereRaw("TenHocPhan COLLATE utf8mb4_general_ci LIKE ?", ['%' . $timKiem . '%']);
                  });
            });
        }

        // Logic lọc theo môn học (Dropdown)
        if (!empty($locHocPhan)) {
            $query->where('HocPhanID', $locHocPhan);
        }

        // Logic lọc theo số sao trung bình (Sub-query an toàn cho Paginate)
        if (!empty($locSao)) {
            $reviewTable = (new Review)->getTable();
            $danhGiaTable = (new DanhGiaReview)->getTable();
            
            $query->whereRaw("(SELECT COALESCE(AVG(SoSao), 0) FROM {$danhGiaTable} WHERE {$danhGiaTable}.ReviewID = {$reviewTable}.ReviewID) >= ?", [$locSao]);
        }

        // Phân trang và giữ nguyên URL parameters khi sang trang khác
        $reviews = $query->orderBy('NgayDang', 'desc')->paginate(9);
        $reviews->appends(['timKiem' => $timKiem, 'locHocPhan' => $locHocPhan, 'locSao' => $locSao]);

        // Lấy danh sách Học Phần để đổ vào Dropdown bộ lọc
        $danhSachHocPhan = HocPhan::orderBy('TenHocPhan', 'asc')->get();

        return view('review.index', compact('reviews', 'danhSachHocPhan', 'timKiem', 'locHocPhan', 'locSao'));
    }

    public function create()
    {
        $danhSachKhoa = Khoa::orderBy('TenKhoa', 'asc')->get();
        return view('review.create', compact('danhSachKhoa'));
    }

    // Áp dụng rẽ nhánh 3 trạng thái cho khâu đăng tải bài Review
    public function store(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'HocPhanID' => 'required',
            'NoiDung' => 'required|min:10',
        ], [
            'HocPhanID.required' => 'Vui lòng chọn môn học cần đánh giá.',
            'NoiDung.required' => 'Bạn chưa nhập nội dung đánh giá.',
            'NoiDung.min' => 'Nội dung đánh giá quá ngắn (tối thiểu 10 ký tự).'
        ]);

        // Gọi AI chạy phân tích nội dung đánh giá môn học
        $ketQuaAI = $geminiService->kiemDuyetVanBan($request->NoiDung);
        
        // Tách bạch rõ ràng 3 trạng thái kiểm duyệt đầu ra từ AI
        if ($ketQuaAI === 'HopLe') {
            $trangThaiDuyet = 'HopLe';
        } elseif ($ketQuaAI === 'TuChoi') {
            $trangThaiDuyet = 'BiChan';
        } else {
            $trangThaiDuyet = 'ChoDuyet';
        }

        Review::create([
            'HocPhanID' => $request->HocPhanID,
            'NguoiDungID' => Auth::id(),
            'NoiDung' => $request->NoiDung,
            'SoSao' => 0,
            'NgayDang' => now(),
            'TrangThaiDuyet' => $trangThaiDuyet
        ]);

        if ($trangThaiDuyet === 'BiChan') {
            return redirect()->route('review.index')->with('error', 'Bài đánh giá của bạn vi phạm tiêu chuẩn nội dung và đã bị hệ thống tự động chặn đăng tải.');
        } elseif ($trangThaiDuyet === 'ChoDuyet') {
            return redirect()->route('review.index')->with('info', 'Bài đánh giá của bạn chứa yếu tố nhạy cảm và đang chờ Giảng viên kiểm duyệt.');
        }

        // Phát sóng sự kiện thông báo thời gian thực đến toàn hệ thống
        $hocPhan = HocPhan::find($request->HocPhanID);
        $tenMon = $hocPhan ? $hocPhan->TenHocPhan : 'chưa xác định';
        $message = "Một bài review cho môn '{$tenMon}' vừa được đăng tải!";
        event(new \App\Events\ThongBaoHeThong($message));

        return redirect()->route('review.index')->with('success', 'Đã đăng bài review thành công! Hãy chờ cộng đồng đánh giá điểm số.');
    }

    public function show($id)
    {
        $review = Review::with(['HocPhan', 'NguoiDung', 'danhGias'])->findOrFail($id);
        
        $danhSachBinhLuan = BinhLuan::with('NguoiDung')
            ->where('ReviewID', $id)
            ->where('TrangThaiDuyet', 'HopLe') // Chỉ hiển thị bình luận đã kiểm duyệt hợp lệ
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

    // Áp dụng rẽ nhánh 3 trạng thái cho thảo luận phản hồi bài Review
    public function addComment(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'ReviewID' => 'required',
            'NoiDung' => 'required|max:500'
        ]);

        // Gọi AI quét nội dung thảo luận phản hồi bài review
        $ketQuaAI = $geminiService->kiemDuyetVanBan($request->NoiDung);
        
        // Tách bạch rõ ràng 3 trạng thái kiểm duyệt đầu ra từ AI
        if ($ketQuaAI === 'HopLe') {
            $trangThaiDuyet = 'HopLe';
        } elseif ($ketQuaAI === 'TuChoi') {
            $trangThaiDuyet = 'BiChan';
        } else {
            $trangThaiDuyet = 'ChoDuyet';
        }

        BinhLuan::create([
            'ReviewID' => $request->ReviewID,
            'ParentID' => $request->ParentID,
            'NguoiDungID' => Auth::id(),
            'NoiDung' => $request->NoiDung,
            'NgayDang' => now(),
            'TrangThaiDuyet' => $trangThaiDuyet
        ]);

        if ($trangThaiDuyet === 'BiChan') {
            return back()->with('ThongBaoBinhLuan', 'Bình luận chứa nội dung vi phạm tiêu chuẩn và đã bị hệ thống tự động chặn.');
        } elseif ($trangThaiDuyet === 'ChoDuyet') {
            return back()->with('ThongBaoBinhLuan', 'Bình luận chứa từ ngữ chưa phù hợp và đã được chuyển đến bộ phận kiểm duyệt.');
        }

        return back()->with('ThongBaoBinhLuan', 'Đã thêm bình luận thành công.');
    }
}