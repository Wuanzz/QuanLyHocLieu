<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\NguoiDung;
use App\Models\TaiLieu;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;

class HoSoController extends Controller
{
    public function index()
    {
        // Lấy thông tin user hiện tại
        $nguoiDung = Auth::user();

        // Lấy danh sách tài liệu người này đã up
        $taiLieuCuaToi = TaiLieu::with('HocPhan')
            ->where('NguoiDungID', $nguoiDung->NguoiDungID)
            ->orderBy('NgayUpload', 'desc')
            ->get();

        // Lấy danh sách review người này đã viết
        $reviewCuaToi = Review::with('HocPhan')
            ->where('NguoiDungID', $nguoiDung->NguoiDungID)
            ->orderBy('NgayDang', 'desc')
            ->get();

        return view('hoso.index', compact('nguoiDung', 'taiLieuCuaToi', 'reviewCuaToi'));
    }

    public function capNhatAnhDaiDien(Request $request)
    {
        $request->validate([
            'fileDaiDien' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // Tối đa 2MB
        ], [
            'fileDaiDien.required' => 'Vui lòng chọn ảnh.',
            'fileDaiDien.image' => 'File tải lên phải là định dạng ảnh.',
            'fileDaiDien.max' => 'Kích thước ảnh tối đa là 2MB.'
        ]);

        $user = NguoiDung::find(Auth::id());

        if ($request->hasFile('fileDaiDien')) {
            $file = $request->file('fileDaiDien');
            $filename = time() . '_avatar_' . Auth::id() . '.' . $file->getClientOriginalExtension();
            
            // Xóa ảnh cũ trên server nếu có (để tiết kiệm dung lượng)
            if ($user->AnhDaiDien && str_starts_with($user->AnhDaiDien, 'storage/')) {
                $oldPath = str_replace('storage/', 'public/', $user->AnhDaiDien);
                Storage::delete($oldPath);
            }

            // Lưu ảnh mới vào thư mục public/avatars
            $path = $file->storeAs('avatars', $filename, 'public');
            
            // Lưu đường dẫn vào database
            $user->AnhDaiDien = 'storage/' . $path;
            $user->save();

            return back()->with('ThongBaoHoSo', 'Cập nhật ảnh đại diện thành công!');
        }

        return back()->with('LoiHoSo', 'Có lỗi xảy ra khi tải ảnh lên.');
    }

    // Hiển thị giao diện Đổi mật khẩu
    public function doiMatKhau()
    {
        return view('hoso.doimatkhau');
    }

    // Xử lý logic Đổi mật khẩu
    public function xuLyDoiMatKhau(Request $request)
    {
        // Kiểm tra tính hợp lệ của dữ liệu nhập vào
        $request->validate([
            'matKhauCu' => 'required',
            'matKhauMoi' => 'required|min:6',
            'xacNhanMatKhau' => 'required|same:matKhauMoi'
        ], [
            'matKhauCu.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'matKhauMoi.required' => 'Vui lòng nhập mật khẩu mới.',
            'matKhauMoi.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'xacNhanMatKhau.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'xacNhanMatKhau.same' => 'Xác nhận mật khẩu không khớp.'
        ]);

        $user = NguoiDung::find(Auth::id());

        // Kiểm tra xem user có tồn tại không (trường hợp hiếm khi xảy ra)
        if (!$user) {
            return back()->with('Loi', 'Không tìm thấy thông tin tài khoản.');
        }

        // Kiểm tra mật khẩu cũ có khớp với Database không
        // Lưu ý: Nếu cột mật khẩu trong Database của cậu tên khác, hãy đổi chữ MatKhau cho khớp nhé
        if (!Hash::check($request->matKhauCu, $user->MatKhau)) {
            return back()->with('Loi', 'Mật khẩu hiện tại không đúng.');
        }

        // Mã hóa và lưu mật khẩu mới
        $user->MatKhau = Hash::make($request->matKhauMoi);
        $user->save();

        // Trở về trang Hồ sơ kèm thông báo
        return redirect()->route('hoso.index')->with('ThongBaoDoiMatKhau', 'Đổi mật khẩu thành công!');
    }
}