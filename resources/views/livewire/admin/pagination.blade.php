<div>
    @if ($paginator->hasPages())
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 px-2">
            <!-- Information Section -->
             <div class="flex items-center gap-3">
                <div class="text-[16px] font-black text-black uppercase tracking-[0.2em] flex items-center gap-2">
                    Menampilkan
                    <span class="text-black">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="text-black">{{ $paginator->lastItem() }}</span>
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
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 cursor-not-allowed border border-slate-100">
                        <i class="fas fa-chevron-left text-[16px]"></i>
                    </span>
                @else
                    <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-black hover:text-orange-600 hover:border-orange-500 hover:shadow-md transition-all border border-slate-100 active:scale-95 group">
                        <i class="fas fa-chevron-left text-[16px] group-hover:-translate-x-0.5 transition-transform"></i>
                    </button>
                @endif

                {{-- Pagination Elements --}}
                <div class="flex items-center gap-2 hidden sm:flex">
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="w-10 h-10 flex items-center justify-center text-black font-black">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span
                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-orange-600 text-white font-black shadow-lg shadow-orange-600/30 text-[16px] border border-orange-600 relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-tr from-white/0 to-white/20"></div>
                                        {{ $page }}
                                    </span>
                                @else
                                    <button wire:click="gotoPage({{ $page }})"
                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-black font-bold hover:text-orange-600 hover:border-orange-500 hover:bg-orange-50 transition-all border border-slate-100 active:scale-95 text-[16px]">
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
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-black hover:text-orange-600 hover:border-orange-500 hover:shadow-md transition-all border border-slate-100 active:scale-95 group">
                        <i class="fas fa-chevron-right text-[16px] group-hover:translate-x-0.5 transition-transform"></i>
                    </button>
                @else
                    <span
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 cursor-not-allowed border border-slate-100">
                        <i class="fas fa-chevron-right text-[16px]"></i>
                    </span>
                @endif
            </div>
        </div>
    @endif
</div>