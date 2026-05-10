<div>
    @push('styles')
        <style>
            .tm-page {
                padding: 24px;
                background: var(--paper, #F7F4EF);
                min-height: 100vh;
            }

            /* HEADER */
            .tm-hdr {
                margin-bottom: 20px;
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
            }

            .tm-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 20px;
                font-weight: 700;
                margin: 0 0 4px;
                color: var(--ink, #2c3e50);
            }

            .tm-hdr p {
                font-size: 12px;
                color: var(--smoke, #7f8c8d);
                margin: 0;
            }

            /* STATS / COURTS */
            .tm-stats {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
                gap: 12px;
                margin-bottom: 20px;
            }

            .tm-stat-pill {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                padding: 14px;
                position: relative;
                overflow: hidden;
                transition: all .2s;
            }

            .tm-stat-pill:hover {
                border-color: #bbb;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
            }

            .court-status {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 8px;
            }

            .court-title {
                font-size: 13px;
                font-weight: 700;
                color: var(--ink, #2c3e50);
                text-transform: uppercase;
                font-family: 'Cinzel', serif;
            }

            .court-match-name {
                font-size: 12px;
                font-weight: 700;
                color: #27ae60;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: flex;
                align-items: center;
                gap: 6px;
                margin-bottom: 4px;
            }

            .court-match-contingent {
                font-size: 11px;
                color: var(--smoke);
                font-weight: 600;
            }

            .court-links {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 6px;
                margin-top: 12px;
            }

            .court-link {
                background: #fdfbf7;
                border: 1px solid var(--paper2);
                border-radius: 6px;
                padding: 6px;
                text-align: center;
                font-size: 9px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
                transition: all .15s;
                text-decoration: none;
            }

            .court-link:hover {
                background: var(--ink);
                color: #fff;
                border-color: var(--ink);
            }

            .court-ref-list {
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px dashed var(--paper2);
            }

            .court-ref-item {
                font-size: 10px;
                color: var(--ink);
                display: flex;
                align-items: center;
                gap: 6px;
                margin-bottom: 4px;
                font-weight: 600;
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
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                overflow: hidden;
                position: sticky;
                top: 16px;
            }

            .tm-panel-title {
                padding: 14px 16px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
                font-family: 'Cinzel', serif;
                font-size: 12px;
                font-weight: 700;
                color: var(--ink, #2c3e50);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            /* FILTER SELECT */
            .tm-filter-group {
                padding: 12px 14px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
            }

            .tm-filter-label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke, #7f8c8d);
                margin-bottom: 6px;
            }

            .tm-filter-sel {
                width: 100%;
                padding: 8px 10px;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 9px;
                font-size: 12px;
                font-family: 'DM Sans', sans-serif;
                color: var(--ink, #2c3e50);
                background: #fff;
                outline: none;
                cursor: pointer;
            }

            .tm-filter-sel:focus {
                border-color: var(--red, #c0392b);
            }

            .tm-filter-input {
                width: 100%;
                padding: 8px 10px;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 9px;
                font-size: 12px;
                font-family: 'DM Sans', sans-serif;
                color: var(--ink, #2c3e50);
                background: #fff;
                outline: none;
            }

            .tm-filter-input:focus {
                border-color: var(--red, #c0392b);
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
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                overflow: hidden;
            }

            .tm-card-head {
                padding: 14px 18px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #fff;
            }

            .tm-card-head h3 {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                font-weight: 700;
                margin: 0 0 2px;
                color: var(--ink);
            }

            .tm-card-head p {
                font-size: 11px;
                color: var(--smoke);
                margin: 0;
            }

            .tm-card-body {
                padding: 0;
            }

            /* DRAWING RESULT TABLE */
            .draw-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12.5px;
            }

            .draw-table th {
                padding: 10px 14px;
                background: #fdfbf7;
                font-size: 10px;
                color: var(--smoke, #7f8c8d);
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
                text-align: left;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
            }

            .draw-table td {
                padding: 12px 14px;
                border-bottom: 1px solid var(--paper2, #e0dcd3);
                vertical-align: middle;
            }

            .draw-badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 9px;
                font-weight: 700;
                text-transform: uppercase;
            }

            .draw-badge.randori {
                background: rgba(192, 57, 43, .1);
                color: var(--red, #c0392b);
            }

            .draw-badge.embu {
                background: rgba(39, 174, 96, .1);
                color: #27ae60;
            }

            /* ACTION BUTTONS */
            .btn-gen {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 14px;
                border-radius: 8px;
                border: none;
                font-size: 11px;
                font-weight: 700;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                transition: all .15s;
                text-transform: uppercase;
                letter-spacing: .05em;
                text-decoration: none;
            }

            .btn-gen.primary {
                background: var(--ink, #2c3e50);
                color: #fff;
            }

            .btn-gen.primary:hover {
                background: #1a252f;
                transform: translateY(-1px);
            }

            .btn-gen.danger {
                background: var(--red, #c0392b);
                color: #fff;
                box-shadow: 0 4px 12px rgba(192, 57, 43, .25);
            }

            .btn-gen.danger:hover {
                background: #a93226;
                transform: translateY(-1px);
            }

            .btn-gen.ghost {
                background: #fff;
                color: var(--smoke, #7f8c8d);
                border: 1px solid var(--paper2, #e0dcd3);
            }

            .btn-gen.ghost:hover {
                border-color: var(--ink);
                color: var(--ink);
            }

            /* MODAL */
            .modal-overlay {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5);
                backdrop-filter: blur(4px);
                z-index: 100;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
            }

            .modal-content {
                background: #fff;
                border-radius: 20px;
                width: 100%;
                max-width: 600px;
                overflow: hidden;
                box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            }

            .modal-hdr {
                padding: 20px 24px;
                border-bottom: 1px solid var(--paper2);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .modal-hdr h3 {
                font-family: 'Cinzel', serif;
                font-size: 16px;
                font-weight: 700;
                margin: 0;
                color: var(--ink);
            }

            .modal-close {
                background: var(--paper);
                border: none;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: var(--smoke);
                transition: .2s;
            }

            .modal-close:hover {
                background: var(--red);
                color: #fff;
            }

            .modal-body {
                padding: 24px;
            }

            @media(max-width:900px) {
                .tm-layout {
                    grid-template-columns: 1fr;
                }
                .tm-left {
                    position: static;
                }
            }
        </style>
    @endpush

    <div class="tm-page">
        {{-- FIXED RESET BUTTON --}}
        <div style="position: fixed; bottom: 30px; right: 30px; z-index: 90;">
            <button wire:click="clearAllCourts"
                wire:confirm="PERINGATAN: Ini akan mereset status SEMUA lapangan & match yang sedang berjalan menjadi KOSONG. Lanjutkan?"
                class="btn-gen danger" style="padding: 12px 20px; border-radius: 12px; font-size: 12px; box-shadow: 0 8px 24px rgba(192,57,43,.3);">
                <i class="fas fa-eraser" style="margin-right: 8px;"></i>
                <span class="hidden md:inline">Reset Semua Lapangan</span>
                <span class="md:hidden">Reset</span>
            </button>
        </div>

        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fa-solid fa-gavel" style="color:var(--ink);margin-right:8px;"></i>Dashboard Panitera</h2>
                <p>Panggil Drawing per Lapangan — Filter by Kontingen, Pool, Court, Sesi, Rundown & Babak</p>
            </div>
            <div>
                <button wire:click="resetFilters" class="btn-gen ghost">
                    <i class="fas fa-filter"></i> Reset Filter
                </button>
            </div>
        </div>

        {{-- ACTIVE COURT CARDS --}}
        <div class="tm-stats">
            @foreach($courts as $courtCard)
                @php
                    $ad = $courtCard->activeDrawing;
                    $adMatch = $courtCard->activeMatch;
                    $adPool = $ad?->pool;
                    $adSession = $ad?->sessionTime;
                    $adRundown = $ad?->rundown;
                    $adContingent = $ad?->registration?->contingent;
                    $adType = $ad?->draft_type ?? $adMatch?->draft_type;
                    $isActive = (bool) $courtCard->active_match_id;
                @endphp
                <div class="tm-stat-pill">
                    <div class="court-status">
                        <span class="court-title">Panel {{ $courtCard->name }}</span>
                        @if($isActive)
                            <div style="display:flex; align-items:center; gap:8px;">
                                <button wire:click="clearCourt({{ $courtCard->id }})" title="Kosongkan Lapangan"
                                    style="width:24px;height:24px;border-radius:50%;background:rgba(192,57,43,.1);color:var(--red);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-times" style="font-size:10px;"></i>
                                </button>
                                <span style="width:10px;height:10px;border-radius:50%;background:#27ae60;animation:pulse 2s infinite;"></span>
                            </div>
                        @else
                            <span style="width:10px;height:10px;border-radius:50%;background:var(--paper2);"></span>
                        @endif
                    </div>

                    <div style="min-height: 50px; margin-bottom: 10px;">
                        @if($isActive && $adMatch)
                            <div class="court-match-name">
                                <span class="draw-badge {{ $adType === 'randori' ? 'randori' : 'embu' }}">{{ $adType ?? '?' }}</span>
                                <span style="flex:1; overflow:hidden; text-overflow:ellipsis;">{{ $adMatch->name }}</span>
                            </div>
                            @if($adContingent)
                                <div class="court-match-contingent"><i class="fas fa-shield-alt" style="margin-right:4px;"></i>{{ $adContingent->name }}</div>
                            @endif
                            <div style="display:flex; gap:4px; flex-wrap:wrap; margin-top:6px;">
                                @if($adPool) <span class="draw-badge" style="background:rgba(41,128,185,.1);color:#2980b9;">Pool {{ $adPool->name }}</span> @endif
                                @if($adSession) <span class="draw-badge" style="background:rgba(39,174,96,.1);color:#27ae60;">{{ $adSession->name }}</span> @endif
                            </div>
                        @else
                            <div style="font-size:12px; color:var(--smoke); font-style:italic; padding-top:10px;">KOSONG (Idle)</div>
                        @endif
                    </div>

                    <div class="court-ref-list">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:9px; font-weight:700; color:var(--smoke); text-transform:uppercase;">Panel Wasit</span>
                            @if($courtCard->current_referees->count() > 0)
                                <button wire:confirm="Kosongkan panel wasit?" wire:click="resetActiveReferees({{ $courtCard->id }})" style="border:none;background:none;color:var(--red);font-size:9px;font-weight:700;cursor:pointer;text-transform:uppercase;">Reset</button>
                            @endif
                        </div>
                        @forelse($courtCard->current_referees as $sch)
                            <div class="court-ref-item">
                                <span style="width:16px;height:16px;border-radius:4px;background:var(--ink);color:#fff;display:flex;align-items:center;justify-content:center;font-size:8px;">{{ $sch->judge_index }}</span>
                                <span style="flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $sch->referee?->name }}</span>
                            </div>
                        @empty
                            <div style="font-size:10px; color:var(--smoke); font-style:italic;">Belum ada wasit</div>
                        @endforelse
                    </div>

                    <div class="court-links" style="grid-template-columns: repeat(2, 1fr);">
                        <a href="{{ route('admin.arbitrase.scoring.monitor', $courtCard->id) }}" target="_blank" class="court-link" style="background:var(--ink);color:#fff;border-color:var(--ink);">Panggilan</a>
                        <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.court', $courtCard->id) }}" target="_blank" class="court-link">Hasil</a>
                        <a href="{{ route('admin.arbitrase.scoring.monitor-timer.court', $courtCard->id) }}" target="_blank" class="court-link">Timer</a>
                        <a href="{{ route('admin.arbitrase.scoring.monitor-rekapitulasi-hasil.court', $courtCard->id) }}" target="_blank" class="court-link">Rekapitulasi</a>
                        <div style="display:flex; gap:4px; grid-column: span 2;">
                            <a href="{{ route('admin.arbitrase.scoring.monitor-referee.court', $courtCard->id) }}" target="_blank" class="court-link" style="flex:1;">Wasit</a>
                            <button wire:click="openRefereeModal({{ $courtCard->id }}, {{ $courtCard->activeDrawing?->rundown_id ?? 'null' }}, {{ $courtCard->activeDrawing?->session_time_id ?? 'null' }})" class="court-link" style="padding:4px 8px;cursor:pointer;"><i class="fas fa-user-plus"></i></button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- TWO-COLUMN LAYOUT --}}
        <div class="tm-layout">
            
            {{-- LEFT: FILTER PANEL --}}
            <div class="tm-left">
                <div class="tm-panel-title">
                    <div style="display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-filter" style="color:var(--ink);"></i> Filter
                    </div>
                </div>

                <div class="tm-filter-group">
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input" placeholder="Cari match / kontingen...">
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Kategori</div>
                    <select wire:model.live="filterType" class="tm-filter-sel">
                        <option value="">Semua Kategori</option>
                        <option value="embu">Embu</option>
                        <option value="randori">Randori</option>
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Gender</div>
                    <select wire:model.live="filterGender" class="tm-filter-sel">
                        <option value="">Semua Gender</option>
                        <option value="Male">Laki-laki (Male)</option>
                        <option value="Female">Perempuan (Female)</option>
                        <option value="Mix">Campuran (Mix)</option>
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Kelompok Umur</div>
                    <select wire:model.live="filterAgeGroup" class="tm-filter-sel">
                        <option value="">Semua Kategori Umur</option>
                        @foreach($ageGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Nomor Pertandingan</div>
                    <select wire:model.live="filterMatchNumber" class="tm-filter-sel">
                        <option value="">Semua Nomor Match</option>
                        @foreach($matchNumbers as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->name }} - {{ $mn?->ageGroup?->name }} ({{ $mn->gender }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Kontingen</div>
                    <select wire:model.live="filterContingent" class="tm-filter-sel">
                        <option value="">Semua Kontingen</option>
                        @foreach($contingents as $contingent)
                            <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Lainnya</div>
                    <select wire:model.live="filterPool" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Pool</option>
                        @foreach($pools as $pool)
                            <option value="{{ $pool->id }}">{{ $pool->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterRound" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Babak</option>
                        @foreach($rounds as $rnd)
                            <option value="{{ $rnd }}">{{ $rnd }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterCourt" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Lapangan</option>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}">{{ $court->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterSession" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Sesi</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterRundown" class="tm-filter-sel">
                        <option value="">Semua Rundown</option>
                        @foreach($rundowns as $rundown)
                            <option value="{{ $rundown->id }}">{{ $rundown->name ?? $rundown->date }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- RIGHT: CONTENT --}}
            <div class="tm-right">
                <div class="tm-card">
                    <div class="tm-card-head">
                        <div>
                            <h3>Daftar Panggilan</h3>
                            <p>{{ $drawings->total() }} entri tersedia</p>
                        </div>
                    </div>
                    <div class="tm-card-body" style="overflow-x:auto;">
                        <table class="draw-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kategori / Match</th>
                                    <th>Pool / Babak</th>
                                    <th>Jadwal / Lapangan</th>
                                    <th style="text-align:center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($drawings as $drawing)
                                    @php
                                        $mn = $drawing->matchNumber;
                                        $pool = $drawing->pool;
                                        $court = $drawing->court;
                                        $session = $drawing->sessionTime;
                                        $rundown = $drawing->rundown;
                                        $isRandori = $drawing->draft_type === 'randori';
                                        $detailRoute = 'admin.new-scoring-' . $drawing->draft_type . '-index';
                                    @endphp
                                    <tr>
                                        <td style="font-weight:700;">{{ $drawing->sequence_number ?? '-' }}</td>
                                        <td>
                                            <span class="draw-badge {{ $isRandori ? 'randori' : 'embu' }} mb-1" style="margin-bottom:4px;display:inline-block;">{{ $drawing->draft_type }}</span>
                                            <div style="font-weight:700; color:var(--ink); font-size:13px;">{{ $mn?->name ?? '—' }}</div>
                                            @if($mn?->ageGroup)
                                                <div style="font-size:11px; color:var(--smoke);">{{ $mn->ageGroup->name }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pool) <div style="font-size:12px; font-weight:700; margin-bottom:2px;">Pool {{ $pool->name }}</div> @endif
                                            @if($drawing->round) <span class="draw-badge" style="background:rgba(211,84,0,.1);color:#d35400;">{{ $drawing->round }}</span> @endif
                                        </td>
                                        <td>
                                            @if($court) <div style="font-size:12px; font-weight:700; color:var(--ink);"><i class="fas fa-vector-square" style="color:var(--smoke);margin-right:4px;"></i> {{ $court->name }}</div> @else <div style="font-size:12px; color:var(--smoke); font-style:italic;">Lapangan (-)</div> @endif
                                            @if($session) <div style="font-size:11px; color:var(--smoke); margin-top:2px;">{{ $session->name }} | {{ substr($session->start_time,0,5) }}</div> @endif
                                            @if($rundown) <div style="font-size:11px; color:var(--smoke);">{{ $rundown->date ?? '' }}</div> @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if($mn)
                                                <a href="{{ route($detailRoute, $mn->id) }}?round={{ $drawing->round ?? '' }}&pool_id={{ $pool?->id ?? '' }}" class="btn-gen primary" title="Input Nilai">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.arbitrase.scoring.monitor-hasil.match', $mn->id) }}?round={{ $drawing->round ?? '' }}&pool_id={{ $pool?->id ?? '' }}" target="_blank" class="btn-gen ghost" title="Monitor Hasil">
                                                    <i class="fas fa-tv"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align:center; padding:40px 20px; color:var(--smoke);">
                                            <i class="fas fa-clipboard-list" style="font-size:24px; margin-bottom:12px; color:var(--paper2);"></i>
                                            <p style="font-size:13px; font-weight:600; margin:0;">Tidak ada data</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div style="padding: 12px 18px; border-top: 1px solid var(--paper2);">
                        {{ $drawings->links('livewire.admin.pagination') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL: REFEREE ASSIGNMENT --}}
        @if($showRefereeModal)
            <div class="modal-overlay">
                <div class="modal-content">
                    <div class="modal-hdr">
                        <h3>Penugasan Wasit - {{ \App\Models\Court\Court::find($assigningCourtId)?->name ?? 'Lalu' }}</h3>
                        <button wire:click="$set('showRefereeModal', false)" class="modal-close"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
                            <div>
                                <label style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; margin-bottom:4px; display:block;">Tanggal / Hari</label>
                                <select wire:model.live="assigningRundownId" style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--paper2); font-size:13px; outline:none;">
                                    <option value="">Pilih Tanggal</option>
                                    @foreach($rundowns as $rd)
                                        <option value="{{ $rd->id }}">{{ $rd->name ?? $rd->date }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; margin-bottom:4px; display:block;">Sesi / Shift</label>
                                <select wire:model.live="assigningSessionId" style="width:100%; padding:10px; border-radius:8px; border:1px solid var(--paper2); font-size:13px; outline:none;">
                                    <option value="">Pilih Sesi</option>
                                    @foreach($sessions as $ss)
                                        <option value="{{ $ss->id }}">{{ $ss->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom:20px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                <span style="font-size:11px; font-weight:700; color:var(--smoke); text-transform:uppercase;">Wasit Terpilih</span>
                                <button wire:click="syncFromSchedule" class="btn-gen ghost" style="padding:4px 10px; font-size:9px;"><i class="fas fa-sync-alt"></i> Tarik dari Jadwal</button>
                            </div>
                            <div style="background:var(--paper); border:1px solid var(--paper2); border-radius:12px; padding:16px; min-height:120px;">
                                @if(count($selectedReferees) > 0)
                                    <div style="display:flex; flex-direction:column; gap:8px;">
                                        @foreach($allReferees->whereIn('id', $selectedReferees)->sortBy(fn($r) => array_search((string)$r->id, $selectedReferees)) as $index => $ref)
                                            <div style="display:flex; align-items:center; gap:12px; background:#fff; padding:8px 12px; border-radius:8px; border:1px solid var(--paper2);">
                                                <span style="width:24px; height:24px; border-radius:6px; background:var(--ink); color:#fff; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700;">{{ array_search((string)$ref->id, $selectedReferees) + 1 }}</span>
                                                <div>
                                                    <div style="font-size:13px; font-weight:700; color:var(--ink);">{{ $ref->name }}</div>
                                                    <div style="font-size:9px; font-weight:700; color:var(--smoke); text-transform:uppercase;">
                                                        @switch(array_search((string)$ref->id, $selectedReferees) + 1)
                                                            @case(1) Wasit Nasional (Ketua) @break
                                                            @case(2) Wasit Daerah 1 @break
                                                            @case(3) Wasit Daerah 2 @break
                                                            @case(4) Wasit Pembantu 1 @break
                                                            @case(5) Wasit Pembantu 2 @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div style="text-align:center; padding:20px 0; color:var(--smoke);">
                                        <i class="fas fa-user-friends" style="font-size:24px; margin-bottom:8px; color:var(--paper2);"></i>
                                        <p style="font-size:12px; font-weight:600; margin:0;">Belum Ada Wasit Terpilih</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div style="display:flex; gap:8px;">
                            <button wire:click="$set('showRefereeModal', false)" class="btn-gen ghost" style="flex:1; padding:12px; justify-content:center; font-size:12px;">Batal</button>
                            <button wire:click="saveRefereeAssignment" class="btn-gen primary" style="flex:2; padding:12px; justify-content:center; font-size:12px;" {{ count($selectedReferees) !== 5 ? 'disabled' : '' }}>Ya, Tentukan Wasit</button>
                        </div>
                        @if(count($selectedReferees) > 0)
                            <button wire:confirm="Kosongkan wasit?" wire:click="resetCourtReferees" class="btn-gen ghost" style="width:100%; margin-top:8px; padding:10px; justify-content:center; color:var(--red); border-color:rgba(192,57,43,.2);">Kosongkan Penugasan Wasit</button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
