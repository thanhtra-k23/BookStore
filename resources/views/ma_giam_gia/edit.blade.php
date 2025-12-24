@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.magiamgia.show', $maGiamGia->ma_giam_gia) }}" class="btn btn-info me-2">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        <a href="{{ route('admin.magiamgia.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.magiamgia.update', $maGiamGia->ma_giam_gia) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ten_ma_giam_gia">Tên mã giảm giá <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ten_ma_giam_gia') is-invalid @enderror" 
                                           id="ten_ma_giam_gia" name="ten_ma_giam_gia" 
                                           value="{{ old('ten_ma_giam_gia', $maGiamGia->ten_ma_giam_gia) }}" required>
                                    @error('ten_ma_giam_gia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ma_code">Mã code <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('ma_code') is-invalid @enderror" 
                                               id="ma_code" name="ma_code" 
                                               value="{{ old('ma_code', $maGiamGia->ma_code) }}" required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="generate-code">
                                                <i class="fas fa-random"></i> Tạo mã
                                            </button>
                                        </div>
                                        @error('ma_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mo_ta">Mô tả</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="3">{{ old('mo_ta', $maGiamGia->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loai_giam_gia">Loại giảm giá <span class="text-danger">*</span></label>
                                    <select class="form-control @error('loai_giam_gia') is-invalid @enderror" 
                                            id="loai_giam_gia" name="loai_giam_gia" required>
                                        <option value="">Chọn loại giảm giá</option>
                                        <option value="phan_tram" {{ old('loai_giam_gia', $maGiamGia->loai_giam_gia) == 'phan_tram' ? 'selected' : '' }}>
                                            Phần trăm (%)
                                        </option>
                                        <option value="so_tien" {{ old('loai_giam_gia', $maGiamGia->loai_giam_gia) == 'so_tien' ? 'selected' : '' }}>
                                            Số tiền (VNĐ)
                                        </option>
                                    </select>
                                    @error('loai_giam_gia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gia_tri_giam">Giá trị giảm <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('gia_tri_giam') is-invalid @enderror" 
                                           id="gia_tri_giam" name="gia_tri_giam" 
                                           value="{{ old('gia_tri_giam', $maGiamGia->gia_tri_giam) }}" 
                                           min="0" step="0.01" required>
                                    @error('gia_tri_giam')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gia_tri_don_hang_toi_thieu">Giá trị đơn hàng tối thiểu <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('gia_tri_don_hang_toi_thieu') is-invalid @enderror" 
                                           id="gia_tri_don_hang_toi_thieu" name="gia_tri_don_hang_toi_thieu" 
                                           value="{{ old('gia_tri_don_hang_toi_thieu', $maGiamGia->gia_tri_don_hang_toi_thieu) }}" 
                                           min="0" step="0.01" required>
                                    @error('gia_tri_don_hang_toi_thieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6" id="max-discount-group" 
                                 style="display: {{ old('loai_giam_gia', $maGiamGia->loai_giam_gia) == 'phan_tram' ? 'block' : 'none' }};">
                                <div class="form-group">
                                    <label for="gia_tri_giam_toi_da">Giá trị giảm tối đa</label>
                                    <input type="number" class="form-control @error('gia_tri_giam_toi_da') is-invalid @enderror" 
                                           id="gia_tri_giam_toi_da" name="gia_tri_giam_toi_da" 
                                           value="{{ old('gia_tri_giam_toi_da', $maGiamGia->gia_tri_giam_toi_da) }}" 
                                           min="0" step="0.01">
                                    @error('gia_tri_giam_toi_da')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="so_luong">Số lượng sử dụng</label>
                                    <input type="number" class="form-control @error('so_luong') is-invalid @enderror" 
                                           id="so_luong" name="so_luong" 
                                           value="{{ old('so_luong', $maGiamGia->so_luong) }}" min="1">
                                    <small class="form-text text-muted">
                                        Để trống nếu không giới hạn số lượng. 
                                        @if($maGiamGia->da_su_dung > 0)
                                            <strong>Đã sử dụng: {{ $maGiamGia->da_su_dung }}</strong>
                                        @endif
                                    </small>
                                    @error('so_luong')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gioi_han_su_dung_moi_user">Giới hạn sử dụng mỗi user</label>
                                    <input type="number" class="form-control @error('gioi_han_su_dung_moi_user') is-invalid @enderror" 
                                           id="gioi_han_su_dung_moi_user" name="gioi_han_su_dung_moi_user" 
                                           value="{{ old('gioi_han_su_dung_moi_user', $maGiamGia->gioi_han_su_dung_moi_user ?? 1) }}" 
                                           min="1">
                                    @error('gioi_han_su_dung_moi_user')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ngay_bat_dau">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('ngay_bat_dau') is-invalid @enderror" 
                                           id="ngay_bat_dau" name="ngay_bat_dau" 
                                           value="{{ old('ngay_bat_dau', $maGiamGia->ngay_bat_dau->format('Y-m-d\TH:i')) }}" required>
                                    @error('ngay_bat_dau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ngay_ket_thuc">Ngày kết thúc <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('ngay_ket_thuc') is-invalid @enderror" 
                                           id="ngay_ket_thuc" name="ngay_ket_thuc" 
                                           value="{{ old('ngay_ket_thuc', $maGiamGia->ngay_ket_thuc->format('Y-m-d\TH:i')) }}" required>
                                    @error('ngay_ket_thuc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="trang_thai" name="trang_thai" value="1" 
                                       {{ old('trang_thai', $maGiamGia->trang_thai) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="trang_thai">Kích hoạt mã giảm giá</label>
                            </div>
                        </div>

                        @if($maGiamGia->donHangs->count() > 0)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Mã giảm giá này đã được sử dụng trong {{ $maGiamGia->donHangs->count() }} đơn hàng. 
                            Việc thay đổi một số thông tin có thể ảnh hưởng đến báo cáo thống kê.
                        </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                        <a href="{{ route('admin.magiamgia.show', $maGiamGia->ma_giam_gia) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        <a href="{{ route('admin.magiamgia.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate random code
    document.getElementById('generate-code').addEventListener('click', function() {
        fetch('{{ route("admin.magiamgia.generate-code") }}')
            .then(response => response.json())
            .then(data => {
                document.getElementById('ma_code').value = data.code;
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback to client-side generation
                const code = 'SALE' + Math.random().toString(36).substr(2, 6).toUpperCase();
                document.getElementById('ma_code').value = code;
            });
    });

    // Show/hide max discount field based on discount type
    const loaiGiamGia = document.getElementById('loai_giam_gia');
    const maxDiscountGroup = document.getElementById('max-discount-group');
    
    function toggleMaxDiscount() {
        if (loaiGiamGia.value === 'phan_tram') {
            maxDiscountGroup.style.display = 'block';
            document.getElementById('gia_tri_giam_toi_da').required = true;
        } else {
            maxDiscountGroup.style.display = 'none';
            document.getElementById('gia_tri_giam_toi_da').required = false;
        }
    }
    
    loaiGiamGia.addEventListener('change', toggleMaxDiscount);
    toggleMaxDiscount(); // Initial call
});
</script>
@endsection