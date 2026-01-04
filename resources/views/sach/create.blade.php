@extends('layouts.admin')

@section('title', $title)

@section('content')
    <!-- Breadcrumb -->
    <nav style="margin-bottom: 1.5rem;">
        <span style="color: #64748b; font-size: 0.9rem;">
            <a href="{{ route('admin.sach.index') }}" style="color: #3b82f6; text-decoration: none;">
                <i class="fas fa-book"></i>
                Quản lý sách
            </a>
            <li class="breadcrumb-item active">Thêm sách mới</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Thêm sách mới
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.sach.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Tên sách <span class="text-danger">*</span></label>
                                <input type="text" name="ten_sach" class="form-control @error('ten_sach') is-invalid @enderror" 
                                       value="{{ old('ten_sach') }}" required>
                                @error('ten_sach')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tác giả <span class="text-danger">*</span></label>
                                <select name="tac_gia_id" class="form-select @error('tac_gia_id') is-invalid @enderror" required>
                                    <option value="">Chọn tác giả</option>
                                    @foreach($tacGia as $author)
                                        <option value="{{ $author->ma_tac_gia }}" {{ old('tac_gia_id') == $author->ma_tac_gia ? 'selected' : '' }}>
                                            {{ $author->ten_tac_gia }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tac_gia_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thể loại <span class="text-danger">*</span></label>
                                <select name="the_loai_id" class="form-select @error('the_loai_id') is-invalid @enderror" required>
                                    <option value="">Chọn thể loại</option>
                                    @foreach($theLoai as $category)
                                        <option value="{{ $category->ma_the_loai }}" {{ old('the_loai_id') == $category->ma_the_loai ? 'selected' : '' }}>
                                            {{ $category->ten_the_loai }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('the_loai_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nhà xuất bản <span class="text-danger">*</span></label>
                                <select name="nha_xuat_ban_id" class="form-select @error('nha_xuat_ban_id') is-invalid @enderror" required>
                                    <option value="">Chọn nhà xuất bản</option>
                                    @foreach($nhaXuatBan as $publisher)
                                        <option value="{{ $publisher->ma_nxb }}" {{ old('nha_xuat_ban_id') == $publisher->ma_nxb ? 'selected' : '' }}>
                                            {{ $publisher->ten_nxb }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nha_xuat_ban_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Năm xuất bản <span class="text-danger">*</span></label>
                                <input type="number" name="nam_xuat_ban" class="form-control @error('nam_xuat_ban') is-invalid @enderror" 
                                       value="{{ old('nam_xuat_ban', date('Y')) }}" min="1900" max="{{ date('Y') }}" required>
                                @error('nam_xuat_ban')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                                <textarea name="mo_ta" class="form-control @error('mo_ta') is-invalid @enderror" 
                                          rows="4" required>{{ old('mo_ta') }}</textarea>
                                @error('mo_ta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" name="gia_ban" class="form-control @error('gia_ban') is-invalid @enderror" 
                                       value="{{ old('gia_ban') }}" min="0" step="1000" required>
                                @error('gia_ban')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Giá khuyến mãi (VNĐ)</label>
                                <input type="number" name="gia_khuyen_mai" class="form-control @error('gia_khuyen_mai') is-invalid @enderror" 
                                       value="{{ old('gia_khuyen_mai') }}" min="0" step="1000">
                                @error('gia_khuyen_mai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                                <input type="number" name="so_luong_ton" class="form-control @error('so_luong_ton') is-invalid @enderror" 
                                       value="{{ old('so_luong_ton', 0) }}" min="0" required>
                                @error('so_luong_ton')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                                    <option value="active" {{ old('trang_thai', 'active') == 'active' ? 'selected' : '' }}>
                                        Đang bán
                                    </option>
                                    <option value="inactive" {{ old('trang_thai') == 'inactive' ? 'selected' : '' }}>
                                        Ngừng bán
                                    </option>
                                </select>
                                @error('trang_thai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Ảnh bìa sách</label>
                                <input type="file" name="anh_bia" class="form-control @error('anh_bia') is-invalid @enderror" 
                                       accept="image/*" onchange="previewImage(this)">
                                @error('anh_bia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Chấp nhận file: JPG, PNG, GIF. Kích thước tối đa: 2MB
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Lưu sách
                            </button>
                            <a href="{{ route('admin.sach.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Hủy bỏ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Image Preview -->
            <div class="card card-modern">
                <div class="card-header">
                    <h6 class="mb-0">Xem trước ảnh bìa</h6>
                </div>
                <div class="card-body text-center">
                    <img id="imagePreview" src="https://via.placeholder.com/200x250/e9ecef/6c757d?text=Chưa+có+ảnh" 
                         alt="Preview" class="img-fluid rounded" style="max-height: 250px;">
                </div>
            </div>

            <!-- Tips -->
            <div class="card card-modern mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Gợi ý
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Tên sách nên rõ ràng và dễ hiểu
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Mô tả chi tiết giúp khách hàng hiểu rõ nội dung
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Ảnh bìa chất lượng cao tăng tỷ lệ mua hàng
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            Giá khuyến mãi phải nhỏ hơn giá gốc
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Validate promotion price
    document.querySelector('input[name="gia_khuyen_mai"]').addEventListener('input', function() {
        const originalPrice = parseFloat(document.querySelector('input[name="gia_ban"]').value) || 0;
        const salePrice = parseFloat(this.value) || 0;
        
        if (salePrice > 0 && salePrice >= originalPrice) {
            this.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá bán');
        } else {
            this.setCustomValidity('');
        }
    });

    document.querySelector('input[name="gia_ban"]').addEventListener('input', function() {
        const saleInput = document.querySelector('input[name="gia_khuyen_mai"]');
        const originalPrice = parseFloat(this.value) || 0;
        const salePrice = parseFloat(saleInput.value) || 0;
        
        if (salePrice > 0 && salePrice >= originalPrice) {
            saleInput.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá bán');
        } else {
            saleInput.setCustomValidity('');
        }
    });
</script>
@endpush