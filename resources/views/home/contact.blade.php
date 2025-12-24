@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="mb-4">
                <i class="fas fa-envelope me-2"></i>
                Liên hệ với chúng tôi
            </h1>
            <p class="lead text-muted">
                Chúng tôi luôn sẵn sàng hỗ trợ bạn. Hãy liên hệ với chúng tôi qua các thông tin dưới đây.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-paper-plane me-2"></i>
                        Gửi tin nhắn cho chúng tôi
                    </h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Chủ đề *</label>
                                <select class="form-select" id="subject" required>
                                    <option value="">Chọn chủ đề</option>
                                    <option value="general">Câu hỏi chung</option>
                                    <option value="order">Đơn hàng</option>
                                    <option value="book">Sách</option>
                                    <option value="technical">Hỗ trợ kỹ thuật</option>
                                    <option value="partnership">Hợp tác</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Tin nhắn *</label>
                            <textarea class="form-control" id="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>
                            Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin liên hệ
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6><i class="fas fa-map-marker-alt text-danger me-2"></i>Địa chỉ</h6>
                        <p class="text-muted mb-0">
                            Khóm 9, Phường Nguyệt Hóa<br>
                            Tỉnh Vĩnh Long, Việt Nam
                        </p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6><i class="fas fa-phone text-success me-2"></i>Điện thoại</h6>
                        <p class="text-muted mb-0">
                            <a href="tel:+84787905089" class="text-decoration-none">
                                0787905089
                            </a>
                        </p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                        <p class="text-muted mb-0">
                            <a href="mailto:info@bookstore.com" class="text-decoration-none">
                                info@bookstore.com
                            </a>
                        </p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h6><i class="fas fa-clock text-warning me-2"></i>Giờ làm việc</h6>
                        <p class="text-muted mb-0">
                            Thứ 2 - Thứ 6: 8:00 - 18:00<br>
                            Thứ 7: 8:00 - 12:00<br>
                            Chủ nhật: Nghỉ
                        </p>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-share-alt me-2"></i>
                        Theo dõi chúng tôi
                    </h5>
                </div>
                <div class="card-body text-center">
                    <a href="#" class="btn btn-outline-primary btn-sm me-2 mb-2">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="#" class="btn btn-outline-info btn-sm me-2 mb-2">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="#" class="btn btn-outline-danger btn-sm me-2 mb-2">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                    <a href="#" class="btn btn-outline-dark btn-sm mb-2">
                        <i class="fab fa-youtube"></i> YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
});
</script>
@endpush