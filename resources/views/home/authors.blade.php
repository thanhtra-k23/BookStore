@extends('layouts.pure-blade')

@section('title', $title)

@push('styles')
<style>
    .page-header {
        background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
        color: white;
        padding: 3rem 0;
        margin: -2.5rem -20px 3rem;
        text-align: center;
    }
    
    .page-header h1 {
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .page-header p {
        opacity: 0.9;
        font-size: 1.1rem;
    }
    
    .search-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-md);
    }
    
    .search-form {
        display: flex;
        gap: 1rem;
    }
    
    .search-form input {
        flex: 1;
        padding: 0.85rem 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.3s;
    }
    
    .search-form input:focus {
        outline: none;
        border-color: var(--success-color);
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.15);
    }
    
    .search-form button {
        padding: 0.85rem 2rem;
        border-radius: 50px;
    }
    
    .authors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .author-card {
        background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: var(--shadow-md);
        transition: all 0.4s ease;
        border: 1px solid rgba(22, 163, 74, 0.1);
    }
    
    .author-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(22, 163, 74, 0.2);
        border-color: var(--success-color);
    }
    
    .author-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(22, 163, 74, 0.3);
        transition: transform 0.3s ease;
        overflow: hidden;
    }
    
    .author-card:hover .author-avatar {
        transform: scale(1.1);
    }
    
    .author-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .author-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }
    
    .author-country {
        color: var(--secondary-color);
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
    }
    
    .author-bio {
        color: var(--secondary-color);
        font-size: 0.95rem;
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    
    .author-count {
        display: inline-block;
        background: linear-gradient(135deg, #dcfce7, #f0fdf4);
        color: var(--success-color);
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        border-radius: 25px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: var(--shadow-md);
    }
    
    .empty-state .icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #9ca3af;
        margin-bottom: 1.5rem;
    }
    
    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }
    
    @media (max-width: 768px) {
        .search-form {
            flex-direction: column;
        }
        
        .authors-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>✍️ Tất cả tác giả</h1>
    <p>Khám phá các tác giả nổi tiếng tại BookStore</p>
</div>

<!-- Search Form -->
<div class="search-section">
    <form method="GET" action="{{ route('authors') }}" class="search-form">
        <input type="text" 
               name="search" 
               placeholder="🔍 Tìm kiếm tác giả..." 
               value="{{ request('search') }}">
        <button class="btn btn-success" type="submit">
            Tìm kiếm
        </button>
    </form>
</div>

@if($authors->count() > 0)
    <div class="authors-grid">
        @foreach($authors as $author)
            <div class="author-card fade-in-up">
                <div class="author-avatar">
                    @if($author->hinh_anh)
                        <img src="{{ asset('storage/' . $author->hinh_anh) }}" 
                             alt="{{ $author->ten_tac_gia }}">
                    @else
                        👤
                    @endif
                </div>
                
                <h3 class="author-name">{{ $author->ten_tac_gia }}</h3>
                
                @if($author->quoc_tich)
                    <p class="author-country">🏳️ {{ $author->quoc_tich }}</p>
                @endif
                
                @if($author->tieu_su)
                    <p class="author-bio">
                        {{ Str::limit($author->tieu_su, 100) }}
                    </p>
                @endif
                
                <div class="author-count">
                    📚 {{ $author->sach_count }} cuốn sách
                </div>
                
                <a href="{{ route('author', $author->duong_dan) }}" class="btn btn-success">
                    👁️ Xem sách
                </a>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($authors->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination">
                @if($authors->onFirstPage())
                    <span class="disabled">← Trước</span>
                @else
                    <a href="{{ $authors->previousPageUrl() }}">← Trước</a>
                @endif
                
                @foreach($authors->getUrlRange(1, $authors->lastPage()) as $page => $url)
                    @if($page == $authors->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
                
                @if($authors->hasMorePages())
                    <a href="{{ $authors->nextPageUrl() }}">Sau →</a>
                @else
                    <span class="disabled">Sau →</span>
                @endif
            </div>
        </div>
    @endif
@else
    <div class="empty-state">
        <div class="icon">👥</div>
        <h3>
            @if(request('search'))
                Không tìm thấy tác giả nào
            @else
                Chưa có tác giả nào
            @endif
        </h3>
        <p>
            @if(request('search'))
                Không có tác giả nào phù hợp với từ khóa "{{ request('search') }}"
            @else
                Hệ thống chưa có tác giả nào được tạo.
            @endif
        </p>
        @if(request('search'))
            <a href="{{ route('authors') }}" class="btn btn-success">
                Xem tất cả tác giả
            </a>
        @endif
    </div>
@endif
@endsection
