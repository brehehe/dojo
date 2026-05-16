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

            .btn-gen.ghost {
                background: #fff;
                color: var(--smoke, #7f8c8d);
                border: 1px solid var(--paper2, #e0dcd3);
            }

            .btn-gen.ghost:hover {
                border-color: var(--ink);
                color: var(--ink);
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
        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fa-solid fa-chart-line" style="color:var(--ink);margin-right:8px;"></i>Rekapitulasi Embu
                </h2>
                <p>Lihat rekapitulasi nilai Embu per Lapangan, Pool, & Babak</p>
            </div>
            <div>
                <button wire:click="resetFilters" class="btn-gen ghost">
                    <i class="fas fa-filter"></i> Reset Filter
                </button>
            </div>
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
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input"
                        placeholder="Cari match / kontingen...">
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
                        @foreach ($ageGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Nomor Pertandingan</div>
                    <select wire:model.live="filterMatchNumber" class="tm-filter-sel">
                        <option value="">Semua Nomor Match</option>
                        @foreach ($matchNumbers as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->name }} - {{ $mn?->ageGroup?->name }}
                                ({{ $mn->gender }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Kontingen</div>
                    <select wire:model.live="filterContingent" class="tm-filter-sel">
                        <option value="">Semua Kontingen</option>
                        @foreach ($contingents as $contingent)
                            <option value="{{ $contingent->id }}">{{ $contingent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="tm-filter-group">
                    <div class="tm-filter-label">Lainnya</div>
                    <select wire:model.live="filterPool" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Pool</option>
                        @foreach ($pools as $pool)
                            <option value="{{ $pool->id }}">{{ $pool->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterRound" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Babak</option>
                        @foreach ($rounds as $rnd)
                            <option value="{{ $rnd }}">{{ $rnd }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterCourt" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Lapangan</option>
                        @foreach ($courts as $court)
                            <option value="{{ $court->id }}">{{ $court->name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="filterSession" class="tm-filter-sel" style="margin-bottom:8px;">
                        <option value="">Semua Sesi</option>
                        @foreach ($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- RIGHT: CONTENT --}}
            <div class="tm-right">
                <div class="tm-card">
                    <div class="tm-card-head">
                        <div>
                            <h3>Daftar Match Embu</h3>
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
                                    @endphp
                                    <tr>
                                        <td style="font-weight:700;">{{ $drawing->sequence_number ?? '-' }}</td>
                                        <td>
                                            <span class="draw-badge embu mb-1"
                                                style="margin-bottom:4px;display:inline-block;">embu</span>
                                            <div style="font-weight:700; color:var(--ink); font-size:13px;">
                                                {{ $drawing->merge_name ?: $drawing->aggregated_match_names ?? ($mn?->name ?? '—') }}
                                            </div>
                                            @if ($drawing->merge_name)
                                                <div style="font-size:10px; color:var(--smoke); font-style:italic;">
                                                    {{ $drawing->aggregated_match_names }}</div>
                                            @endif
                                            @if ($mn?->ageGroup)
                                                <div style="font-size:11px; color:var(--smoke);">
                                                    {{ $mn->ageGroup->name }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($pool)
                                                <div style="font-size:12px; font-weight:700; margin-bottom:2px;">Pool
                                                    {{ $pool->name }}</div>
                                            @endif
                                            @if ($drawing->round)
                                                <span class="draw-badge"
                                                    style="background:rgba(211,84,0,.1);color:#d35400;">{{ $drawing->round }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($court)
                                                <div style="font-size:12px; font-weight:700; color:var(--ink);"><i
                                                        class="fas fa-vector-square"
                                                        style="color:var(--smoke);margin-right:4px;"></i>
                                                    {{ $court->name }}</div>
                                            @else
                                                <div style="font-size:12px; color:var(--smoke); font-style:italic;">
                                                    Lapangan (-)</div>
                                            @endif
                                            @if ($session)
                                                <div style="font-size:11px; color:var(--smoke); margin-top:2px;">
                                                    {{ $session->name }} | {{ substr($session->start_time, 0, 5) }}
                                                </div>
                                            @endif
                                            @if ($rundown)
                                                <div style="font-size:11px; color:var(--smoke);">
                                                    {{ $rundown->date ?? '' }}</div>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if ($mn)
                                                <a href="{{ route('admin.arbitrase.new-rekapitulasi-embu-detail', $mn->id) }}?round={{ $drawing->round ?? '' }}&pool_id={{ $pool?->id ?? '' }}"
                                                    class="btn-gen primary" title="Lihat Rekap">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            style="text-align:center; padding:40px 20px; color:var(--smoke);">
                                            <i class="fas fa-clipboard-list"
                                                style="font-size:24px; margin-bottom:12px; color:var(--paper2);"></i>
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
    </div>
</div>
