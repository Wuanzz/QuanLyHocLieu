<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NguoiDung;
use App\Models\TaiLieu;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy các con số thống kê tổng quan
        $tongSoNguoiDung = NguoiDung::count();
        $tongSoTaiLieu = TaiLieu::count();
        $tongSoReview = Review::count();
        $taiLieuChoDuyet = TaiLieu::where('TrangThaiDuyet', 'ChoDuyet')->count();

        // Dữ liệu biểu đồ cột: Top 5 Ngành có nhiều tài liệu nhất
        $topNganh = DB::table('TaiLieu')
            ->join('HocPhan', 'TaiLieu.HocPhanID', '=', 'HocPhan.HocPhanID')
            ->join('Nganh', 'HocPhan.NganhID', '=', 'Nganh.NganhID')
            ->select('Nganh.TenNganh', DB::raw('count(TaiLieu.TaiLieuID) as total'))
            ->groupBy('Nganh.NganhID', 'Nganh.TenNganh')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $tenNganhChart = $topNganh->pluck('TenNganh')->toArray();
        $soLuongChart = $topNganh->pluck('total')->toArray();

        // Dữ liệu biểu đồ tròn: Cơ cấu vai trò người dùng
        $roles = DB::table('NguoiDung')
            ->select('VaiTro', DB::raw('count(NguoiDungID) as total'))
            ->groupBy('VaiTro')
            ->get();

        $rolesChart = $roles->pluck('VaiTro')->toArray();
        $rolesData = $roles->pluck('total')->toArray();

        // Trả về View kèm toàn bộ dữ liệu
        return view('admin.dashboard', compact(
            'tongSoNguoiDung', 
            'tongSoTaiLieu', 
            'tongSoReview', 
            'taiLieuChoDuyet',
            'tenNganhChart', 
            'soLuongChart', 
            'rolesChart', 
            'rolesData'
        ));
    }
}