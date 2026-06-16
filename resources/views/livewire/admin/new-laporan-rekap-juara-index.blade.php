<div>
    @push('styles')
    <style>
        /* ══════════════════════════════════════════════════════
           PAGE STYLES — Master Rekap Seluruh Juara (Premium Layout)
           ══════════════════════════════════════════════════════ */
        .prem-page-a {
            background: var(--paper);
            color: var(--ink);
            padding: 28px;
        }

        /* ── PAGE HEADER ── */
        .page-hdr-a {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 24px;
        }

        .page-hdr-a h2 {
            font-family: 'Cinzel', serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 4px;
        }

        .page-hdr-a p {
            font-size: 12px;
            color: var(--smoke);
            margin: 0;
        }

        .btn-prem-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            transition: all .2s;
            white-space: nowrap;
            border: none;
        }

        /* ── STAT CARDS ── */
        .stats-grid-a {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }

        .stat-card-a {
            background: #fff;
            border-radius: 16px;
            padding: 18px 20px;
            border: 1px solid var(--paper2);
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .stat-card-a:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 32px rgba(0, 0, 0, .08);
        }

        .stat-card-a::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            opacity: .08;
            transform: translate(20px, 20px);
        }

        .stat-card-a.gold::after {
            background: var(--gold);
        }

        .stat-card-a.yellow::after {
            background: #f1c40f;
        }

        .stat-card-a.green::after {
            background: #2ec4b6;
        }

        .stat-icon-a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .stat-card-a.gold .stat-icon-a {
            background: rgba(212, 168, 67, 0.15);
            color: #b8860b;
        }

        .stat-card-a.yellow .stat-icon-a {
            background: rgba(243, 156, 18, 0.15);
            color: #f39c12;
        }

        .stat-card-a.green .stat-icon-a {
            background: rgba(46, 196, 182, 0.15);
            color: #008080;
        }

        .stat-value-a {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label-a {
            font-size: 11px;
            color: var(--smoke);
            text-transform: uppercase;
            letter-spacing: .05em;
            font-weight: 600;
        }

        /* ── FILTERS BAR ── */
        .filters-bar-prem {
            background: #fff;
            border: 1px solid var(--paper2);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .filter-input-prem {
            flex: 1;
            min-width: 200px;
            padding: 10px 14px;
            border: 1px solid var(--paper2);
            border-radius: 12px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            background: var(--paper);
            color: var(--ink);
        }

        .filter-select-prem {
            padding: 10px 14px;
            border: 1px solid var(--paper2);
            border-radius: 12px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            background: var(--paper);
            color: var(--ink);
            min-width: 140px;
        }

        /* ── TABLE ── */
        .table-section-prem {
            background: #fff;
            border: 1px solid var(--paper2);
            border-radius: 16px;
            padding: 24px;
            overflow: hidden;
        }

        .premium-table-container {
            overflow-x: auto;
            border: 1px solid var(--paper2);
            border-radius: 12px;
            background: #fff;
        }

        .premium-table {
            width: 100%;
            border-collapse: collapse;
        }

        .premium-table th {
            background: var(--paper);
            padding: 14px 16px;
            text-align: left;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--smoke);
            font-weight: 700;
            border-bottom: 1px solid var(--paper2);
        }

        .premium-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--paper2);
            vertical-align: middle;
        }

        .premium-table tr:last-child td {
            border-bottom: none;
        }

        /* Border Left Indicators on rows */
        .premium-table tr {
            transition: background .2s;
        }

        .premium-table tr.row-yellow {
            border-left: 5px solid #f1c40f;
            background-color: #fffdeb !important;
        }

        .premium-table tr.row-yellow:hover {
            background-color: #fffbeb !important;
        }

        .premium-table tr.row-green {
            border-left: 5px solid #2ecc71;
            background-color: #f0fdf4 !important;
        }

        .premium-table tr.row-green:hover {
            background-color: #dcfce7 !important;
        }

        /* ── BADGES & LABELS ── */
        .badge-prem {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .badge-prem.yellow {
            background: rgba(243, 156, 18, 0.15);
            color: #d35400;
        }

        .badge-prem.green {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .badge-prem.blue {
            background: rgba(41, 128, 185, 0.15);
            color: #2980b9;
        }

        .badge-prem.red {
            background: rgba(192, 57, 43, 0.15);
            color: var(--red);
        }

        .athlete-cell {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ink);
            text-transform: uppercase;
            line-height: 1.3;
        }

        .contingent-sub {
            font-size: 10px;
            color: var(--smoke);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: .02em;
            margin-top: 1px;
        }
    </style>
    @endpush

    <div class="prem-page-a">
        <!-- Header -->
        <div class="page-hdr-a">
            <div>
                <h2>Rekap Laporan Seluruh Juara</h2>
                <p>Rekapitulasi seluruh juara per nomor pertandingan (Embu &amp; Randori)</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button wire:click="resetFilters" class="btn-prem-action" style="background:var(--paper);border:1px solid var(--paper2);color:var(--ink);">
                    <i class="fas fa-sync" style="margin-right:6px;"></i> Reset Filter
                </button>
                <button wire:click="downloadExcel" class="btn-prem-action" style="background:#27ae60;color:#fff;box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);">
                    <i class="fas fa-file-excel" style="margin-right:6px;"></i> Export Ke Excel
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        @php
            $totalNomor = count($matchData);
            $totalKuning = count(array_filter($matchData, fn($m) => $m['color'] === 'yellow'));
            $totalHijau = count(array_filter($matchData, fn($m) => $m['color'] === 'green'));
        @endphp
        <div class="stats-grid-a">
            <div class="stat-card-a gold">
                <div class="stat-icon-a"><i class="fas fa-medal"></i></div>
                <div class="stat-label-a">Total Nomor Pertandingan</div>
                <div class="stat-value-a" style="color:#b8860b;">{{ $totalNomor }}</div>
            </div>
            <div class="stat-card-a yellow">
                <div class="stat-icon-a"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-label-a">Aturan Khusus (Kuning)</div>
                <div class="stat-value-a" style="color:#e67e22;">{{ $totalKuning }}</div>
            </div>
            <div class="stat-card-a green">
                <div class="stat-icon-a"><i class="fas fa-check-circle"></i></div>
                <div class="stat-label-a">Aturan Normal (Hijau)</div>
                <div class="stat-value-a" style="color:#16a085;">{{ $totalHijau }}</div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="filters-bar-prem">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nomor pertandingan..." class="filter-input-prem" />

            <select wire:model.live="draftTypeFilter" class="filter-select-prem">
                <option value="">Semua Tipe</option>
                <option value="embu">Embu</option>
                <option value="randori">Randori</option>
            </select>

            <select wire:model.live="ageGroupFilter" class="filter-select-prem">
                <option value="">Semua Kelompok Umur</option>
                @foreach($ageGroups as $ag)
                    <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                @endforeach
            </select>

            <select wire:model.live="highlightFilter" class="filter-select-prem">
                <option value="">Semua Aturan</option>
                <option value="yellow">Kuning (3 Peserta &amp; &gt;= 2 Kont.)</option>
                <option value="green">Hijau (Lainnya)</option>
            </select>
        </div>

        <!-- Table Area -->
        <div class="table-section-prem">
            @if(count($matchData) > 0)
                <div class="premium-table-container">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th style="text-align: center; width: 40px;">No</th>
                                <th>Nomor Pertandingan</th>
                                <th style="text-align: center; width: 140px;">Peserta &amp; Kontingen</th>
                                <th>Juara 1 🥇</th>
                                <th>Juara 2 🥈</th>
                                <th>Juara 3 Bersama 1 🥉</th>
                                <th>Juara 3 Bersama 2 🥉</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matchData as $idx => $m)
                                <tr class="row-{{ $m['color'] }}" wire:key="match-{{ $m['id'] }}">
                                    <td style="text-align: center;">
                                        <span style="font-weight: 700; color: var(--smoke);">{{ $idx + 1 }}</span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 800; font-size: 13.5px; color: var(--ink); text-transform: uppercase;">
                                            {{ $m['name'] }}
                                        </div>
                                        <div style="display: flex; gap: 6px; margin-top: 4px;">
                                            <span class="badge-prem blue" style="padding: 1px 6px; font-size: 9px;">{{ $m['draft_type'] }}</span>
                                            <span class="badge-prem red" style="padding: 1px 6px; font-size: 9px;">{{ $m['age_group'] }}</span>
                                            <span class="badge-prem" style="background:#f5f5f0; color:var(--smoke); padding: 1px 6px; font-size: 9px;">{{ $m['gender'] }}</span>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="badge-prem {{ $m['color'] }}">
                                            {{ $m['participant_count'] }} Pes ({{ $m['contingent_count'] }} Kont)
                                        </span>
                                    </td>
                                    <td>
                                        @if($m['juara1'])
                                            <div class="athlete-cell">{{ $m['juara1']['athlete_names'] }}</div>
                                            <div class="contingent-sub">{{ $m['juara1']['contingent_name'] }}</div>
                                        @else
                                            <span style="color: var(--smoke); font-style: italic; font-size: 11px;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m['juara2'])
                                            <div class="athlete-cell">{{ $m['juara2']['athlete_names'] }}</div>
                                            <div class="contingent-sub">{{ $m['juara2']['contingent_name'] }}</div>
                                        @else
                                            <span style="color: var(--smoke); font-style: italic; font-size: 11px;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m['juara3'])
                                            <div class="athlete-cell">{{ $m['juara3']['athlete_names'] }}</div>
                                            <div class="contingent-sub">{{ $m['juara3']['contingent_name'] }}</div>
                                        @else
                                            <span style="color: var(--smoke); font-style: italic; font-size: 11px;">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($m['juara4'])
                                            <div class="athlete-cell">{{ $m['juara4']['athlete_names'] }}</div>
                                            <div class="contingent-sub">{{ $m['juara4']['contingent_name'] }}</div>
                                        @else
                                            <span style="color: var(--smoke); font-style: italic; font-size: 11px;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 48px 0;">
                    <div style="width: 48px; height: 48px; background: var(--paper); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
                        <i class="fas fa-search" style="font-size: 18px; color: var(--smoke);"></i>
                    </div>
                    <h3 style="font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--ink); margin: 0 0 4px;">Data Tidak Ditemukan</h3>
                    <p style="font-size: 12px; color: var(--smoke); margin: 0;">Coba sesuaikan filter pencarian Anda.</p>
                </div>
            @endif
        </div>
    </div>
</div>
