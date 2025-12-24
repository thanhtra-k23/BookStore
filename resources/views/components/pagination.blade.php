@if ($paginator->hasPages())
    <nav aria-label="Pagination Navigation" class="pagination-wrapper">
        <div class="pagination-info">
            <span class="pagination-text">
                Hiển thị 
                <span class="fw-bold">{{ $paginator->firstItem() }}</span>
                đến 
                <span class="fw-bold">{{ $paginator->lastItem() }}</span>
                trong tổng số 
                <span class="fw-bold">{{ $paginator->total() }}</span>
                kết quả
            </span>
        </div>
        
        <div class="pagination-controls">
            <ul class="pagination pagination-modern mb-0">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- First Page Link --}}
                @if($paginator->currentPage() > 3)
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                    </li>
                    @if($paginator->currentPage() > 4)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled">
                            <span class="page-link">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Last Page Link --}}
                @if($paginator->currentPage() < $paginator->lastPage() - 2)
                    @if($paginator->currentPage() < $paginator->lastPage() - 3)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
            
            {{-- Page Size Selector --}}
            <div class="page-size-selector ms-3">
                <select class="form-select form-select-sm" onchange="changePageSize(this.value)" style="width: auto;">
                    <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10/trang</option>
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15/trang</option>
                    <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25/trang</option>
                    <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50/trang</option>
                    <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100/trang</option>
                </select>
            </div>
        </div>
    </nav>

    <style>
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
            padding: 1rem 0;
            border-top: 1px solid #e2e8f0;
        }

        .pagination-info {
            color: #64748b;
            font-size: 0.875rem;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
        }

        .pagination-modern {
            --bs-pagination-padding-x: 0.75rem;
            --bs-pagination-padding-y: 0.5rem;
            --bs-pagination-font-size: 0.875rem;
            --bs-pagination-color: #475569;
            --bs-pagination-bg: #ffffff;
            --bs-pagination-border-width: 1px;
            --bs-pagination-border-color: #e2e8f0;
            --bs-pagination-border-radius: 8px;
            --bs-pagination-hover-color: #1e293b;
            --bs-pagination-hover-bg: #f1f5f9;
            --bs-pagination-hover-border-color: #cbd5e1;
            --bs-pagination-focus-color: #1e293b;
            --bs-pagination-focus-bg: #f1f5f9;
            --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
            --bs-pagination-active-color: #ffffff;
            --bs-pagination-active-bg: #2563eb;
            --bs-pagination-active-border-color: #2563eb;
            --bs-pagination-disabled-color: #94a3b8;
            --bs-pagination-disabled-bg: #f8fafc;
            --bs-pagination-disabled-border-color: #e2e8f0;
        }

        .pagination-modern .page-link {
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.2s ease;
            font-weight: 500;
            min-width: 40px;
            text-align: center;
        }

        .pagination-modern .page-link:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .pagination-modern .page-item.active .page-link {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .pagination-modern .page-item.disabled .page-link {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
        }

        .page-size-selector .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            font-size: 0.875rem;
            padding: 0.375rem 2rem 0.375rem 0.75rem;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }

        .page-size-selector .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .pagination-wrapper {
                flex-direction: column;
                text-align: center;
            }
            
            .pagination-info {
                order: 2;
                margin-top: 0.5rem;
            }
            
            .pagination-controls {
                order: 1;
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .pagination-modern {
                --bs-pagination-padding-x: 0.5rem;
                --bs-pagination-padding-y: 0.375rem;
                --bs-pagination-font-size: 0.8125rem;
            }
            
            .pagination-modern .page-link {
                min-width: 35px;
            }
        }

        @media (max-width: 576px) {
            .pagination-modern .page-item:not(.active):not(:first-child):not(:last-child):not(.disabled) {
                display: none;
            }
            
            .pagination-modern .page-item.active ~ .page-item:nth-child(-n+2):not(:last-child),
            .pagination-modern .page-item.active ~ .page-item:nth-last-child(-n+2):not(:first-child) {
                display: inline-block;
            }
        }
    </style>

    <script>
        function changePageSize(perPage) {
            const url = new URL(window.location);
            url.searchParams.set('per_page', perPage);
            url.searchParams.delete('page'); // Reset to first page
            window.location.href = url.toString();
        }
    </script>
@endif