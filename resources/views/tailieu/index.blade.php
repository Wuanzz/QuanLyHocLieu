@extends('layouts.app')

@section('title', 'Quản lý Tài Liệu')

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary mb-0"><i class="fa-solid fa-folder-open text-primary me-2"></i>Kho Tài Liệu</h2>

        <a class="btn btn-primary text-white fw-bold rounded-pill px-4 shadow-sm" href="{{ url('kho-tai-lieu/them-moi') }}">
            <i class="fa-solid fa-cloud-arrow-up me-2 text-white"></i>Upload Tài Liệu
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-circle-info me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 bg-light mb-4">
        <div class="card-body p-4">
            <form id="formTimKiem" action="{{ route('tailieu.index') }}" method="GET" onsubmit="return false;">
                <div class="row g-3 align-items-center">

                    <div class="col-md-6">
                        <div class="position-relative">
                            <i class="fa-solid fa-magnifying-glass position-absolute text-primary" style="top: 50%; left: 18px; transform: translateY(-50%); z-index: 10;"></i>
                            <input type="text" id="timKiem" name="timKiem" class="form-control form-control-lg border-0 shadow-sm rounded-pill text-dark" style="padding-left: 3rem !important;" placeholder="Nhập tên tài liệu, đề thi, giáo trình..." value="{{ $timKiem }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <select id="locHocPhan" name="locHocPhan" class="form-select form-select-lg border-0 shadow-sm rounded-pill text-dark">
                            <option value="" class="text-muted">-- Tất cả các môn học --</option>
                            @foreach ($danhSachHocPhan as $hp)
                                <option value="{{ $hp->HocPhanID }}" {{ $locHocPhan == $hp->HocPhanID ? 'selected' : '' }}>
                                    {{ $hp->TenHocPhan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2" id="khu-vuc-nut-bam">
                        <a href="{{ route('tailieu.index') }}" class="btn btn-outline-secondary text-dark fw-bold rounded-pill shadow-sm w-100" title="Xóa bộ lọc">
                            <i class="fa-solid fa-rotate-right me-2"></i>Làm mới
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div id="khu-vuc-du-lieu">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="text-primary fw-bold py-3 ps-4 border-0">Tên Tài Liệu</th>
                            <th class="text-primary fw-bold py-3 border-0">Môn Học</th>
                            <th class="text-primary fw-bold py-3 border-0">Loại</th>
                            <th class="text-primary fw-bold py-3 border-0">Người Up</th>
                            <th class="text-primary fw-bold py-3 border-0">Dung lượng</th>
                            <th class="text-primary fw-bold py-3 pe-4 border-0 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @if ($taiLieus->count() > 0)
                            @foreach ($taiLieus as $item)
                                <tr class="border-bottom border-light">
                                    <td class="fw-bold text-dark ps-4 py-3">{{ $item->TenTaiLieu }}</td>
                                    <td class="text-success fw-semibold">{{ $item->HocPhan?->TenHocPhan }}</td>

                                    <td>
                                        @if ($item->LoaiTaiLieu == "Slide")
                                            <span class="badge bg-white border border-primary px-3 py-2 rounded-pill shadow-sm" style="color: #0d6efd !important;">
                                                <i class="fa-solid fa-desktop me-1"></i> Slide
                                            </span>
                                        @elseif ($item->LoaiTaiLieu == "DeThi")
                                            <span class="badge bg-white border border-danger px-3 py-2 rounded-pill shadow-sm" style="color: #dc3545 !important;">
                                                <i class="fa-solid fa-file-pen me-1"></i> Đề thi
                                            </span>
                                        @else
                                            <span class="badge bg-white border border-success px-3 py-2 rounded-pill shadow-sm" style="color: #198754 !important;">
                                                <i class="fa-solid fa-book-open me-1"></i> Tham khảo
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-dark"><i class="fa-regular fa-circle-user text-muted me-1"></i>{{ $item->NguoiDung?->HoTen ?? 'Ẩn danh' }}</td>
                                    <td class="text-muted small fw-semibold">{{ $item->KichThuoc }} MB</td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-sm btn-primary text-white fw-bold rounded-pill px-3 shadow-sm" href="{{ url('kho-tai-lieu/chi-tiet/' . $item->TaiLieuID) }}"><i class="fa-regular fa-eye text-white me-1"></i>Xem</a>
                                            <a class="btn btn-sm btn-primary text-white fw-bold rounded-pill px-3 shadow-sm" href="{{ url('kho-tai-lieu/tai-ve/' . $item->TaiLieuID) }}"><i class="fa-solid fa-download text-white me-1"></i>({{ $item->LuotTai }})</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted fst-italic">
                                    <i class="fa-solid fa-folder-open fs-1 d-block mb-3 text-secondary opacity-50"></i>
                                    Chưa có tài liệu nào phù hợp với tìm kiếm của bạn.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if ($taiLieus->lastPage() > 1)
            <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4 mb-5">
                <ul class="pagination shadow-sm rounded-pill overflow-hidden mb-0">
                    <li class="page-item {{ $taiLieus->currentPage() == 1 ? 'disabled' : '' }}">
                        <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $taiLieus->url($taiLieus->currentPage() - 1) }}">&laquo; Trước</a>
                    </li>
                    
                    @for ($i = 1; $i <= $taiLieus->lastPage(); $i++)
                        @if ($i == 1 || $i == $taiLieus->lastPage() || abs($i - $taiLieus->currentPage()) <= 1)
                            <li class="page-item {{ $i == $taiLieus->currentPage() ? 'active' : '' }}">
                                <a class="page-link fw-bold border-0 py-2 px-3 {{ $i == $taiLieus->currentPage() ? 'bg-primary text-white' : 'text-primary' }}" href="{{ $taiLieus->url($i) }}">{{ $i }}</a>
                            </li>
                        @elseif ($i == 2 && $taiLieus->currentPage() > 3)
                            <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                        @elseif ($i == $taiLieus->lastPage() - 1 && $taiLieus->currentPage() < $taiLieus->lastPage() - 2)
                            <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                        @endif
                    @endfor
                    
                    <li class="page-item {{ $taiLieus->currentPage() == $taiLieus->lastPage() ? 'disabled' : '' }}">
                        <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $taiLieus->url($taiLieus->currentPage() + 1) }}">Sau &raquo;</a>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let debounceTimer;

        function liveSearch() {
            let timKiem = $('#timKiem').val();
            let locHocPhan = $('#locHocPhan').val();
            let url = '{{ route("tailieu.index") }}';

            $('.fa-magnifying-glass').removeClass('fa-magnifying-glass').addClass('fa-spinner fa-spin');

            $.get(url, { timKiem: timKiem, locHocPhan: locHocPhan }, function(data) {
                $('#khu-vuc-du-lieu').html($(data).find('#khu-vuc-du-lieu').html());
                
                $('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-magnifying-glass');
                
                window.history.pushState({}, '', url + '?timKiem=' + timKiem + '&locHocPhan=' + locHocPhan);
            });
        }

        $('#timKiem').on('keyup', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(liveSearch, 400);
        });

        $('#locHocPhan').on('change', function() {
            liveSearch();
        });
    });
</script>
@endpush