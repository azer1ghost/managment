@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if (!$paginator->onFirstPage())
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="page-link" aria-label="@lang('pagination.previous')"><i class="fas fa-arrow-left"></i></a>
                </li>
            @else
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
            <!-- Array Of Links -->
                @foreach ($element as $page => $url)
                <!--  Use three dots when current page is greater than 4.  -->
                    @if ($paginator->currentPage() > 4 && $page === 2)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif

                <!--  Show active page else show the first and last two pages from current page.  -->
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @elseif ($page === $paginator->currentPage() + 1 || $page === $paginator->currentPage() + 2 || $page === $paginator->currentPage() - 1 || $page === $paginator->currentPage() - 2 || $page === $paginator->lastPage() || $page === 1)
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif

                <!--  Use three dots when current page is away from end.  -->
                    @if ($paginator->currentPage() < $paginator->lastPage() - 3 && $page === $paginator->lastPage() - 1)
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    @endif
                @endforeach
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="page-link" aria-label="@lang('pagination.next')"><i class="fas fa-arrow-right"></i></a>
                </li>
            @endif
        </ul>
    </nav>
@endif
