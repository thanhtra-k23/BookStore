@extends('layouts.admin')

@section('title', 'Thêm Tác Giả Mới')

@section('content')
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title" style="margin: 0; font-weight: 600;">Thêm Tác Giả Mới</h3>
            <div>
                <a href="{{ route('admin.tacgia.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                    </div>
                </div>

                <form action="{{ route('admin.tacgia.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="ten_tac_gia">Tên Tác Giả <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten_tac_gia') is-invalid @enderror" 
                                           id="ten_tac_gia" name="ten_tac_gia" value="{{ old('ten_tac_gia') }}" required>
                                    @error('ten_tac_gia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="tieu_su">Tiểu Sử <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('tieu_su') is-invalid @enderror" 
                                              id="tieu_su" name="tieu_su" rows="6" required 
                                              placeholder="Nhập tiểu sử của tác giả...">{{ old('tieu_su') }}</textarea>
                                    @error('tieu_su')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Mô tả chi tiết về cuộc đời và sự nghiệp của tác giả.</small>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ngay_sinh">Ngày Sinh</label>
                                            <input type="date" class="form-control @error('ngay_sinh') is-invalid @enderror" 
                                                   id="ngay_sinh" name="ngay_sinh" value="{{ old('ngay_sinh') }}">
                                            @error('ngay_sinh')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="ngay_mat">Ngày Mất</label>
                                            <input type="date" class="form-control @error('ngay_mat') is-invalid @enderror" 
                                                   id="ngay_mat" name="ngay_mat" value="{{ old('ngay_mat') }}">
                                            @error('ngay_mat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quoc_tich">Quốc Tịch</label>
                                            <input type="text" class="form-control @error('quoc_tich') is-invalid @enderror" 
                                                   id="quoc_tich" name="quoc_tich" value="{{ old('quoc_tich') }}" 
                                                   placeholder="Ví dụ: Việt Nam">
                                            @error('quoc_tich')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="website">Website</label>
                                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                                   id="website" name="website" value="{{ old('website') }}" 
                                                   placeholder="https://example.com">
                                            @error('website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="trang_thai">Trạng Thái</label>
                                    <select class="form-control @error('trang_thai') is-invalid @enderror" 
                                            id="trang_thai" name="trang_thai">
                                        <option value="active" {{ old('trang_thai', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                        <option value="inactive" {{ old('trang_thai') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="hinh_anh">Ảnh Đại Diện</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('hinh_anh') is-invalid @enderror" 
                                               id="hinh_anh" name="hinh_anh" accept="image/*">
                                        <label class="custom-file-label" for="hinh_anh">Chọn ảnh...</label>
                                    </div>
                                    @error('hinh_anh')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Định dạng: JPG, PNG, GIF. Tối đa 2MB.</small>
                                </div>

                                <div class="form-group">
                                    <div id="image-preview" class="text-center" style="display: none;">
                                        <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu Tác Giả
                        </button>
                        <a href="{{ route('admin.tacgia.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Preview image
    $('#hinh_anh').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#image-preview').show();
            }
            reader.readAsDataURL(file);
            
            // Update label
            $(this).next('.custom-file-label').text(file.name);
        } else {
            $('#image-preview').hide();
            $(this).next('.custom-file-label').text('Chọn ảnh...');
        }
    });
});
</script>
@endpush
@endsection