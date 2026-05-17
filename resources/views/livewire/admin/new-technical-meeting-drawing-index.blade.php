<div>
    @push('styles')
        <style>
            .tm-page {
                padding: 24px;
                background: var(--paper);
            }

            /* HEADER */
            .tm-hdr {
                margin-bottom: 20px;
            }

            .tm-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 20px;
                font-weight: 700;
                margin: 0 0 4px;
            }

            .tm-hdr p {
                font-size: 12px;
                color: var(--smoke);
                margin: 0;
            }

            /* TYPE TABS */
            .tm-tabs {
                display: flex;
                gap: 4px;
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 12px;
                padding: 4px;
                margin-bottom: 20px;
                width: fit-content;
            }

            .tm-tab {
                padding: 8px 24px;
                border-radius: 9px;
                border: none;
                font-size: 12.5px;
                font-weight: 600;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                color: var(--smoke);
                transition: all .15s;
                background: none;
            }

            .tm-tab.active {
                background: var(--ink);
                color: #fff;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
            }

            /* LAYOUT */
            .tm-layout {
                display: grid;
                grid-template-columns: 260px 1fr;
                gap: 16px;
                align-items: start;
            }

            /* LEFT PANEL */
            .tm-left {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                overflow: visible;
                align-self: start;
            }

            .tm-panel-title {
                padding: 14px 16px;
                border-bottom: 1px solid var(--paper2);
                font-family: 'Cinzel', serif;
                font-size: 12px;
                font-weight: 700;
                color: var(--ink);
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .tm-panel-title i {
                color: var(--red);
            }

            /* FILTER SELECT */
            .tm-filter-group {
                padding: 12px 14px;
                border-bottom: 1px solid var(--paper2);
            }

            .tm-filter-label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke);
                margin-bottom: 6px;
            }

            .tm-filter-sel {
                width: 100%;
                padding: 8px 10px;
                border: 1px solid var(--paper2);
                border-radius: 9px;
                font-size: 12px;
                font-family: 'DM Sans', sans-serif;
                color: var(--ink);
                background: #fff;
                outline: none;
                cursor: pointer;
            }

            .tm-filter-sel:focus {
                border-color: var(--red);
            }

            .tm-search-box {
                padding: 10px 14px;
                border-bottom: 1px solid var(--paper2);
                position: relative;
            }

            .tm-search-box input {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12px;
                font-family: 'DM Sans', sans-serif;
                outline: none;
                transition: all .2s;
                background: #fff;
            }

            .tm-search-box input:focus {
                border-color: var(--red);
                box-shadow: 0 0 0 4px rgba(192, 57, 43, 0.08);
                background: #fff;
            }

            /* MATCH LIST */
            .tm-match-list {
                max-height: 420px;
                overflow-y: auto;
            }

            .tm-match-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px 14px;
                cursor: pointer;
                transition: background .12s;
                border-bottom: 1px solid var(--paper2);
            }

            .tm-match-item:hover {
                background: rgba(247, 244, 239, .7);
            }

            .tm-match-item.active {
                background: rgba(192, 57, 43, .06);
            }

            .tm-match-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                flex-shrink: 0;
            }

            .tm-match-dot.drawn {
                background: #27ae60;
            }

            .tm-match-dot.pending {
                background: var(--paper2);
                border: 1px solid #bbb;
            }

            .tm-match-text {
                flex: 1;
                min-width: 0;
            }

            .tm-match-text .name {
                font-size: 12px;
                font-weight: 600;
                color: var(--ink);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .tm-match-text .cnt {
                font-size: 10.5px;
                color: var(--smoke);
            }

            /* STATS BAR */
            .tm-stats {
                display: flex;
                gap: 8px;
                margin-bottom: 16px;
            }

            .tm-stat-pill {
                flex: 1;
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                padding: 10px 12px;
                text-align: center;
            }

            .tm-stat-pill .val {
                font-family: 'Cinzel', serif;
                font-size: 20px;
                font-weight: 700;
            }

            .tm-stat-pill .lbl {
                font-size: 10px;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .05em;
            }

            /* RIGHT PANEL */
            .tm-right {
                display: flex;
                flex-direction: column;
                gap: 14px;
                min-width: 0;
            }

            /* CARD */
            .tm-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                overflow: hidden;
            }

            .tm-card-head {
                padding: 14px 18px;
                border-bottom: 1px solid var(--paper2);
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .tm-card-head .icon {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                flex-shrink: 0;
            }

            .tm-card-head .icon.randori {
                background: rgba(192, 57, 43, .1);
                color: var(--red);
            }

            .tm-card-head .icon.embu {
                background: rgba(212, 168, 67, .12);
                color: #b8860b;
            }

            .tm-card-head .info {
                flex: 1;
            }

            .tm-card-head .info h3 {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                font-weight: 700;
                margin: 0 0 2px;
            }

            .tm-card-head .info p {
                font-size: 11px;
                color: var(--smoke);
                margin: 0;
            }

            .tm-card-body {
                padding: 16px 18px;
            }

            /* PARTICIPANTS */
            .participant-list {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 12px;
                margin-bottom: 20px;
            }

            .p-cont-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 12px;
                padding: 12px 14px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, .02);
            }

            .p-cont-name {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 12px;
                font-weight: 700;
                color: var(--ink);
                margin-bottom: 8px;
                padding-bottom: 8px;
                border-bottom: 1px dashed var(--paper2);
            }

            .p-cont-ava {
                width: 24px;
                height: 24px;
                border-radius: 6px;
                background: var(--ink);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: 700;
                font-family: 'Cinzel', serif;
            }

            .p-ath-list {
                list-style: none;
                padding: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 4px;
            }

            .p-ath-item {
                font-size: 12px;
                color: var(--ink);
                display: flex;
                align-items: flex-start;
                gap: 6px;
                font-weight: 500;
            }

            .p-ath-item::before {
                content: '';
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background: var(--red);
                opacity: .8;
                margin-top: 6px;
                flex-shrink: 0;
            }

            /* DRAWING RESULT TABLE */
            .draw-result-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 12px;
                flex-wrap: wrap;
                gap: 8px;
            }

            .draw-result-head h4 {
                font-family: 'Cinzel', serif;
                font-size: 12.5px;
                font-weight: 700;
                margin: 0;
            }

            .draw-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 10.5px;
                font-weight: 600;
            }

            .draw-badge.done {
                background: rgba(39, 174, 96, .1);
                color: #27ae60;
            }

            .draw-badge.pending {
                background: var(--paper);
                color: var(--smoke);
            }

            .draw-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12.5px;
            }

            .draw-table th {
                padding: 12px 16px;
                background: #fafafa;
                font-size: 10px;
                color: var(--smoke);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
                text-align: left;
                border-bottom: 2px solid var(--paper2);
            }

            .draw-table td {
                padding: 12px 16px;
                border-bottom: 1px solid var(--paper2);
                vertical-align: top;
            }

            .draw-table tr:hover td {
                background: rgba(0, 0, 0, 0.015);
            }

            .draw-table tr:last-child td {
                border-bottom: none;
            }

            .draw-num {
                width: 28px;
                height: 28px;
                border-radius: 8px;
                background: var(--ink);
                color: #fff;
                font-family: 'Cinzel', serif;
                font-size: 12px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            /* POOL SECTION */
            .pool-section {
                margin-bottom: 16px;
            }

            .pool-label {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 12px;
                background: rgba(212, 168, 67, .12);
                color: #b8860b;
                border-radius: 20px;
                font-size: 10.5px;
                font-weight: 700;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            /* ACTION BUTTONS */
            .tm-actions {
                display: flex;
                gap: 8px;
                flex-wrap: wrap;
            }

            .btn-gen {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 9px 18px;
                border-radius: 10px;
                border: none;
                font-size: 12.5px;
                font-weight: 700;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                transition: all .15s;
            }

            .btn-gen.primary {
                background: var(--red);
                color: #fff;
                box-shadow: 0 4px 12px rgba(192, 57, 43, .25);
            }

            .btn-gen.primary:hover {
                background: var(--red-deep);
                transform: translateY(-1px);
            }

            .btn-gen.ghost {
                background: #fff;
                color: var(--smoke);
                border: 1px solid var(--paper2);
            }

            .btn-gen.ghost:hover {
                border-color: var(--red);
                color: var(--red);
            }

            .btn-gen:disabled {
                opacity: .6;
                cursor: not-allowed;
                transform: none !important;
            }

            /* EMPTY */
            .tm-empty {
                padding: 48px 20px;
                text-align: center;
            }

            .tm-empty i {
                font-size: 36px;
                color: var(--paper2);
                margin-bottom: 12px;
            }

            .tm-empty h4 {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                color: var(--ink);
                margin: 0 0 4px;
            }

            .tm-empty p {
                font-size: 12px;
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

            .bracket-scroll {
                overflow-x: auto;
                padding: 20px;
                display: flex;
                gap: 32px;
                align-items: flex-start;
                scrollbar-width: thin;
            }

            .bracket-round-col {
                display: flex;
                flex-direction: column;
                gap: 16px;
                width: 240px;
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
                padding: 12px 14px;
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
                font-size: 12.5px;
                font-weight: 700;
                color: var(--ink);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .b-slot-cont {
                font-size: 10.5px;
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
            }

            @media (max-width: 901px) {
                .tm-layout {
                    grid-template-columns: 1fr;
                }

                .tm-left {
                    position: static;
                    max-height: none;
                }
            }
        </style>
    @endpush

    <div class="tm-page">

        {{-- HEADER --}}
        <div class="tm-hdr">
            @if ($selectedMatch)
                <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                    <div>
                        <h2>{{ $selectedMatch->name }}</h2>
                        <p>{{ $selectedMatch->ageGroup->name ?? '-' }} · {{ $matchAthletes->count() }} kontingen</p>
                    </div>
                    @php
                        $isDrawn =
                            $selectedMatch instanceof \App\Models\MatchNumberMerge
                                ? $selectedMatch->matchNumbers->every(fn($mn) => $mn->drawing_generated_at)
                                : $selectedMatch->drawing_generated_at;
                    @endphp
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span class="draw-badge {{ $isDrawn ? 'done' : 'pending' }}"
                            style="font-size: 11px; padding: 6px 14px;">
                            @if ($isDrawn)
                            <i class="fa-solid fa-check-circle"></i> Sudah Di-draw @else<i
                                    class="fa-solid fa-clock"></i> Belum Di-draw
                            @endif
                        </span>
                    </div>
                </div>
            @else
                <h2><i class="fa-solid fa-dice" style="color:var(--red);margin-right:8px;"></i>Drawing Technical Meeting
                </h2>
                <p>Pilih jenis, kelompok umur, dan nomor pertandingan untuk melihat peserta dan generate drawing</p>
            @endif
        </div>

        {{-- TYPE TABS --}}
        <div class="tm-tabs">
            <button class="tm-tab {{ $draftType === 'randori' ? 'active' : '' }}"
                wire:click="$set('draftType','randori')">
                <i class="fa-solid fa-fist-raised"></i> Randori
            </button>
            <button class="tm-tab {{ $draftType === 'embu' ? 'active' : '' }}" wire:click="$set('draftType','embu')">
                <i class="fa-solid fa-wind"></i> Embu
            </button>
            <button class="tm-tab {{ $draftType === 'jadwal' ? 'active' : '' }}"
                wire:click="$set('draftType','jadwal')">
                <i class="fa-solid fa-calendar-alt"></i> Jadwal
            </button>
            <div style="margin-left: auto; display:flex; gap: 8px;">
                @if ($draftType === 'jadwal')
                    <button class="btn-gen primary" wire:click="exportExcel" wire:loading.attr="disabled"
                        style="background:#27ae60; box-shadow:0 4px 12px rgba(39, 174, 96, .25);">
                        <i class="fa-solid fa-file-excel"></i> Export Excel
                    </button>
                @else
                    <button class="btn-gen primary" wire:click="generateAllDrawings" wire:loading.attr="disabled" wire:target="generateAllDrawings">
                        <i class="fa-solid fa-magic" wire:loading.remove wire:target="generateAllDrawings"></i>
                        <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="generateAllDrawings"></i>
                        Generate Semua
                    </button>
                    <button class="btn-gen ghost" onclick="confirmResetAll()" wire:loading.attr="disabled">
                        <i class="fa-solid fa-rotate-left"></i> Reset Semua
                    </button>
                @endif
            </div>
        </div>

        {{-- STATS --}}
        <div class="tm-stats">
            <div class="tm-stat-pill">
                <div class="val" style="color:var(--ink);">{{ $stats['total'] }}</div>
                <div class="lbl">Total Kelas</div>
            </div>
            <div class="tm-stat-pill">
                <div class="val" style="color:#27ae60;">{{ $stats['drawn'] }}</div>
                <div class="lbl">Sudah Di-draw</div>
            </div>
            <div class="tm-stat-pill">
                <div class="val" style="color:var(--red);">{{ $stats['pending'] }}</div>
                <div class="lbl">Belum Di-draw</div>
            </div>
        </div>

        {{-- TWO-COLUMN LAYOUT --}}
        <div class="tm-layout">

            {{-- LEFT: FILTER PANEL --}}
            <div class="tm-left" x-data="{ openPanel: true }">
                <div class="tm-panel-title" style="cursor:pointer; user-select:none;"
                    @click.stop="openPanel = !openPanel">
                    <i class="fa-solid fa-filter"></i> Filter
                </div>

                <div x-show="openPanel">
                    <div class="tm-filter-group">
                        <div class="tm-filter-label">Kelompok Umur</div>
                        <select wire:model.live="filterAgeGroupId" class="tm-filter-sel">
                            <option value="">— Semua KU —</option>
                            @foreach ($filterAgeGroups as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="tm-search-box">
                        <input type="text" wire:model.live.debounce.300ms="searchMatchNumber"
                            placeholder="Cari nomor pertandingan...">
                    </div>

                    <div class="tm-match-list">
                        @if ($filterMerges->count() > 0)
                            <div
                                class="px-4 py-2 bg-indigo-50 text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-100">
                                <i class="fas fa-object-group mr-1"></i> Merge Nomer Pertandingan
                            </div>
                            @foreach ($filterMerges as $merge)
                                <div class="tm-match-item {{ $filterMergeId == $merge->id ? 'active' : '' }}"
                                    wire:click="selectMerge({{ $merge->id }})">
                                    <span
                                        class="tm-match-dot {{ $merge->matchNumbers->every(fn($mn) => $mn->drawing_generated_at) ? 'drawn' : 'pending' }}"></span>
                                    <div class="tm-match-text">
                                        <div class="name">{{ $merge->display_name }}</div>
                                        <div class="cnt">{{ $merge->ageGroup->name ?? '-' }} · <span
                                                style="color:var(--red); font-weight:700;">{{ $merge->matchNumbers->count() }}
                                                Kelas Digabung</span></div>
                                    </div>
                                </div>
                            @endforeach
                            <div
                                class="px-4 py-2 bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <i class="fas fa-list-check mr-1"></i> Nomor Standar
                            </div>
                        @endif

                        @forelse($filterMatchNumbers as $mn)
                            <div class="tm-match-item {{ $filterMatchNumberId == $mn->id ? 'active' : '' }}"
                                wire:click="selectMatch({{ $mn->id }})">
                                <span
                                    class="tm-match-dot {{ $mn->drawing_generated_at ? 'drawn' : 'pending' }}"></span>
                                <div class="tm-match-text">
                                    <div class="name">{{ $mn->name }}</div>
                                    <div class="cnt">{{ $mn->ageGroup->name ?? '-' }} · <span
                                            style="color:var(--red); font-weight:700;">{{ $contingentCounts[$mn->id] ?? 0 }}
                                            Kontingen</span></div>
                                </div>
                                @if ($mn->drawing_generated_at)
                                    <i class="fa-solid fa-check" style="font-size:9px;color:#27ae60;flex-shrink:0;"></i>
                                @endif
                            </div>
                        @empty
                            <div style="padding:20px;text-align:center;font-size:12px;color:var(--smoke);">Tidak ada
                                kelas tersedia</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT: CONTENT --}}
            <div class="tm-right">

                @if ($draftType === 'jadwal')
                    <div style="display:flex; gap:12px; margin-bottom: 20px;">
                        <div class="tm-card" style="flex:1; display:flex; align-items:center; gap:16px;">
                            <div style="padding: 12px 20px;">
                                <div
                                    style="font-size:11px; font-weight:700; color:var(--smoke); text-transform:uppercase;">
                                    Total Pertandingan</div>
                                <div
                                    style="font-size:24px; font-weight:800; font-family:'Cinzel', serif; color:var(--ink);">
                                    {{ $scheduleStats['total'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="tm-card" style="flex:1; display:flex; align-items:center; gap:16px;">
                            <div style="padding: 12px 20px;">
                                <div
                                    style="font-size:11px; font-weight:700; color:var(--smoke); text-transform:uppercase;">
                                    Total Embu</div>
                                <div
                                    style="font-size:24px; font-weight:800; font-family:'Cinzel', serif; color:var(--ink);">
                                    {{ $scheduleStats['embu'] ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="tm-card" style="flex:1; display:flex; align-items:center; gap:16px;">
                            <div style="padding: 12px 20px;">
                                <div
                                    style="font-size:11px; font-weight:700; color:var(--smoke); text-transform:uppercase;">
                                    Total Randori</div>
                                <div
                                    style="font-size:24px; font-weight:800; font-family:'Cinzel', serif; color:var(--ink);">
                                    {{ $scheduleStats['randori'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    @forelse($scheduleByRundown as $rId => $rundownData)
                        <div class="tm-card" style="margin-bottom: 24px; border: 2px solid var(--paper2);">
                            <div class="tm-card-head" style="background: var(--ink); color: #fff; padding: 12px 20px;">
                                <div class="icon" style="background: rgba(255,255,255,0.1); color: #fff;"><i
                                        class="fa-solid fa-calendar-day"></i></div>
                                <div class="info">
                                    <h3 style="color: #fff;">{{ $rundownData['model']->name ?? 'Rundown' }}</h3>
                                    <p style="color: rgba(255,255,255,0.7);">
                                        {{ \Carbon\Carbon::parse($rundownData['model']->date)->isoFormat('dddd, D MMMM YYYY') }}
                                    </p>
                                </div>
                            </div>
                            <div class="tm-card-body" style="padding: 0;">
                                @foreach ($rundownData['sessions'] as $sId => $sessionData)
                                    <div
                                        style="background: var(--paper); padding: 10px 20px; border-bottom: 1px solid var(--paper2); font-weight: 800; font-size: 12px; color: var(--smoke); display: flex; align-items: center; gap: 8px;">
                                        <div
                                            style="width:24px; height:24px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#64748b;">
                                            <i class="fa-solid fa-clock"></i></div>
                                        {{ $sessionData['model']->name ?? 'Sesi' }}
                                        <span
                                            style="font-weight: 500; opacity: 0.7; font-family:'DM Mono', monospace;">({{ Carbon\Carbon::parse($sessionData['model']->start_time)->isoFormat('HH:mm') }}
                                            -
                                            {{ Carbon\Carbon::parse($sessionData['model']->end_time)->isoFormat('HH:mm') }})</span>
                                    </div>
                                    <div style="overflow-x:auto;">
                                        <table class="draw-table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 80px;">Waktu</th>
                                                    <th style="width: 40px;">Dur</th>
                                                    @foreach ($courts as $court)
                                                        <th
                                                            style="min-width: 150px; border-left: 1px solid var(--paper2);">
                                                            {{ $court->name }}</th>
                                                        <th style="width: 80px;">Code</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sessionData['times'] as $time => $row)
                                                    <tr>
                                                        <td
                                                            style="font-weight: 800; color: var(--ink); font-family:'DM Mono', monospace; font-size:13px; vertical-align:middle;">
                                                            {{ $time }}</td>
                                                        <td
                                                            style="color: var(--smoke); font-size: 10px; vertical-align:middle;">
                                                            <span
                                                                style="background:#f1f5f9; padding:2px 6px; border-radius:4px; font-weight:600;">{{ $row['duration'] }}'</span>
                                                        </td>
                                                        @foreach ($courts as $court)
                                                            @php $entries = $row['courts'][$court->id] ?? []; @endphp
                                                            @if (count($entries) > 0)
                                                                <td
                                                                    style="font-size: 11px; padding: 8px 12px; border-left: 1px solid var(--paper2);">
                                                                    <div style="font-weight: 700;">
                                                                        {{ $entries[0]->merge->name ?? $entries[0]->matchNumber->name }}
                                                                    </div>

                                                                    @if ($entries[0]->draft_type === 'randori')
                                                                        @php
                                                                            $red = null;
                                                                            $blue = null;
                                                                            foreach ($entries as $e) {
                                                                                if (
                                                                                    ($e->metadata['side'] ?? '') ===
                                                                                    'RED'
                                                                                ) {
                                                                                    $red = $e;
                                                                                } else {
                                                                                    $blue = $e;
                                                                                }
                                                                            }
                                                                            if (!$blue && count($entries) > 1) {
                                                                                $blue = $entries[1];
                                                                            }
                                                                            if (!$red && count($entries) > 0) {
                                                                                $red = $entries[0];
                                                                            }
                                                                        @endphp
                                                                        <div
                                                                            style="display: flex; flex-direction: column; gap: 4px; margin-top: 6px;">
                                                                            <div
                                                                                style="display: flex; align-items: center; gap: 6px;">
                                                                                <div
                                                                                    style="width:4px; height:12px; background:var(--red); border-radius:2px;">
                                                                                </div>
                                                                                <div
                                                                                    style="color: var(--ink); font-size: 10.5px; font-weight: 600;">
                                                                                    {{ $red->registration?->contingent?->name ?? ($red->metadata['contingent'] ?? 'TBD') }}
                                                                                </div>
                                                                            </div>
                                                                            <div
                                                                                style="display: flex; align-items: center; gap: 6px;">
                                                                                <div
                                                                                    style="width:4px; height:12px; background:#3b82f6; border-radius:2px;">
                                                                                </div>
                                                                                <div
                                                                                    style="color: var(--ink); font-size: 10.5px; font-weight: 600;">
                                                                                    {{ $blue->registration?->contingent?->name ?? ($blue->metadata['contingent'] ?? 'TBD') }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div
                                                                            style="color: var(--smoke); font-size: 10px; margin-top: 2px;">
                                                                            {{ $entries[0]->registration?->contingent?->name ?? ($entries[0]->metadata['contingent'] ?? 'TBD') }}
                                                                        </div>
                                                                    @endif

                                                                    <div style="margin-top: 6px;"><span
                                                                            class="draw-badge done"
                                                                            style="font-size: 8px; padding: 2px 6px;">{{ $entries[0]->round }}</span>
                                                                    </div>
                                                                </td>
                                                                <td
                                                                    style="font-family: 'DM Mono', monospace; font-size: 11px; color: var(--red); font-weight: 700; text-align: center;">
                                                                    {{ $entries[0]->metadata['match_id_code'] ?? '-' }}<br>
                                                                    <span
                                                                        style="color: var(--smoke); font-size: 9px;">#{{ $entries[0]->sequence_number }}</span>
                                                                </td>
                                                            @else
                                                                <td colspan="2"
                                                                    style="text-align: center; color: var(--paper2); font-style: italic; background: rgba(0,0,0,0.01); border-left: 1px solid var(--paper2);">
                                                                    Istirahat</td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="tm-card">
                            <div class="tm-empty"><i class="fa-solid fa-calendar-times"></i>
                                <h4>Belum Ada Jadwal</h4>
                                <p>Silahkan lakukan drawing pada tab Randori atau Embu terlebih dahulu.</p>
                            </div>
                        </div>
                    @endforelse
                @elseif($selectedMatch)
                    {{-- Match Info Card --}}
                    <div class="tm-card">
                        <div class="tm-card-head">
                            <div class="icon {{ $draftType }}">
                                @if ($draftType === 'randori')
                                <i class="fa-solid fa-fist-raised"></i>@else<i class="fa-solid fa-wind"></i>
                                @endif
                            </div>
                            <div class="info">
                                <h3>{{ $selectedMatch->display_name }}</h3>
                                <p>{{ $selectedMatch->ageGroup->name ?? '-' }} @if (isset($selectedMatch->gender))
                                        · {{ $selectedMatch->gender === 'L' ? 'Putra' : 'Putri' }}
                                    @endif · {{ $matchAthletes->count() }} kontingen</p>
                            </div>
                            @php
                                $isDrawn =
                                    $selectedMatch instanceof \App\Models\MatchNumberMerge
                                        ? $selectedMatch->matchNumbers->every(fn($mn) => $mn->drawing_generated_at)
                                        : $selectedMatch->drawing_generated_at;
                            @endphp
                            <span class="draw-badge {{ $isDrawn ? 'done' : 'pending' }}">
                                @if ($isDrawn)
                                <i class="fa-solid fa-check-circle"></i> Sudah Di-draw @else<i
                                        class="fa-solid fa-clock"></i> Belum Di-draw
                                @endif
                            </span>
                        </div>
                        <div class="tm-card-body">
                            <div class="participant-list">
                                @foreach ($matchAthletes as $contName => $aths)
                                    <div class="p-cont-card">
                                        <div class="p-cont-name">
                                            <div class="p-cont-ava">{{ substr($contName, 0, 1) }}</div>
                                            {{ $contName }}
                                        </div>
                                        <ul class="p-ath-list">
                                            @foreach ($aths as $ath)
                                                <li class="p-ath-item">{{ $ath->athlete_name }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>

                            <div class="draw-result-head">
                                <h4>Hasil Drawing & Bagan</h4>
                                <div class="tm-actions">
                                    @if ($isDrawn)
                                        <button onclick="confirmReset()" class="btn-gen ghost"><i
                                                class="fa-solid fa-rotate-left"></i> Reset</button>
                                    @endif
                                    <button
                                        wire:click="{{ $draftType === 'randori' ? 'generateRandoriDrawing' : 'generateEmbuDrawing' }}"
                                        class="btn-gen primary" {{ $isGenerating ? 'disabled' : '' }}>
                                        @if ($isGenerating)
                                        <i class="fa-solid fa-spinner fa-spin"></i> Generating...@else<i
                                                class="fa-solid fa-wand-magic-sparkles"></i>
                                            {{ $isDrawn ? 'Generate Ulang' : 'Generate Drawing' }}
                                        @endif
                                    </button>
                                </div>
                            </div>

                            @if ($isDrawn)
                                <div style="margin-top:20px;">
                                    @if ($draftType === 'randori' && isset($selectedMatch->drawing_data['upper_bracket']))
                                        <div class="bracket-wrapper">
                                            <div class="bracket-hdr ub"><i class="fa-solid fa-sitemap"></i> Bagan
                                                Pemenang (Upper Bracket)</div>
                                            <div class="bracket-scroll">
                                                @foreach ($selectedMatch->drawing_data['upper_bracket']['rounds'] as $rIdx => $round)
                                                    <div class="bracket-round-col">
                                                        <div class="bracket-round-title">Babak {{ $rIdx + 1 }}
                                                        </div>
                                                        @foreach ($round as $mIdx => $match)
                                                            <div class="b-match">
                                                                <div
                                                                    class="b-slot {{ ($match['winner'] ?? null) === 'athlete1' ? 'winner' : '' }}">
                                                                    <div class="b-slot-color red"></div>
                                                                    <div class="b-slot-info">
                                                                        <div class="b-slot-name">
                                                                            {{ $match['athlete1']['name'] ?? 'TBD' }}
                                                                        </div>
                                                                        <div class="b-slot-cont">
                                                                            {{ $match['athlete1']['contingent'] ?? '-' }}
                                                                        </div>
                                                                    </div>
                                                                    @if (($match['winner'] ?? null) === 'athlete1')
                                                                        <i class="fa-solid fa-trophy b-win-icon"></i>
                                                                    @endif
                                                                </div>
                                                                <div
                                                                    class="b-slot {{ ($match['winner'] ?? null) === 'athlete2' ? 'winner' : '' }}">
                                                                    <div class="b-slot-color blue"></div>
                                                                    <div class="b-slot-info">
                                                                        <div class="b-slot-name">
                                                                            {{ $match['athlete2']['name'] ?? 'TBD' }}
                                                                        </div>
                                                                        <div class="b-slot-cont">
                                                                            {{ $match['athlete2']['contingent'] ?? '-' }}
                                                                        </div>
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

                                        @if (isset($selectedMatch->drawing_data['lower_bracket']['rounds']) &&
                                                count($selectedMatch->drawing_data['lower_bracket']['rounds']) > 0)
                                            <div class="bracket-wrapper" style="margin-top:40px;">
                                                <div class="bracket-hdr lb"><i class="fa-solid fa-rotate-left"></i>
                                                    Bagan Harapan (Lower Bracket)</div>
                                                <div class="bracket-scroll">
                                                    @foreach ($selectedMatch->drawing_data['lower_bracket']['rounds'] as $rIdx => $round)
                                                        <div class="bracket-round-col">
                                                            <div class="bracket-round-title">LB Round
                                                                {{ $rIdx + 1 }}</div>
                                                            @foreach ($round as $mIdx => $match)
                                                                <div class="b-match">
                                                                    <div
                                                                        class="b-slot {{ ($match['winner'] ?? null) === 'athlete1' ? 'winner' : '' }}">
                                                                        <div class="b-slot-color red"></div>
                                                                        <div class="b-slot-info">
                                                                            <div class="b-slot-name">
                                                                                {{ $match['athlete1']['name'] ?? 'TBD' }}
                                                                            </div>
                                                                            <div class="b-slot-cont">
                                                                                {{ $match['athlete1']['contingent'] ?? '-' }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="b-slot {{ ($match['winner'] ?? null) === 'athlete2' ? 'winner' : '' }}">
                                                                        <div class="b-slot-color blue"></div>
                                                                        <div class="b-slot-info">
                                                                            <div class="b-slot-name">
                                                                                {{ $match['athlete2']['name'] ?? 'TBD' }}
                                                                            </div>
                                                                            <div class="b-slot-cont">
                                                                                {{ $match['athlete2']['contingent'] ?? '-' }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if (isset($selectedMatch->drawing_data['grand_final']))
                                            <div class="bracket-wrapper" style="margin-top:40px;">
                                                <div class="bracket-hdr gf"><i class="fa-solid fa-crown"></i> Grand
                                                    Final</div>
                                                <div class="bracket-scroll" style="justify-content:center;">
                                                    <div class="bracket-round-col">
                                                        <div class="bracket-round-title">Final Match</div>
                                                        <div class="b-match" style="width:300px;">
                                                            <div
                                                                class="b-slot {{ ($selectedMatch->drawing_data['grand_final']['winner'] ?? null) === 'athlete1' ? 'winner' : '' }}">
                                                                <div class="b-slot-color red"></div>
                                                                <div class="b-slot-info">
                                                                    <div class="b-slot-name">
                                                                        {{ $selectedMatch->drawing_data['grand_final']['athlete1']['name'] ?? 'Winner UB' }}
                                                                    </div>
                                                                    <div class="b-slot-cont">
                                                                        {{ $selectedMatch->drawing_data['grand_final']['athlete1']['contingent'] ?? '-' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="b-slot {{ ($selectedMatch->drawing_data['grand_final']['winner'] ?? null) === 'athlete2' ? 'winner' : '' }}">
                                                                <div class="b-slot-color blue"></div>
                                                                <div class="b-slot-info">
                                                                    <div class="b-slot-name">
                                                                        {{ $selectedMatch->drawing_data['grand_final']['athlete2']['name'] ?? 'Winner LB' }}
                                                                    </div>
                                                                    <div class="b-slot-cont">
                                                                        {{ $selectedMatch->drawing_data['grand_final']['athlete2']['contingent'] ?? '-' }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif

                                    @foreach ($drawingEntries as $groupLabel => $groupEntries)
                                        <div class="pool-section">
                                            <div
                                                style="display:flex; justify-content:space-between; align-items:center;">
                                                <div class="pool-label"><i class="fa-solid fa-users-viewfinder"></i>
                                                    {{ $groupLabel }}</div>
                                                <button
                                                    wire:click="openEditModal({{ $groupEntries->first()->pool_id }})"
                                                    class="btn-gen ghost" style="padding:2px 8px; font-size:10px;"><i
                                                        class="fa-solid fa-edit"></i> Edit Penugasan</button>
                                            </div>
                                            <div
                                                style="background:var(--paper); padding:10px 14px; border-radius:10px; margin-bottom:10px; display:flex; gap:20px; font-size:11px; border:1px solid var(--paper2);">
                                                <div><span
                                                        style="color:var(--smoke); font-weight:700; text-transform:uppercase; font-size:9px;">Tanggal:</span>
                                                    <span
                                                        style="font-weight:700; color:var(--red);">{{ \Carbon\Carbon::parse($groupEntries->first()->rundown->date ?? now())->isoFormat('D MMM YYYY') }}</span>
                                                </div>
                                                <div><span
                                                        style="color:var(--smoke); font-weight:700; text-transform:uppercase; font-size:9px;">Court:</span>
                                                    <span
                                                        style="font-weight:700;">{{ $groupEntries->first()->court->name ?? '-' }}</span>
                                                </div>
                                                <div><span
                                                        style="color:var(--smoke); font-weight:700; text-transform:uppercase; font-size:9px;">Sesi:</span>
                                                    <span
                                                        style="font-weight:700;">{{ $groupEntries->first()->sessionTime->name ?? '-' }}</span>
                                                </div>
                                                <div><span
                                                        style="color:var(--smoke); font-weight:700; text-transform:uppercase; font-size:9px;">Koor:</span>
                                                    <span
                                                        style="font-weight:700;">{{ $groupEntries->first()->metadata['officials']['koordinator_lapangan'] ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <table class="draw-table">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Kontingen</th>
                                                        <th>Waktu</th>
                                                        <th>ID Code</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $groupedByTime = $groupEntries->groupBy(
                                                            fn($e) => ($e->metadata['start_time'] ?? '00:00') .
                                                                ($e->metadata['end_time'] ?? '00:00'),
                                                        );
                                                    @endphp
                                                    @foreach ($groupedByTime as $timeKey => $timeEntries)
                                                        @php $isPair = count($timeEntries) > 1; @endphp
                                                        @foreach ($timeEntries as $idx => $entry)
                                                            <tr
                                                                style="{{ $isPair && $idx === 0 ? 'border-top: 2px solid var(--paper2);' : '' }}">
                                                                <td>
                                                                    @if ($idx === 0)
                                                                        <span
                                                                            class="draw-num">{{ $entry->sequence_number }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        style="display:flex; align-items:center; gap:8px;">
                                                                        @if (isset($entry->metadata['side']))
                                                                            <div
                                                                                style="width:4px; height:16px; border-radius:2px; background:{{ $entry->metadata['side'] === 'RED' ? '#ef4444' : '#3b82f6' }};">
                                                                            </div>
                                                                        @endif
                                                                        <div
                                                                            style="font-weight:700; color:var(--ink);">
                                                                            {{ $entry->registration?->contingent?->name ?? ($entry->metadata['contingent'] ?? 'TBD') }}
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    @if ($idx === 0)
                                                                        <div
                                                                            style="font-weight:700; color:var(--red);">
                                                                            {{ $entry->metadata['start_time'] ?? '-' }}
                                                                            - {{ $entry->metadata['end_time'] ?? '-' }}
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div
                                                                        style="font-family:'DM Mono',monospace; font-size:10px; color:var(--smoke);">
                                                                        {{ $entry->metadata['match_id_code'] ?? '-' }}-{{ $entry->sequence_number }}
                                                                    </div>
                                                                </td>
                                                                <td><span class="draw-badge done"
                                                                        style="font-size:9px;"><i
                                                                            class="fa-solid fa-check"></i> Siap</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="tm-card">
                        <div class="tm-empty"><i class="fa-solid fa-hand-pointer"></i>
                            <h4>Pilih Nomor Pertandingan</h4>
                            <p>Pilih kelompok umur dan nomor pertandingan di panel kiri untuk melihat peserta dan
                                generate drawing.</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    @if ($showEditModal)
        <div
            style="position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:9999; display:flex; align-items:center; justify-content:center; padding:20px;">
            <div
                style="background:#fff; width:100%; max-width:500px; border-radius:20px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.3s ease-out;">
                <div
                    style="padding:20px 24px; border-bottom:1px solid var(--paper2); display:flex; justify-content:space-between; align-items:center; background:var(--ink); color:#fff;">
                    <h3 style="margin:0; font-family:'Cinzel',serif; font-size:16px;">Manual Edit Penugasan</h3>
                    <button wire:click="$set('showEditModal', false)"
                        style="background:none; border:none; color:#fff; cursor:pointer; font-size:20px;">&times;</button>
                </div>
                <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div><label
                                style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Lapangan</label>
                            <select wire:model="editCourtId" class="tm-filter-sel">
                                <option value="">— Pilih Lapangan —</option>
                                @foreach ($courts as $c)
                                    <option value="{{ $c->id }}">Court {{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label
                                style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Sesi
                                Waktu</label>
                            <select wire:model="editSessionId" class="tm-filter-sel">
                                <option value="">— Pilih Sesi —</option>
                                @foreach ($sessionTimes as $st)
                                    <option value="{{ $st->id }}">{{ $st->name }}
                                        ({{ substr($st->start_time, 0, 5) }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div><label
                            style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Koordinator
                            Lapangan</label>
                        <select wire:model="editKoorName" class="tm-filter-sel">
                            <option value="">— Pilih Koordinator —</option>
                            @foreach ($koorUsers as $user)
                                <option value="{{ $user->name }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label
                            style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Panitera
                            (Pilih 4)</label>
                        <div style="position:relative; margin-bottom:8px;"><input type="text"
                                wire:model.live.debounce.300ms="searchPanitera" placeholder="Cari nama panitera..."
                                style="width:100%; padding:10px 12px; border:1px solid var(--paper2); border-radius:10px; font-size:12.5px;">
                        </div>
                        <div
                            style="max-height:150px; overflow-y:auto; border:1px solid var(--paper2); border-radius:10px; padding:10px; display:flex; flex-direction:column; gap:6px;">
                            @foreach ($paniteraUsers as $user)
                                <label
                                    style="display:flex; align-items:center; gap:8px; font-size:12.5px; cursor:pointer;"><input
                                        type="checkbox" wire:model="editPaniteraNames"
                                        value="{{ $user->name }}"><span>{{ $user->name }}</span></label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div
                    style="padding:16px 24px; background:var(--paper); border-top:1px solid var(--paper2); display:flex; justify-content:flex-end; gap:12px;">
                    <button wire:click="$set('showEditModal', false)" class="btn-gen ghost">Batal</button>
                    <button wire:click="saveAssignments" class="btn-gen primary"
                        {{ count($editPaniteraNames) != 4 ? 'disabled' : '' }}>Simpan Perubahan</button>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            function confirmReset() {
                Swal.fire({
                    title: 'Reset Drawing?',
                    text: 'Data drawing akan dihapus dan bisa di-generate ulang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#c0392b',
                    cancelButtonColor: '#7f8c8d',
                    confirmButtonText: 'Ya, Reset!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((r) => {
                    if (r.isConfirmed) @this.resetDrawing();
                });
            }

            function confirmResetAll() {
                Swal.fire({
                    title: 'Reset Semua Drawing?',
                    text: 'SEMUA data drawing akan dihapus dan harus di-generate ulang.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#c0392b',
                    cancelButtonColor: '#7f8c8d',
                    confirmButtonText: 'Ya, Reset Semua!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((r) => {
                    if (r.isConfirmed) @this.resetAllDrawings();
                });
            }
        </script>
    @endpush
</div>
