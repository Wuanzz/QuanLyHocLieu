<?php

namespace App\Http\Controllers\GiangVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiLieu;
use App\Models\BaoCao;
use App\Models\BinhLuan;
use App\Models\Review;
use App\Events\ThongBaoHeThong;

class KiemDuyetController extends Controller
{
    // Hiển thị không gian kiểm duyệt tổng hợp
    public function index()
    {
        // Lấy tài liệu đang ở trạng thái chờ duyệt
        $taiLieuChoDuyet = TaiLieu::with(['HocPhan', 'NguoiDung'])
            ->where('TrangThaiDuyet', 'ChoDuyet')
            ->orderBy('NgayUpload', 'desc')
            ->get();

        // Lấy danh sách báo cáo vi phạm tài liệu (Giả định trạng thái Chưa xử lý hoặc lấy tất cả báo cáo hoạt động)
        $danhSachBaoCao = BaoCao::with(['TaiLieu', 'NguoiDung'])
            ->orderBy('NgayBaoCao', 'desc')
            ->get();

        // Lấy bình luận bị AI gắn cờ cảnh báo (TrangThaiDuyet = ChoDuyet)
        $binhLuanChoDuyet = BinhLuan::with(['TaiLieu', 'NguoiDung'])
            ->where('TrangThaiDuyet', 'ChoDuyet')
            ->orderBy('NgayDang', 'desc')
            ->get();

        // Lấy bài đánh giá bị AI gắn cờ cảnh báo (TrangThaiDuyet = ChoDuyet)
        $reviewChoDuyet = Review::with(['HocPhan', 'NguoiDung'])
            ->where('TrangThaiDuyet', 'ChoDuyet')
            ->orderBy('NgayDang', 'desc')
            ->get();

        return view('giangvien.kiemduyet.index', compact(
            'taiLieuChoDuyet', 
            'danhSachBaoCao', 
            'binhLuanChoDuyet', 
            'reviewChoDuyet'
        ));
    }

    // TÀI LIỆU MỚI
    public function duyetTaiLieu(Request $request)
    {
        $taiLieu = TaiLieu::with('HocPhan')->findOrFail($request->id);
        $taiLieu->update(['TrangThaiDuyet' => 'HopLe']); 

        // Phát sóng sự kiện thông báo thời gian thực đến toàn hệ thống
        $tenMon = $taiLieu->HocPhan ? $taiLieu->HocPhan->TenHocPhan : 'chưa xác định';
        $message = "Tài liệu mới '{$taiLieu->TenTaiLieu}' của môn {$tenMon} vừa được thêm vào Kho!";
        event(new ThongBaoHeThong($message));

        return back()->with('success', 'Đã phê duyệt tài liệu thành công!');
    }

    public function tuChoiTaiLieu(Request $request)
    {
        $taiLieu = TaiLieu::findOrFail($request->id);
        $taiLieu->update(['TrangThaiDuyet' => 'BiChan']);
        return back()->with('success', 'Đã từ chối tài liệu!');
    }

    // BÁO CÁO TÀI LIỆU VI PHẠM
    public function xoaTaiLieuViPham(Request $request)
    {
        $baoCao = BaoCao::findOrFail($request->id);
        if ($baoCao->TaiLieu) {
            // Chuyển tài liệu sang trạng thái bị chặn/ẩn khỏi hệ thống
            $baoCao->TaiLieu->update(['TrangThaiDuyet' => 'BiChan']);
        }
        // Sau khi xử lý xong thì xóa bản ghi báo cáo này hoặc đổi trạng thái báo cáo
        $baoCao->delete(); 
        return back()->with('success', 'Đã ẩn tài liệu bị báo cáo vi phạm thành công!');
    }

    public function boQuaBaoCao(Request $request)
    {
        $baoCao = BaoCao::findOrFail($request->id);
        $baoCao->delete(); // Bỏ qua báo cáo (Xóa bản ghi báo cáo, giữ lại tài liệu)
        return back()->with('success', 'Đã từ chối báo cáo vi phạm!');
    }

    // BÌNH LUẬN AI CẢNH BÁO
    public function duyetBinhLuan(Request $request)
    {
        $binhLuan = BinhLuan::findOrFail($request->id);
        $binhLuan->update(['TrangThaiDuyet' => 'HopLe']);
        return back()->with('success', 'Đã cho phép hiển thị bình luận!');
    }

    public function tuChoiBinhLuan(Request $request)
    {
        $binhLuan = BinhLuan::findOrFail($request->id);
        $binhLuan->update(['TrangThaiDuyet' => 'BiChan']);
        return back()->with('success', 'Đã chặn bình luận vi phạm!');
    }

    // BÀI ĐÁNH GIÁ REVIEW AI CẢNH BÁO
    public function duyetReview(Request $request)
    {
        $review = Review::with('HocPhan')->findOrFail($request->id);
        $review->update(['TrangThaiDuyet' => 'HopLe']);

        // Phát sóng sự kiện thông báo thời gian thực đến toàn hệ thống
        $tenMon = $review->HocPhan ? $review->HocPhan->TenHocPhan : 'chưa xác định';
        $message = "Một bài review cho môn '{$tenMon}' vừa được đăng tải!";
        event(new ThongBaoHeThong($message));

        return back()->with('success', 'Đã cho phép hiển thị bài review!');
    }

    public function tuChoiReview(Request $request)
    {
        $review = Review::findOrFail($request->id);
        $review->update(['TrangThaiDuyet' => 'BiChan']);
        return back()->with('success', 'Đã chặn bài review vi phạm!');
    }
}