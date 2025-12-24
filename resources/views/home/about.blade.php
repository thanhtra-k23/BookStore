@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Về BookStore
            </h1>
            <p class="lead text-muted">
                Điểm đến tin cậy cho những người yêu sách
            </p>
        </div>
    </div>

    <!-- About Content -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <h2 class="h3 mb-3">Câu chuyện của chúng tôi</h2>
            <p class="text-muted">
                BookStore được thành lập với sứ mệnh mang đến cho độc giả Việt Nam một kho tàng sách phong phú, 
                đa dạng và chất lượng. Chúng tôi tin rằng sách là cầu nối tri thức, là nguồn cảm hứng bất tận 
                cho sự phát triển của con người.
            </p>
            <p class="text-muted">
                Với hơn 10 năm kinh nghiệm trong ngành xuất bản và phân phối sách, chúng tôi hiểu rõ nhu cầu 
                và sở thích của độc giả. Từ văn học kinh điển đến khoa học hiện đại, từ sách thiếu nhi đến 
                tài liệu chuyên ngành, BookStore luôn cập nhật những đầu sách mới nhất và hay nhất.
            </p>
        </div>
        <div class="col-lg-6 mb-4">
            <img src="https://via.placeholder.com/500x300/667eea/ffffff?text=BookStore+Team" 
                 alt="BookStore Team" 
                 class="img-fluid rounded shadow">
        </div>
    </div>

    <!-- Mission & Vision -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-bullseye text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="card-title">Sứ mệnh</h4>
                    <p class="card-text text-muted">
                        Lan tỏa tri thức, kết nối độc giả với những cuốn sách hay, 
                        góp phần xây dựng một xã hội học tập suốt đời.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-eye text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h4 class="card-title">Tầm nhìn</h4>
                    <p class="card-text text-muted">
                        Trở thành nhà sách trực tuyến hàng đầu Việt Nam, 
                        nơi mọi người có thể dễ dàng tiếp cận tri thức.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Values -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2 class="h3 text-center mb-4">Giá trị cốt lõi</h2>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <i class="fas fa-heart text-danger" style="font-size: 2.5rem;"></i>
                </div>
                <h5>Tận tâm</h5>
                <p class="text-muted small">
                    Phục vụ khách hàng với tất cả sự tận tâm và nhiệt huyết
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <i class="fas fa-star text-warning" style="font-size: 2.5rem;"></i>
                </div>
                <h5>Chất lượng</h5>
                <p class="text-muted small">
                    Cam kết mang đến những sản phẩm và dịch vụ tốt nhất
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <i class="fas fa-handshake text-info" style="font-size: 2.5rem;"></i>
                </div>
                <h5>Tin cậy</h5>
                <p class="text-muted small">
                    Xây dựng mối quan hệ lâu dài dựa trên sự tin cậy
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <div class="mb-3">
                    <i class="fas fa-rocket text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5>Đổi mới</h5>
                <p class="text-muted small">
                    Không ngừng cải tiến và áp dụng công nghệ mới
                </p>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-5">
        <div class="col-12 mb-4">
            <h2 class="h3 text-center mb-4">BookStore trong con số</h2>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <h2 class="text-primary mb-2">10,000+</h2>
                <p class="text-muted">Đầu sách</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <h2 class="text-success mb-2">50,000+</h2>
                <p class="text-muted">Khách hàng</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <h2 class="text-warning mb-2">100+</h2>
                <p class="text-muted">Nhà xuất bản</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="text-center">
                <h2 class="text-info mb-2">10+</h2>
                <p class="text-muted">Năm kinh nghiệm</p>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-12 text-center">
            <div class="bg-light rounded p-5">
                <h3 class="mb-3">Bắt đầu hành trình khám phá tri thức</h3>
                <p class="text-muted mb-4">
                    Tham gia cùng hàng nghìn độc giả đã tin tưởng BookStore
                </p>
                <div>
                    <a href="{{ route('categories') }}" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-book me-2"></i>
                        Khám phá sách
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>
                        Liên hệ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection