<div>
    @push('styles')
    <style>
    /* ── HERO ── */
    .std-hero {
        background: var(--ink); padding: 24px 20px 28px;
        position: relative; overflow: hidden;
    }
    .std-hero::before {
        content: ''; position: absolute;
        top: -60px; right: -60px; width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(192,57,43,.4) 0%, transparent 70%);
        pointer-events: none;
    }
    .std-hero-label {
        font-size: 9.5px; color: var(--smoke); font-weight: 700;
        letter-spacing: .2em; text-transform: uppercase; margin-bottom: 8px;
    }
    .std-hero-title {
        font-family: 'Cinzel', serif; font-size: 22px; font-weight: 700;
        color: #fff; margin: 0 0 4px;
    }
    .std-hero-sub { font-size: 12px; color: var(--smoke); margin: 0; }

    /* ── FILTER TABS ── */
    .std-filters {
        display: flex; background: #fff; border-bottom: 1px solid var(--paper2);
        position: sticky; top: 0; z-index: 50; padding: 12px 16px; gap: 8px;
    }
    .std-filter-btn {
        flex: 1; padding: 10px 4px; border-radius: 10px; border: 1px solid var(--paper2);
        background: var(--paper); color: var(--smoke); font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em; transition: all .2s;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
    }
    .std-filter-btn.active {
        background: var(--red); color: #fff; border-color: var(--red);
        box-shadow: 0 4px 12px rgba(192,57,43,.25);
    }

    /* ── STANDINGS LIST ── */
    .std-container { padding: 16px; }
    .std-section { margin-bottom: 24px; }
    .std-section-hdr {
        background: var(--ink); padding: 12px 16px; border-radius: 14px 14px 0 0;
        display: flex; flex-direction: column; gap: 2px;
    }
    .std-section-hdr h3 { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: #fff; margin: 0; }
    .std-section-hdr p { font-size: 10px; color: var(--smoke); margin: 0; font-weight: 600; text-transform: uppercase; }

    .std-table {
        background: #fff; border: 1px solid var(--paper2); border-top: none;
        border-radius: 0 0 14px 14px; overflow: hidden;
    }
    .std-row {
        display: flex; align-items: center; gap: 12px; padding: 12px 16px;
        border-bottom: 1px solid var(--paper); transition: background .2s;
    }
    .std-row:last-child { border-bottom: none; }
    .std-row.highlight { background: rgba(212,168,67,.08); }

    .std-rank { width: 28px; text-align: center; flex-shrink: 0; font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; }
    .std-rank.first { color: #f1c40f; font-size: 20px; }
    .std-rank.second { color: #bdc3c7; font-size: 20px; }
    .std-rank.third { color: #e67e22; font-size: 20px; }
    
    .std-ctg-info { flex: 1; min-width: 0; }
    .std-ctg-name { font-size: 13px; font-weight: 700; color: var(--ink); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .std-ctg-name.mine { color: var(--red); }
    .std-ctg-sub { font-size: 10px; color: var(--smoke); font-weight: 600; text-transform: uppercase; }

    .std-score { text-align: right; min-width: 60px; }
    .std-score-val { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--ink); }
    .std-score-lbl { font-size: 9px; color: var(--smoke); text-transform: uppercase; font-weight: 700; }

    /* ── EMPTY ── */
    .std-empty { padding: 60px 24px; text-align: center; }
    .std-empty i { font-size: 40px; color: var(--paper2); margin-bottom: 16px; display: block; }
    .std-empty h4 { font-family: 'Cinzel', serif; font-size: 14px; color: var(--smoke); margin: 0; }
    </style>
    @endpush

    {{-- ── HERO ── --}}
    <div class="std-hero">
        <div class="std-hero-label">Klasemen & Statistik Terkini</div>
        <h2 class="std-hero-title">{{ $contingent->name }}</h2>
        <p class="std-hero-sub">Posisi kontingen dalam setiap kategori</p>
    </div>

    {{-- ── FILTERS ── --}}
    <div class="std-filters">
        <button wire:click=\"$set('filterType', 'embu')\" class="std-filter-btn {{ $filterType === 'embu' ? 'active' : '' }}">Embu</button>
        <button wire:click=\"$set('filterType', 'randori')\" class="std-filter-btn {{ $filterType === 'randori' ? 'active' : '' }}">Randori</button>
    </div>

    <div class="std-container">
        @if($filterType === 'embu')
            @forelse($standings as $matchNumberId => $scores)
                @php $matchNumber = $scores->first()->matchNumber; @endphp
                <div class="std-section">
                    <div class="std-section-hdr">
                        <h3>{{ $matchNumber->name ?? '-' }}</h3>
                        <p>{{ $matchNumber->ageGroup->name ?? '' }} · {{ $matchNumber->gender_indo ?? '' }}</p>
                    </div>
                    <div class="std-table">
                        @foreach($scores->take(10) as $score)
                            @php $isMine = in_array($score->registration_id, $registrationIds); @endphp
                            <div class="std-row {{ $isMine ? 'highlight' : '' }}">
                                <div class="std-rank {{ $score->rank === 1 ? 'first' : ($score->rank === 2 ? 'second' : ($score->rank === 3 ? 'third' : '')) }}">
                                    @if($score->rank === 1) 🥇 @elseif($score->rank === 2) 🥈 @elseif($score->rank === 3) 🥉 @else {{ $score->rank }} @endif
                                </div>
                                <div class="std-ctg-info">
                                    <p class="std-ctg-name {{ $isMine ? 'mine' : '' }}">
                                        {{ $score->registration->contingent->name ?? '-' }}
                                        @if($isMine) <span style="font-size:8px; background:var(--red); color:#fff; padding:1px 4px; border-radius:3px; margin-left:4px;">ANDA</span> @endif
                                    </p>
                                    <p class="std-ctg-sub">{{ $score->round_label ?? 'Penyisihan' }}</p>
                                </div>
                                <div class="std-score">
                                    <div class="std-score-val">{{ number_format($score->nilai_akhir ?? $score->total_score ?? 0, 2) }}</div>
                                    <div class="std-score-lbl">Skor</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="std-empty">
                    <i class="fa-solid fa-chart-line"></i>
                    <h4>Belum ada klasemen Embu tersedia</h4>
                </div>
            @endforelse
        @else
            {{-- RANDORI --}}
            @forelse($standings as $matchNumber)
                <div class="std-section">
                    <div class="std-section-hdr">
                        <h3>{{ $matchNumber->name }}</h3>
                        <p>{{ $matchNumber->ageGroup->name ?? '' }} · Randori</p>
                    </div>
                    <div class="std-table">
                        @php
                            $myDrawings = $matchNumber->drawings->whereIn('registration_id', $registrationIds);
                        @endphp
                        @forelse($myDrawings as $drawing)
                            <div class="std-row highlight">
                                <div class="std-rank"><i class="fa-solid fa-user-ninja" style="font-size:14px; color:var(--red);"></i></div>
                                <div class="std-ctg-info">
                                    <p class="std-ctg-name mine">{{ $drawing->registration->contingent->name ?? '-' }}</p>
                                    <p class="std-ctg-sub">Pool {{ $drawing->pool->name ?? '-' }} · {{ $drawing->round ?? 'Penyisihan' }}</p>
                                </div>
                                <div class="std-score">
                                    <div class="std-score-val">Active</div>
                                    <div class="std-score-lbl">Status</div>
                                </div>
                            </div>
                        @empty
                            <div class="std-row">
                                <p style="font-size:11px; color:var(--smoke); text-align:center; width:100%;">Tidak ada peserta dari kontingen ini</p>
                            </div>
                        @endforelse

                        @if($matchNumber->randoriResults->count() > 0)
                            <div style="padding: 10px 16px; background: var(--paper); font-size: 9px; font-weight: 700; color: var(--smoke); text-transform: uppercase;">Hasil Terbaru</div>
                            @foreach($matchNumber->randoriResults->take(3) as $result)
                                <div class="std-row">
                                    <div class="std-ctg-info">
                                        <p style="font-size:11.5px; font-weight:600; color:var(--ink);">Bagan Node {{ $result->bracket_node }}</p>
                                        <p class="std-ctg-sub">Pemenang: {{ $result->winner->name ?? '-' }}</p>
                                    </div>
                                    <div class="std-score">
                                        <div class="std-score-val" style="color:var(--red);">{{ $result->score_red }} - {{ $result->score_blue }}</div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @empty
                <div class="std-empty">
                    <i class="fa-solid fa-diagram-project"></i>
                    <h4>Belum ada bagan Randori tersedia</h4>
                </div>
            @endforelse
        @endif
    </div>
</div>
