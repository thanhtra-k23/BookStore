@extends('layouts.app')

@section('title', $title)

@section('content')
<div class="container py-5">
    <!-- Author Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('authors') }}" class="text-decoration-none">Tác giả</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $author->ten_tac_gia }}
                    </li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        @if($author->anh_dai_dien)
                            <img src="{{ $author->anh_dai_dien_url }}" 
                                 alt="{{ $author->ten_tac_gia }}" 
                                 class="rounded-circle me-3" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center me-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fs-3"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="mb-1">{{ $author->ten_tac_gia }}</h1>
                            @if($author->quoc_tich)
                                <p class="text-muted mb-1">
                                    <i class="fas fa-flag me-1"></i>
                                    {{ $author->quoc_tich }}
                                </p>
                            @endif
                            @if($author->ngay_sinh)
                                <p class="text-muted mb-0">
                                    <i class="fas fa-birthday-cake me-1"></i>
                                    {{ $author->ngay_sinh->format('d/m/Y') }}
                                    @if($author->ngay_mat)
                                        - {{ $author->ngay_mat->format('d/m/Y') }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($author->tieu_su)
                        <div class="mb-4">
                            <h5>Tiểu sử</h5>
                            <p class="text-muted">{{ $author->tieu_su }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Thống kê</h5>
                            <div class="row">
                                <div class="col-6">
                                    <h3 class="text-primary mb-1">{{ $books->total() }}</h3>
                                    <small class="text-muted">Cuốn sách</small>
                                </div>
                                <div class="col-6">
                                    <h3 class="text-success mb-1">{{ $author->luot_xem ?? 0 }}</h3>
                                    <small class="text-muted">Lượt xem</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Sorting -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3>Sách của {{ $author->ten_tac_gia }}</h3>
            <p class="text-muted mb-0">
                Tìm thấy {{ $books->total() }} cuốn sách
            </p>
        </div>
        <div class="col-md-6">
            <form method="GET" action="{{ route('author', $author->duong_dan) }}" class="d-flex justify-content-end">
                <select name="sort" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                        Mới nhất
                    </option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                        Phổ biến nhất
                    </option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                        Giá tăng dần
                    </option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                        Giá giảm dần
                    </option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>
                        Đánh giá cao
                    </option>
                </select>
            </form>
        </div>
    </div>

    @if($books->count() > 0)
        <!-- Books Grid -->
        <div class="row">
            @foreach($books as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    @include('partials.book-card', ['book' => $book])
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $books->appends(request()->query())->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-book-open text-muted" style="font-size: 4rem;"></i>
            <h3 class="mt-3 text-muted">Chưa có sách nào</h3>
            <p class="text-muted">
                Tác giả "{{ $author->ten_tac_gia }}" hiện chưa có sách nào trong hệ thống.
            </p>
            <div class="mt-3">
                <a href="{{ route('authors') }}" class="btn btn-primary me-2">
                    Xem tác giả khác
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    Về trang chủ
                </a>
            </div>
        </div>
    @endif
</div>
@endsection