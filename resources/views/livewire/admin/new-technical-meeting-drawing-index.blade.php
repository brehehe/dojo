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
                overflow: hidden;
                position: sticky;
                top: 16px;
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
                padding: 8px 10px 8px 30px;
                border: 1px solid var(--paper2);
                border-radius: 8px;
                font-size: 11.5px;
                font-family: 'DM Sans', sans-serif;
                outline: none;
                transition: all .15s;
            }

            .tm-search-box input:focus {
                border-color: var(--red);
                box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.05);
            }

            .tm-search-box i {
                position: absolute;
                left: 24px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--smoke);
                font-size: 11px;
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

            .tm-match-item:last-child {
                border-bottom: none;
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
                padding: 8px 12px;
                background: var(--paper);
                font-size: 10px;
                color: var(--smoke);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
                text-align: left;
            }

            .draw-table td {
                padding: 10px 12px;
                border-top: 1px solid var(--paper2);
                vertical-align: middle;
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

            .btn-gen.secondary {
                background: #2980b9;
                color: #fff;
                box-shadow: 0 4px 12px rgba(41, 128, 185, .2);
            }

            .btn-gen.secondary:hover {
                background: #1f6391;
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

            .bracket-hdr.lb {
                background: rgba(211, 84, 0, .05);
            }

            .bracket-hdr.gf {
                background: rgba(243, 156, 18, .05);
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

            .b-match:hover {
                border-color: #bbb;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
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

            .b-slot-empty {
                font-size: 12px;
                color: var(--smoke);
                font-style: italic;
            }

            .b-win-icon {
                color: #27ae60;
                font-size: 11px;
                flex-shrink: 0;
            }

            @media(max-width:900px) {
                .tm-layout {
                    grid-template-columns: 1fr;
                }

                .tm-left {
                    position: static;
                }

                .tm-match-list {
                    max-height: 240px;
                }
            }
        </style>
    @endpush

    <div class="tm-page">

        {{-- HEADER --}}
        <div class="tm-hdr">
            <h2><i class="fa-solid fa-dice" style="color:var(--red);margin-right:8px;"></i>Drawing Technical Meeting
            </h2>
            <p>Pilih jenis, kelompok umur, dan nomor pertandingan untuk generate drawing</p>
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
            <div class="tm-left">
                <div class="tm-panel-title">
                    <i class="fa-solid fa-filter"></i> Filter
                </div>

                {{-- Filter Age Group --}}
                <div class="tm-filter-group">
                    <div class="tm-filter-label">Kelompok Umur</div>
                    <select wire:model.live="filterAgeGroupId" class="tm-filter-sel">
                        <option value="">— Semua KU —</option>
                        @foreach($filterAgeGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Match Number --}}
                <div class="tm-search-box">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" wire:model.live.debounce.300ms="searchMatchNumber" placeholder="Cari nomor pertandingan...">
                </div>

                {{-- Match Number List --}}
                <div class="tm-filter-group" style="padding-bottom:8px;">
                    <div class="tm-filter-label">Nomor Pertandingan</div>
                </div>

                <div class="tm-match-list">
                    @forelse($filterMatchNumbers as $mn)
                        <div class="tm-match-item {{ $filterMatchNumberId == $mn->id ? 'active' : '' }}"
                            wire:click="selectMatch({{ $mn->id }})">
                            <span class="tm-match-dot {{ $mn->drawing_generated_at ? 'drawn' : 'pending' }}"></span>
                            <div class="tm-match-text">
                                <div class="name">{{ $mn->name }}</div>
                                <div class="cnt">
                                    {{ $mn->ageGroup->name ?? '-' }}
                                    @if($mn->gender) · {{ $mn->gender === 'L' ? 'Putra' : 'Putri' }} @endif
                                    <span style="color:var(--red); font-weight:700;"> · {{ $mn->contingent_count }}
                                        Kontingen</span>
                                </div>
                            </div>
                            @if($mn->drawing_generated_at)
                                <i class="fa-solid fa-check" style="font-size:9px;color:#27ae60;flex-shrink:0;"></i>
                            @endif
                        </div>
                    @empty
                        <div style="padding:20px;text-align:center;font-size:12px;color:var(--smoke);">
                            Tidak ada kelas tersedia
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- RIGHT: CONTENT --}}
            <div class="tm-right">

                @if($selectedMatch)

                    {{-- Match Info Card --}}
                    <div class="tm-card">
                        <div class="tm-card-head">
                            <div class="icon {{ $draftType }}">
                                @if($draftType === 'randori')
                                    <i class="fa-solid fa-fist-raised"></i>
                                @else
                                    <i class="fa-solid fa-wind"></i>
                                @endif
                            </div>
                            <div class="info">
                                <h3>{{ $selectedMatch->name }}</h3>
                                <p>
                                    {{ $selectedMatch->ageGroup->name ?? '-' }}
                                    @if($selectedMatch->gender) · {{ $selectedMatch->gender === 'L' ? 'Putra' : 'Putri' }} @endif
                                    · {{ $matchAthletes->count() }} kontingen
                                    @if($selectedMatch->drawing_generated_at)
                                        · <span style="color:#27ae60;font-weight:600;"><i class="fa-solid fa-calendar-check"></i> Terjadwal</span>
                                    @endif
                                </p>
                            </div>
                            <span class="draw-badge {{ $selectedMatch->drawing_generated_at ? 'done' : 'pending' }}">
                                @if($selectedMatch->drawing_generated_at)
                                    <i class="fa-solid fa-check-circle"></i> Sudah Di-draw
                                @else
                                    <i class="fa-solid fa-clock"></i> Belum Di-draw
                                @endif
                            </span>
                        </div>
                        <div class="tm-card-body">
                            {{-- Registered Contingents & Athletes --}}
                            <div
                                style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:12px;">
                                <div
                                    style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--smoke);">
                                    Daftar Kontingen & Atlet
                                </div>
                                <div
                                    style="font-size:11px; font-weight:600; color:var(--ink); background:var(--paper); padding:4px 10px; border-radius:6px; border:1px solid var(--paper2);">
                                    Total: <span
                                        style="font-weight:700; color:var(--red);">{{ $matchAthletes->sum(fn($g) => $g->count()) }}
                                        Atlet</span> dari <span
                                        style="font-weight:700; color:var(--red);">{{ $matchAthletes->count() }}
                                        Kontingen</span>
                                </div>
                            </div>

                            @if($matchAthletes->count() > 0)
                                <div class="participant-list">
                                    @foreach($matchAthletes as $contingentName => $athletes)
                                        <div class="p-cont-card">
                                            <div class="p-cont-name">
                                                <div class="p-cont-ava">{{ strtoupper(substr($contingentName, 0, 2)) }}</div>
                                                <span>{{ $contingentName }}</span>
                                            </div>
                                            <ul class="p-ath-list">
                                                @foreach($athletes as $athlete)
                                                    <li class="p-ath-item">{{ $athlete->athlete_name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="tm-empty" style="padding:20px;">
                                    <p>Belum ada peserta terdaftar.</p>
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="tm-actions"
                                style="margin-top:24px; padding-top:20px; border-top:1px solid var(--paper2);">
                                @if($selectedMatch->drawing_generated_at)
                                    <button
                                        wire:click="{{ $draftType === 'randori' ? 'generateRandoriDrawing' : 'generateEmbuDrawing' }}"
                                        wire:loading.attr="disabled" class="btn-gen secondary">
                                        <span wire:loading.remove
                                            wire:target="{{ $draftType === 'randori' ? 'generateRandoriDrawing' : 'generateEmbuDrawing' }}">
                                            <i class="fa-solid fa-arrows-rotate"></i> Drawing Ulang
                                        </span>
                                        <span wire:loading
                                            wire:target="{{ $draftType === 'randori' ? 'generateRandoriDrawing' : 'generateEmbuDrawing' }}">
                                            <i class="fa-solid fa-spinner fa-spin"></i> Proses...
                                        </span>
                                    </button>
                                    <button onclick="confirmReset()" class="btn-gen ghost">
                                        <i class="fa-solid fa-trash"></i> Reset Drawing
                                    </button>
                                @else
                                    <button
                                        wire:click="{{ $draftType === 'randori' ? 'generateRandoriDrawing' : 'generateEmbuDrawing' }}"
                                        wire:loading.attr="disabled" class="btn-gen primary">
                                        <span wire:loading.remove
                                            wire:target="{{ $draftType === 'randori' ? 'generateRandoriDrawing' : 'generateEmbuDrawing' }}">
                                            <i class="fa-solid fa-dice"></i> Drawing Sekarang
                                        </span>
                                        <span wire:loading
                                            wire:target="{{ $draftType === 'randori' ? 'generateRandoriDrawing' : 'generateEmbuDrawing' }}">
                                            <i class="fa-solid fa-spinner fa-spin"></i> Proses...
                                        </span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Drawing Result Card --}}
                    @if($drawingEntries->count() > 0)
                        <div class="tm-card">
                            <div class="tm-card-head">
                                <div class="icon" style="background:rgba(41,128,185,.1);color:#2980b9;">
                                    <i class="fa-solid fa-table-list"></i>
                                </div>
                                <div class="info">
                                    <h3 style="font-family:'Cinzel',serif; font-size:16px; font-weight:800; color:var(--ink);">
                                        Hasil Drawing: <span style="color:var(--red);">{{ $selectedMatch->name }}</span> = <span
                                            style="color:var(--red);">{{ $matchAthletes->count() }} Kontingen</span>
                                    </h3>
                                    @php
                                        $firstEntry = $drawingEntries->flatten()->first();
                                        $koor = $firstEntry->metadata['officials']['koordinator_lapangan'] ?? null;
                                        $paniteras = $firstEntry->metadata['officials']['panitera'] ?? [];
                                    @endphp
                                    <div style="font-size:12px; display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                                        <span><i class="fa-solid fa-clock"></i> Urutan penampilan berdasarkan undian</span>
                                        @if($firstEntry)
                                            <span style="color:#2980b9; font-weight:700;"><i class="fa-solid fa-map-marker-alt"></i> Court {{ $firstEntry->court->name ?? '-' }}</span>
                                            <span style="color:#2980b9; font-weight:700;"><i class="fa-solid fa-business-time"></i> {{ $firstEntry->sessionTime->name ?? '-' }}</span>
                                        @endif
                                    </div>
                                    @if($koor)
                                    <div style="margin-top:12px; display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                                        <div style="font-size:12.5px; color:#fff; background:#2c3e50; padding:8px 16px; border-radius:8px; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 4px rgba(0,0,0,0.15);">
                                            <i class="fa-solid fa-user-tie" style="color:#f39c12; font-size:14px;"></i>
                                            <span>Koor. Lapangan: <span style="font-weight:800;">{{ $koor }}</span></span>
                                        </div>
                                        @if(count($paniteras) > 0)
                                        <div style="font-size:12.5px; color:#fff; background:#34495e; padding:8px 16px; border-radius:8px; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 4px rgba(0,0,0,0.15);">
                                            <i class="fa-solid fa-users" style="color:#3498db; font-size:14px;"></i>
                                            <span>Panitera: <span style="font-weight:800;">{{ implode(', ', $paniteras) }}</span></span>
                                        </div>
                                        @endif
                                        
                                        <button wire:click="openEditModal" class="btn-gen ghost" style="padding:6px 12px; font-size:11px; border-color:rgba(41,128,185,0.3); color:#2980b9;">
                                            <i class="fa-solid fa-edit"></i> Edit Penugasan
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tm-card-body">

                                @if($draftType === 'randori')
                                    {{-- RANDORI: Visual Bracket per Round (Elegant Horizontal Tree) --}}
                                    @php
                                        $drawData = $selectedMatch->drawing_data ?? [];
                                        $ubRounds = $drawData['upper_bracket']['rounds'] ?? [];
                                        $lbRounds = $drawData['lower_bracket']['rounds'] ?? [];
                                        $gf = $drawData['grand_final'] ?? null;
                                    @endphp

                                    {{-- UPPER BRACKET --}}
                                    <div class="bracket-wrapper">
                                        <div class="bracket-hdr ub">
                                            <i class="fa-solid fa-sitemap" style="color:#2980b9;"></i> Upper Bracket &mdash; Winner
                                            Path
                                        </div>
                                        <div class="bracket-scroll">
                                            @foreach($ubRounds as $rIdx => $ubRound)
                                                @php
                                                    if ($rIdx === count($ubRounds) - 1) {
                                                        $roundLabel = 'UB Final';
                                                    } elseif ($rIdx === count($ubRounds) - 2) {
                                                        $roundLabel = 'UB Semi Final';
                                                    } else {
                                                        $roundLabel = 'UB Round ' . ($rIdx + 1);
                                                    }
                                                @endphp
                                                <div class="bracket-round-col">
                                                    <div class="bracket-round-title">{{ $roundLabel }}</div>
                                                    @foreach($ubRound as $mIdx => $match)
                                                        @php
                                                            $a1 = $match['athlete1'] ?? null;
                                                            $a2 = $match['athlete2'] ?? null;
                                                            $winner = $match['winner'] ?? null;
                                                        @endphp
                                                        <div class="b-match">
                                                            {{-- Red Slot --}}
                                                            <div class="b-slot {{ $winner === 'athlete1' ? 'winner' : '' }}">
                                                                <div class="b-slot-color red"></div>
                                                                <div class="b-slot-info">
                                                                    @if($a1)
                                                                        <div class="b-slot-name">{{ $a1['name'] }}</div>
                                                                        <div class="b-slot-cont">{{ $a1['contingent'] ?? '' }}</div>
                                                                    @else
                                                                        <div class="b-slot-empty">TBD</div>
                                                                    @endif
                                                                </div>
                                                                @if($winner === 'athlete1')<i
                                                                class="fa-solid fa-check b-win-icon"></i>@endif
                                                            </div>
                                                            {{-- Blue Slot --}}
                                                            <div class="b-slot {{ $winner === 'athlete2' ? 'winner' : '' }}">
                                                                <div class="b-slot-color blue"></div>
                                                                <div class="b-slot-info">
                                                                    @if($a2)
                                                                        <div class="b-slot-name">{{ $a2['name'] }}</div>
                                                                        <div class="b-slot-cont">{{ $a2['contingent'] ?? '' }}</div>
                                                                    @else
                                                                        <div class="b-slot-empty">TBD</div>
                                                                    @endif
                                                                </div>
                                                                @if($winner === 'athlete2')<i
                                                                class="fa-solid fa-check b-win-icon"></i>@endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- LOWER BRACKET --}}
                                    @if(count($lbRounds) > 0)
                                        <div class="bracket-wrapper">
                                            <div class="bracket-hdr lb">
                                                <i class="fa-solid fa-rotate-left" style="color:#d35400;"></i> Lower Bracket &mdash;
                                                Repechage
                                            </div>
                                            <div class="bracket-scroll">
                                                @foreach($lbRounds as $lrIdx => $lbRound)
                                                    @php
                                                        if ($lrIdx === count($lbRounds) - 1) {
                                                            $roundLabel = 'LB Final';
                                                        } elseif ($lrIdx === count($lbRounds) - 2) {
                                                            $roundLabel = 'LB Semi Final';
                                                        } else {
                                                            $roundLabel = 'LB Round ' . ($lrIdx + 1);
                                                        }
                                                    @endphp
                                                    <div class="bracket-round-col">
                                                        <div class="bracket-round-title" style="color:#d35400;">{{ $roundLabel }}</div>
                                                        @foreach($lbRound as $lmIdx => $lmatch)
                                                            @php
                                                                $la1 = $lmatch['athlete1'] ?? null;
                                                                $la2 = $lmatch['athlete2'] ?? null;
                                                                $lw = $lmatch['winner'] ?? null;
                                                            @endphp
                                                            <div class="b-match">
                                                                <div class="b-slot {{ $lw === 'athlete1' ? 'winner' : '' }}">
                                                                    <div class="b-slot-color red" style="background:#e74c3c;"></div>
                                                                    <div class="b-slot-info">
                                                                        @if($la1)
                                                                            <div class="b-slot-name">{{ $la1['name'] }}</div>
                                                                            <div class="b-slot-cont">{{ $la1['contingent'] ?? '' }}</div>
                                                                        @else
                                                                            <div class="b-slot-empty">TBD</div>
                                                                        @endif
                                                                    </div>
                                                                    @if($lw === 'athlete1')<i class="fa-solid fa-check b-win-icon"></i>@endif
                                                                </div>
                                                                <div class="b-slot {{ $lw === 'athlete2' ? 'winner' : '' }}">
                                                                    <div class="b-slot-color blue" style="background:#95a5a6;"></div>
                                                                    <div class="b-slot-info">
                                                                        @if($la2)
                                                                            <div class="b-slot-name">{{ $la2['name'] }}</div>
                                                                            <div class="b-slot-cont">{{ $la2['contingent'] ?? '' }}</div>
                                                                        @else
                                                                            <div class="b-slot-empty">TBD</div>
                                                                        @endif
                                                                    </div>
                                                                    @if($lw === 'athlete2')<i class="fa-solid fa-check b-win-icon"></i>@endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{-- GRAND FINAL --}}
                                    @if($gf)
                                        <div class="bracket-wrapper" style="border-color:rgba(243,156,18,.4);">
                                            <div class="bracket-hdr gf">
                                                <i class="fa-solid fa-trophy" style="color:#f39c12;"></i> Grand Final &mdash; UB
                                                Champion vs LB Champion
                                            </div>
                                            <div class="bracket-scroll" style="justify-content:center; padding:32px 20px;">
                                                <div
                                                    style="display:flex; align-items:center; gap:24px; flex-wrap:wrap; justify-content:center;">
                                                    {{-- UB Champ --}}
                                                    <div class="b-match" style="width:260px; text-align:center; padding:24px 16px;">
                                                        <div
                                                            style="font-size:10px; font-weight:700; color:#2980b9; text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px;">
                                                            UB Champion</div>
                                                        @if($gf['athlete1'] ?? null)
                                                            <div class="b-slot-name" style="font-size:15px; margin-bottom:4px;">
                                                                {{ $gf['athlete1']['name'] }}</div>
                                                            <div class="b-slot-cont">{{ $gf['athlete1']['contingent'] ?? '' }}</div>
                                                        @else
                                                            <div class="b-slot-empty">TBD</div>
                                                        @endif
                                                    </div>

                                                    <div
                                                        style="font-family:'Cinzel',serif; font-size:16px; font-weight:700; color:var(--smoke); background:var(--paper); padding:8px 14px; border-radius:12px; border:1px solid var(--paper2);">
                                                        VS</div>

                                                    {{-- LB Champ --}}
                                                    <div class="b-match" style="width:260px; text-align:center; padding:24px 16px;">
                                                        <div
                                                            style="font-size:10px; font-weight:700; color:#d35400; text-transform:uppercase; letter-spacing:.05em; margin-bottom:8px;">
                                                            LB Champion</div>
                                                        @if($gf['athlete2'] ?? null)
                                                            <div class="b-slot-name" style="font-size:15px; margin-bottom:4px;">
                                                                {{ $gf['athlete2']['name'] }}</div>
                                                            <div class="b-slot-cont">{{ $gf['athlete2']['contingent'] ?? '' }}</div>
                                                        @else
                                                            <div class="b-slot-empty">TBD</div>
                                                        @endif
                                                    </div>

                                                    @if($gf['winner'] ?? null)
                                                        <div style="width:100%; display:flex; justify-content:center; margin-top:12px;">
                                                            <div
                                                                style="background:#f39c12; color:#fff; padding:12px 24px; border-radius:12px; text-align:center; box-shadow:0 4px 12px rgba(243,156,18,.3);">
                                                                <div
                                                                    style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin-bottom:4px; opacity:.9;">
                                                                    Juara 1</div>
                                                                <div style="font-size:16px; font-weight:700; font-family:'Cinzel',serif;"><i
                                                                        class="fa-solid fa-crown"></i>
                                                                    {{ $gf['winner_data']['name'] ?? '-' }}</div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                @else
                                    {{-- Embu: Grouped by pool/round (Elegant Premium Theme) --}}
                                    <div style="display:flex; flex-wrap:wrap; gap:20px; align-items:flex-start;">
                                        @foreach($drawingEntries as $poolName => $entries)
                                            @php
                                                // Soft elegant colors for pools
                                                $colors = ['#8e44ad', '#2980b9', '#16a085', '#d35400'];
                                                $poolColor = $colors[$loop->index % count($colors)];
                                                $isPool = str_contains(strtoupper($poolName), 'POOL');
                                            @endphp
                                            <div
                                                style="flex:1 1 min(100%, 400px); background:#fff; border:1px solid var(--paper2); border-radius:16px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,0.02);">
                                                {{-- Header --}}
                                                <div
                                                    style="background:rgba({{ hexdec(substr($poolColor, 1, 2)) }}, {{ hexdec(substr($poolColor, 3, 2)) }}, {{ hexdec(substr($poolColor, 5, 2)) }}, 0.05); border-bottom:1px solid var(--paper2); padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
                                                    <div style="display:flex; align-items:center; gap:16px;">
                                                        <div
                                                            style="width:40px; height:40px; border-radius:10px; background:{{ $poolColor }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:18px; font-weight:800; font-family:'Cinzel',serif; box-shadow:0 2px 8px rgba({{ hexdec(substr($poolColor, 1, 2)) }}, {{ hexdec(substr($poolColor, 3, 2)) }}, {{ hexdec(substr($poolColor, 5, 2)) }}, 0.3);">
                                                            {{ $isPool ? str_replace(['POOL ', 'Pool ', 'pool '], '', $poolName) : substr($poolName, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div
                                                                style="font-size:14px; font-weight:800; color:var(--ink); text-transform:uppercase; font-family:'Cinzel',serif; letter-spacing:0.05em; margin-bottom:2px;">
                                                                {{ $isPool ? 'POOL ' . str_replace(['POOL ', 'Pool ', 'pool '], '', $poolName) : $poolName }}
                                                            </div>
                                                            <div style="display:flex; gap:6px;">
                                                                @if($entries->first() && $entries->first()->court_id)
                                                                    <span
                                                                        style="font-size:10.5px; font-weight:600; color:var(--smoke); background:var(--paper); padding:2px 8px; border-radius:4px; border:1px solid var(--paper2);">
                                                                        Court {{ $entries->first()->court->name ?? '-' }}
                                                                    </span>
                                                                @endif
                                                                <span
                                                                    style="font-size:10.5px; font-weight:600; color:var(--smoke); background:var(--paper); padding:2px 8px; border-radius:4px; border:1px solid var(--paper2);">
                                                                    Penyisihan
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="text-align:right; display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
                                                        <div style="display:flex; align-items:center; gap:8px;">
                                                            <div style="text-align:right;">
                                                                <div style="font-size:20px; font-weight:800; font-family:'Cinzel',serif; color:{{ $poolColor }};">
                                                                    {{ $entries->count() }}
                                                                </div>
                                                                <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:var(--smoke); letter-spacing:.05em;">
                                                                    Kontingen
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button wire:click="openEditModal({{ $entries->first()->pool_id }})" class="btn-gen ghost" style="padding:4px 10px; font-size:10px; height:auto; border-color:rgba(0,0,0,0.1);">
                                                            <i class="fa-solid fa-edit"></i> Edit
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- Pool Officials --}}
                                                @php
                                                    $poolFirst = $entries->first();
                                                    $poolKoor = $poolFirst->metadata['officials']['koordinator_lapangan'] ?? null;
                                                    $poolPaniteras = $poolFirst->metadata['officials']['panitera'] ?? [];
                                                @endphp
                                                @if($poolKoor)
                                                <div style="background:var(--paper); padding:10px 20px; border-bottom:1px solid var(--paper2); display:flex; flex-wrap:wrap; gap:12px;">
                                                    <div style="font-size:11px; color:#2c3e50; background:rgba(44, 62, 80, 0.08); padding:4px 10px; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                                                        <i class="fa-solid fa-user-tie" style="color:#d35400;"></i>
                                                        <span>Koor: <span style="font-weight:800;">{{ $poolKoor }}</span></span>
                                                    </div>
                                                    @if(count($poolPaniteras) > 0)
                                                    <div style="font-size:11px; color:#34495e; background:rgba(52, 73, 94, 0.08); padding:4px 10px; border-radius:6px; display:inline-flex; align-items:center; gap:6px;">
                                                        <i class="fa-solid fa-users" style="color:#2980b9;"></i>
                                                        <span>Panitera: <span style="font-weight:800;">{{ count($poolPaniteras) }} Orang</span></span>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endif

                                                {{-- Table --}}
                                                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                                                    <thead>
                                                        <tr
                                                            style="background:var(--paper); color:var(--smoke); font-size:10.5px; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; border-bottom:1px solid var(--paper2);">
                                                            <th style="padding:10px 20px; text-align:center; width:60px;">#</th>
                                                            <th style="padding:10px 20px; text-align:left;">Kontingen</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($entries as $entry)
                                                            <tr style="border-bottom:1px solid var(--paper2); transition:background 0.2s;">
                                                                <td
                                                                    style="padding:14px 20px; text-align:center; font-weight:800; color:{{ $poolColor }}; font-size:14px; background:rgba({{ hexdec(substr($poolColor, 1, 2)) }}, {{ hexdec(substr($poolColor, 3, 2)) }}, {{ hexdec(substr($poolColor, 5, 2)) }}, 0.02);">
                                                                    {{ $entry->sequence_number ?? $loop->iteration }}
                                                                </td>
                                                                <td
                                                                    style="padding:14px 20px; font-weight:700; color:var(--ink); text-transform:uppercase;">
                                                                    {{ $entry->registration?->contingent?->name ?? '-' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                {{-- Footer --}}
                                                @if($isPool)
                                                    <div
                                                        style="padding:14px 20px; font-size:11px; font-weight:700; color:#d35400; text-transform:uppercase; letter-spacing:0.05em; background:rgba(211,84,0,0.05); display:flex; align-items:center; gap:8px; justify-content:center;">
                                                        <i class="fa-solid fa-trophy"></i> RANK 1-3 LOLOS KE FINAL
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif

                @else
                    {{-- Empty state --}}
                    <div class="tm-card">
                        <div class="tm-empty">
                            <i class="fa-solid fa-hand-pointer"></i>
                            <h4>Pilih Nomor Pertandingan</h4>
                            <p>Pilih kelompok umur dan nomor pertandingan di panel kiri untuk melihat peserta dan generate
                                drawing.</p>
                        </div>
                    </div>
                @endif

            </div>{{-- end right --}}
        </div>{{-- end layout --}}

    </div>

    @if($showEditModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); backdrop-filter:blur(4px); z-index:9999; display:flex; align-items:center; justify-content:center; padding:20px;">
        <div style="background:#fff; width:100%; max-width:500px; border-radius:20px; overflow:hidden; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.3s ease-out;">
            <div style="padding:20px 24px; border-bottom:1px solid var(--paper2); display:flex; justify-content:space-between; align-items:center; background:var(--ink); color:#fff;">
                <h3 style="margin:0; font-family:'Cinzel',serif; font-size:16px;">Manual Edit Penugasan</h3>
                <button wire:click="$set('showEditModal', false)" style="background:none; border:none; color:#fff; cursor:pointer; font-size:20px;">&times;</button>
            </div>
            <div style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Lapangan (Court)</label>
                        <select wire:model="editCourtId" class="tm-filter-sel">
                            <option value="">— Pilih Lapangan —</option>
                            @foreach($courts as $c)
                                <option value="{{ $c->id }}">Court {{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Sesi Waktu</label>
                        <select wire:model="editSessionId" class="tm-filter-sel">
                            <option value="">— Pilih Sesi —</option>
                            @foreach($sessionTimes as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->start_time }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Koordinator Lapangan</label>
                    <select wire:model="editKoorName" class="tm-filter-sel">
                        <option value="">— Pilih Koordinator —</option>
                        @foreach($koorUsers as $user)
                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:11px; font-weight:700; text-transform:uppercase; color:var(--smoke); margin-bottom:8px;">Panitera (Pilih 4)</label>
                    
                    <div style="position:relative; margin-bottom:8px;">
                        <i class="fa-solid fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-size:10px; color:var(--smoke);"></i>
                        <input type="text" wire:model.live.debounce.300ms="searchPanitera" placeholder="Cari nama panitera..." 
                            style="width:100%; padding:8px 12px 8px 32px; border:1px solid var(--paper2); border-radius:8px; font-size:12px; outline:none; transition:border-color 0.15s;">
                    </div>

                    <div style="max-height:150px; overflow-y:auto; border:1px solid var(--paper2); border-radius:10px; padding:10px; display:flex; flex-direction:column; gap:6px;">
                        @foreach($paniteraUsers as $user)
                            <label style="display:flex; align-items:center; gap:8px; font-size:12.5px; cursor:pointer;">
                                <input type="checkbox" wire:model="editPaniteraNames" value="{{ $user->name }}">
                                <span>{{ $user->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div style="margin-top:6px; font-size:10px; color:{{ count($editPaniteraNames) == 4 ? '#27ae60' : 'var(--red)' }}; font-weight:700;">
                        Terpilih: {{ count($editPaniteraNames) }} / 4 Panitera
                    </div>
                </div>

            </div>
            <div style="padding:16px 24px; background:var(--paper); border-top:1px solid var(--paper2); display:flex; justify-content:flex-end; gap:12px;">
                <button wire:click="$set('showEditModal', false)" class="btn-gen ghost">Batal</button>
                <button wire:click="saveAssignments" class="btn-gen primary" {{ count($editPaniteraNames) != 4 ? 'disabled' : '' }}>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    <style>
        @keyframes modalIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
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
                }).then((r) => { if (r.isConfirmed) @this.resetDrawing(); });
            }
        </script>
    @endpush
</div>