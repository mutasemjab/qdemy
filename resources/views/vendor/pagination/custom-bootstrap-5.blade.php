<style>
    /* Main Pagination Container */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 40px 0;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }

    /* Pagination List Container */
    nav[role="navigation"] .pagination,
    nav .pagination,
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        background: #FFFFFF;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 85, 210, 0.08);
        overflow: hidden;
        gap: 2px;
        padding: 4px;
    }

    /* Individual Page Items */
    .pagination .page-item {
        margin: 0;
    }

    /* Page Links Base Style */
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        text-decoration: none;
        color: #0055D2;
        background: #FFFFFF;
        border: 1px solid #F3F3F3;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
        user-select: none;
    }

    /* Hover State */
    .pagination .page-link:hover:not(.active):not([aria-disabled="true"]) {
        background: linear-gradient(135deg, #F3F3F3 0%, #FFFFFF 100%);
        color: #0055D2;
        border-color: #3488FC;
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(52, 136, 252, 0.15);
    }

    /* Active Page */
    .pagination .page-item.active .page-link,
    .pagination .page-link.active {
        background: linear-gradient(135deg, #0055D2 0%, #3488FC 100%);
        color: #FFFFFF;
        border-color: #0055D2;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(0, 85, 210, 0.2);
        cursor: default;
    }

    /* Disabled State */
    .pagination .page-item.disabled .page-link,
    .pagination .page-link[aria-disabled="true"],
    .pagination .page-link:disabled {
        background: #F3F3F3;
        color: #b0b9c3;
        border-color: #F3F3F3;
        cursor: not-allowed;
        opacity: 0.6;
    }

    /* Previous/Next Buttons Special Styling */
    .pagination .page-item:first-child .page-link,
    .pagination .page-link[rel="prev"] {
        border-radius: 8px 6px 6px 8px;
        padding: 0 14px;
        font-weight: 500;
    }

    .pagination .page-item:last-child .page-link,
    .pagination .page-link[rel="next"] {
        border-radius: 6px 8px 8px 6px;
        padding: 0 14px;
        font-weight: 500;
    }

    /* Add Icons to Prev/Next using CSS */
    .pagination .page-link[rel="prev"]::before,
    .pagination .page-link[aria-label*="Previous"]::before {
        content: '←';
        margin-right: 6px;
    }

    .pagination .page-link[rel="next"]::after,
    .pagination .page-link[aria-label*="Next"]::after {
        content: '→';
        margin-left: 6px;
    }

    /* Three Dots (Ellipsis) Styling */
    .pagination .page-item.disabled span.page-link,
    .pagination .dots {
        background: transparent;
        border: none;
        color: #b0b9c3;
        cursor: default;
        padding: 0 8px;
    }

    /* Page Info Text (Showing X to Y of Z results) */
    .pagination-info {
        display: flex;
        align-items: center;
        margin: 20px 0;
        justify-content: center;
        color: #6c757d;
        font-size: 14px;
    }

    /* Laravel Default Classes Fix */
    nav[aria-label="Pagination Navigation"] {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    nav[aria-label="Pagination Navigation"] > div:first-child {
        margin-bottom: 15px;
    }

    /* Simple Mode (for Laravel's simple pagination) */
    .simple-pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 30px 0;
    }

    .simple-pagination .page-link {
        padding: 10px 20px;
        background: linear-gradient(135deg, #FFFFFF 0%, #F3F3F3 100%);
        border: 1px solid #3488FC;
        color: #0055D2;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .simple-pagination .page-link:hover {
        background: linear-gradient(135deg, #0055D2 0%, #3488FC 100%);
        color: #FFFFFF;
        transform: translateX(3px);
    }

    .simple-pagination .page-link.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background: #F3F3F3;
        border-color: #e0e6ed;
        color: #b0b9c3;
    }

    /* Responsive Design */
    @media (max-width: 640px) {
        .pagination {
            flex-wrap: wrap;
            padding: 8px;
            gap: 4px;
        }

        .pagination .page-link {
            min-width: 35px;
            height: 35px;
            font-size: 13px;
            padding: 0 10px;
        }

        /* Hide some page numbers on mobile */
        .pagination .page-item:not(.active):not(:first-child):not(:last-child):nth-child(n+5):nth-last-child(n+5) {
            display: none;
        }
    }

    /* Loading State Animation */
    .pagination.loading {
        opacity: 0.5;
        pointer-events: none;
        position: relative;
    }

    .pagination.loading::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 20px;
        height: 20px;
        border: 2px solid #0055D2;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }
</style>

@if ($paginator->hasPages())
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">@lang('pagination.next')</span>
                    </li>
                @endif
            </ul>
        </div>

        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <!-- <div>
                <p class="small text-muted">
                    {!! __('Showing') !!}
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    {!! __('of') !!}
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    {!! __('results') !!}
                </p>
            </div> -->

            <div>
                <ul class="pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                            <span class="page-link" aria-hidden="true">&lsaquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                            <span class="page-link" aria-hidden="true">&rsaquo;</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
