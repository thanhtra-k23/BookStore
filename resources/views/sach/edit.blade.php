@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container mt-4 mb-5">
    <!-- Header Section -->
    <div class="card card-modern mb-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-1 fw-bold">
                        <i class="fas fa-edit me-2"></i>
                        Chỉnh sửa sách
                    </h4>
                    <p class="mb-0 text-muted">
                        Cập nhật thông tin sách: {{ $sach->ten_sach }}
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('admin.sach.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="card card-modern">
        <div class="card-body">
            <form action="{{ route('admin.sach.update', $sach->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Thông tin cơ bản</h6>
                            
                            <div class="mb-3">
                                <label for="ten_sach" class="form-label">Tên sách <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ten_sach') is-invalid @enderror" 
                                       id="ten_sach" name="ten_sach" value="{{ old('ten_sach', $sach->ten_sach) }}" required>
                                @error('ten_sach')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="tac_gia_id" class="form-label">Tác giả <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tac_gia_id') is-invalid @enderror" 
                                            id="tac_gia_id" name="tac_gia_id" required>
                                        <option value="">Chọn tác giả</option>
                                        @foreach($tacGia as $author)
                                            <option value="{{ $author->ma_tac_gia }}" 
                                                    {{ old('tac_gia_id', $sach->ma_tac_gia) == $author->ma_tac_gia ? 'selected' : '' }}>
                                                {{ $author->ten_tac_gia }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tac_gia_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="the_loai_id" class="form-label">Thể loại <span class="text-danger">*</span></label>
                                    <select class="form-select @error('the_loai_id') is-invalid @enderror" 
                                            id="the_loai_id" name="the_loai_id" required>
                                        <option value="">Chọn thể loại</option>
                                        @foreach($theLoai as $category)
                                            <option value="{{ $category->ma_the_loai }}" 
                                                    {{ old('the_loai_id', $sach->ma_the_loai) == $category->ma_the_loai ? 'selected' : '' }}>
                                                {{ $category->ten_the_loai }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('the_loai_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="nha_xuat_ban_id" class="form-label">Nhà xuất bản <span class="text-danger">*</span></label>
                                    <select class="form-select @error('nha_xuat_ban_id') is-invalid @enderror" 
                                            id="nha_xuat_ban_id" name="nha_xuat_ban_id" required>
                                        <option value="">Chọn nhà xuất bản</option>
                                        @foreach($nhaXuatBan as $publisher)
                                            <option value="{{ $publisher->ma_nxb }}" 
                                                    {{ old('nha_xuat_ban_id', $sach->ma_nxb) == $publisher->ma_nxb ? 'selected' : '' }}>
                                                {{ $publisher->ten_nxb }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nha_xuat_ban_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mo_ta" class="form-label">Mô tả <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                          id="mo_ta" name="mo_ta" rows="5" required>{{ old('mo_ta', $sach->mo_ta) }}</textarea>
                                @error('mo_ta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pricing & Stock -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Giá bán & Tồn kho</h6>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="gia_ban" class="form-label">Giá bán <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('gia_ban') is-invalid @enderror" 
                                               id="gia_ban" name="gia_ban" value="{{ old('gia_ban', $sach->gia_ban) }}" 
                                               min="0" step="1000" required>
                                        <span class="input-group-text">VNĐ</span>
                                        @error('gia_ban')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="gia_khuyen_mai" class="form-label">Giá khuyến mãi</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('gia_khuyen_mai') is-invalid @enderror" 
                                               id="gia_khuyen_mai" name="gia_khuyen_mai" 
                                               value="{{ old('gia_khuyen_mai', $sach->gia_khuyen_mai) }}" 
                                               min="0" step="1000">
                                        <span class="input-group-text">VNĐ</span>
                                        @error('gia_khuyen_mai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="so_luong_ton" class="form-label">Số lượng tồn <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('so_luong_ton') is-invalid @enderror" 
                                           id="so_luong_ton" name="so_luong_ton" 
                                           value="{{ old('so_luong_ton', $sach->so_luong_ton) }}" 
                                           min="0" required>
                                    @error('so_luong_ton')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Thông tin bổ sung</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nam_xuat_ban" class="form-label">Năm xuất bản <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('nam_xuat_ban') is-invalid @enderror" 
                                           id="nam_xuat_ban" name="nam_xuat_ban" 
                                           value="{{ old('nam_xuat_ban', $sach->nam_xuat_ban) }}" 
                                           min="1900" max="{{ date('Y') }}" required>
                                    @error('nam_xuat_ban')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                    <select class="form-select @error('trang_thai') is-invalid @enderror" 
                                            id="trang_thai" name="trang_thai" required>
                                        <option value="active" {{ old('trang_thai', $sach->trang_thai) == 'active' ? 'selected' : '' }}>
                                            Đang bán
                                        </option>
                                        <option value="inactive" {{ old('trang_thai', $sach->trang_thai) == 'inactive' ? 'selected' : '' }}>
                                            Ngừng bán
                                        </option>
                                    </select>
                                    @error('trang_thai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Current Image -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Ảnh bìa hiện tại</h6>
                            @if($sach->anh_bia)
                                <div class="text-center mb-3">
                                    <img src="{{ $sach->anh_bia_url }}" alt="{{ $sach->ten_sach }}" 
                                         class="img-fluid rounded shadow" style="max-height: 300px;">
                                </div>
                            @else
                                <div class="text-center mb-3">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 300px;">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Upload New Image -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Cập nhật ảnh bìa</h6>
                            <div class="mb-3">
                                <label for="anh_bia" class="form-label">Chọn ảnh mới</label>
                                <input type="file" class="form-control @error('anh_bia') is-invalid @enderror" 
                                       id="anh_bia" name="anh_bia" accept="image/*">
                                @error('anh_bia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div id="imagePreview" class="mb-4" style="display: none;">
                            <h6 class="fw-bold mb-3">Xem trước</h6>
                            <div class="text-center">
                                <img id="previewImg" src="" alt="Preview" 
                                     class="img-fluid rounded shadow" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.sach.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Hủy bỏ
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Cập nhật sách
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Image preview
    document.getElementById('anh_bia').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').style.display = 'none';
        }
    });

    // Price validation
    document.getElementById('gia_khuyen_mai').addEventListener('input', function() {
        const giaBan = parseFloat(document.getElementById('gia_ban').value) || 0;
        const giaKhuyenMai = parseFloat(this.value) || 0;
        
        if (giaKhuyenMai >= giaBan && giaBan > 0) {
            this.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá bán');
        } else {
            this.setCustomValidity('');
        }
    });

    document.getElementById('gia_ban').addEventListener('input', function() {
        const giaKhuyenMai = document.getElementById('gia_khuyen_mai');
        giaKhuyenMai.dispatchEvent(new Event('input'));
    });
</script>
@endpush