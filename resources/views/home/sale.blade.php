@extends('layouts.pure-blade')

@section('title', $title ?? 'Khuyến mãi')

@push('styles')
<style>
    .page-hero {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #f59e0b 100%);
        color: white;
        padding: 3rem 0;
        margin: -1.5rem -15px 0;
        width: calc(100% + 30px);
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '💰';
        position: absolute;
        font-size: 15rem;
        opacity: 0.1;
        right: -2rem;
        top: -2rem;
    }
    .page-hero-content { text-align: center; position: relative; z-index: 1; }
    .page-icon-wrapper {
        width: 80px; height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .page-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; }
    .page-desc { font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto; }
</style>
@endpush

@section('content')
<div class="page-hero">
    <div class="container">
        <div class="page-hero-content">
            <div class="page-icon-wrapper">💰</div>
            <h1 class="page-title">KHUYẾN MÃI HOT</h1>
            <p class="page-desc">Săn sách giảm giá - Tiết kiệm đến 50%!</p>
        </div>
    </div>
</div>
@include('home.partials.book-grid-sale', ['books' => $books])
@endsection
