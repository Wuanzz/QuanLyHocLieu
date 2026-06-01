@extends('layouts.admin')

@section('title', 'Thêm Tài Khoản Mới')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h4 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-user-plus text-primary me-2"></i>Thêm Tài Khoản Mới</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.nguoi-dung.store') }}" method="POST">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 form-group mb-4">
                                <label for="HoTen" class="form-label fw-bold text-dark">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" id="HoTen" name="HoTen" value="{{ old('HoTen') }}" class="form-control form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" placeholder="Nhập họ tên đầy đủ..." required />
                                @error('HoTen')
                                    <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 form-group mb-4">
                                <label for="Email" class="form-label fw-bold text-dark">Địa chỉ Email <span class="text-danger">*</span></label>
                                <input type="email" id="Email" name="Email" value="{{ old('Email') }}" class="form-control form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" placeholder="Ví dụ: nguyenvan@example.com" required />
                                @error('Email')
                                    <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="MatKhau" class="form-label fw-bold text-dark">Mật khẩu khởi tạo <span class="text-danger">*</span></label>
                            <input type="password" id="MatKhau" name="MatKhau" class="form-control form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" placeholder="Mật khẩu ít nhất 6 ký tự..." required minlength="6" />
                            <small class="text-muted mt-1 d-block"><i class="fa-solid fa-circle-info me-1"></i>Người dùng có thể tự đổi mật khẩu này sau khi đăng nhập.</small>
                            @error('MatKhau')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group mb-4">
                                <label for="VaiTro" class="form-label fw-bold text-dark">Vai trò (Phân quyền) <span class="text-danger">*</span></label>
                                <select id="VaiTro" name="VaiTro" class="form-select form-control-lg shadow-sm border-primary rounded-3 px-3" required>
                                    <option value="SinhVien" {{ old('VaiTro') == 'SinhVien' ? 'selected' : '' }}>Sinh viên (Người dùng chung)</option>
                                    <option value="GiangVien" {{ old('VaiTro') == 'GiangVien' ? 'selected' : '' }}>Giảng viên (Người kiểm duyệt)</option>
                                    <option value="Admin" {{ old('VaiTro') == 'Admin' ? 'selected' : '' }}>Admin (Quản trị viên)</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-5">
                                <label for="TrangThai" class="form-label fw-bold text-dark">Trạng thái <span class="text-danger">*</span></label>
                                <select id="TrangThai" name="TrangThai" class="form-select form-control-lg shadow-sm border-success rounded-3 px-3" required>
                                    <option value="HoatDong" {{ old('TrangThai') == 'HoatDong' ? 'selected' : '' }}>Cho phép Hoạt động</option>
                                    <option value="Khoa" {{ old('TrangThai') == 'Khoa' ? 'selected' : '' }}>Khóa tài khoản</option>
                                </select>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25 mb-4" />

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu tài khoản
                            </button>
                            <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-outline-secondary fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection