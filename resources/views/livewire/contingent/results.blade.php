<div>
    @push('styles')
    <style>
    /* ── HERO ── */
    .res-hero {
        background: var(--ink); padding: 24px 20px 28px;
        position: relative; overflow: hidden;
    }
    .res-hero::before {
        content: ''; position: absolute;
        top: -60px; right: -60px; width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(192,57,43,.4) 0%, transparent 70%);
        pointer-events: none;
    }
    .res-hero-label {
        font-size: 9.5px; color: var(--smoke); font-weight: 700;
        letter-spacing: .2em; text-transform: uppercase; margin-bottom: 8px;
    }
    .res-hero-title {
        font-family: 'Cinzel', serif; font-size: 22px; font-weight: 700;
        color: #fff; margin: 0 0 4px;
    }
    .res-hero-sub { font-size: 12px; color: var(--smoke); margin: 0; }

    /* ── FILTER TABS ── */
    .res-filters {
        display: flex; background: #fff; border-bottom: 1px solid var(--paper2);
        position: sticky; top: 0; z-index: 50; padding: 12px 16px; gap: 8px;
    }
    .res-filter-btn {
        flex: 1; padding: 10px 4px; border-radius: 10px; border: 1px solid var(--paper2);
        background: var(--paper); color: var(--smoke); font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em; transition: all .2s;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
    }
    .res-filter-btn.active {
        background: var(--red); color: #fff; border-color: var(--red);
        box-shadow: 0 4px 12px rgba(192,57,43,.25);
    }

    /* ── RESULTS LIST ── */
    .res-container { padding: 16px; }
    .res-list { display: flex; flex-direction: column; gap: 12px; }
    .res-card {
        background: #fff; border-radius: 16px; border: 1px solid var(--paper2);
        padding: 18px; position: relative; display: flex; gap: 16px; align-items: flex-start;
    }
    .res-rank {
        width: 48px; height: 48px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .res-rank.gold   { background: linear-gradient(135deg, #f1c40f, #f39c12); color: #fff; }
    .res-rank.silver { background: linear-gradient(135deg, #bdc3c7, #95a5a6); color: #fff; }
    .res-rank.bronze { background: linear-gradient(135deg, #e67e22, #d35400); color: #fff; }
    .res-rank.none   { background: var(--paper); color: var(--smoke); font-family: 'Cinzel', serif; font-size: 16px; font-weight: 700; }

    .res-body { flex: 1; min-width: 0; }
    .res-match-name {
        font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700;
        color: var(--ink); margin: 0 0 4px; line-height: 1.4;
    }
    .res-match-meta { display: flex; gap: 8px; align-items: center; margin-bottom: 12px; }
    .res-badge {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .05em; padding: 2px 8px; border-radius: 4px;
        background: var(--paper); color: var(--smoke);
    }

    .res-athletes {
        display: flex; align-items: center; gap: 8px;
        padding-top: 12px; border-top: 1px solid var(--paper2);
    }
    .res-athletes i { font-size: 14px; color: var(--smoke); }
    .res-athletes span { font-size: 11.5px; font-weight: 600; color: var(--ink); }

    .res-score {
        position: absolute; top: 18px; right: 18px; text-align: right;
    }
    .res-score-val { font-family: 'Cinzel', serif; font-size: 16px; font-weight: 700; color: var(--red); line-height: 1; }
    .res-score-lbl { font-size: 9px; color: var(--smoke); text-transform: uppercase; font-weight: 700; margin-top: 2px; }

    /* ── EMPTY ── */
    .res-empty { padding: 60px 24px; text-align: center; }
    .res-empty i { font-size: 40px; color: var(--paper2); margin-bottom: 16px; display: block; }
    .res-empty h4 { font-family: 'Cinzel', serif; font-size: 14px; color: var(--smoke); margin: 0; }
    </style>
    @endpush

    {{-- ── HERO ── --}}
    <div class="res-hero">
        <div class="res-hero-label">Rekapitulasi Hasil Pertandingan</div>
        <h2 class="res-hero-title">{{ $contingent->name }}</h2>
        <p class="res-hero-sub">Daftar juara dan hasil kejuaraan</p>
    </div>

    {{-- ── FILTERS ── --}}
    <div class="res-filters">
        <button wire:click=\"$set('filterType', 'all')\" class="res-filter-btn {{ $filterType === 'all' ? 'active' : '' }}">Semua</button>
        <button wire:click=\"$set('filterType', 'embu')\" class="res-filter-btn {{ $filterType === 'embu' ? 'active' : '' }}">Embu</button>
        <button wire:click=\"$set('filterType', 'randori')\" class="res-filter-btn {{ $filterType === 'randori' ? 'active' : '' }}">Randori</button>
    </div>

    <div class="res-container">
        <div class="res-list">
            @forelse($results as $champion)
                <div class="res-card">
                    <div class="res-rank {{ $champion->rank === 1 ? 'gold' : ($champion->rank === 2 ? 'silver' : ($champion->rank === 3 ? 'bronze' : 'none')) }}">
                        @if($champion->rank === 1) 🥇 @elseif($champion->rank === 2) 🥈 @elseif($champion->rank === 3) 🥉 @else #{{ $champion->rank }} @endif
                    </div>

                    <div class="res-body">
                        <h3 class="res-match-name">{{ $champion->matchNumber->name ?? '-' }}</h3>
                        <div class="res-match-meta">
                            <span class="res-badge">{{ $champion->draft_type }}</span>
                            <span class="res-badge">{{ $champion->matchNumber->ageGroup->name ?? '' }}</span>
                        </div>

                        @if($champion->accumulated_score > 0)
                        <div class="res-score">
                            <div class="res-score-val">{{ number_format($champion->accumulated_score, 2) }}</div>
                            <div class="res-score-lbl">Skor</div>
                        </div>
                        @endif

                        <div class="res-athletes">
                            <i class="fa-solid fa-circle-user"></i>
                            <span>{{ $champion->athlete_names }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="res-empty">
                    <i class="fa-solid fa-award"></i>
                    <h4>Belum ada hasil kejuaraan yang tercatat</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>
