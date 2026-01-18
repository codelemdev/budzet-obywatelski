<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between my-8">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        {{-- Hidden on first page --}}
        <div class="w-20"></div> {{-- Spacer to maintain layout if needed --}}
    @else
        <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
            class="flex items-center justify-center w-32 h-11 bg-gray-200 border border-gray-200 font-bold text-xxs uppercase rounded-xl hover:border-gray-400 transition duration-150 ease-in px-4 py-3">
            « Poprzednia
        </button>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
            class="flex items-center justify-center w-32 h-11 bg-gray-200 border border-gray-200 font-bold text-xxs uppercase rounded-xl hover:border-gray-400 transition duration-150 ease-in px-4 py-3">
            Następna »
        </button>
    @else
        {{-- Hidden on last page --}}
        <div class="w-20"></div>
    @endif
</nav>