@extends('layouts.pure-blade')

@section('title', $title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">
                <i class="fas fa-search me-2"></i>
                Kết quả tìm kiếm
            </h1>
            @if($keyword)
                <p class="lead text-muted mb-4">
                    Kết quả cho từ khóa: <strong>"{{ $keyword }}"</strong>
                </p>
            @endif
        </div>
    </div>

    <!-- Search Form -->
    <div class="row mb-4">
        <div class="col-12">
            <form method="GET" action="{{ route('search') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" 
                               class="form-control" 
                               name="q" 
                               placeholder="Tìm kiếm sách..." 
                               value="{{ $keyword }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <select name="the_loai_id" class="form-select">
                            <option value="">Tất cả thể loại</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->ma_the_loai }}" 
                                        {{ request('the_loai_id') == $category->ma_the_loai ? 'selected' : '' }}>
                                    {{ $category->ten_the_loai }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <select name="tac_gia_id" class="form-select">
                            <option value="">Tất cả tác giả</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->ma_tac_gia }}" 
                                        {{ request('tac_gia_id') == $author->ma_tac_gia ? 'selected' : '' }}>
                                    {{ $author->ten_tac_gia }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <select name="sort" class="form-select">
                            <option value="relevance" {{ request('sort') == 'relevance' ? 'selected' : '' }}>
                                Liên quan
                            </option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                Mới nhất
                            </option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                Phổ biến
                            </option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                Giá tăng dần
                            </option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                Giá giảm dần
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($sach->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <p class="text-muted">
                    Tìm thấy {{ $totalResults ?? $sach->total() }} kết quả
                </p>
            </div>
        </div>

        <div class="row">
            @foreach($sach as $book)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    @include('partials.book-card', ['book' => $book])
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $sach->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-search text-muted" style="font-size: 4rem;"></i>
            <h3 class="mt-3 text-muted">Không tìm thấy kết quả nào</h3>
            @if($keyword)
                <p class="text-muted">
                    Không có sách nào phù hợp với từ khóa "{{ $keyword }}"
                </p>
                <div class="mt-3">
                    <a href="{{ route('home') }}" class="btn btn-primary me-2">
                        Về trang chủ
                    </a>
                    <a href="{{ route('categories') }}" class="btn btn-outline-primary">
                        Xem thể loại
                    </a>
                </div>
            @else
                <p class="text-muted">Vui lòng nhập từ khóa để tìm kiếm</p>
            @endif
        </div>
    @endif
</div>
@endsection