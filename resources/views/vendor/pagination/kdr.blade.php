@if ($paginator->hasPages())
    <nav class="kdr-pagination" role="navigation" aria-label="Pagination">
        <ul class="kdr-pagination__list">
            {{-- Previous --}}
            <li>
                @if ($paginator->onFirstPage())
                    <span class="kdr-pagination__btn kdr-pagination__btn--disabled" aria-disabled="true">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="kdr-pagination__btn" rel="prev" aria-label="Previous page">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="kdr-pagination__dots">…</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li>
                            @if ($page == $paginator->currentPage())
                                <span class="kdr-pagination__btn kdr-pagination__btn--active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="kdr-pagination__btn" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="kdr-pagination__btn" rel="next" aria-label="Next page">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </a>
                @else
                    <span class="kdr-pagination__btn kdr-pagination__btn--disabled" aria-disabled="true">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </span>
                @endif
            </li>
        </ul>
        <p class="kdr-pagination__meta text-muted small mb-0 mt-2">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} vehicles
        </p>
    </nav>
@endif
