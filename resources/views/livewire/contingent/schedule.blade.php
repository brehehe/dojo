<div>
    @push('styles')
    <style>
    /* ── HERO ── */
    .sch-hero {
        background: var(--ink); padding: 24px 20px 28px;
        position: relative; overflow: hidden;
    }
    .sch-hero::before {
        content: ''; position: absolute;
        top: -60px; right: -60px; width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(192,57,43,.4) 0%, transparent 70%);
        pointer-events: none;
    }
    .sch-hero-label {
        font-size: 9.5px; color: var(--smoke); font-weight: 700;
        letter-spacing: .2em; text-transform: uppercase; margin-bottom: 8px;
    }
    .sch-hero-title {
        font-family: 'Cinzel', serif; font-size: 22px; font-weight: 700;
        color: #fff; margin: 0 0 4px;
    }
    .sch-hero-sub { font-size: 12px; color: var(--smoke); margin: 0; }

    /* ── FILTER TABS ── */
    .sch-filters {
        display: flex; background: #fff; border-bottom: 1px solid var(--paper2);
        position: sticky; top: 0; z-index: 50; padding: 12px 16px; gap: 8px;
    }
    .sch-filter-btn {
        flex: 1; padding: 10px 4px; border-radius: 10px; border: 1px solid var(--paper2);
        background: var(--paper); color: var(--smoke); font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em; transition: all .2s;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
    }
    .sch-filter-btn.active {
        background: var(--red); color: #fff; border-color: var(--red);
        box-shadow: 0 4px 12px rgba(192,57,43,.25);
    }

    /* ── DAY GROUPS ── */
    .sch-container { padding: 16px; }
    .sch-day-hdr {
        display: flex; align-items: center; gap: 12px; margin: 24px 0 16px;
    }
    .sch-day-hdr span {
        font-family: 'Cinzel', serif; font-size: 11px; font-weight: 700;
        color: var(--smoke); text-transform: uppercase; letter-spacing: .15em;
        white-space: nowrap;
    }
    .sch-day-line { flex: 1; height: 1px; background: var(--paper2); }

    /* ── MATCH CARDS ── */
    .sch-list { display: flex; flex-direction: column; gap: 12px; }
    .sch-card {
        background: #fff; border-radius: 16px; border: 1px solid var(--paper2);
        padding: 16px; position: relative; overflow: hidden;
    }
    .sch-card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
    .sch-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
    }
    .sch-badge.red  { background: rgba(192,57,43,.1); color: var(--red); }
    .sch-badge.blue { background: rgba(52,152,219,.1); color: #2980b9; }
    .sch-badge.gold { background: rgba(212,168,67,.15); color: var(--gold-lt); }

    .sch-match-code { font-size: 11px; color: var(--smoke); font-weight: 700; font-family: 'Cinzel', serif; }
    .sch-match-name {
        font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700;
        color: var(--ink); margin: 0 0 12px; line-height: 1.4;
    }

    .sch-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .sch-item { display: flex; align-items: center; gap: 8px; }
    .sch-item i { width: 14px; font-size: 12px; color: var(--smoke); text-align: center; }
    .sch-item span { font-size: 11.5px; font-weight: 600; color: var(--smoke); }
    .sch-item b { font-size: 11.5px; font-weight: 700; color: var(--ink); margin-left: auto; }

    /* ── EMPTY ── */
    .sch-empty {
        padding: 60px 24px; text-align: center;
    }
    .sch-empty i { font-size: 40px; color: var(--paper2); margin-bottom: 16px; display: block; }
    .sch-empty h4 { font-family: 'Cinzel', serif; font-size: 14px; color: var(--smoke); margin: 0; }
    </style>
    @endpush

    {{-- ── HERO ── --}}
    <div class="sch-hero">
        <div class="sch-hero-label">Jadwal Pertandingan</div>
        <h2 class="sch-hero-title">{{ $contingent->name }}</h2>
        <p class="sch-hero-sub">Daftar jadwal per tanding & sesi</p>
    </div>

    {{-- ── FILTERS ── --}}
    <div class="sch-filters">
        <button wire:click=\"$set('filterType', 'all')\" class="sch-filter-btn {{ $filterType === 'all' ? 'active' : '' }}">Semua</button>
        <button wire:click=\"$set('filterType', 'embu')\" class="sch-filter-btn {{ $filterType === 'embu' ? 'active' : '' }}">Embu</button>
        <button wire:click=\"$set('filterType', 'randori')\" class="sch-filter-btn {{ $filterType === 'randori' ? 'active' : '' }}">Randori</button>
    </div>

    <div class="sch-container">
        @forelse($schedules as $date => $daySchedules)
            <div class="sch-day-hdr">
                <span>{{ $date !== 'Belum Dijadwalkan' ? \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') : 'Belum Dijadwalkan' }}</span>
                <div class="sch-day-line"></div>
            </div>

            <div class="sch-list">
                @foreach($daySchedules as $drawing)
                    <div class="sch-card">
                        <div class="sch-card-top">
                            @if($drawing->draft_type === 'embu')
                                <span class="sch-badge blue"><i class="fa-solid fa-users"></i> Embu</span>
                            @else
                                <span class="sch-badge red"><i class="fa-solid fa-hand-fist"></i> Randori</span>
                            @endif

                            @if($drawing->round)
                                <span class="sch-badge gold">{{ $drawing->round }}</span>
                            @endif
                            
                            <span class="sch-match-code">{{ $drawing->metadata['match_id_code'] ?? '-' }}</span>
                        </div>

                        <h3 class="sch-match-name">{{ $drawing->matchNumber->name ?? 'Pertandingan' }}</h3>

                        <div class="sch-grid">
                            <div class="sch-item">
                                <i class="fa-solid fa-map-pin"></i>
                                <span>Court</span>
                                <b>{{ $drawing->court->name ?? '-' }}</b>
                            </div>
                            <div class="sch-item">
                                <i class="fa-solid fa-clock"></i>
                                <span>Sesi</span>
                                <b>{{ $drawing->sessionTime->name ?? '-' }}</b>
                            </div>
                            <div class="sch-item">
                                <i class="fa-solid fa-hourglass-start"></i>
                                <span>Mulai</span>
                                <b style="color:var(--red);">{{ $drawing->metadata['start_time'] ?? '-' }}</b>
                            </div>
                            <div class="sch-item">
                                <i class="fa-solid fa-users-viewfinder"></i>
                                <span>Pool</span>
                                <b>{{ $drawing->metadata['pool_label'] ?? ($drawing->pool->name ?? '-') }}</b>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="sch-empty">
                <i class="fa-solid fa-calendar-xmark"></i>
                <h4>Belum ada jadwal untuk filter ini</h4>
            </div>
        @endforelse
    </div>
</div>
