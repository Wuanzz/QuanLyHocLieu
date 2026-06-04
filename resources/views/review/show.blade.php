@extends('layouts.app')

@section('title', 'Chi tiết Đánh giá')

@section('content')
<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
        <h2 class="fw-bold text-primary mb-0"><i class="fa-solid fa-star text-warning me-3"></i>Chi tiết Đánh giá</h2>
        <a href="{{ route('review.index') }}" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="card shadow border-0 rounded-4 mb-5">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-4 gap-3">
                <div>
                    <h3 class="fw-bold text-success mb-3"><i class="fa-solid fa-book-open me-2"></i>Môn: {{ $review->HocPhan?->TenHocPhan }}</h3>
                    <div class="d-flex align-items-center text-muted small">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <span class="fw-bold text-dark me-3">{{ $review->NguoiDung?->HoTen ?? 'Ẩn danh' }}</span>
                        <span class="bg-light px-2 py-1 rounded-pill"><i class="fa-regular fa-calendar-days me-1"></i>{{ \Carbon\Carbon::parse($review->NgayDang)->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="bg-warning bg-opacity-10 px-3 py-2 rounded-pill shadow-sm fs-5 text-nowrap text-warning fw-bold">
                    ⭐ <span id="diem-trung-binh">{{ $review->sao_trung_binh }}</span> (<span id="luot-vote">{{ $review->danhGias->count() }}</span> lượt vote)
                </div>
            </div>

            <hr class="text-muted opacity-25 mb-4" />

            <p class="fs-5 lh-lg text-dark mb-4" style="white-space: pre-line;">{{ $review->NoiDung }}</p>

            <div class="p-3 bg-light rounded-4 d-inline-block border border-white shadow-sm">
                <span class="fw-bold text-dark me-3 small text-uppercase">Chấm điểm độ hữu ích:</span>
                
                <div class="d-inline-flex gap-1 fs-4" id="star-rating-box" style="cursor: pointer;" data-user-vote="{{ $userVote ?? 0 }}">
                    @php $voteHienTai = $userVote ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $i <= $voteHienTai ? 'fa-solid' : 'fa-regular' }} fa-star text-warning" data-value="{{ $i }}"></i>
                    @endfor
                </div>
                <span id="vote-status-text" class="ms-2 small text-muted fst-italic"></span>
            </div>
        </div>
    </div>

    @if (session('ThongBaoBinhLuan'))
        @php
            $msg = session('ThongBaoBinhLuan');
            $alertClass = 'bg-success bg-opacity-10 text-success';
            $iconClass = 'fa-circle-check';
            $titleMsg = 'Thành công';

            if (str_contains(strtolower($msg), 'vi phạm') || str_contains(strtolower($msg), 'chặn')) {
                $alertClass = 'bg-danger bg-opacity-10 text-danger';
                $iconClass = 'fa-circle-xmark';
                $titleMsg = 'Bị chặn';
            } elseif (str_contains(strtolower($msg), 'kiểm duyệt')) {
                $alertClass = 'bg-warning bg-opacity-10 text-warning-emphasis';
                $iconClass = 'fa-circle-exclamation';
                $titleMsg = 'Chờ duyệt';
            }
        @endphp

        <div class="alert {{ $alertClass }} alert-dismissible fade show shadow-sm rounded-4 border-0 d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid {{ $iconClass }} fs-4 me-3"></i>
            <div>
                <strong class="fw-bold">{{ $titleMsg }}:</strong> {{ $msg }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary bg-opacity-10 border-0 py-3 px-4">
            <h5 class="mb-0 fw-bold text-primary"><i class="fa-regular fa-comments me-2"></i>Thảo luận ({{ $danhSachBinhLuan->count() }} bình luận)</h5>
        </div>
        <div class="card-body bg-white rounded-bottom-4 p-4 p-md-5">

            <form action="{{ route('review.addComment') }}" method="POST" class="mb-5">
                @csrf
                <input type="hidden" name="ReviewID" value="{{ $review->ReviewID }}" />
                <div class="d-flex gap-2">
                    <div class="position-relative flex-grow-1">
                        <i class="fa-regular fa-comment-dots position-absolute text-muted" style="top: 50%; left: 18px; transform: translateY(-50%); z-index: 10;"></i>
                        <input type="text" name="NoiDung" class="form-control form-control-lg bg-light border-0 shadow-sm rounded-pill text-dark w-100" style="padding-left: 3.5rem !important;" placeholder="Bạn có đồng ý với đánh giá này không?..." required />
                    </div>
                    <button type="submit" class="btn btn-primary text-white fw-bold rounded-pill px-4 shadow-sm flex-shrink-0">
                        <i class="fa-solid fa-paper-plane text-white me-2"></i>Gửi
                    </button>
                </div>
            </form>

            <div class="comment-section">
                @php $binhLuanGoc = $danhSachBinhLuan->whereNull('ParentID'); @endphp

                @if ($binhLuanGoc->count() > 0)
                    @foreach ($binhLuanGoc as $cmt)
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px;">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <div class="bg-light p-3 rounded-4 shadow-sm border border-white d-inline-block w-100">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <strong class="text-primary small">{{ $cmt->NguoiDung?->HoTen ?? 'Ẩn danh' }}</strong>
                                        <small class="text-muted" style="font-size: 0.75rem;"><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($cmt->NgayDang)->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-0 text-dark">{{ $cmt->NoiDung }}</p>
                                </div>

                                <button class="btn btn-sm btn-link text-decoration-none p-0 fw-bold text-primary mt-2 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#replyForm-{{ $cmt->BinhLuanID }}">
                                    <i class="fa-solid fa-reply me-1"></i>Trả lời
                                </button>

                                <div class="collapse mt-2" id="replyForm-{{ $cmt->BinhLuanID }}">
                                    <form action="{{ route('review.addComment') }}" method="POST" class="d-flex gap-2 mt-2">
                                        @csrf
                                        <input type="hidden" name="ReviewID" value="{{ $review->ReviewID }}" />
                                        <input type="hidden" name="ParentID" value="{{ $cmt->BinhLuanID }}" />
                                        <div class="position-relative flex-grow-1">
                                            <i class="fa-solid fa-reply position-absolute text-muted" style="top: 50%; left: 16px; transform: translateY(-50%); z-index: 10;"></i>
                                            <input type="text" name="NoiDung" class="form-control bg-light border-0 shadow-sm rounded-pill text-dark w-100" style="padding-left: 3rem !important;" placeholder="Viết phản hồi..." required />
                                        </div>
                                        <button type="submit" class="btn btn-primary text-white btn-sm fw-bold rounded-pill px-3 shadow-sm">Gửi</button>
                                    </form>
                                </div>

                                @php $cacReply = $danhSachBinhLuan->where('ParentID', $cmt->BinhLuanID); @endphp
                                @if ($cacReply->count() > 0)
                                    <div class="mt-3 ps-4 ms-2 border-start border-3 border-light">
                                        @foreach ($cacReply as $reply)
                                            <div class="d-flex mb-3">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                        <i class="fa-solid fa-user small"></i>
                                                    </div>
                                                </div>
                                                <div class="bg-white p-3 rounded-4 shadow-sm border border-light flex-grow-1">
                                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                                        <strong class="text-dark small">{{ $reply->NguoiDung?->HoTen ?? 'Ẩn danh' }}</strong>
                                                        <small class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($reply->NgayDang)->format('d/m/Y H:i') }}</small>
                                                    </div>
                                                    <p class="mb-0 text-dark small">{{ $reply->NoiDung }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fa-regular fa-comments fs-1 d-block mb-3 opacity-50"></i>
                        <h6 class="fw-bold">Chưa có bình luận nào.</h6>
                        <p class="small fst-italic">Hãy là người đầu tiên tham gia thảo luận về môn học này!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let currentVote = parseInt($('#star-rating-box').data('user-vote')) || 0;

        $('#star-rating-box i').hover(function() {
            let val = $(this).data('value');
            $('#star-rating-box i').each(function() {
                if ($(this).data('value') <= val) {
                    $(this).removeClass('fa-regular').addClass('fa-solid');
                } else {
                    $(this).removeClass('fa-solid').addClass('fa-regular');
                }
            });
        }, function() {
            $('#star-rating-box i').each(function() {
                if ($(this).data('value') <= currentVote) {
                    $(this).removeClass('fa-regular').addClass('fa-solid');
                } else {
                    $(this).removeClass('fa-solid').addClass('fa-regular');
                }
            });
        });

        $('#star-rating-box i').click(function() {
            let soSao = $(this).data('value');
            let reviewId = '{{ $review->ReviewID }}';

            $.ajax({
                url: '{{ route("review.rate") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ReviewID: reviewId,
                    SoSao: soSao
                },
                success: function(response) {
                    $('#diem-trung-binh').text(response.saoTrungBinh);
                    $('#luot-vote').text(response.luotVote);
                    
                    $('#vote-status-text').text(response.success).removeClass('text-danger').addClass('text-success');
                    
                    currentVote = soSao; 
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON ? xhr.responseJSON.error : 'Có lỗi xảy ra.';
                    $('#vote-status-text').text(errorMsg).removeClass('text-success').addClass('text-danger');
                }
            });
        });
    });
</script>
@endpush