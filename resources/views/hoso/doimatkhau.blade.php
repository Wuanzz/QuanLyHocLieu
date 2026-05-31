@extends('layouts.app')

@section('title', 'Đổi Mật Khẩu')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 border-top border-danger border-4 rounded-4">
                <div class="card-header bg-white py-3 text-center border-bottom-0">
                    <h4 class="mb-0 fw-bold text-danger">🔐 Đổi Mật Khẩu</h4>
                </div>
                <div class="card-body p-4">

                    @if (session('Loi'))
                        <div class="alert alert-danger py-2 rounded-3 shadow-sm border-0">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('Loi') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 rounded-3 shadow-sm border-0">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hoso.xuLyDoiMatKhau') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1 text-dark">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                            <input type="password" name="matKhauCu" class="form-control bg-light border-0 shadow-sm rounded-3 py-2" required placeholder="Nhập mật khẩu đang dùng..." />
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1 text-dark">Mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="matKhauMoi" class="form-control bg-light border-0 shadow-sm rounded-3 py-2" required minlength="6" placeholder="Ít nhất 6 ký tự..." />
                        </div>

                        <div class="form-group mb-4">
                            <label class="fw-bold mb-1 text-dark">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                            <input type="password" name="xacNhanMatKhau" class="form-control bg-light border-0 shadow-sm rounded-3 py-2" required placeholder="Nhập lại mật khẩu mới..." />
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold rounded-pill shadow-sm py-2">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu mật khẩu mới
                            </button>
                            <a href="{{ route('hoso.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill shadow-sm py-2">
                                Hủy bỏ
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection