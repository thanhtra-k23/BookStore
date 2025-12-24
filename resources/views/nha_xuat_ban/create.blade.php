@extends('layouts.app')

@section('title', 'Thêm nhà xuất bản mới')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>Thêm nhà xuất bản mới
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.nhaxuatban.index') }}">Nhà xuất bản</a></li>
                    <li class="breadcrumb-item active">Thêm mới</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.nhaxuatban.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin nhà xuất bản</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.nhaxuatban.store') }}" method="POST" enctype="multipart/form-data" id="publisherForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="ten_nxb" class="form-label">
                                    Tên nhà xuất bản <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ten_nxb') is-invalid @enderror" 
                                       id="ten_nxb" name="ten_nxb" value="{{ old('ten_nxb') }}" 
                                       placeholder="Nhập tên nhà xuất bản" required>
                                @error('ten_nxb')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="nam_thanh_lap" class="form-label">Năm thành lập</label>
                                <input type="number" class="form-control @error('nam_thanh_lap') is-invalid @enderror" 
                                       id="nam_thanh_lap" name="nam_thanh_lap" value="{{ old('nam_thanh_lap') }}" 
                                       min="1800" max="{{ date('Y') }}" placeholder="Năm thành lập">
                                @error('nam_thanh_lap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="dia_chi" class="form-label">
                                Địa chỉ <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('dia_chi') is-invalid @enderror" 
                                      id="dia_chi" name="dia_chi" rows="3" 
                                      placeholder="Nhập địa chỉ nhà xuất bản" required>{{ old('dia_chi') }}</textarea>
                            @error('dia_chi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                       id="so_dien_thoai" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" 
                                       placeholder="Số điện thoại">
                                @error('so_dien_thoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="Email liên hệ">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="quoc_gia" class="form-label">Quốc gia</label>
                                <input type="text" class="form-control @error('quoc_gia') is-invalid @enderror" 
                                       id="quoc_gia" name="quoc_gia" value="{{ old('quoc_gia', 'Việt Nam') }}" 
                                       placeholder="Quốc gia">
                                @error('quoc_gia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                   id="website" name="website" value="{{ old('website') }}" 
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="4" 
                                      placeholder="Mô tả về nhà xuất bản">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                       id="logo" name="logo" accept="image/*">
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Chấp nhận: JPG, PNG, GIF. Tối đa 2MB.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="trang_thai" name="trang_thai" 
                                           {{ old('trang_thai', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="trang_thai">
                                        Kích hoạt nhà xuất bản
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Logo Preview -->
                        <div id="logoPreview" class="mb-3" style="display: none;">
                            <label class="form-label">Xem trước logo:</label>
                            <div>
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                <i class="fas fa-times me-1"></i>Hủy
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Lưu nhà xuất bản
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- SEO Settings -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Cài đặt SEO</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                               placeholder="Tiêu đề SEO">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" 
                                  placeholder="Mô tả SEO">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                               id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                               placeholder="Từ khóa SEO (cách nhau bởi dấu phẩy)">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Help -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Hướng dẫn</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Tên nhà xuất bản và địa chỉ là bắt buộc
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Logo nên có tỷ lệ vuông (1:1) để hiển thị tốt nhất
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Website phải bắt đầu bằng http:// hoặc https://
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Số điện thoại chỉ chấp nhận số và ký tự đặc biệt
                        </li>
                        <li>
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Cài đặt SEO giúp tối ưu hóa công cụ tìm kiếm
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-label {
    font-weight: 600;
    color: #5a5c69;
}

.card-header {
    background-color: #f8f9fc;
    border-bottom: 1px solid #e3e6f0;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

#logoPreview img {
    border: 2px solid #e3e6f0;
    border-radius: 0.35rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate SEO fields from name
    $('#ten_nxb').on('input', function() {
        const name = $(this).val();
        if (name && !$('#meta_title').val()) {
            $('#meta_title').val(`Sách từ ${name} - BookStore`);
        }
        if (name && !$('#meta_description').val()) {
            $('#meta_description').val(`Khám phá các đầu sách chất lượng từ nhà xuất bản ${name} tại BookStore`);
        }
        if (name && !$('#meta_keywords').val()) {
            $('#meta_keywords').val(`${name}, nhà xuất bản, sách, BookStore`);
        }
    });

    // Logo preview
    $('#logo').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImg').attr('src', e.target.result);
                $('#logoPreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#logoPreview').hide();
        }
    });

    // Form validation
    $('#publisherForm').submit(function(e) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = ['ten_nxb', 'dia_chi'];
        requiredFields.forEach(function(field) {
            const input = $(`#${field}`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });

        // Validate email format
        const email = $('#email').val();
        if (email && !isValidEmail(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }

        // Validate website format
        const website = $('#website').val();
        if (website && !isValidUrl(website)) {
            $('#website').addClass('is-invalid');
            isValid = false;
        }

        // Check logo file size
        const logoFile = $('#logo')[0].files[0];
        if (logoFile && logoFile.size > 2 * 1024 * 1024) { // 2MB
            $('#logo').addClass('is-invalid');
            showToast('error', 'Kích thước logo không được vượt quá 2MB');
            isValid = false;
        }

        // Validate year
        const year = $('#nam_thanh_lap').val();
        if (year && (year < 1800 || year > new Date().getFullYear())) {
            $('#nam_thanh_lap').addClass('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            showToast('error', 'Vui lòng kiểm tra lại thông tin đã nhập');
        }
    });

    // Real-time validation
    $('.form-control, .form-select').on('input change', function() {
        if ($(this).hasClass('is-invalid') && $(this).val().trim()) {
            $(this).removeClass('is-invalid');
        }
    });
});

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidUrl(url) {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

function showToast(type, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}
</script>
@endpush