@extends('layouts.admin')

@section('title', 'Quản lý Tài Khoản')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-users text-primary me-2"></i>Quản lý Người Dùng</h2>
        <span class="badge bg-primary rounded-pill fs-6 px-3 py-2 shadow-sm">Tổng số: {{ number_format($tongSo) }} tài khoản</span>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row align-items-center mb-4">
        <div class="col-md-9 mb-3 mb-md-0">
            <form action="{{ route('admin.nguoi-dung.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="timKiem" class="form-control shadow-sm" placeholder="Nhập tên hoặc email..." value="{{ $timKiem }}" style="max-width: 300px;">
                
                <select name="vaiTro" class="form-select shadow-sm" style="max-width: 180px;">
                    <option value="">-- Mọi vai trò --</option>
                    <option value="Admin" {{ $vaiTro == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="GiangVien" {{ $vaiTro == 'GiangVien' ? 'selected' : '' }}>Giảng viên</option>
                    <option value="SinhVien" {{ $vaiTro == 'SinhVien' ? 'selected' : '' }}>Sinh viên</option>
                </select>

                <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4">Lọc</button>
                <a href="{{ route('admin.nguoi-dung.index') }}" class="btn btn-outline-secondary shadow-sm">Xóa</a>
            </form>
        </div>
        <div class="col-md-3 text-md-end">
            <a class="btn btn-success fw-bold shadow-sm px-4" href="{{ route('admin.nguoi-dung.create') }}">
                <i class="fa-solid fa-user-plus me-2"></i>Thêm mới
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="22%">Họ tên</th>
                        <th>Email</th>
                        <th width="12%" class="text-center">Vai trò</th>
                        <th width="15%" class="text-center">Trạng thái</th>
                        <th width="15%" class="text-center">Ngày đăng ký</th>
                        <th width="15%" class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($nguoidungs->count() > 0)
                        @foreach ($nguoidungs as $item)
                            <tr>
                                <td class="fw-bold text-dark">{{ $item->HoTen }}</td>
                                <td class="text-muted">{{ $item->Email }}</td>
                                <td class="text-center">
                                    @if ($item->VaiTro == "Admin")
                                        <span class="badge bg-danger shadow-sm px-3 py-2">Admin</span>
                                    @elseif ($item->VaiTro == "GiangVien")
                                        <span class="badge bg-warning text-dark shadow-sm px-2 py-2">Giảng viên</span>
                                    @else
                                        <span class="badge bg-info text-dark shadow-sm px-3 py-2">Sinh viên</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($item->TrangThai == "HoatDong" || $item->TrangThai == "Hoạt động")
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1">Hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary px-2 py-1">Đã khóa</span>
                                    @endif
                                </td>
                                <td class="text-center text-muted small fw-bold">
                                    {{ \Carbon\Carbon::parse($item->NgayDangKy)->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    <a class="btn btn-sm btn-primary fw-bold px-3 shadow-sm" href="{{ route('admin.nguoi-dung.edit', $item->NguoiDungID) }}">
                                        <i class="fa-solid fa-user-shield me-1"></i> Phân quyền
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted fst-italic">
                                <i class="fa-solid fa-users-slash fs-3 d-block mb-2 opacity-50"></i>
                                Không tìm thấy người dùng nào phù hợp.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($nguoidungs->lastPage() > 1)
    <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4 mb-5">
        <ul class="pagination shadow-sm rounded-pill overflow-hidden mb-0">
            <li class="page-item {{ $nguoidungs->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $nguoidungs->url($nguoidungs->currentPage() - 1) }}">&laquo; Trước</a>
            </li>
            @for ($i = 1; $i <= $nguoidungs->lastPage(); $i++)
                @if ($i == 1 || $i == $nguoidungs->lastPage() || abs($i - $nguoidungs->currentPage()) <= 1)
                    <li class="page-item {{ $i == $nguoidungs->currentPage() ? 'active' : '' }}">
                        <a class="page-link fw-bold border-0 py-2 px-3 {{ $i == $nguoidungs->currentPage() ? 'bg-primary text-white' : 'text-primary' }}" href="{{ $nguoidungs->url($i) }}">{{ $i }}</a>
                    </li>
                @elseif ($i == 2 && $nguoidungs->currentPage() > 3)
                    <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                @elseif ($i == $nguoidungs->lastPage() - 1 && $nguoidungs->currentPage() < $nguoidungs->lastPage() - 2)
                    <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                @endif
            @endfor
            <li class="page-item {{ $nguoidungs->currentPage() == $nguoidungs->lastPage() ? 'disabled' : '' }}">
                <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $nguoidungs->url($nguoidungs->currentPage() + 1) }}">Sau &raquo;</a>
            </li>
        </ul>
    </nav>
@endif
@endsection