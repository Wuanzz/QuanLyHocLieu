@extends('layouts.admin')

@section('title', 'Quản lý Học Phần')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-dark fw-bold"><i class="fa-solid fa-book-open text-primary me-2"></i>Danh sách Học Phần</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3 mt-3">
        <div class="col-md-6">
            <form action="{{ route('admin.hoc-phan.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="timKiem" class="form-control shadow-sm" placeholder="Nhập tên học phần cần tìm..." value="{{ $timKiem }}">
                <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4">Tìm</button>
                <a href="{{ route('admin.hoc-phan.index') }}" class="btn btn-outline-secondary shadow-sm">Xóa</a>
            </form>
        </div>
    </div>

    <p>
        <a class="btn btn-success fw-bold shadow-sm" href="{{ route('admin.hoc-phan.create') }}">
            <i class="fa-solid fa-plus me-2"></i>Thêm Học Phần mới
        </a>
    </p>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" width="8%">STT</th>
                        <th width="25%">Tên Học Phần</th>
                        <th width="20%">Thuộc Ngành</th>
                        <th>Mô tả</th>
                        <th class="text-center" width="15%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($hocphans->count() > 0)
                        @foreach ($hocphans as $index => $item)
                            <tr>
                                <td class="text-center fw-bold">{{ $hocphans->firstItem() + $index }}</td>
                                <td class="fw-bold text-success">{{ $item->TenHocPhan }}</td>
                                <td>
                                    <span class="badge bg-white text-dark border border-secondary px-2 py-1 shadow-sm">
                                        {{ $item->Nganh?->TenNganh ?? 'Không xác định' }}
                                    </span>
                                </td>
                                <td>{{ $item->MoTa }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="btn btn-sm btn-primary fw-bold px-3 shadow-sm" href="{{ route('admin.hoc-phan.edit', $item->HocPhanID) }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Sửa
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-danger fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $item->HocPhanID }}">
                                            <i class="fa-solid fa-trash"></i> Xóa
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="deleteModal-{{ $item->HocPhanID }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $item->HocPhanID }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 rounded-4 shadow">
                                        <div class="modal-header bg-danger text-white border-bottom-0 py-3 px-4">
                                            <h5 class="modal-title fw-bold" id="deleteModalLabel-{{ $item->HocPhanID }}">
                                                <i class="fa-solid fa-triangle-exclamation me-2"></i>Xác nhận xóa Học Phần
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-modal="dismiss" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <p class="text-dark fs-6 mb-3">Bạn có chắc chắn muốn xóa học phần này không? Hành động này sẽ không thể hoàn tác!</p>
                                            <div class="p-3 bg-light rounded-3 border border-light small text-start">
                                                <div class="mb-1"><strong>Tên Học Phần:</strong> <span class="text-success fw-bold">{{ $item->TenHocPhan }}</span></div>
                                                <div class="mb-1"><strong>Thuộc Ngành:</strong> <span class="text-dark">{{ $item->Nganh?->TenNganh }}</span></div>
                                                <div><strong>Mô tả:</strong> <span class="text-muted">{{ $item->MoTa ?? 'Chưa có mô tả.' }}</span></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top-0 p-3 pt-0 px-4">
                                            <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                                            <form action="{{ route('admin.hoc-phan.destroy', $item->HocPhanID) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4 shadow-sm">
                                                    Xác nhận Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted fst-italic">
                                <i class="fa-solid fa-book-open fs-3 d-block mb-2 opacity-50"></i>
                                Chưa có dữ liệu Học Phần nào trong hệ thống.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($hocphans->lastPage() > 1)
    <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4 mb-5">
        <ul class="pagination shadow-sm rounded-pill overflow-hidden mb-0">
            
            <li class="page-item {{ $hocphans->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $hocphans->url($hocphans->currentPage() - 1) }}">&laquo; Trước</a>
            </li>
            
            @for ($i = 1; $i <= $hocphans->lastPage(); $i++)
                @if ($i == 1 || $i == $hocphans->lastPage() || abs($i - $hocphans->currentPage()) <= 1)
                    <li class="page-item {{ $i == $hocphans->currentPage() ? 'active' : '' }}">
                        <a class="page-link fw-bold border-0 py-2 px-3 {{ $i == $hocphans->currentPage() ? 'bg-primary text-white' : 'text-primary' }}" href="{{ $hocphans->url($i) }}">{{ $i }}</a>
                    </li>
                @elseif ($i == 2 && $hocphans->currentPage() > 3)
                    <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                @elseif ($i == $hocphans->lastPage() - 1 && $hocphans->currentPage() < $hocphans->lastPage() - 2)
                    <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                @endif
            @endfor
            
            <li class="page-item {{ $hocphans->currentPage() == $hocphans->lastPage() ? 'disabled' : '' }}">
                <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $hocphans->url($hocphans->currentPage() + 1) }}">Sau &raquo;</a>
            </li>
            
        </ul>
    </nav>
@endif
@endsection