@props(['items' => []])

@if(!empty($items))
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">
                <i class="fas fa-home me-1"></i>
                Trang chủ
            </a>
        </li>
        
        @foreach($items as $item)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $item['title'] }}
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif