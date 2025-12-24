@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="container py-5">
    <!-- Category Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories') }}" class="text-decoration-none">Thể loại</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $category->ten_the_loai }}
                    </li>
                </ol>
            </nav>

            <div class="d-flex align-items-center mb-3">
                @if($category->anh_dai_dien)
                    <img src="{{ $category->anh_dai_dien_url }}" 
                         alt="{{ $category->ten_the_loai }}" 
                         class="rounded-circle me-3" 
                         style="width: 60px; height: 60px; object-fit: cover;">
                @else
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3" 
                         style="width: 60px; height: 60px;">
                        <i class="fas fa-book text-white fs-4"></i>
                    </div>
                @endif
                <div>
                    <h1 class="mb-1">{{ $category->ten_the_loai }}</h1>
                    @if($category->mo_ta)
                        <p class="text-muted mb-0">{{ $category->mo_ta }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Sorting -->
    <div class="row mb-4">
        <div class="col-md-6">
            <p class="text-muted mb-0">
                Tìm thấy {{ $books->total() }} cuốn sách
            </p>
        </div>
        <div class="col-md-6">
            <form method="GET" action="{{ route('category', $category->duong_dan) }}" class="d-flex justify-content-end">
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
                Thể loại "{{ $category->ten_the_loai }}" hiện chưa có sách nào.
            </p>
            <div class="mt-3">
                <a href="{{ route('categories') }}" class="btn btn-primary me-2">
                    Xem thể loại khác
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    Về trang chủ
                </a>
            </div>
        </div>
    @endif

    <!-- Related Categories section removed since we simplified the category structure -->
</div>
@endsection