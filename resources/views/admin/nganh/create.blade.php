@extends('layouts.admin')

@section('title', 'Thêm Ngành Mới')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h4 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-plus text-primary me-2"></i>Thêm Ngành Mới</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.nganh.store') }}" method="POST">
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
                            <label for="KhoaID" class="form-label fw-bold text-dark">Thuộc Khoa <span class="text-danger">*</span></label>
                            <select id="KhoaID" name="KhoaID" class="form-select form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" required>
                                <option value="">-- Chọn Khoa --</option>
                                @foreach($danhSachKhoa as $khoa)
                                    <option value="{{ $khoa->KhoaID }}" {{ old('KhoaID') == $khoa->KhoaID ? 'selected' : '' }}>
                                        {{ $khoa->TenKhoa }}
                                    </option>
                                @endforeach
                            </select>
                            @error('KhoaID')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="TenNganh" class="form-label fw-bold text-dark">Tên Ngành <span class="text-danger">*</span></label>
                            <input type="text" id="TenNganh" name="TenNganh" value="{{ old('TenNganh') }}" class="form-control form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" placeholder="Nhập tên ngành..." required />
                            @error('TenNganh')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-5">
                            <label for="MoTa" class="form-label fw-bold text-dark">Mô tả chi tiết</label>
                            <textarea id="MoTa" name="MoTa" class="form-control shadow-sm rounded-3 bg-light border-0 px-3 py-2" rows="4" placeholder="Nhập mô tả cho ngành này...">{{ old('MoTa') }}</textarea>
                        </div>

                        <hr class="text-muted opacity-25 mb-4" />

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu dữ liệu
                            </button>
                            <a href="{{ route('admin.nganh.index') }}" class="btn btn-outline-secondary fw-bold px-4 rounded-pill shadow-sm">
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