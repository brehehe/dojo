<div>
    @if ($paginator->hasPages())
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-2">
            <!-- Information Section -->
             <div class="flex items-center gap-3">
                <div class="h-8 w-[2px] bg-slate-100 rounded-full hidden md:block"></div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                    Menampilkan
                    <span class="text-slate-800">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="text-slate-800">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="text-orange-600">{{ $paginator->total() }}</span>
                    Hasil
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="flex items-center gap-1.5">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-300 cursor-not-allowed border border-slate-100">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-slate-600 hover:text-orange-600 hover:border-orange-500 hover:shadow-md transition-all border border-slate-100 active:scale-95 group">
                        <i class="fas fa-chevron-left text-[10px] group-hover:-translate-x-0.5 transition-transform"></i>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                <div class="flex items-center gap-2 hidden sm:flex">
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="w-9 h-9 flex items-center justify-center text-slate-400 font-black">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-orange-600 text-white font-black shadow-lg shadow-orange-600/30 text-xs border border-orange-600 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-tr from-white/0 to-white/20"></div>
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-slate-500 font-bold hover:text-orange-600 hover:border-orange-500 hover:bg-orange-50 transition-all border border-slate-100 active:scale-95 text-xs">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif  
                    @endforeach
                </div>

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <button wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-slate-600 hover:text-orange-600 hover:border-orange-500 hover:shadow-md transition-all border border-slate-100 active:scale-95 group">
                        <i class="fas fa-chevron-right text-[10px] group-hover:translate-x-0.5 transition-transform"></i>
                    </button>
                @else
                    <span
                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-300 cursor-not-allowed border border-slate-100">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>