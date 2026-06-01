@extends('layouts.admin')

@section('title', 'Thêm Học Phần')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h4 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-plus text-primary me-2"></i>Thêm Học Phần Mới</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.hoc-phan.store') }}" method="POST">
                        @csrf
                        
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
                                    <option value="{{ $khoa->KhoaID }}">{{ $khoa->TenKhoa }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="NganhSelect" class="form-label fw-bold text-success">Bước 2: Chọn Ngành <span class="text-danger">*</span></label>
                            <select name="NganhID" id="NganhSelect" class="form-select form-control-lg shadow-sm rounded-3 bg-light border-2 border-success px-3" required disabled>
                                <option value="">-- Vui lòng chọn Khoa trước --</option>
                            </select>
                            @error('NganhID')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="TenHocPhan" class="form-label fw-bold text-dark">Tên Học Phần <span class="text-danger">*</span></label>
                            <input type="text" id="TenHocPhan" name="TenHocPhan" value="{{ old('TenHocPhan') }}" class="form-control form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" placeholder="Ví dụ: Lập trình Web ASP.NET Core..." required />
                            @error('TenHocPhan')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-5">
                            <label for="MoTa" class="form-label fw-bold text-dark">Mô tả</label>
                            <textarea id="MoTa" name="MoTa" class="form-control shadow-sm rounded-3 bg-light border-0 px-3 py-2" rows="4" placeholder="Nhập mô tả tóm tắt nội dung môn học...">{{ old('MoTa') }}</textarea>
                        </div>

                        <hr class="text-muted opacity-25 mb-4" />

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu dữ liệu
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

            // Xóa rỗng dữ liệu cũ và vô hiệu hóa tạm thời ô chọn Ngành
            nganhSelect.empty().prop('disabled', true);

            if (khoaId) {
                // Thực hiện gọi AJAX nạp dữ liệu động từ Route API của Laravel
                $.ajax({
                    url: '{{ route("admin.hoc-phan.getNganh") }}',
                    type: 'GET',
                    data: { khoaId: khoaId },
                    success: function (data) {
                        // Thêm dòng mặc định đầu tiên
                        nganhSelect.append($('<option/>', {
                            value: "",
                            text: "-- Chọn Ngành đào tạo --"
                        }));
                        
                        // Duyệt mảng JSON đổ dữ liệu vào ô chọn Ngành
                        $.each(data, function (index, item) {
                            nganhSelect.append($('<option/>', {
                                value: item.NganhID,
                                text: item.TenNganh
                            }));
                        });
                        
                        // Mở khóa cho phép người dùng tương tác chọn ngành
                        nganhSelect.prop('disabled', false);
                    }
                });
            } else {
                // Nếu chọn lại giá trị mặc định trống thì reset trạng thái ban đầu
                nganhSelect.append($('<option/>', {
                    value: "",
                    text: "-- Vui lòng chọn Khoa trước --"
                }));
            }
        });
    });
</script>
@endpush