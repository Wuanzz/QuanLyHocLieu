<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaiLieu;
use App\Models\HocPhan;
use App\Models\Khoa;
use App\Models\Nganh;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BinhLuan;
use App\Services\GeminiService;

class TaiLieuController extends Controller
{
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm và bộ lọc từ URL
        $timKiem = $request->input('timKiem');
        $locHocPhan = $request->input('locHocPhan');

        // Bắt đầu câu truy vấn, kết nối sẵn với bảng Học phần và Người dùng
        $query = TaiLieu::with(['HocPhan', 'NguoiDung'])->where('TrangThaiDuyet', 'HopLe');

        // Xử lý logic Tìm kiếm theo tên
        if (!empty($timKiem)) {
            $query->where('TenTaiLieu', 'like', '%' . $timKiem . '%');
        }

        // Xử lý logic Lọc theo Học phần
        if (!empty($locHocPhan)) {
            $query->where('HocPhanID', $locHocPhan);
        }

        // Thực thi truy vấn, sắp xếp mới nhất và phân trang (ví dụ 10 item/trang)
        $taiLieus = $query->orderBy('NgayUpload', 'desc')->paginate(10);

        // Giữ lại các tham số lọc trên URL khi bấm sang trang khác
        $taiLieus->appends(['timKiem' => $timKiem, 'locHocPhan' => $locHocPhan]);

        // Lấy danh sách Học phần để đổ vào thẻ Select
        $danhSachHocPhan = HocPhan::orderBy('TenHocPhan', 'asc')->get();

        return view('tailieu.index', compact('taiLieus', 'danhSachHocPhan', 'timKiem', 'locHocPhan'));
    }

    public function create()
    {
        $danhSachKhoa = Khoa::orderBy('TenKhoa', 'asc')->get();
        return view('tailieu.create', compact('danhSachKhoa'));
    }

    // Hàm xử lý lưu file và data
    public function store(Request $request)
    {
        $request->validate([
            'HocPhanID' => 'required',
            'TenTaiLieu' => 'required|max:255',
            'LoaiTaiLieu' => 'required',
            'fileUpload' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar|max:51200' 
        ], [
            'fileUpload.mimes' => 'Chỉ chấp nhận các định dạng: pdf, doc, docx, ppt, pptx, zip, rar.',
            'fileUpload.max' => 'Dung lượng file không được vượt quá 50MB.'
        ]);

        if ($request->hasFile('fileUpload')) {
            $file = $request->file('fileUpload');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Lưu trực tiếp vào ổ 'public' để đường dẫn trong DB được gọn gàng
            $path = $file->storeAs('tailieu', $filename, 'public');
            
            $sizeMB = round($file->getSize() / 1048576, 2);

            TaiLieu::create([
                'TenTaiLieu' => $request->TenTaiLieu,
                'DuongDanFile' => $path,
                'LoaiTaiLieu' => $request->LoaiTaiLieu,
                'KichThuoc' => $sizeMB,
                'NgayUpload' => now(),
                'TrangThaiDuyet' => 'ChoDuyet',
                'LuotTai' => 0,
                'NguoiDungID' => Auth::id(),
                'HocPhanID' => $request->HocPhanID
            ]);

            return redirect()->route('tailieu.index')->with('success', 'Tải lên thành công! Tài liệu đang chờ kiểm duyệt.');
        }

        return back()->with('error', 'Có lỗi xảy ra khi tải file.');
    }

    // API Lấy danh sách Ngành theo Khoa
    public function getNganh(Request $request)
    {
        $nganhs = Nganh::where('KhoaID', $request->khoaId)->get();
        return response()->json($nganhs);
    }

    // API Lấy danh sách Học Phần theo Ngành
    public function getHocPhan(Request $request)
    {
        $hocphans = HocPhan::where('NganhID', $request->nganhId)->get();
        return response()->json($hocphans);
    }

    // Hàm hiển thị Chi tiết tài liệu
    public function show($id)
    {
        $taiLieu = TaiLieu::with([
            'HocPhan', 
            'NguoiDung', 
            'BinhLuans' => function($query) {
                // Chỉ nạp những bình luận sạch (HopLe) lên giao diện người dùng
                $query->where('TrangThaiDuyet', 'HopLe')->orderBy('NgayDang', 'desc');
            },
            'BinhLuans.NguoiDung'
        ])->findOrFail($id);

        return view('tailieu.show', compact('taiLieu'));
    }

    // Hàm xử lý Tải xuống và Tăng lượt tải
    public function download($id)
    {
        $taiLieu = TaiLieu::findOrFail($id);
        
        $taiLieu->increment('LuotTai');

        $filePath = storage_path('app/public/' . $taiLieu->DuongDanFile);

        if (!file_exists($filePath)) {
            return back()->with('error', 'File không tồn tại trên hệ thống.');
        }

        return response()->download($filePath, $taiLieu->TenTaiLieu . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }

    // Tích hợp dịch vụ Gemini AI tự động chấm điểm văn bản
    public function addComment(Request $request, GeminiService $geminiService)
    {
        $request->validate([
            'TaiLieuID' => 'required',
            'NoiDung' => 'required|max:500'
        ]);

        // Gọi AI chạy thẩm định văn bản bình luận
        $ketQuaAI = $geminiService->kiemDuyetVanBan($request->NoiDung);

        // Chuyển đổi trạng thái lưu trữ dựa vào kết quả phân loại
        $trangThaiDuyet = ($ketQuaAI === 'HopLe') ? 'HopLe' : 'ChoDuyet';

        BinhLuan::create([
            'TaiLieuID' => $request->TaiLieuID,
            'NguoiDungID' => Auth::id(),
            'NoiDung' => $request->NoiDung,
            'NgayDang' => now(),
            'TrangThaiDuyet' => $trangThaiDuyet
        ]);

        if ($trangThaiDuyet === 'ChoDuyet') {
            return back()->with('info', 'Bình luận của bạn chứa yếu tố cần xem xét và đang chờ kiểm duyệt.');
        }

        return back()->with('success', 'Đã thêm bình luận thành công.');
    }
}