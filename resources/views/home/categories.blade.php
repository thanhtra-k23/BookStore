@extends('layouts.pure-blade')

@section('title', $title)

@push('styles')
<style>
    .page-header {
        background: var(--gradient-primary);
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
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
    }
    
    .category-card {
        background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: var(--shadow-md);
        transition: all 0.4s ease;
        border: 1px solid rgba(37, 99, 235, 0.1);
    }
    
    .category-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(37, 99, 235, 0.2);
        border-color: var(--primary-color);
    }
    
    .category-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
        transition: transform 0.3s ease;
    }
    
    .category-card:hover .category-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .category-name {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.75rem;
    }
    
    .category-desc {
        color: var(--secondary-color);
        font-size: 0.95rem;
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    
    .category-count {
        display: inline-block;
        background: linear-gradient(135deg, #dbeafe, #eff6ff);
        color: var(--primary-color);
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        border-radius: 25px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
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
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>📚 Tất cả thể loại sách</h1>
    <p>Khám phá các thể loại sách đa dạng tại BookStore</p>
</div>

@if($categories->count() > 0)
    <div class="categories-grid">
        @foreach($categories as $category)
            <div class="category-card fade-in-up">
                <div class="category-icon">
                    @if($category->hinh_anh)
                        <img src="{{ asset('storage/' . $category->hinh_anh) }}" 
                             alt="{{ $category->ten_the_loai }}" 
                             style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        📖
                    @endif
                </div>
                
                <h3 class="category-name">{{ $category->ten_the_loai }}</h3>
                
                @if($category->mo_ta)
                    <p class="category-desc">
                        {{ Str::limit($category->mo_ta, 100) }}
                    </p>
                @endif
                
                <div class="category-count">
                    📚 {{ $category->sach_count }} cuốn sách
                </div>
                
                <a href="{{ route('category', $category->duong_dan) }}" class="btn btn-primary">
                    👁️ Xem sách
                </a>
            </div>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <div class="icon">📁</div>
        <h3>Chưa có thể loại nào</h3>
        <p>Hệ thống chưa có thể loại sách nào được tạo.</p>
    </div>
@endif
@endsection
