@extends('layouts.app')

@section('title', 'Viết Review Học Phần')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary"><i class="fa-solid fa-pen-nib text-primary me-2"></i>Viết Đánh Giá Môn Học</h2>
                <p class="text-muted">Chia sẻ kinh nghiệm và trải nghiệm của bạn để giúp đỡ các hậu bối nhé!</p>
            </div>

            <div class="card shadow border-0 rounded-4 p-4 p-md-5 bg-white">
                <form action="{{ route('review.store') }}" method="POST">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 bg-danger bg-opacity-10 text-danger border-0">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card bg-primary bg-opacity-10 border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-list-check text-primary me-2"></i>Chọn môn học cần đánh giá</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-dark">1. Khoa</label>
                                    <select id="KhoaID" class="form-select border-0 shadow-sm text-dark rounded-3">
                                        <option value="" class="text-muted">-- Chọn Khoa --</option>
                                        @foreach($danhSachKhoa as $khoa)
                                            <option value="{{ $khoa->KhoaID }}">{{ $khoa->TenKhoa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-dark">2. Ngành</label>
                                    <select id="NganhID" class="form-select border-0 shadow-sm text-dark rounded-3" disabled>
                                        <option value="" class="text-muted">-- Đợi chọn Khoa --</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-dark">3. Học Phần <span class="text-danger">*</span></label>
                                    <select name="HocPhanID" id="HocPhanID" class="form-select border-2 border-primary shadow-sm text-dark rounded-3" required disabled>
                                        <option value="" class="text-muted">-- Đợi chọn Ngành --</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <label for="NoiDung" class="form-label fw-bold text-dark">Nội dung Review <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <i class="fa-solid fa-comment-dots position-absolute text-muted" style="top: 16px; left: 18px; z-index: 10;"></i>
                            <textarea name="NoiDung" id="NoiDung" class="form-control bg-light border-0 shadow-sm rounded-4 text-dark" rows="6" style="padding-left: 3rem !important; padding-top: 12px;" placeholder="Chia sẻ kinh nghiệm học tập, thầy cô, cách thi cử của môn này nhé..." required>{{ old('NoiDung') }}</textarea>
                        </div>
                        @error('NoiDung')
                            <span class="text-danger small fw-semibold mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr class="text-muted mb-4 opacity-25" />

                    <div class="d-flex justify-content-end gap-3">
                        <a class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm" href="{{ route('review.index') }}">
                            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary text-white fw-bold border-0 rounded-pill px-5 shadow-sm">
                            <i class="fa-solid fa-paper-plane text-white me-2"></i>Đăng Review
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // KHI NGƯỜI DÙNG THAY ĐỔI KHOA
        $('#KhoaID').change(function () {
            var khoaId = $(this).val();

            $('#NganhID').empty().append('<option value="" class="text-muted">-- Chọn Ngành --</option>').prop('disabled', true);
            $('#HocPhanID').empty().append('<option value="" class="text-muted">-- Vui lòng chọn Ngành trước --</option>').prop('disabled', true);

            if (khoaId) {
                $.get('{{ route("api.review.getNganh") }}', { khoaId: khoaId }, function (data) {
                    $.each(data, function (i, item) {
                        $('#NganhID').append('<option value="' + item.NganhID + '">' + item.TenNganh + '</option>');
                    });
                    $('#NganhID').prop('disabled', false);
                });
            }
        });

        // KHI NGƯỜI DÙNG THAY ĐỔI NGÀNH
        $('#NganhID').change(function () {
            var nganhId = $(this).val();

            $('#HocPhanID').empty().append('<option value="" class="text-muted">-- Chọn Học Phần --</option>').prop('disabled', true);

            if (nganhId) {
                $.get('{{ route("api.review.getHocPhan") }}', { nganhId: nganhId }, function (data) {
                    $.each(data, function (i, item) {
                        $('#HocPhanID').append('<option value="' + item.HocPhanID + '">' + item.TenHocPhan + '</option>');
                    });
                    $('#HocPhanID').prop('disabled', false);
                });
            }
        });
    });
</script>
@endpush