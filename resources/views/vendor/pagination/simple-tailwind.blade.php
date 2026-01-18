<nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        {{-- Hidden on first page --}}
    @else
        <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
            class="flex items-center justify-center w-32 h-11 text-xs bg-gray-200 font-semibold uppercase rounded-xl border border-gray-200 hover:border-gray-400 transition duration-150 ease-in px-6 py-3">
            « Poprzednia
        </button>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
            class="flex items-center justify-center w-32 h-11 text-xs bg-gray-200 font-semibold uppercase rounded-xl border border-gray-200 hover:border-gray-400 transition duration-150 ease-in px-6 py-3">
            Następna »
        </button>
    @else
        {{-- Hidden on last page --}}
    @endif
</nav>