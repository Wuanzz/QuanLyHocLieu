@extends('layouts.app')

@section('title', 'Cộng đồng Review')

@section('content')
<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
        <h2 class="fw-bold text-primary mb-0"><i class="fa-solid fa-star text-warning me-2"></i>Cộng đồng Review Học Phần</h2>

        <a class="btn btn-primary text-white fw-bold rounded-pill px-4 shadow-sm" href="{{ route('review.create') }}">
            <i class="fa-solid fa-pen-nib text-white me-2"></i>Viết Review Mới
        </a>
    </div>

    @if (session('success'))
        <div class="alert bg-success bg-opacity-10 text-success alert-dismissible fade show shadow-sm rounded-4 border-0 d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-circle-check fs-4 me-3"></i>
            <div>
                <strong class="fw-bold">Thành công:</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('info'))
        <div class="alert bg-info bg-opacity-10 text-info alert-dismissible fade show shadow-sm rounded-4 border-0 d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-circle-info fs-4 me-3"></i>
            <div>
                <strong class="fw-bold">Thông báo:</strong> {{ session('info') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert bg-danger bg-opacity-10 text-danger alert-dismissible fade show shadow-sm rounded-4 border-0 d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-circle-xmark fs-4 me-3"></i>
            <div>
                <strong class="fw-bold">Bị chặn:</strong> {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4 bg-light mb-5">
        <div class="card-body p-4">
            <form id="formTimKiem" action="{{ route('review.index') }}" method="GET" onsubmit="return false;">
                <div class="row g-3 align-items-center">

                    <div class="col-md-4">
                        <div class="position-relative">
                            <i class="fa-solid fa-magnifying-glass position-absolute text-primary" style="top: 50%; left: 18px; transform: translateY(-50%); z-index: 10;"></i>
                            <input type="text" id="timKiem" name="timKiem" class="form-control form-control-lg border-0 shadow-sm rounded-pill text-dark" style="padding-left: 3rem !important;" placeholder="Nhập tên môn học, nội dung review..." value="{{ $timKiem ?? '' }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <select id="locHocPhan" name="locHocPhan" class="form-select form-select-lg border-0 shadow-sm rounded-pill text-dark">
                            <option value="" class="text-muted">-- Tất cả môn học --</option>
                            @foreach ($danhSachHocPhan as $hp)
                                <option value="{{ $hp->HocPhanID }}" {{ (isset($locHocPhan) && $locHocPhan == $hp->HocPhanID) ? 'selected' : '' }}>
                                    {{ $hp->TenHocPhan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select id="locSao" name="locSao" class="form-select form-select-lg border-0 shadow-sm rounded-pill text-dark">
                            <option value="" class="text-muted">-- Đánh giá --</option>
                            <option value="5" {{ (isset($locSao) && $locSao == '5') ? 'selected' : '' }}>⭐ 5 Sao</option>
                            <option value="4" {{ (isset($locSao) && $locSao == '4') ? 'selected' : '' }}>⭐ Từ 4 Sao</option>
                            <option value="3" {{ (isset($locSao) && $locSao == '3') ? 'selected' : '' }}>⭐ Từ 3 Sao</option>
                            <option value="2" {{ (isset($locSao) && $locSao == '2') ? 'selected' : '' }}>⭐ Từ 2 Sao</option>
                            <option value="1" {{ (isset($locSao) && $locSao == '1') ? 'selected' : '' }}>⭐ Từ 1 Sao</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <a href="{{ route('review.index') }}" class="btn btn-outline-secondary text-dark fw-bold rounded-pill shadow-sm w-100" title="Xóa bộ lọc">
                            <i class="fa-solid fa-rotate-right me-2"></i>Làm mới
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div id="khu-vuc-du-lieu">
        @if ($reviews->count() > 0)
            <div class="row g-4">
                @foreach ($reviews as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100 border-0 rounded-4 position-relative" style="transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">

                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title text-success fw-bold mb-0 lh-base pe-2">
                                        <i class="fa-solid fa-book-open me-1"></i> {{ $item->HocPhan?->TenHocPhan ?? 'Môn học ẩn' }}
                                    </h5>
                                    <div class="bg-warning bg-opacity-10 px-2 py-1 rounded-pill flex-shrink-0 text-nowrap shadow-sm text-warning fw-bold" style="font-size: 0.85rem;">
                                        ⭐ {{ $item->sao_trung_binh }} ({{ $item->danhGias->count() }} vote)
                                    </div>
                                </div>

                                <p class="card-text text-dark opacity-75 mb-4 flex-grow-1" style="display: -webkit-box; display: box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    "{{ $item->NoiDung }}"
                                </p>
                            </div>

                            <div class="card-footer bg-light border-0 py-3 px-4 rounded-bottom-4 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                        <i class="fa-solid fa-user-graduate small"></i>
                                    </div>
                                    <div>
                                        <small class="d-block fw-bold text-dark">{{ $item->NguoiDung?->HoTen ?? 'Ẩn danh' }}</small>
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-regular fa-calendar-days me-1"></i>{{ \Carbon\Carbon::parse($item->NgayDang)->format('d/m/Y') }}</small>
                                    </div>
                                </div>

                                <a href="{{ route('review.show', $item->ReviewID) }}" class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3 stretched-link">
                                    Chi tiết
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            @if ($reviews->lastPage() > 1)
                <nav aria-label="Page navigation" class="d-flex justify-content-center mt-5 mb-2">
                    <ul class="pagination shadow-sm rounded-pill overflow-hidden mb-0">
                        <li class="page-item {{ $reviews->currentPage() == 1 ? 'disabled' : '' }}">
                            <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $reviews->url($reviews->currentPage() - 1) }}">&laquo; Trước</a>
                        </li>
                        
                        @for ($i = 1; $i <= $reviews->lastPage(); $i++)
                            @if ($i == 1 || $i == $reviews->lastPage() || abs($i - $reviews->currentPage()) <= 1)
                                <li class="page-item {{ $i == $reviews->currentPage() ? 'active' : '' }}">
                                    <a class="page-link fw-bold border-0 py-2 px-3 {{ $i == $reviews->currentPage() ? 'bg-primary text-white' : 'text-primary' }}" href="{{ $reviews->url($i) }}">{{ $i }}</a>
                                </li>
                            @elseif ($i == 2 && $reviews->currentPage() > 3)
                                <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                            @elseif ($i == $reviews->lastPage() - 1 && $reviews->currentPage() < $reviews->lastPage() - 2)
                                <li class="page-item disabled"><span class="page-link text-muted fw-bold border-0 py-2 px-3">...</span></li>
                            @endif
                        @endfor
                        
                        <li class="page-item {{ $reviews->currentPage() == $reviews->lastPage() ? 'disabled' : '' }}">
                            <a class="page-link fw-bold text-primary border-0 py-2 px-3" href="{{ $reviews->url($reviews->currentPage() + 1) }}">Sau &raquo;</a>
                        </li>
                    </ul>
                </nav>
            @endif

        @else
            <div class="card border-0 shadow-sm rounded-4 bg-light py-5 text-center">
                <div class="card-body">
                    <i class="fa-solid fa-comment-slash fs-1 text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">Chưa có bài đánh giá nào phù hợp!</h5>
                    <p class="text-muted">Hãy thử thay đổi tiêu chí bộ lọc hoặc trở thành người đầu tiên review môn học này nhé.</p>
                </div>
            </div>
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
            let locSao = $('#locSao').val();
            let url = '{{ route("review.index") }}';

            $('.fa-magnifying-glass').removeClass('fa-magnifying-glass').addClass('fa-spinner fa-spin');

            $.get(url, { timKiem: timKiem, locHocPhan: locHocPhan, locSao: locSao }, function(data) {
                $('#khu-vuc-du-lieu').html($(data).find('#khu-vuc-du-lieu').html());
                $('.fa-spinner').removeClass('fa-spinner fa-spin').addClass('fa-magnifying-glass');
                window.history.pushState({}, '', url + '?timKiem=' + timKiem + '&locHocPhan=' + locHocPhan + '&locSao=' + locSao);
            });
        }

        $('#timKiem').on('keyup', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(liveSearch, 400);
        });

        $('#locHocPhan, #locSao').on('change', function() {
            liveSearch();
        });
    });
</script>
@endpush