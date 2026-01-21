@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';

// Smart pagination: show first pages, current, and last page
$currentPage = $paginator->currentPage();
$lastPage = $paginator->lastPage();
$onFirstPage = $paginator->onFirstPage();
$hasMorePages = $paginator->hasMorePages();
$pageName = $paginator->getPageName();

// Build page numbers array: 1, 2, 3, ..., last
$pagesToShow = [];
if ($lastPage <= 7) {
    // Show all pages if 7 or less
    $pagesToShow = range(1, $lastPage);
} else {
    // Always show 1, 2, 3
    $pagesToShow = [1, 2, 3];
    
    // Add ellipsis if current page is far from start
    if ($currentPage > 4) {
        $pagesToShow[] = '...';
    }
    
    // Add pages around current page
    if ($currentPage > 3 && $currentPage < $lastPage - 2) {
        if ($currentPage > 4) {
            $pagesToShow[] = $currentPage - 1;
        }
        $pagesToShow[] = $currentPage;
        if ($currentPage < $lastPage - 3) {
            $pagesToShow[] = $currentPage + 1;
        }
    }
    
    // Add ellipsis before last page if needed
    if ($currentPage < $lastPage - 3) {
        $pagesToShow[] = '...';
    }
    
    // Always show last page
    if (!in_array($lastPage, $pagesToShow)) {
        $pagesToShow[] = $lastPage;
    }
}

// Remove duplicates and sort
$pagesToShow = array_unique($pagesToShow);
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; align-items: center; gap: 6px;">
            {{-- Previous Page Link --}}
            @if ($onFirstPage)
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background: #EDF2F7; color: #A0AEC0; cursor: not-allowed;">
                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <button type="button" wire:click="previousPage('{{ $pageName }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background: #EDF2F7; color: #4A5568; border: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#4FD1C5'; this.style.color='white';" onmouseout="this.style.background='#EDF2F7'; this.style.color='#4A5568';">
                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif

            {{-- Page Numbers --}}
            @foreach ($pagesToShow as $page)
                @if ($page === '...')
                    <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 4px; color: #718096; font-size: 14px;">...</span>
                @elseif ($page == $currentPage)
                    <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px; background: linear-gradient(126.97deg, #4FD1C5 28.26%, #38B2AC 91.2%); color: white; font-size: 13px; font-weight: 600;">{{ $page }}</span>
                @else
                    <button type="button" wire:click="gotoPage({{ $page }}, '{{ $pageName }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" style="display: inline-flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px; border-radius: 8px; background: #EDF2F7; color: #4A5568; font-size: 13px; font-weight: 500; border: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#4FD1C5'; this.style.color='white';" onmouseout="this.style.background='#EDF2F7'; this.style.color='#4A5568';">{{ $page }}</button>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($hasMorePages)
                <button type="button" wire:click="nextPage('{{ $pageName }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background: #EDF2F7; color: #4A5568; border: none; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#4FD1C5'; this.style.color='white';" onmouseout="this.style.background='#EDF2F7'; this.style.color='#4A5568';">
                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 8px; background: #EDF2F7; color: #A0AEC0; cursor: not-allowed;">
                    <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </nav>
    @endif
</div>
