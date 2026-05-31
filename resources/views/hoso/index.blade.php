@extends('layouts.app')

@section('title', 'Hồ Sơ Của Tôi')

@section('content')
<div class="container mt-4 mb-5">
    @if (session('ThongBaoDoiMatKhau'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>✅ Chúc mừng!</strong> {{ session('ThongBaoDoiMatKhau') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 border-top border-primary border-4 text-center pb-4">
                <div class="card-body">

                    <div class="mb-3 mt-3">
                        @if (!empty($nguoiDung->AnhDaiDien))
                            <img src="{{ asset($nguoiDung->AnhDaiDien) }}" alt="Avatar" class="rounded-circle object-fit-cover shadow-sm" style="width: 120px; height: 120px; border: 3px solid #0d6efd;" />
                        @else
                            <span class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; font-size: 3rem; border: 3px solid #0d6efd;">
                                {{ mb_substr($nguoiDung->HoTen, 0, 1) }}
                            </span>
                        @endif
                    </div>

                    <h4 class="fw-bold">{{ $nguoiDung->HoTen }}</h4>
                    <p class="text-muted mb-1">{{ $nguoiDung->Email }}</p>
                    <div class="mt-2">
                        @if ($nguoiDung->VaiTro == "Admin")
                            <span class="badge bg-danger">Quản trị viên</span>
                        @elseif ($nguoiDung->VaiTro == "GiangVien")
                            <span class="badge bg-warning text-dark">Giảng viên</span>
                        @else
                            <span class="badge bg-info text-dark">Sinh viên</span>
                        @endif
                    </div>
                    <hr class="opacity-25" />
                    <p class="small text-muted mb-0">Tham gia từ: {{ \Carbon\Carbon::parse($nguoiDung->NgayDangKy)->format('d/m/Y') }}</p>

                    <div class="mt-4 d-flex flex-column gap-2 px-3">
                        <form action="{{ route('hoso.capNhatAnhDaiDien') }}" method="POST" enctype="multipart/form-data" id="formAvatar" class="d-none">
                            @csrf
                            <input type="file" name="fileDaiDien" id="fileDaiDien" accept="image/*" onchange="document.getElementById('formAvatar').submit();">
                        </form>

                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold w-100 shadow-sm" onclick="document.getElementById('fileDaiDien').click();">
                            Thay avatar
                        </button>

                        <a href="{{ route('hoso.doiMatKhau') }}" class="btn btn-outline-danger btn-sm w-100 fw-bold shadow-sm">Đổi mật khẩu</a>
                    </div>

                    @if (session('ThongBaoHoSo'))
                        <div class="alert alert-success mt-3 mx-3 py-2 small mb-0 rounded-3">✅ {{ session('ThongBaoHoSo') }}</div>
                    @endif
                    @if (session('LoiHoSo') || $errors->any())
                        <div class="alert alert-danger mt-3 mx-3 py-2 small mb-0 rounded-3">
                            ❌ {{ session('LoiHoSo') ?? $errors->first() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-success"><i class="fa-solid fa-folder-open me-2"></i>Tài liệu tôi đã chia sẻ ({{ $taiLieuCuaToi->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if ($taiLieuCuaToi->count() > 0)
                            @foreach ($taiLieuCuaToi as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3 position-relative list-group-item-action">
                                    <div>
                                        @if ($item->TrangThaiDuyet == "HopLe")
                                            <a href="{{ route('tailieu.show', $item->TaiLieuID) }}" class="text-decoration-none h6 mb-1 fw-bold text-primary d-block stretched-link">{{ $item->TenTaiLieu }}</a>
                                        @else
                                            <h6 class="mb-1 fw-bold text-dark">{{ $item->TenTaiLieu }}</h6>
                                        @endif
                                        <small class="text-muted">Môn: {{ $item->HocPhan?->TenHocPhan }} | Ngày: {{ \Carbon\Carbon::parse($item->NgayUpload)->format('d/m/Y') }}</small>
                                    </div>
                                    <div>
                                        @if ($item->TrangThaiDuyet == "HopLe")
                                            <span class="badge bg-success rounded-pill px-3 shadow-sm">Đã duyệt</span>
                                        @elseif ($item->TrangThaiDuyet == "ChoDuyet")
                                            <span class="badge bg-warning text-dark rounded-pill px-3 shadow-sm">Đang chờ</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-3 shadow-sm">Bị từ chối</span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item text-muted text-center py-4 border-0">
                                <i class="fa-regular fa-folder-open fs-3 d-block mb-2 opacity-50"></i>
                                Bạn chưa tải lên tài liệu nào.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold text-warning"><i class="fa-solid fa-star me-2"></i>Đánh giá tôi đã viết ({{ $reviewCuaToi->count() }})</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @if ($reviewCuaToi->count() > 0)
                            @foreach ($reviewCuaToi as $item)
                                <li class="list-group-item p-3 position-relative list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="pe-3 w-100">
                                            @if ($item->TrangThaiDuyet == "HopLe" || $item->TrangThaiDuyet == "DaDuyet")
                                                <a href="{{ route('review.show', $item->ReviewID) }}" class="text-decoration-none h6 mb-1 fw-bold text-primary d-block stretched-link">{{ $item->HocPhan?->TenHocPhan }}</a>
                                            @else
                                                <h6 class="mb-1 fw-bold text-dark">{{ $item->HocPhan?->TenHocPhan }}</h6>
                                            @endif
                                            <p class="mb-0 small text-truncate text-muted" style="max-width: 95%;">"{{ $item->NoiDung }}"</p>
                                        </div>

                                        <div class="text-end flex-shrink-0" style="min-width: 85px;">
                                            <small class="text-muted d-block mb-1">{{ \Carbon\Carbon::parse($item->NgayDang)->format('d/m/Y') }}</small>
                                            @if ($item->TrangThaiDuyet == "HopLe" || $item->TrangThaiDuyet == "DaDuyet")
                                                <span class="badge bg-success rounded-pill px-3 shadow-sm">Hiển thị</span>
                                            @elseif ($item->TrangThaiDuyet == "ChoDuyet")
                                                <span class="badge bg-warning text-dark rounded-pill px-3 shadow-sm">Chờ duyệt</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill px-3 shadow-sm">Bị chặn</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item text-muted text-center py-4 border-0">
                                <i class="fa-regular fa-comment-dots fs-3 d-block mb-2 opacity-50"></i>
                                Bạn chưa viết bài đánh giá nào.
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection