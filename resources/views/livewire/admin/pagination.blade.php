<div>
    @if ($paginator->hasPages())
        <style>
            .pagination-prem {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
            }

            .page-info-prem {
                font-size: 12px;
                color: var(--smoke);
                font-family: 'DM Sans', sans-serif;
                font-weight: 500;
            }

            .pagination-wrapper-prem {
                display: flex;
                align-items: center;
                gap: 4px;
                flex-wrap: wrap;
            }

            .page-btn-prem {
                min-width: 32px;
                height: 32px;
                padding: 0 10px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 1px solid var(--paper2);
                border-radius: 8px;
                background: #fff;
                color: var(--ink);
                font-size: 12px;
                font-weight: 700;
                font-family: 'DM Sans', sans-serif;
                cursor: pointer;
                transition: all .2s;
                text-decoration: none;
            }

            .page-btn-prem:hover:not(:disabled) {
                border-color: var(--ink);
                background: var(--paper);
            }

            .page-btn-prem.active {
                background: var(--red);
                color: #fff;
                border-color: var(--red);
                box-shadow: 0 2px 8px rgba(192, 57, 43, 0.2);
            }

            .page-btn-prem:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }

            .page-btn-prem.ellipsis {
                border: none;
                background: transparent;
                cursor: default;
                min-width: auto;
                padding: 0 2px;
            }

            .page-btn-prem.ellipsis:hover {
                background: transparent;
                border: none;
            }

            @media (max-width: 640px) {
                .pagination-prem {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .pagination-wrapper-prem {
                    width: 100%;
                }
            }
        </style>

        @php
            $current = $paginator->currentPage();
            $last = $paginator->lastPage();

            /*
            |--------------------------------------------------------------------------
            | Bagian awal
            |--------------------------------------------------------------------------
            */
            $firstPages = [1, 2];

            /*
            |--------------------------------------------------------------------------
            | Bagian tengah
            |--------------------------------------------------------------------------
            */
            $middleStart = max(1, $current - 1);
            $middleEnd = min($last, $current + 1);

            /*
            |--------------------------------------------------------------------------
            | Bagian akhir
            |--------------------------------------------------------------------------
            */
            $lastPages = [$last - 2, $last - 1];
        @endphp

        <div class="pagination-prem">

            {{-- INFO --}}
            <span class="page-info-prem">
                Menampilkan
                {{ $paginator->firstItem() }}
                –
                {{ $paginator->lastItem() }}
                dari
                {{ number_format($paginator->total()) }}
                data
            </span>

            {{-- PAGINATION --}}
            <div class="pagination-wrapper-prem">

                {{-- PREVIOUS --}}
                @unless($paginator->onFirstPage())
                    <button class="page-btn-prem" wire:click="previousPage" wire:loading.attr="disabled">

                        <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i>
                    </button>
                @endunless

                {{-- FIRST PAGES --}}
                @foreach($firstPages as $page)
                    @if($page <= $last)

                        <button class="page-btn-prem {{ $page == $current ? 'active' : '' }}" wire:click="gotoPage({{ $page }})">

                            {{ $page }}

                        </button>

                    @endif
                @endforeach

                {{-- DOT BEFORE MIDDLE --}}
                @if($middleStart > 4)
                    <button class="page-btn-prem ellipsis" disabled>

                        ...

                    </button>
                @endif

                {{-- MIDDLE PAGES --}}
                @for($i = $middleStart; $i <= $middleEnd; $i++)

                    @if(
                            !in_array($i, $firstPages) &&
                            !in_array($i, $lastPages)
                        )

                        <button class="page-btn-prem {{ $i == $current ? 'active' : '' }}" wire:click="gotoPage({{ $i }})">

                            {{ $i }}

                        </button>

                    @endif

                @endfor

                {{-- DOT BEFORE LAST --}}
                @if($middleEnd < $last - 3)
                    <button class="page-btn-prem ellipsis" disabled>

                        ...

                    </button>
                @endif

                {{-- LAST PAGES --}}
                @foreach($lastPages as $page)

                    @if($page > 3 && $page <= $last)

                        <button class="page-btn-prem {{ $page == $current ? 'active' : '' }}" wire:click="gotoPage({{ $page }})">

                            {{ $page }}

                        </button>

                    @endif

                @endforeach

                {{-- NEXT --}}
                @if($paginator->hasMorePages())
                    <button class="page-btn-prem" wire:click="nextPage" wire:loading.attr="disabled">

                        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
                    </button>
                @endif

            </div>
        </div>
    @endif
</div>