@extends('layouts.admin')

@section('title', 'Sửa thông tin Khoa')

@section('content')
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 py-3 px-4">
                    <h4 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i>Sửa thông tin Khoa</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="{{ route('admin.khoa.update', $khoa->KhoaID) }}" method="POST">
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
                            <label for="TenKhoa" class="form-label fw-bold text-dark">Tên Khoa <span class="text-danger">*</span></label>
                            <input type="text" id="TenKhoa" name="TenKhoa" value="{{ old('TenKhoa', $khoa->TenKhoa) }}" class="form-control form-control-lg shadow-sm rounded-3 bg-light border-0 px-3" required />
                            @error('TenKhoa')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-5">
                            <label for="MoTa" class="form-label fw-bold text-dark">Mô tả chi tiết</label>
                            <textarea id="MoTa" name="MoTa" class="form-control shadow-sm rounded-3 bg-light border-0 px-3 py-2" rows="5" placeholder="Nhập mô tả cho khoa này...">{{ old('MoTa', $khoa->MoTa) }}</textarea>
                            @error('MoTa')
                                <span class="text-danger small fw-bold mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <hr class="text-muted opacity-25 mb-4" />

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
                            </button>
                            <a href="{{ route('admin.khoa.index') }}" class="btn btn-outline-secondary fw-bold px-4 rounded-pill shadow-sm">
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