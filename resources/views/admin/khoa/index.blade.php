@extends('layouts.admin')

@section('title', 'Quản lý Khoa')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-dark fw-bold"><i class="fa-solid fa-building text-primary me-2"></i>Danh sách các Khoa</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-3 mt-3">
        <div class="col-md-6">
            <form action="{{ route('admin.khoa.index') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="timKiem" class="form-control shadow-sm" placeholder="Nhập tên khoa cần tìm..." value="{{ $timKiem }}">
                <button type="submit" class="btn btn-primary fw-bold shadow-sm px-4">Tìm</button>
                <a href="{{ route('admin.khoa.index') }}" class="btn btn-outline-secondary shadow-sm">Xóa</a>
            </form>
        </div>
    </div>

    <p>
        <a class="btn btn-success fw-bold shadow-sm" href="{{ route('admin.khoa.create') }}">
            <i class="fa-solid fa-plus me-2"></i>Thêm Khoa mới
        </a>
    </p>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" width="8%">STT</th>
                        <th width="30%">Tên Khoa</th>
                        <th>Mô tả</th>
                        <th class="text-center" width="15%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($khoas->count() > 0)
                        @foreach ($khoas as $index => $item)
                            <tr>
                                <td class="text-center fw-bold">{{ $khoas->firstItem() + $index }}</td>
                                <td class="fw-bold text-primary">{{ $item->TenKhoa }}</td>
                                <td>{{ $item->MoTa }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="btn btn-sm btn-primary fw-bold px-3 shadow-sm" href="{{ route('admin.khoa.edit', $item->KhoaID) }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Sửa
                                        </a>
                                        
                                        <form action="{{ route('admin.khoa.destroy', $item->KhoaID) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa Khoa này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger fw-bold px-3 shadow-sm">
                                                <i class="fa-solid fa-trash"></i> Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted fst-italic">
                                <i class="fa-solid fa-folder-open fs-3 d-block mb-2 opacity-50"></i>
                                Chưa có dữ liệu Khoa nào trong hệ thống.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($khoas->lastPage() > 1)
    <nav aria-label="Page navigation" class="mt-4 mb-5">
        <ul class="pagination justify-content-center shadow-sm rounded-pill overflow-hidden d-inline-flex">
            
            <li class="page-item {{ $khoas->currentPage() == 1 ? 'disabled' : '' }}">
                <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $khoas->url($khoas->currentPage() - 1) }}">&laquo; Trước</a>
            </li>

            @for ($i = 1; $i <= $khoas->lastPage(); $i++)
                @if ($i == 1 || $i == $khoas->lastPage() || abs($i - $khoas->currentPage()) <= 1)
                    <li class="page-item {{ $i == $khoas->currentPage() ? 'active' : '' }}">
                        <a class="page-link fw-bold border-0 py-2 px-3 {{ $i == $khoas->currentPage() ? 'bg-primary text-white' : 'text-primary' }}" href="{{ $khoas->url($i) }}">{{ $i }}</a>
                    </li>
                @elseif ($i == 2 && $khoas->currentPage() > 3)
                    <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                @elseif ($i == $khoas->lastPage() - 1 && $khoas->currentPage() < $khoas->lastPage() - 2)
                    <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                @endif
            @endfor

            <li class="page-item {{ $khoas->currentPage() == $khoas->lastPage() ? 'disabled' : '' }}">
                <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $khoas->url($khoas->currentPage() + 1) }}">Sau &raquo;</a>
            </li>

        </ul>
    </nav>
@endif
@endsection