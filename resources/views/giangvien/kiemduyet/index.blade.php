@extends('layouts.admin')

@section('title', 'Bảng điều khiển Giảng viên')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold"><i class="fa-solid fa-shield-halved text-primary me-2"></i>Không gian Kiểm duyệt Nội dung</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 border-top border-primary border-4 mb-5 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="mb-0 text-primary fw-bold"><i class="fa-solid fa-file-arrow-up me-2"></i>1. Tài Liệu Mới Cần Phê Duyệt</h5>
            <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm">Chờ duyệt: {{ $taiLieuChoDuyet->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="30%">Tên Tài liệu</th>
                            <th width="20%">Môn Học</th>
                            <th width="15%">Người đăng</th>
                            <th width="18%">Thời gian</th>
                            <th class="text-center" width="17%">Quyết định</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($taiLieuChoDuyet->count() > 0)
                            @foreach ($taiLieuChoDuyet as $item)
                                <tr>
                                    <td class="fw-bold text-dark">
                                        {{ $item->TenTaiLieu }}
                                        <br />
                                        <a href="{{ asset('storage/' . $item->DuongDanFile) }}" target="_blank" class="text-info small text-decoration-none d-inline-block mt-1">
                                            <i class="fa-regular fa-eye me-1"></i>Xem nội dung
                                        </a>
                                    </td>
                                    <td class="text-dark fw-bold small">{{ $item->HocPhan?->TenHocPhan }}</td>
                                    <td><span class="text-muted small fw-bold">{{ $item->NguoiDung?->HoTen }}</span></td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($item->NgayUpload)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('giangvien.kiemduyet.duyetTailieu') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->TaiLieuID }}" />
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-3 shadow-sm">Duyệt</button>
                                            </form>
                                            <form action="{{ route('giangvien.kiemduyet.tuChoiTaiLieu') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->TaiLieuID }}" />
                                                <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-2 shadow-sm">Từ chối</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5" class="text-center py-5 text-success fw-bold fst-italic"><i class="fa-solid fa-circle-check me-2 fs-5"></i>Không có tài liệu nào chờ duyệt.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 border-top border-danger border-4 mb-5 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="mb-0 text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i>2. Tài Liệu Cần Xử Lý</h5>
            <span class="badge bg-danger rounded-pill px-3 py-2 shadow-sm">Chờ xử lý: {{ $danhSachBaoCao->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="30%">Tài liệu bị báo cáo</th>
                            <th width="25%">Lý do vi phạm</th>
                            <th width="15%">Người báo cáo</th>
                            <th width="15%">Thời gian</th>
                            <th class="text-center" width="15%">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($danhSachBaoCao->count() > 0)
                            @foreach ($danhSachBaoCao as $item)
                                <tr>
                                    <td class="fw-bold text-danger">
                                        {{ $item->TaiLieu?->TenTaiLieu }}
                                        <br />
                                        <a href="{{ asset('storage/' . $item->TaiLieu?->DuongDanFile) }}" target="_blank" class="text-info small text-decoration-none d-inline-block mt-1">
                                            <i class="fa-solid fa-magnifying-glass me-1"></i>Kiểm tra file
                                        </a>
                                    </td>
                                    <td class="text-wrap text-muted small" style="max-width: 250px;">{{ $item->LyDo }}</td>
                                    <td><span class="text-dark small fw-bold">{{ $item->NguoiDung?->HoTen }}</span></td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($item->NgayBaoCao)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-danger fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAnTaiLieu_{{ $item->BaoCaoID }}">
                                                Ẩn tài liệu
                                            </button>

                                            <form action="{{ route('giangvien.kiemduyet.boQuaBaoCao') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->BaoCaoID }}" />
                                                <button type="submit" class="btn btn-sm btn-outline-secondary fw-bold shadow-sm">Bỏ qua</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <div class="modal fade" id="modalAnTaiLieu_{{ $item->BaoCaoID }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 rounded-4 shadow">
                                            <div class="modal-header bg-danger text-white border-bottom-0 py-3 px-4">
                                                <h5 class="modal-title fw-bold">⚠️ Xác nhận Ẩn Tài Liệu</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-4 text-start">
                                                <p class="text-dark fs-6 mb-3">Bạn có chắc chắn muốn ẩn tài liệu <strong class="text-danger">{{ $item->TaiLieu?->TenTaiLieu }}</strong> khỏi hệ thống không?</p>
                                                <div class="p-3 bg-light rounded-3 small text-muted border border-light">
                                                    Hành động này sẽ chuyển trạng thái tài liệu thành bị chặn, khiến sinh viên không thể nhìn thấy hay tải xuống tài liệu này nữa để đảm bảo an toàn nội dung.
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 p-3 pt-0 px-4">
                                                <button type="button" class="btn btn-outline-secondary fw-bold rounded-pill px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                                                <form action="{{ route('giangvien.kiemduyet.xoaTaiLieuViPham') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->BaoCaoID }}" />
                                                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4 shadow-sm">Xác nhận Ẩn</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <tr><td colspan="5" class="text-center py-5 text-success fw-bold fst-italic"><i class="fa-solid fa-heart-circle-check me-2 fs-5"></i>Không có tài liệu nào bị báo cáo vi phạm.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 border-top border-warning border-4 mb-5 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="mb-0 text-warning fw-bold"><i class="fa-solid fa-robot me-2"></i>3. Bình Luận Bị AI Cảnh Báo</h5>
            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 shadow-sm">Chờ duyệt: {{ $binhLuanChoDuyet->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="40%">Nội dung bình luận</th>
                            <th width="20%">Tại tài liệu / Bài viết</th>
                            <th width="13%">Người đăng</th>
                            <th width="15%">Thời gian</th>
                            <th class="text-center" width="12%">Quyết định</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($binhLuanChoDuyet->count() > 0)
                            @foreach ($binhLuanChoDuyet as $item)
                                <tr>
                                    <td class="fw-bold text-dark fst-italic">"{{ $item->NoiDung }}"</td>
                                    <td>
                                        @if(!empty($item->TaiLieu?->TenTaiLieu))
                                            <span class="d-block text-truncate small text-dark fw-bold" style="max-width: 220px;" title="{{ $item->TaiLieu->TenTaiLieu }}">
                                                Tài liệu: {{ $item->TaiLieu->TenTaiLieu }}
                                            </span>
                                        @elseif(!empty($item->Review?->HocPhan?->TenHocPhan))
                                            <span class="d-block text-truncate small text-dark fw-bold" style="max-width: 220px;" title="Review môn: {{ $item->Review->HocPhan->TenHocPhan }}">
                                                Review: {{ $item->Review->HocPhan->TenHocPhan }}
                                            </span>
                                        @else
                                            <span class="text-muted small fst-italic">Không xác định</span>
                                        @endif
                                    </td>
                                    <td><span class="text-muted small fw-bold">{{ $item->NguoiDung?->HoTen }}</span></td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($item->NgayDang)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('giangvien.kiemduyet.duyetBinhLuan') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->BinhLuanID }}" />
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-2 shadow-sm">Cho phép</button>
                                            </form>
                                            <form action="{{ route('giangvien.kiemduyet.tuChoiBinhLuan') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->BinhLuanID }}" />
                                                <button type="submit" class="btn btn-sm btn-danger fw-bold px-2 shadow-sm">Chặn</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5" class="text-center py-5 text-success fw-bold fst-italic"><i class="fa-solid fa-robot me-2 fs-5"></i>Không có bình luận nào bị cảnh báo!</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 border-top border-info border-4 mb-5 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="mb-0 text-info fw-bold"><i class="fa-solid fa-star me-2"></i>4. Bài Đánh Giá Bị AI Cảnh Báo</h5>
            <span class="badge bg-info text-dark rounded-pill px-3 py-2 shadow-sm">Chờ duyệt: {{ $reviewChoDuyet->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="40%">Nội dung đánh giá</th>
                            <th width="20%">Môn học</th>
                            <th width="13%">Người đăng</th>
                            <th width="15%">Số sao</th>
                            <th class="text-center" width="12%">Quyết định</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($reviewChoDuyet->count() > 0)
                            @foreach ($reviewChoDuyet as $item)
                                <tr>
                                    <td class="fw-bold text-dark fst-italic">"{{ $item->NoiDung }}"</td>
                                    <td class="text-dark fw-bold small">{{ $item->HocPhan?->TenHocPhan }}</td>
                                    <td><span class="text-muted small fw-bold">{{ $item->NguoiDung?->HoTen }}</span></td>
                                    <td>
                                        @if($item->SoSao > 0)
                                            @for ($i = 0; $i < $item->SoSao; $i++)
                                                <span class="text-warning small">⭐</span>
                                            @endfor
                                        @else
                                            <span class="badge bg-light text-muted border border-light px-2 py-1 small fw-normal shadow-sm">Khởi tạo (0 sao)</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form action="{{ route('giangvien.kiemduyet.duyetReview') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->ReviewID }}" />
                                                <button type="submit" class="btn btn-sm btn-success fw-bold px-2 shadow-sm">Cho phép</button>
                                            </form>
                                            <form action="{{ route('giangvien.kiemduyet.tuChoiReview') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->ReviewID }}" />
                                                <button type="submit" class="btn btn-sm btn-danger fw-bold px-2 shadow-sm">Chặn</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="5" class="text-center py-5 text-success fw-bold fst-italic"><i class="fa-solid fa-star-half-stroke me-2 fs-5"></i>Không có bài đánh giá tiêu cực nào cần xử lý.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection