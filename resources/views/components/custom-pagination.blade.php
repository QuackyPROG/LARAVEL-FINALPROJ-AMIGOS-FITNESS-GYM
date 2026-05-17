@if ($paginator->hasPages())
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $blockSize = 3;
        $currentBlock = (int) ceil($currentPage / $blockSize);
        $startPage = ($currentBlock - 1) * $blockSize + 1;
        $endPage = min($startPage + $blockSize - 1, $lastPage);
    @endphp
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center items-center space-x-1">
        
        {{-- Previous Block Arrow --}}
        @if ($currentBlock > 1)
            <button wire:click="gotoPage({{ $startPage - 1 }}, '{{ $paginator->getPageName() }}')" class="p-1.5 text-gray-400 hover:text-amber-400 hover:bg-white/5 rounded-md transition-colors" title="Previous 3 pages">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <span class="px-2 py-1 text-gray-500 tracking-widest">...</span>
        @endif

        {{-- Page Digits --}}
        @for ($page = $startPage; $page <= $endPage; $page++)
            @if ($page == $currentPage)
                <span class="px-3 py-1.5 bg-amber-500/10 text-amber-400 font-bold rounded-lg border border-amber-500/50 shadow-[0_0_10px_rgba(251,191,36,0.1)]">{{ $page }}</span>
            @else
                <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="px-3 py-1.5 text-gray-400 hover:bg-white/10 hover:text-white rounded-lg border border-transparent transition-colors">{{ $page }}</button>
            @endif
        @endfor

        {{-- Next Block Arrow --}}
        @if ($currentBlock < ceil($lastPage / $blockSize))
            <span class="px-2 py-1 text-gray-500 tracking-widest">...</span>
            <button wire:click="gotoPage({{ $endPage + 1 }}, '{{ $paginator->getPageName() }}')" class="p-1.5 text-gray-400 hover:text-amber-400 hover:bg-white/5 rounded-md transition-colors" title="Next 3 pages">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        @endif

    </nav>
@endif