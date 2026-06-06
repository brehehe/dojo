<div>
    <style>
        .rekap-page {
            padding: 28px 32px;
            font-family: 'Inter', sans-serif;
        }
        .rekap-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .rekap-title {
            font-size: 1.7rem;
            font-weight: 900;
            color: #b22234;
            border-left: 6px solid #f59e0b;
            padding-left: 16px;
            line-height: 1.2;
        }
        .rekap-title small {
            display: block;
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
        }
        .filter-bar {
            background: white;
            border-radius: 18px;
            padding: 20px 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            margin-bottom: 24px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
            min-width: 160px;
        }
        .filter-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .filter-input {
            height: 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 12px;
            font-size: 13px;
            color: #1e293b;
            background: #f8fafc;
            outline: none;
            transition: border-color 0.2s;
            width: 100%;
        }
        .filter-input:focus {
            border-color: #b22234;
            background: white;
        }
        .btn-reset {
            height: 42px;
            padding: 0 20px;
            background: #f1f5f9;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-reset:hover { background: #e2e8f0; }
        .rekap-table-wrap {
            background: white;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            overflow: hidden;
        }
        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }
        .rekap-table thead th {
            background: #1e293b;
            color: white;
            padding: 14px 16px;
            text-align: left;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
        }
        .rekap-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s;
        }
        .rekap-table tbody tr:hover { background: #f8fafc; }
        .rekap-table td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #334155;
        }
        .badge-embu {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #dbeafe;
            color: #1e40af;
        }
        .badge-randori {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #fce7f3;
            color: #9d174d;
        }
        .badge-status-ok {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #d1fae5;
            color: #065f46;
        }
        .badge-status-pending {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            background: #fef3c7;
            color: #92400e;
        }
        .btn-cetak {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #b22234, #e63946);
            color: white;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-cetak:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(178,34,52,0.35);
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: #94a3b8;
        }
        .empty-state i { font-size: 48px; margin-bottom: 16px; display: block; }
        .match-name {
            font-weight: 700;
            color: #0f172a;
        }
        .match-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }
    </style>

    <div class="rekap-page">
        {{-- Header --}}
        <div class="rekap-header">
            <div>
                <div class="rekap-title">
                    <i class="fas fa-file-alt" style="margin-right:8px;"></i>
                    Laporan Rekap Penilaian
                    <small>Embu &amp; Randori — Semua Nomor Pertandingan</small>
                </div>
            </div>
        </div>

        {{-- Filter Bar --}}
        <div class="filter-bar">
            <div class="filter-group" style="flex:2; min-width:220px;">
                <span class="filter-label"><i class="fas fa-search" style="margin-right:4px;"></i>Cari Nomor</span>
                <input wire:model.live.debounce.300ms="search" class="filter-input" type="text" placeholder="Nama nomor pertandingan...">
            </div>
            <div class="filter-group">
                <span class="filter-label">Tipe</span>
                <select wire:model.live="filterType" class="filter-input">
                    <option value="">Semua Tipe</option>
                    <option value="embu">Embu</option>
                    <option value="randori">Randori</option>
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Kelompok Usia</span>
                <select wire:model.live="filterAgeGroup" class="filter-input">
                    <option value="">Semua Usia</option>
                    @foreach($ageGroups as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <span class="filter-label">Gender</span>
                <select wire:model.live="filterGender" class="filter-input">
                    <option value="">Semua</option>
                    <option value="Male">Putra</option>
                    <option value="Female">Putri</option>
                    <option value="Mix">Campuran</option>
                </select>
            </div>
            <button wire:click="resetFilters" class="btn-reset">
                <i class="fas fa-times" style="margin-right:4px;"></i>Reset
            </button>
        </div>

        {{-- Table --}}
        <div class="rekap-table-wrap">
            @if($matchNumbers->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <div style="font-weight:700; color:#475569; font-size:16px; margin-bottom:8px;">Belum ada data</div>
                    <div>Tidak ada nomor pertandingan yang memiliki jadwal.</div>
                </div>
            @else
                <table class="rekap-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No.</th>
                            <th>Nomor Pertandingan</th>
                            <th style="width:100px;">Tipe</th>
                            <th style="width:130px;">Kelompok Usia</th>
                            <th style="width:100px;">Gender</th>
                            <th style="width:100px;">Peserta</th>
                            <th style="width:120px;">Status Skor</th>
                            <th style="width:120px;">Juara</th>
                            <th style="width:130px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matchNumbers as $i => $mn)
                            <tr>
                                <td style="color:#94a3b8; font-weight:600;">{{ $matchNumbers->firstItem() + $i }}</td>
                                <td>
                                    <div class="match-name">{{ $mn->name }}</div>
                                    @if($mn->merge_name)
                                        <div class="match-sub"><i class="fas fa-code-branch" style="margin-right:3px;"></i>Merge: {{ $mn->merge_name }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($mn->draft_type === 'embu')
                                        <span class="badge-embu"><i class="fas fa-layer-group"></i> Embu</span>
                                    @else
                                        <span class="badge-randori"><i class="fas fa-fist-raised"></i> Randori</span>
                                    @endif
                                </td>
                                <td style="font-weight:600; color:#374151;">{{ $mn->ageGroup?->name ?? '-' }}</td>
                                <td>
                                    <span style="font-size:12px; color:#6b7280;">
                                        @if($mn->gender === 'Male') 👨 Putra
                                        @elseif($mn->gender === 'Female') 👩 Putri
                                        @else 🤝 Campuran
                                        @endif
                                    </span>
                                </td>
                                <td style="font-weight:700; color:#1e293b;">{{ $mn->athletes_count }}</td>
                                <td>
                                    @if($mn->has_results)
                                        <span class="badge-status-ok"><i class="fas fa-check-circle"></i> Ada Skor</span>
                                    @else
                                        <span class="badge-status-pending"><i class="fas fa-clock"></i> Belum</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mn->has_champion)
                                        <span class="badge-status-ok"><i class="fas fa-trophy"></i> Ada</span>
                                    @else
                                        <span class="badge-status-pending"><i class="fas fa-minus-circle"></i> Belum</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <a href="{{ route('admin.laporan-rekap-penilaian.cetak', $mn->id) }}"
                                       target="_blank"
                                       class="btn-cetak">
                                        <i class="fas fa-print"></i> Cetak Rekap
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 16px 20px;">
                    {{ $matchNumbers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
