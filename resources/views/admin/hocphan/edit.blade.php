@extends('layouts.admin')

@section('title', 'Sửa thông tin Học Phần')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h4 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Sửa thông tin Học Phần</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.hoc-phan.update', $hocphan->HocPhanID) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group mb-4">
                            <label for="KhoaSelect" class="form-label fw-bold text-primary">Bước 1: Chọn Khoa</label>
                            <select id="KhoaSelect" class="form-select form-control-lg shadow-sm rounded-3 bg-light border-0 px-3">
                                <option value="">-- Vui lòng chọn Khoa --</option>
                                @foreach($danhSachKhoa as $khoa)
                                    <option value="{{ $khoa->KhoaID }}" {{ old('KhoaID', $khoaIdHienTai) == $khoa->KhoaID ? 'selected' : '' }}>
                                        {{ $khoa->TenKhoa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="NganhSelect" class="form-label fw-bold text-success">Bước 2: Chọn Ngành <span class="text-danger">*</span></label>
                            <select name="NganhID" id="NganhSelect" class="form-select form-control-lg shadow-sm rounded-3 bg-light border-2 border-success px-3" required>
                                <option value="">-- Chọn Ngành đào tạo --</option>
                                @foreach($danhSachNganh as $nganh)
                                    <option value="{{ $nganh->NganhID }}" {{ old('NganhID', $hocphan->NganhID) == $nganh->NganhID ? 'selected' : '' }}>
                                        {{ $nganh->TenNganh }}
                                    </option>
                                @endforeach
                            </select>
                            @error('NganhID')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="TenHocPhan" class="form-label fw-bold text-dark">Tên Học Phần <span class="text-danger">*</span></label>
                            <input type="text" id="TenHocPhan" name="TenHocPhan" value="{{ old('TenHocPhan', $hocphan->TenHocPhan) }}" class="form-control form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" required />
                            @error('TenHocPhan')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-5">
                            <label for="MoTa" class="form-label fw-bold text-dark">Mô tả chi tiết</label>
                            <textarea id="MoTa" name="MoTa" class="form-control shadow-sm rounded-3 bg-light border-0 px-3 py-2" rows="4">{{ old('MoTa', $hocphan->MoTa) }}</textarea>
                        </div>

                        <hr class="text-muted opacity-25 mb-4" />

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.hoc-phan.index') }}" class="btn btn-outline-secondary fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Lắng nghe sự kiện người dùng thay đổi lựa chọn ở ô chọn Khoa
        $('#KhoaSelect').change(function () {
            var khoaId = $(this).val();
            var nganhSelect = $('#NganhSelect');

            nganhSelect.empty().prop('disabled', true);

            if (khoaId) {
                // Gọi AJAX lấy danh sách Ngành tương ứng với Khoa mới
                $.ajax({
                    url: '{{ route("admin.hoc-phan.getNganh") }}',
                    type: 'GET',
                    data: { khoaId: khoaId },
                    success: function (data) {
                        nganhSelect.append($('<option/>', {
                            value: "",
                            text: "-- Chọn Ngành đào tạo --"
                        }));
                        
                        $.each(data, function (index, item) {
                            nganhSelect.append($('<option/>', {
                                value: item.NganhID,
                                text: item.TenNganh
                            }));
                        });
                        
                        nganhSelect.prop('disabled', false);
                    }
                });
            } else {
                nganhSelect.append($('<option/>', {
                    value: "",
                    text: "-- Vui lòng chọn Khoa trước --"
                }));
            }
        });
    });
</script>
@endpush