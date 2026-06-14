<div>
    @push('styles')
        <style>
            /* ── HERO ── */
            .sch-hero {
                background: var(--ink);
                padding: 24px 20px 28px;
                position: relative;
                overflow: hidden;
            }

            .sch-hero::before {
                content: '';
                position: absolute;
                top: -60px;
                right: -60px;
                width: 180px;
                height: 180px;
                background: radial-gradient(circle, rgba(192, 57, 43, .4) 0%, transparent 70%);
                pointer-events: none;
            }

            .sch-hero-label {
                font-size: 9.5px;
                color: var(--smoke);
                font-weight: 700;
                letter-spacing: .2em;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .sch-hero-title {
                font-family: 'Cinzel', serif;
                font-size: 22px;
                font-weight: 700;
                color: #fff;
                margin: 0 0 4px;
            }

            .sch-hero-sub {
                font-size: 12px;
                color: var(--smoke);
                margin: 0;
            }

            /* ── FILTER TABS ── */
            .sch-filters {
                display: flex;
                background: #fff;
                border-bottom: 1px solid var(--paper2);
                position: sticky;
                top: 0;
                z-index: 50;
                padding: 12px 16px;
                gap: 8px;
            }

            .sch-filter-btn {
                flex: 1;
                padding: 10px 4px;
                border-radius: 10px;
                border: 1px solid var(--paper2);
                background: var(--paper);
                color: var(--smoke);
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
                transition: all .2s;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
            }

            .sch-filter-btn.active {
                background: var(--red);
                color: #fff;
                border-color: var(--red);
                box-shadow: 0 4px 12px rgba(192, 57, 43, .25);
            }

            /* ── DAY GROUPS ── */
            .sch-container {
                padding: 16px;
            }

            .sch-day-hdr {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 24px 0 16px;
            }

            .sch-day-hdr span {
                font-family: 'Cinzel', serif;
                font-size: 11px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .15em;
                white-space: nowrap;
            }

            .sch-day-line {
                flex: 1;
                height: 1px;
                background: var(--paper2);
            }

            /* ── MATCH CARDS ── */
            .sch-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .sch-card {
                background: #fff;
                border-radius: 16px;
                border: 1px solid var(--paper2);
                padding: 16px;
                position: relative;
                overflow: hidden;
            }

            .sch-card-top {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                margin-bottom: 12px;
            }

            .sch-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
            }

            .sch-badge.red {
                background: rgba(192, 57, 43, .1);
                color: var(--red);
            }

            .sch-badge.blue {
                background: rgba(52, 152, 219, .1);
                color: #2980b9;
            }

            .sch-badge.gold {
                background: rgba(212, 168, 67, .15);
                color: var(--gold-lt);
            }

            .sch-match-code {
                font-size: 11px;
                color: var(--smoke);
                font-weight: 700;
                font-family: 'Cinzel', serif;
            }

            .sch-match-name {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 12px;
                line-height: 1.4;
            }

            .sch-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .sch-item {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .sch-item i {
                width: 14px;
                font-size: 12px;
                color: var(--smoke);
                text-align: center;
            }

            .sch-item span {
                font-size: 11.5px;
                font-weight: 600;
                color: var(--smoke);
            }

            .sch-item b {
                font-size: 11.5px;
                font-weight: 700;
                color: var(--ink);
                margin-left: auto;
            }

            /* ── EMPTY ── */
            .sch-empty {
                padding: 60px 24px;
                text-align: center;
            }

            .sch-empty i {
                font-size: 40px;
                color: var(--paper2);
                margin-bottom: 16px;
                display: block;
            }

            .sch-empty h4 {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                color: var(--smoke);
                margin: 0;
            }

            /* ELEGANT BRACKET */
            .bracket-wrapper {
                margin-bottom: 24px;
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                overflow: hidden;
            }

            .bracket-hdr {
                padding: 12px 18px;
                border-bottom: 1px solid var(--paper2);
                font-family: 'Cinzel', serif;
                font-size: 13px;
                font-weight: 700;
                color: var(--ink);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .bracket-hdr.ub {
                background: rgba(41, 128, 185, .05);
            }

            .bracket-hdr.lb {
                background: rgba(192, 57, 43, .05);
            }

            .bracket-hdr.gf {
                background: rgba(212, 168, 67, .05);
            }

            .bracket-scroll {
                overflow-x: auto;
                padding: 20px;
                display: flex;
                gap: 24px;
                align-items: flex-start;
                scrollbar-width: thin;
            }

            .bracket-round-col {
                display: flex;
                flex-direction: column;
                gap: 16px;
                width: 220px;
                flex-shrink: 0;
            }

            .bracket-round-title {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke);
                text-align: center;
                margin-bottom: 4px;
            }

            .b-match {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 12px;
                overflow: hidden;
                transition: all .2s;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .02);
            }

            .b-slot {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 12px;
                transition: background .15s;
            }

            .b-slot+.b-slot {
                border-top: 1px solid var(--paper2);
            }

            .b-slot.winner {
                background: rgba(39, 174, 96, .06);
            }

            .b-slot-color {
                width: 4px;
                height: 18px;
                border-radius: 2px;
                flex-shrink: 0;
            }

            .b-slot-color.red {
                background: var(--red);
            }

            .b-slot-color.blue {
                background: #2980b9;
            }

            .b-slot-info {
                flex: 1;
                min-width: 0;
            }

            .b-slot-name {
                font-size: 12px;
                font-weight: 700;
                color: var(--ink);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .b-slot-cont {
                font-size: 10px;
                color: var(--smoke);
                font-weight: 600;
                text-transform: uppercase;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .b-win-icon {
                color: #27ae60;
                font-size: 11px;
                flex-shrink: 0;
                margin-left: auto;
            }
        </style>
    @endpush

    {{-- ── HERO ── --}}
    <div class="sch-hero">
        <div class="sch-hero-label">Jadwal Pertandingan</div>
        <h2 class="sch-hero-title">{{ $contingent->name }}</h2>
        <p class="sch-hero-sub">Daftar jadwal per tanding, sesi, dan bagan bagan randori</p>
    </div>

    {{-- Tab Toggles --}}
    <div style="display: flex; border-bottom: 1px solid var(--paper2); background: #fff; position: sticky; top: 0; z-index: 40;">
        <button wire:click="$set('activeTab', 'schedule')"
            style="flex: 1; padding: 14px 8px; border: none; border-bottom: 3px solid {{ $activeTab === 'schedule' ? 'var(--red)' : 'transparent' }}; background: transparent; font-family: 'Cinzel', serif; font-size: 12px; font-weight: 700; color: {{ $activeTab === 'schedule' ? 'var(--ink)' : 'var(--smoke)' }}; text-transform: uppercase; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-calendar-days" style="margin-right: 6px;"></i> Jadwal Saya
        </button>
        <button wire:click="$set('activeTab', 'bracket')"
            style="flex: 1; padding: 14px 8px; border: none; border-bottom: 3px solid {{ $activeTab === 'bracket' ? 'var(--red)' : 'transparent' }}; background: transparent; font-family: 'Cinzel', serif; font-size: 12px; font-weight: 700; color: {{ $activeTab === 'bracket' ? 'var(--ink)' : 'var(--smoke)' }}; text-transform: uppercase; cursor: pointer; transition: all 0.2s;">
            <i class="fa-solid fa-sitemap" style="margin-right: 6px;"></i> Bagan Randori
        </button>
    </div>

    @if ($activeTab === 'schedule')
        {{-- ── FILTERS ── --}}
        <div class="sch-filters">
            <button wire:click="$set('filterType', 'all')"
                class="sch-filter-btn {{ $filterType === 'all' ? 'active' : '' }}">Semua</button>
            <button wire:click="$set('filterType', 'embu')"
                class="sch-filter-btn {{ $filterType === 'embu' ? 'active' : '' }}">Embu</button>
            <button wire:click="$set('filterType', 'randori')"
                class="sch-filter-btn {{ $filterType === 'randori' ? 'active' : '' }}">Randori</button>
        </div>

        <div class="sch-container">
            @forelse($schedules as $date => $daySchedules)
                <div class="sch-day-hdr">
                    <span>{{ $date !== 'Belum Dijadwalkan' ? \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') : 'Belum Dijadwalkan' }}</span>
                    <div class="sch-day-line"></div>
                </div>

                <div class="sch-list">
                    @foreach ($daySchedules as $drawing)
                        <div class="sch-card">
                            <div class="sch-card-top">
                                @if ($drawing->draft_type === 'embu')
                                    <span class="sch-badge blue"><i class="fa-solid fa-users"></i> Embu</span>
                                @else
                                    <span class="sch-badge red"><i class="fa-solid fa-hand-fist"></i> Randori</span>
                                @endif

                                @if ($drawing->round)
                                    <span class="sch-badge gold">{{ $drawing->round }}</span>
                                @endif

                                <span class="sch-match-code">{{ $drawing->metadata['match_id_code'] ?? '-' }}</span>
                            </div>

                            <h3 class="sch-match-name">{{ $drawing->matchNumber->name ?? 'Pertandingan' }}</h3>

                            {{-- Participant detail --}}
                            <div style="margin-bottom: 12px; padding: 10px; background: var(--paper); border-radius: 8px; border: 1px solid var(--paper2);">
                                <div style="font-size: 9px; font-weight: 750; color: var(--smoke); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Peserta:</div>
                                @if ($drawing->draft_type === 'embu')
                                    <div style="font-size: 11.5px; font-weight: 700; color: var(--ink);">
                                        {{ $drawing->registration?->athletes->pluck('name')->join(', ') ?: ($drawing->metadata['athlete_name'] ?? 'TBD') }}
                                    </div>
                                @else
                                    <div style="font-size: 11.5px; font-weight: 700; color: var(--ink); display: flex; align-items: center; gap: 6px;">
                                        <span class="text-red-700">{{ $drawing->metadata['athlete_name'] ?? 'TBD' }}</span>
                                        <span style="font-size: 9px; font-weight: 500; color: var(--smoke);">VS</span>
                                        <span class="text-blue-700">{{ $drawing->metadata['blue_athlete_name'] ?? 'TBD' }}</span>
                                    </div>
                                @endif
                            </div>

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
    @elseif ($activeTab === 'bracket')
        <div class="sch-container">
            <div style="background: #fff; border: 1px solid var(--paper2); border-radius: 16px; p-4; padding: 16px; margin-bottom: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <label style="font-size: 10px; font-weight: 800; color: var(--smoke); text-transform: uppercase; tracking-wider; margin-bottom: 6px; display: block;">Pilih Kategori Randori:</label>
                <div class="custom-select-wrapper" style="position: relative;">
                    <select wire:model.live="selectedBracketMatchId"
                        style="width: 100%; padding: 12px; border: 1px solid var(--paper2); border-radius: 12px; background: var(--paper); color: var(--ink); font-size: 13px; font-weight: 700; outline: none; cursor: pointer;">
                        @forelse($randoriMatchNumbers as $rmn)
                            <option value="{{ $rmn->id }}">{{ $rmn->ageGroup->name ?? '' }} - {{ $rmn->name }}</option>
                        @empty
                            <option value="">Tidak ada bagan randori tersedia</option>
                        @endforelse
                    </select>
                </div>
            </div>

            @if ($selectedBracketMatch)
                <div style="margin-bottom: 12px;">
                    <h3 style="font-family: 'Cinzel', serif; font-size: 16px; font-weight: 700; color: var(--ink); text-transform: uppercase; margin: 0 0 4px;">{{ $selectedBracketMatch->name }}</h3>
                    <p style="font-size: 11px; color: var(--smoke); margin: 0;">{{ $selectedBracketMatch->ageGroup->name ?? '' }} &nbsp;·&nbsp; {{ $selectedBracketMatch->gender === 'L' ? 'Putra' : 'Putri' }}</p>
                </div>

                @if (isset($selectedBracketMatch->drawing_data['upper_bracket']))
                    <div class="bracket-wrapper" style="margin-top: 20px;">
                        <div class="bracket-hdr ub"><i class="fa-solid fa-sitemap text-blue-600"></i> Bagan Pemenang (Upper Bracket)</div>
                        <div class="bracket-scroll">
                            @foreach ($selectedBracketMatch->drawing_data['upper_bracket']['rounds'] as $rIdx => $round)
                                <div class="bracket-round-col">
                                    <div class="bracket-round-title">Babak {{ $rIdx + 1 }}</div>
                                    @foreach ($round as $mIdx => $match)
                                        <div class="b-match">
                                            <div class="b-slot {{ ($match['winner'] ?? null) === 'athlete1' ? 'winner' : '' }}">
                                                <div class="b-slot-color red"></div>
                                                <div class="b-slot-info">
                                                    <div class="b-slot-name">{{ $match['athlete1']['name'] ?? 'TBD' }}</div>
                                                    <div class="b-slot-cont">{{ $match['athlete1']['contingent'] ?? '-' }}</div>
                                                </div>
                                                @if (($match['winner'] ?? null) === 'athlete1')
                                                    <i class="fa-solid fa-trophy b-win-icon"></i>
                                                @endif
                                            </div>
                                            <div class="b-slot {{ ($match['winner'] ?? null) === 'athlete2' ? 'winner' : '' }}">
                                                <div class="b-slot-color blue"></div>
                                                <div class="b-slot-info">
                                                    <div class="b-slot-name">{{ $match['athlete2']['name'] ?? 'TBD' }}</div>
                                                    <div class="b-slot-cont">{{ $match['athlete2']['contingent'] ?? '-' }}</div>
                                                </div>
                                                @if (($match['winner'] ?? null) === 'athlete2')
                                                    <i class="fa-solid fa-trophy b-win-icon"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($selectedBracketMatch->drawing_data['lower_bracket']['rounds']) && count($selectedBracketMatch->drawing_data['lower_bracket']['rounds']) > 0)
                    <div class="bracket-wrapper" style="margin-top: 30px;">
                        <div class="bracket-hdr lb"><i class="fa-solid fa-rotate-left text-red-600"></i> Bagan Harapan (Lower Bracket)</div>
                        <div class="bracket-scroll">
                            @foreach ($selectedBracketMatch->drawing_data['lower_bracket']['rounds'] as $rIdx => $round)
                                <div class="bracket-round-col">
                                    <div class="bracket-round-title">LB Round {{ $rIdx + 1 }}</div>
                                    @foreach ($round as $mIdx => $match)
                                        <div class="b-match">
                                            <div class="b-slot {{ ($match['winner'] ?? null) === 'athlete1' ? 'winner' : '' }}">
                                                <div class="b-slot-color red"></div>
                                                <div class="b-slot-info">
                                                    <div class="b-slot-name">{{ $match['athlete1']['name'] ?? 'TBD' }}</div>
                                                    <div class="b-slot-cont">{{ $match['athlete1']['contingent'] ?? '-' }}</div>
                                                </div>
                                                @if (($match['winner'] ?? null) === 'athlete1')
                                                    <i class="fa-solid fa-trophy b-win-icon"></i>
                                                @endif
                                            </div>
                                            <div class="b-slot {{ ($match['winner'] ?? null) === 'athlete2' ? 'winner' : '' }}">
                                                <div class="b-slot-color blue"></div>
                                                <div class="b-slot-info">
                                                    <div class="b-slot-name">{{ $match['athlete2']['name'] ?? 'TBD' }}</div>
                                                    <div class="b-slot-cont">{{ $match['athlete2']['contingent'] ?? '-' }}</div>
                                                </div>
                                                @if (($match['winner'] ?? null) === 'athlete2')
                                                    <i class="fa-solid fa-trophy b-win-icon"></i>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (isset($selectedBracketMatch->drawing_data['grand_final']))
                    <div class="bracket-wrapper" style="margin-top: 30px;">
                        <div class="bracket-hdr gf"><i class="fa-solid fa-crown text-amber-500"></i> Grand Final</div>
                        <div class="bracket-scroll" style="justify-content: center;">
                            <div class="bracket-round-col">
                                <div class="bracket-round-title">Final Match</div>
                                <div class="b-match" style="width: 220px;">
                                    <div class="b-slot {{ ($selectedBracketMatch->drawing_data['grand_final']['winner'] ?? null) === 'athlete1' ? 'winner' : '' }}">
                                        <div class="b-slot-color red"></div>
                                        <div class="b-slot-info">
                                            <div class="b-slot-name">{{ $selectedBracketMatch->drawing_data['grand_final']['athlete1']['name'] ?? 'Winner UB' }}</div>
                                            <div class="b-slot-cont">{{ $selectedBracketMatch->drawing_data['grand_final']['athlete1']['contingent'] ?? '-' }}</div>
                                        </div>
                                        @if (($selectedBracketMatch->drawing_data['grand_final']['winner'] ?? null) === 'athlete1')
                                            <i class="fa-solid fa-trophy b-win-icon"></i>
                                        @endif
                                    </div>
                                    <div class="b-slot {{ ($selectedBracketMatch->drawing_data['grand_final']['winner'] ?? null) === 'athlete2' ? 'winner' : '' }}">
                                        <div class="b-slot-color blue"></div>
                                        <div class="b-slot-info">
                                            <div class="b-slot-name">{{ $selectedBracketMatch->drawing_data['grand_final']['athlete2']['name'] ?? 'Winner LB' }}</div>
                                            <div class="b-slot-cont">{{ $selectedBracketMatch->drawing_data['grand_final']['athlete2']['contingent'] ?? '-' }}</div>
                                        </div>
                                        @if (($selectedBracketMatch->drawing_data['grand_final']['winner'] ?? null) === 'athlete2')
                                            <i class="fa-solid fa-trophy b-win-icon"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="sch-empty">
                    <i class="fa-solid fa-sitemap"></i>
                    <h4>Belum ada bagan tanding yang digenerate</h4>
                </div>
            @endif
        </div>
    @endif
</div>
