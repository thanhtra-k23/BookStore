@extends('layouts.app')

@section('title', 'Chỉnh sửa thể loại: ' . $theLoai->ten_the_loai)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Chỉnh sửa thể loại
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.theloai.index') }}">Thể loại</a></li>
                    <li class="breadcrumb-item active">{{ $theLoai->ten_the_loai }}</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('theloai.show', $theLoai) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-1"></i>Xem chi tiết
            </a>
            <a href="{{ route('admin.theloai.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin thể loại</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('theloai.update', $theLoai) }}" method="POST" enctype="multipart/form-data" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="ten_the_loai" class="form-label">
                                    Tên thể loại <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('ten_the_loai') is-invalid @enderror" 
                                       id="ten_the_loai" name="ten_the_loai" 
                                       value="{{ old('ten_the_loai', $theLoai->ten_the_loai) }}" 
                                       placeholder="Nhập tên thể loại" required>
                                @error('ten_the_loai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="ma_the_loai_cha" class="form-label">Thể loại cha</label>
                                <select class="form-select @error('ma_the_loai_cha') is-invalid @enderror" 
                                        id="ma_the_loai_cha" name="ma_the_loai_cha">
                                    <option value="">Chọn thể loại cha (tùy chọn)</option>
                                    @foreach($parentCategories as $parent)
                                        <option value="{{ $parent->ma_the_loai }}" 
                                                {{ old('ma_the_loai_cha', $theLoai->ma_the_loai_cha) == $parent->ma_the_loai ? 'selected' : '' }}>
                                            {{ $parent->ten_the_loai }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ma_the_loai_cha')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mo_ta" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="4" 
                                      placeholder="Nhập mô tả cho thể loại">{{ old('mo_ta', $theLoai->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hinh_anh" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control @error('hinh_anh') is-invalid @enderror" 
                                       id="hinh_anh" name="hinh_anh" accept="image/*">
                                @error('hinh_anh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Chấp nhận: JPG, PNG, GIF. Tối đa 2MB.</div>
                                
                                @if($theLoai->hinh_anh)
                                    <div class="mt-2">
                                        <label class="form-label">Hình ảnh hiện tại:</label>
                                        <div>
                                            <img src="{{ Storage::url($theLoai->hinh_anh) }}" 
                                                 alt="{{ $theLoai->ten_the_loai }}" 
                                                 class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="thu_tu_hien_thi" class="form-label">Thứ tự hiển thị</label>
                                <input type="number" class="form-control @error('thu_tu_hien_thi') is-invalid @enderror" 
                                       id="thu_tu_hien_thi" name="thu_tu_hien_thi" 
                                       value="{{ old('thu_tu_hien_thi', $theLoai->thu_tu_hien_thi) }}" min="0">
                                @error('thu_tu_hien_thi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="trang_thai" name="trang_thai" 
                                       {{ old('trang_thai', $theLoai->trang_thai) ? 'checked' : '' }}>
                                <label class="form-check-label" for="trang_thai">
                                    Kích hoạt thể loại
                                </label>
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mb-3" style="display: none;">
                            <label class="form-label">Xem trước hình ảnh mới:</label>
                            <div>
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                <i class="fas fa-times me-1"></i>Hủy
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Cập nhật thể loại
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
                               id="meta_title" name="meta_title" 
                               value="{{ old('meta_title', $theLoai->meta_title) }}" 
                               placeholder="Tiêu đề SEO">
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" 
                                  placeholder="Mô tả SEO">{{ old('meta_description', $theLoai->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                               id="meta_keywords" name="meta_keywords" 
                               value="{{ old('meta_keywords', $theLoai->meta_keywords) }}" 
                               placeholder="Từ khóa SEO (cách nhau bởi dấu phẩy)">
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Thống kê thể loại</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $theLoai->sach->count() }}</h4>
                                <small class="text-muted">Số sách</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info">{{ $theLoai->children->count() }}</h4>
                            <small class="text-muted">Thể loại con</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ngày tạo:</span>
                            <span>{{ $theLoai->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Cập nhật:</span>
                            <span>{{ $theLoai->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Đường dẫn:</span>
                            <span class="text-muted">{{ $theLoai->duong_dan }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Lưu ý</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Thay đổi tên thể loại sẽ tự động cập nhật đường dẫn
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Không thể chọn chính nó làm thể loại cha
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Hình ảnh mới sẽ thay thế hình ảnh cũ
                        </li>
                        <li>
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Vô hiệu hóa thể loại sẽ ẩn nó khỏi trang web
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

#imagePreview img {
    border: 2px solid #e3e6f0;
    border-radius: 0.35rem;
}

.border-end {
    border-right: 1px solid #e3e6f0 !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview
    $('#hinh_anh').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImg').attr('src', e.target.result);
                $('#imagePreview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });

    // Form validation
    $('#categoryForm').submit(function(e) {
        let isValid = true;
        
        // Check required fields
        const requiredFields = ['ten_the_loai'];
        requiredFields.forEach(function(field) {
            const input = $(`#${field}`);
            if (!input.val().trim()) {
                input.addClass('is-invalid');
                isValid = false;
            } else {
                input.removeClass('is-invalid');
            }
        });

        // Check image file size
        const imageFile = $('#hinh_anh')[0].files[0];
        if (imageFile && imageFile.size > 2 * 1024 * 1024) { // 2MB
            $('#hinh_anh').addClass('is-invalid');
            showToast('error', 'Kích thước hình ảnh không được vượt quá 2MB');
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

    // Prevent selecting self as parent
    const currentId = {{ $theLoai->ma_the_loai }};
    $('#ma_the_loai_cha option').each(function() {
        if ($(this).val() == currentId) {
            $(this).remove();
        }
    });
});

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