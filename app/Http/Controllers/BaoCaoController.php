<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaoCao;
use Illuminate\Support\Facades\Auth;

class BaoCaoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'TaiLieuID' => 'required',
            'LyDo' => 'required|max:500'
        ]);

        BaoCao::create([
            'TaiLieuID' => $request->TaiLieuID,
            'NguoiDungID' => Auth::id(),
            'LyDo' => $request->LyDo,
            'NgayBaoCao' => now(),
            'TrangThaiXuLy' => 'ChuaXuLy'
        ]);

        // Trả về trang chi tiết kèm session thông báo để view hiển thị
        return back()->with('ThongBaoBaoCao', 'Cảm ơn bạn. Giảng viên sẽ xem xét báo cáo này sớm nhất.');
    }
}