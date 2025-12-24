@if ($paginator->hasPages())
    <nav aria-label="Simple Pagination Navigation" class="simple-pagination-wrapper">
        <div class="d-flex justify-content-between align-items-center">
            <div class="pagination-info">
                <span class="text-muted small">
                    Trang {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
                </span>
            </div>
            
            <div class="pagination-controls">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="btn btn-outline-secondary btn-sm disabled me-2">
                        <i class="fas fa-chevron-left me-1"></i>
                        Trước
                    </span>
                @else
                    <a class="btn btn-outline-primary btn-sm me-2" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left me-1"></i>
                        Trước
                    </a>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a class="btn btn-primary btn-sm" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Tiếp
                        <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                @else
                    <span class="btn btn-outline-secondary btn-sm disabled">
                        Tiếp
                        <i class="fas fa-chevron-right ms-1"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>

    <style>
        .simple-pagination-wrapper {
            margin-top: 1rem;
            padding: 1rem 0;
            border-top: 1px solid #e2e8f0;
        }

        .simple-pagination-wrapper .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .simple-pagination-wrapper .btn:hover:not(.disabled) {
            transform: translateY(-1px);
        }

        @media (max-width: 576px) {
            .simple-pagination-wrapper .d-flex {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }
        }
    </style>
@endif