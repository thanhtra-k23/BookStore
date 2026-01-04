@extends('layouts.pure-blade')

@section('title', $title)

@push('styles')
<style>
    .author-hero {
        background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
        color: white;
        padding: 3rem 0;
        margin: -1.5rem -15px 0;
        width: calc(100% + 30px);
    }
    .author-hero-content { text-align: center; }
    .author-avatar-large {
        width: 120px; height: 120px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
        font-size: 3rem;
        border: 4px solid rgba(255,255,255,0.3);
    }
    .author-avatar-large img {
        width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
    }
    .author-name { font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; }
    .author-meta { font-size: 1rem; opacity: 0.9; }
    .author-bio {
        max-width: 700px; margin: 1rem auto 0;
        font-size: 0.95rem; opacity: 0.85; line-height: 1.6;
    }
    .author-stats {
        display: flex; justify-content: center; gap: 2rem; margin-top: 1.5rem;
    }
    .author-stat { text-align: center; }
    .author-stat-value { font-size: 1.75rem; font-weight: 700; }
    .author-stat-label { font-size: 0.85rem; opacity: 0.8; }
    .filter-bar {
        display: flex; justify-content: space-between; align-items: center;
        padding: 1rem 0; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;
    }
    .filter-bar .result-count { color: #64748b; font-size: 0.95rem; }
    .filter-bar .result-count span { font-weight: 700; color: #8b5cf6; }
    .sort-select {
        padding: 0.6rem 1rem; border: 2px solid #e2e8f0; border-radius: 10px;
        font-size: 0.9rem; background: white; cursor: pointer; min-width: 180px;
    }
    .sort-select:focus { outline: none; border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
    .books-grid-modern { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1rem; }
    @media (max-width: 1400px) { .books-grid-modern { grid-template-columns: repeat(5, 1fr); } }
    @media (max-width: 1200px) { .books-grid-modern { grid-template-columns: repeat(4, 1fr); } }
    @media (max-width: 900px) { .books-grid-modern { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 600px) { 
        .books-grid-modern { grid-template-columns: repeat(2, 1fr); }
        .author-name { font-size: 1.75rem; }
        .author-hero { padding: 2rem 0; }
        .author-stats { gap: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="author-hero">
    <div class="container">
        <div class="author-hero-content">
            <div class="author-avatar-large">
                @if($author->anh_dai_dien)
                    <img src="{{ $author->anh_dai_dien_url }}" alt="{{ $author->ten_tac_gia }}">
                @else
                    ✍️
                @endif
            </div>
            <h1 class="author-name">{{ $author->ten_tac_gia }}</h1>
            @if($author->quoc_tich)
                <p class="author-meta">🌍 {{ $author->quoc_tich }}</p>
            @endif
            @if($author->tieu_su)
                <p class="author-bio">{{ Str::limit($author->tieu_su, 200) }}</p>
            @endif
            <div class="author-stats">
                <div class="author-stat">
                    <div class="author-stat-value">{{ $books->total() }}</div>
                    <div class="author-stat-label">📚 Cuốn sách</div>
                </div>
                <div class="author-stat">
                    <div class="author-stat-value">{{ number_format($author->luot_xem ?? 0) }}</div>
                    <div class="author-stat-label">👁️ Lượt xem</div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('home.partials.book-grid-author', ['books' => $books, 'author' => $author])
@endsection
