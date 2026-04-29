@if ($paginator->hasPages())
    <div class="flex items-center justify-between">
        <p class="text-default-500 text-sm">
            Showing <b>{{ $paginator->firstItem() }}</b> to <b>{{ $paginator->lastItem() }}</b> of <b>{{ $paginator->total() }}</b> Results
        </p>

        <nav class="flex items-center gap-2" aria-label="Pagination">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button type="button" class="btn btn-sm border bg-transparent border-default-200 text-default-400 cursor-not-allowed" disabled>
                    <i data-lucide="chevron-left" class="size-4 me-1"></i> Prev
                </button>
            @else
                <button type="button" wire:click="previousPage" wire:loading.attr="disabled" class="btn btn-sm border bg-transparent border-default-200 text-default-600 hover:bg-custom-500/10 hover:text-custom-500 focus:bg-custom-500/10 focus:text-custom-500 hover:border-custom-500/10">
                    <i data-lucide="chevron-left" class="size-4 me-1"></i> Prev
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="btn size-7.5 bg-transparent border border-default-200 text-default-400 cursor-default">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button type="button" class="btn size-7.5 bg-custom-500/10 text-custom-500 font-semibold" aria-current="page">
                                {{ $page }}
                            </button>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }})" class="btn size-7.5 bg-transparent border border-default-200 text-default-600 hover:bg-custom-500/10 hover:text-custom-500 focus:bg-custom-500/10 focus:text-custom-500 hover:border-custom-500/10">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage" wire:loading.attr="disabled" class="btn btn-sm border bg-transparent border-default-200 text-default-600 hover:bg-custom-500/10 hover:text-custom-500 focus:bg-custom-500/10 focus:text-custom-500 hover:border-custom-500/10">
                    Next <i data-lucide="chevron-right" class="size-4 ms-1"></i>
                </button>
            @else
                <button type="button" class="btn btn-sm border bg-transparent border-default-200 text-default-400 cursor-not-allowed" disabled>
                    Next <i data-lucide="chevron-right" class="size-4 ms-1"></i>
                </button>
            @endif
        </nav>
    </div>
@endif
