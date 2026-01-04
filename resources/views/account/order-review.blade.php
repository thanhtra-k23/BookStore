@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Trang chủ</a> › 
        <a href="{{ route('profile') }}">Tài khoản</a> › 
        <span class="active">Đánh giá đơn hàng</span>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">⭐ Đánh giá đơn hàng #{{ $order->ma_don }}</h2>
        </div>
        <div style="padding: 1.5rem;">
            <p style="color: var(--secondary-color); margin-bottom: 2rem;">
                Cảm ơn bạn đã mua hàng! Hãy chia sẻ cảm nhận của bạn về sản phẩm để giúp những khách hàng khác.
            </p>

            @foreach($order->chiTiet as $index => $item)
            <div class="review-form-item" style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <img src="{{ $item->sach->anh_bia_url ?? '/images/no-image.png' }}" 
                         alt="{{ $item->sach->ten_sach }}" 
                         style="width: 80px; height: 100px; object-fit: cover; border-radius: 8px;">
                    <div>
                        <h4 style="font-size: 1.1rem; margin-bottom: 0.25rem;">{{ $item->sach->ten_sach }}</h4>
                        <p style="color: var(--secondary-color); font-size: 0.9rem; margin: 0;">
                            {{ $item->sach->tacGia->ten_tac_gia ?? 'Chưa rõ tác giả' }}
                        </p>
                    </div>
                </div>

                <form class="review-form" data-sach-id="{{ $item->sach->ma_sach }}">
                    <!-- Star Rating -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Đánh giá của bạn</label>
                        <div class="star-rating" data-index="{{ $index }}">
                            @for($i = 1; $i <= 5; $i++)
                            <span class="star" data-value="{{ $i }}" style="font-size: 2rem; cursor: pointer; color: #e2e8f0; transition: color 0.2s;">☆</span>
                            @endfor
                            <input type="hidden" name="diem_so" class="rating-input" value="0">
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nhận xét của bạn</label>
                        <textarea name="noi_dung" class="form-control" rows="4" 
                                  placeholder="Chia sẻ cảm nhận của bạn về sản phẩm này..." 
                                  minlength="10" maxlength="1000" required></textarea>
                        <small style="color: var(--secondary-color);">Tối thiểu 10 ký tự, tối đa 1000 ký tự</small>
                    </div>

                    <!-- Submit Button -->
                    <div style="text-align: right;">
                        <button type="submit" class="btn btn-primary">
                            📝 Gửi đánh giá
                        </button>
                    </div>
                </form>
            </div>
            @endforeach

            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-secondary">
                    ← Quay lại chi tiết đơn hàng
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.star-rating .star:hover,
.star-rating .star.active {
    color: #f59e0b !important;
}
.star-rating .star.hovered {
    color: #fbbf24 !important;
}
</style>
@endpush

@push('scripts')
<script>
// Star rating functionality
document.querySelectorAll('.star-rating').forEach(container => {
    const stars = container.querySelectorAll('.star');
    const input = container.querySelector('.rating-input');
    
    stars.forEach((star, index) => {
        star.addEventListener('mouseenter', () => {
            stars.forEach((s, i) => {
                s.textContent = i <= index ? '⭐' : '☆';
                s.classList.toggle('hovered', i <= index);
            });
        });
        
        star.addEventListener('mouseleave', () => {
            const currentValue = parseInt(input.value);
            stars.forEach((s, i) => {
                s.textContent = i < currentValue ? '⭐' : '☆';
                s.classList.remove('hovered');
            });
        });
        
        star.addEventListener('click', () => {
            const value = parseInt(star.dataset.value);
            input.value = value;
            stars.forEach((s, i) => {
                s.textContent = i < value ? '⭐' : '☆';
                s.classList.toggle('active', i < value);
            });
        });
    });
});

// Submit review
document.querySelectorAll('.review-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const sachId = this.dataset.sachId;
        const diemSo = this.querySelector('.rating-input').value;
        const noiDung = this.querySelector('textarea[name="noi_dung"]').value;
        
        if (diemSo == 0) {
            showToast('Vui lòng chọn số sao đánh giá!', 'warning');
            return;
        }
        
        if (noiDung.length < 10) {
            showToast('Nội dung đánh giá phải có ít nhất 10 ký tự!', 'warning');
            return;
        }
        
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Đang gửi...';
        
        fetch('/reviews', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                sach_id: sachId,
                diem_so: diemSo,
                noi_dung: noiDung
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast('Đánh giá của bạn đã được gửi và đang chờ duyệt!', 'success');
                this.innerHTML = `
                    <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, #d1fae5, #ecfdf5); border-radius: 8px;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">✅</div>
                        <h4 style="color: var(--success-color);">Đã gửi đánh giá!</h4>
                        <p style="color: var(--secondary-color);">Cảm ơn bạn đã chia sẻ cảm nhận.</p>
                    </div>
                `;
            } else {
                showToast(data.message || 'Có lỗi xảy ra!', 'danger');
                submitBtn.disabled = false;
                submitBtn.textContent = '📝 Gửi đánh giá';
            }
        })
        .catch(error => {
            showToast('Có lỗi xảy ra!', 'danger');
            submitBtn.disabled = false;
            submitBtn.textContent = '📝 Gửi đánh giá';
        });
    });
});
</script>
@endpush
