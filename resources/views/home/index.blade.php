@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-white">
                <h1 class="display-4 fw-bold mb-4">Khám phá thế giới sách</h1>
                <p class="lead mb-4">
                    Hàng ngàn đầu sách chất lượng, từ văn học đến khoa học, 
                    từ kinh tế đến thiếu nhi. Tất cả đều có tại BookStore.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('categories') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-th-large me-2"></i>
                        Xem thể loại
                    </a>
                    <a href="{{ route('search') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>
                        Tìm kiếm
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://via.placeholder.com/500x400/667eea/ffffff?text=BookStore" 
                     alt="BookStore" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-4 bg-white shadow-sm">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-book text-primary fs-2 mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ number_format($stats['total_books']) }}</h4>
                    <small class="text-muted">Đầu sách</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-users text-success fs-2 mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ number_format($stats['total_authors']) }}</h4>
                    <small class="text-muted">Tác giả</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-list text-info fs-2 mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ number_format($stats['total_categories']) }}</h4>
                    <small class="text-muted">Thể loại</small>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="d-flex flex-column align-items-center">
                    <i class="fas fa-shopping-cart text-warning fs-2 mb-2"></i>
                    <h4 class="fw-bold mb-1">{{ number_format($stats['total_orders']) }}</h4>
                    <small class="text-muted">Đơn hàng</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Books -->
@if($featuredBooks->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3">
                    <i class="fas fa-star text-warning me-2"></i>
                    Sách nổi bật
                </h2>
                <p class="text-muted">Những cuốn sách được yêu thích nhất</p>
            </div>
        </div>
        <div class="row">
            @foreach($featuredBooks as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    @include('partials.book-card', ['book' => $book])
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('search', ['sort' => 'popular']) }}" class="btn btn-outline-primary">
                Xem tất cả sách nổi bật
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- New Arrivals -->
@if($newBooks->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3">
                    <i class="fas fa-clock text-success me-2"></i>
                    Sách mới
                </h2>
                <p class="text-muted">Những cuốn sách vừa được cập nhật</p>
            </div>
        </div>
        <div class="row">
            @foreach($newBooks as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    @include('partials.book-card', ['book' => $book])
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('search', ['sort' => 'newest']) }}" class="btn btn-outline-success">
                Xem tất cả sách mới
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Sale Books -->
@if($saleBooks->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3">
                    <i class="fas fa-tags text-danger me-2"></i>
                    Sách khuyến mãi
                </h2>
                <p class="text-muted">Những cuốn sách đang có giá ưu đãi</p>
            </div>
        </div>
        <div class="row">
            @foreach($saleBooks as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    @include('partials.book-card', ['book' => $book])
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('search', ['on_sale' => 1]) }}" class="btn btn-outline-danger">
                Xem tất cả sách khuyến mãi
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Popular Categories -->
@if($popularCategories->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3">
                    <i class="fas fa-th-large text-info me-2"></i>
                    Thể loại phổ biến
                </h2>
                <p class="text-muted">Các thể loại sách được quan tâm nhiều nhất</p>
            </div>
        </div>
        <div class="row">
            @foreach($popularCategories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card card-modern h-100 text-center">
                        <div class="card-body">
                            <div class="mb-3">
                                @if($category->anh_dai_dien)
                                    <img src="{{ $category->anh_dai_dien_url }}" 
                                         alt="{{ $category->ten_the_loai }}" 
                                         class="rounded-circle" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-book text-white fs-2"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="card-title">{{ $category->ten_the_loai }}</h5>
                            <p class="text-muted">{{ $category->sach_count }} cuốn sách</p>
                            <a href="{{ route('category', $category->duong_dan) }}" 
                               class="btn btn-outline-primary">
                                Xem sách
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('categories') }}" class="btn btn-outline-info">
                Xem tất cả thể loại
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Newsletter Section -->
<section class="py-5" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
    <div class="container">
        <div class="row justify-content-center text-center text-white">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3">Đăng ký nhận tin</h3>
                <p class="mb-4">
                    Nhận thông báo về sách mới, khuyến mãi và các sự kiện đặc biệt
                </p>
                <form class="d-flex gap-2">
                    <input type="email" class="form-control" placeholder="Nhập email của bạn">
                    <button type="submit" class="btn btn-light">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Newsletter subscription
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        if (email) {
            showToast('Cảm ơn bạn đã đăng ký nhận tin!', 'success');
            this.reset();
        }
    });
</script>
@endpush