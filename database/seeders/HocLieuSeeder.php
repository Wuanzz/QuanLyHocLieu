<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use App\Models\Khoa;
use App\Models\Nganh;
use App\Models\HocPhan;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HocLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NguoiDung::insert([
            ['HoTen' => 'Sinh viên', 'Email' => 'sinhvien@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'SinhVien'],
            ['HoTen' => 'Giảng viên', 'Email' => 'giangvien@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'GiangVien'],
            ['HoTen' => 'Quản trị viên', 'Email' => 'admin@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'Admin'],
            ['HoTen' => 'Nguyễn Văn A', 'Email' => 'nguyenvana@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'SinhVien'],
            ['HoTen' => 'Nguyễn Văn B', 'Email' => 'nguyenvanb@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'SinhVien'],
            ['HoTen' => 'Nguyễn Văn C', 'Email' => 'nguyenvanc@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'SinhVien'],
            ['HoTen' => 'Trần Thị D', 'Email' => 'tranthid@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'SinhVien'],
            ['HoTen' => 'Trần Thị E', 'Email' => 'tranthie@hcmue.edu.vn', 'MatKhau' => bcrypt('123456'), 'AnhDaiDien' => null, 'NgayDangKy' => now(), 'TrangThai' => 'HoatDong', 'VaiTro' => 'SinhVien'],
        ]);

        Khoa::insert([
            ['KhoaID' => 1, 'TenKhoa' => 'Khoa Công nghệ Thông tin', 'MoTa' => '...'],
            ['KhoaID' => 2, 'TenKhoa' => 'Khoa Toán - Thống kê', 'MoTa' => '...'],
            ['KhoaID' => 3, 'TenKhoa' => 'Khoa Ngoại ngữ', 'MoTa' => '...'],
            ['KhoaID' => 4, 'TenKhoa' => 'Khoa Vật lý', 'MoTa' => '...'],
            ['KhoaID' => 5, 'TenKhoa' => 'Khoa Tâm lý học', 'MoTa' => '...'],
        ]);

        Nganh::insert([
            ['NganhID' => 1, 'TenNganh' => 'Sư phạm Tin học', 'KhoaID' => 1, 'MoTa' => '...'],
            ['NganhID' => 2, 'TenNganh' => 'Công nghệ Thông tin', 'KhoaID' => 1, 'MoTa' => '...'],
            ['NganhID' => 3, 'TenNganh' => 'Sư phạm Toán học', 'KhoaID' => 2, 'MoTa' => '...'],
            ['NganhID' => 4, 'TenNganh' => 'Toán học ứng dụng', 'KhoaID' => 2, 'MoTa' => '...'],
            ['NganhID' => 5, 'TenNganh' => 'Sư phạm Tiếng Anh', 'KhoaID' => 3, 'MoTa' => '...'],
            ['NganhID' => 6, 'TenNganh' => 'Ngôn ngữ Anh', 'KhoaID' => 3, 'MoTa' => '...'],
            ['NganhID' => 7, 'TenNganh' => 'Sư phạm Vật lý', 'KhoaID' => 4, 'MoTa' => '...'],
            ['NganhID' => 8, 'TenNganh' => 'Vật lý học', 'KhoaID' => 4, 'MoTa' => '...'],
            ['NganhID' => 9, 'TenNganh' => 'Tâm lý học giáo dục', 'KhoaID' => 5, 'MoTa' => '...'],
            ['NganhID' => 10, 'TenNganh' => 'Tâm lý học ứng dụng', 'KhoaID' => 5, 'MoTa' => '...'],
        ]);

        HocPhan::insert([
            // Ngành 1: Sư phạm Tin học
            ['TenHocPhan' => 'Lý luận dạy học Tin học', 'NganhID' => 1, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phương pháp giảng dạy lập trình', 'NganhID' => 1, 'MoTa' => '...'],
            ['TenHocPhan' => 'Tin học đại cương', 'NganhID' => 1, 'MoTa' => '...'],
            ['TenHocPhan' => 'Tâm lý học sư phạm Tin', 'NganhID' => 1, 'MoTa' => '...'],
            ['TenHocPhan' => 'Thực tập sư phạm Tin học', 'NganhID' => 1, 'MoTa' => '...'],

            // Ngành 2: Công nghệ Thông tin
            ['TenHocPhan' => 'Nhập môn Lập trình C++', 'NganhID' => 2, 'MoTa' => '...'],
            ['TenHocPhan' => 'Cơ sở dữ liệu SQL Server', 'NganhID' => 2, 'MoTa' => '...'],
            ['TenHocPhan' => 'Lập trình Web với ASP.NET Core', 'NganhID' => 2, 'MoTa' => '...'],
            ['TenHocPhan' => 'Cấu trúc dữ liệu và giải thuật', 'NganhID' => 2, 'MoTa' => '...'],
            ['TenHocPhan' => 'Trí tuệ nhân tạo cơ bản', 'NganhID' => 2, 'MoTa' => '...'],

            // Ngành 3: Sư phạm Toán học
            ['TenHocPhan' => 'Đại số tuyến tính', 'NganhID' => 3, 'MoTa' => '...'],
            ['TenHocPhan' => 'Giải tích 1', 'NganhID' => 3, 'MoTa' => '...'],
            ['TenHocPhan' => 'Hình học giải tích', 'NganhID' => 3, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phương pháp dạy học Toán', 'NganhID' => 3, 'MoTa' => '...'],
            ['TenHocPhan' => 'Toán rời rạc', 'NganhID' => 3, 'MoTa' => '...'],

            // Ngành 4: Toán học ứng dụng
            ['TenHocPhan' => 'Xác suất thống kê', 'NganhID' => 4, 'MoTa' => '...'],
            ['TenHocPhan' => 'Toán tối ưu', 'NganhID' => 4, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phương trình vi phân', 'NganhID' => 4, 'MoTa' => '...'],
            ['TenHocPhan' => 'Toán tài chính', 'NganhID' => 4, 'MoTa' => '...'],
            ['TenHocPhan' => 'Lý thuyết đồ thị', 'NganhID' => 4, 'MoTa' => '...'],

            // Ngành 5: Sư phạm Tiếng Anh
            ['TenHocPhan' => 'Ngữ âm học tiếng Anh (Phonetics)', 'NganhID' => 5, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phương pháp dạy Kỹ năng Đọc - Viết', 'NganhID' => 5, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phương pháp dạy Kỹ năng Nghe - Nói', 'NganhID' => 5, 'MoTa' => '...'],
            ['TenHocPhan' => 'Kiểm tra đánh giá ngoại ngữ', 'NganhID' => 5, 'MoTa' => '...'],
            ['TenHocPhan' => 'Ngữ pháp tiếng Anh nâng cao', 'NganhID' => 5, 'MoTa' => '...'],

            // Ngành 6: Ngôn ngữ Anh
            ['TenHocPhan' => 'Văn hóa Anh - Mỹ', 'NganhID' => 6, 'MoTa' => '...'],
            ['TenHocPhan' => 'Biên dịch cơ bản', 'NganhID' => 6, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phiên dịch cơ bản', 'NganhID' => 6, 'MoTa' => '...'],
            ['TenHocPhan' => 'Tiếng Anh thương mại', 'NganhID' => 6, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phân tích diễn ngôn', 'NganhID' => 6, 'MoTa' => '...'],

            // Ngành 7: Sư phạm Vật lý
            ['TenHocPhan' => 'Cơ học đại cương', 'NganhID' => 7, 'MoTa' => '...'],
            ['TenHocPhan' => 'Nhiệt học', 'NganhID' => 7, 'MoTa' => '...'],
            ['TenHocPhan' => 'Điện từ học', 'NganhID' => 7, 'MoTa' => '...'],
            ['TenHocPhan' => 'Phương pháp thí nghiệm Vật lý', 'NganhID' => 7, 'MoTa' => '...'],
            ['TenHocPhan' => 'Lý luận dạy học Vật lý', 'NganhID' => 7, 'MoTa' => '...'],

            // Ngành 8: Vật lý học
            ['TenHocPhan' => 'Cơ học lượng tử', 'NganhID' => 8, 'MoTa' => '...'],
            ['TenHocPhan' => 'Vật lý hạt nhân', 'NganhID' => 8, 'MoTa' => '...'],
            ['TenHocPhan' => 'Vật lý chất rắn', 'NganhID' => 8, 'MoTa' => '...'],
            ['TenHocPhan' => 'Quang học', 'NganhID' => 8, 'MoTa' => '...'],
            ['TenHocPhan' => 'Vật lý thống kê', 'NganhID' => 8, 'MoTa' => '...'],

            // Ngành 9: Tâm lý học giáo dục
            ['TenHocPhan' => 'Tâm lý học phát triển', 'NganhID' => 9, 'MoTa' => '...'],
            ['TenHocPhan' => 'Tâm lý học lứa tuổi', 'NganhID' => 9, 'MoTa' => '...'],
            ['TenHocPhan' => 'Tham vấn học đường', 'NganhID' => 9, 'MoTa' => '...'],
            ['TenHocPhan' => 'Giáo dục hòa nhập', 'NganhID' => 9, 'MoTa' => '...'],
            ['TenHocPhan' => 'Đo lường tâm lý', 'NganhID' => 9, 'MoTa' => '...'],

            // Ngành 10: Tâm lý học ứng dụng
            ['TenHocPhan' => 'Tâm lý học tổ chức nhân sự', 'NganhID' => 10, 'MoTa' => '...'],
            ['TenHocPhan' => 'Tâm lý học quản lý', 'NganhID' => 10, 'MoTa' => '...'],
            ['TenHocPhan' => 'Trị liệu tâm lý', 'NganhID' => 10, 'MoTa' => '...'],
            ['TenHocPhan' => 'Kỹ năng giao tiếp', 'NganhID' => 10, 'MoTa' => '...'],
            ['TenHocPhan' => 'Tâm lý học tội phạm', 'NganhID' => 10, 'MoTa' => '...'],
        ]);
    }
}
