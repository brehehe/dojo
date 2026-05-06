<div>
    @push('styles')
        <style>
            .tm-page { padding: 24px; padding-bottom: 100px; background: var(--paper, #F7F4EF); min-height: 100vh; }
            .tm-hdr { margin-bottom: 24px; display: flex; flex-direction: column; gap: 16px; }
            @media(min-width:768px) { .tm-hdr { flex-direction: row; justify-content: space-between; align-items: flex-end; } }
            .tm-hdr h2 { font-family: 'Cinzel', serif; font-size: 24px; font-weight: 700; margin: 0 0 4px; color: var(--ink, #2c3e50); }
            .tm-hdr p { font-size: 13px; color: var(--smoke, #7f8c8d); margin: 0; }
            .btn-gen { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: all .2s; border: none; text-decoration: none; }
            .btn-gen.success { background: #27ae60; color: #fff; }
            .btn-gen.success:hover { background: #219a52; }
            .btn-gen.ghost { background: #fff; color: var(--smoke); border: 1px solid var(--paper2); }
            .btn-gen.ghost:hover { border-color: var(--ink); color: var(--ink); }
            .tm-filter-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; padding: 20px; margin-bottom: 20px; }
            .tm-filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }
            .tm-filter-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--smoke); margin-bottom: 6px; display: block; }
            .tm-filter-sel, .tm-filter-input { width: 100%; padding: 9px 12px; border: 1px solid var(--paper2); border-radius: 10px; font-size: 12.5px; color: var(--ink); background: #fdfbf7; outline: none; }
            .tm-filter-sel:focus, .tm-filter-input:focus { border-color: var(--ink); }
            .tm-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; overflow: hidden; }
            .tm-card-head { padding: 14px 20px; border-bottom: 1px solid var(--paper2); display: flex; align-items: center; justify-content: space-between; background: #fdfbf7; }
            .tm-card-title { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--ink); text-transform: uppercase; display: flex; align-items: center; gap: 8px; }
            .tm-card-title i { color: var(--red, #c0392b); }
            .draw-table { width: 100%; border-collapse: collapse; font-size: 12px; }
            .draw-table th { padding: 10px 12px; background: #fdfbf7; font-size: 9px; color: var(--smoke); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; text-align: left; border-bottom: 1px solid var(--paper2); }
            .draw-table th.center { text-align: center; }
            .draw-table td { padding: 11px 12px; border-bottom: 1px solid var(--paper2); vertical-align: middle; }
            .draw-table tr:last-child td { border-bottom: none; }
            .draw-table tr:hover td { background: #fdfbf7; }
            .pita-badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
            .pita-badge.merah { background: rgba(192,57,43,.1); color: #c0392b; }
            .pita-badge.putih { background: rgba(41,128,185,.1); color: #2980b9; }
            .status-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
            .status-badge.menang { background: rgba(39,174,96,.15); color: #27ae60; }
            .status-badge.kalah { background: rgba(192,57,43,.1); color: #c0392b; }
            .score-circle { width: 36px; height: 36px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; background: rgba(39,174,96,.1); color: #27ae60; border: 1px solid rgba(39,174,96,.2); }
        </style>
    @endpush

    <div class="tm-page">
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-fist-raised" style="color:#c0392b; margin-right:8px;"></i>Rekapitulasi Randori</h2>
                <p>Hasil Pertandingan Per Atlet · Semua Babak Randori</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button wire:click="exportExcel" class="btn-gen success"><i class="fas fa-file-excel"></i> Export Excel</button>
                <button onclick="window.print()" class="btn-gen ghost"><i class="fas fa-print"></i> Cetak</button>
            </div>
        </div>

        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label">Cari Pertandingan</span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input" placeholder="Nama kelas / nomor...">
                </div>
                <div>
                    <span class="tm-filter-label">Kelompok Umur</span>
                    <select wire:model.live="ageGroupFilter" class="tm-filter-sel">
                        <option value="">Semua Kelompok</option>
                        @foreach($ageGroups as $ag)
                            <option wire:key="ag-{{ $ag->id }}" value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Nomor Pertandingan</span>
                    <select wire:model.live="matchNumberFilter" class="tm-filter-sel" wire:key="mn-filter-{{ count($matchNumbersForFilter) }}">
                        <option value="">Semua Nomor</option>
                        @foreach($matchNumbersForFilter as $mn)
                            <option wire:key="mn-{{ $mn->id }}" value="{{ $mn->id }}">{{ $mn->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Gender</span>
                    <select wire:model.live="genderFilter" class="tm-filter-sel">
                        <option value="">Semua Gender</option>
                        <option value="Male">Putra</option>
                        <option value="Female">Putri</option>
                        <option value="Mix">Campuran</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="tm-card">
            <div class="tm-card-head">
                <div class="tm-card-title"><i class="fas fa-table"></i> Data Rekap Randori</div>
                <span style="font-size:12px; color:var(--smoke);">{{ count($rekapRows) }} baris</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="draw-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Babak</th>
                            <th>Kelas (kg)</th>
                            <th class="center">Pool</th>
                            <th class="center">Pita</th>
                            <th>Nama Atlet</th>
                            <th>Kontingen</th>
                            <th class="center">Warn</th>
                            <th class="center">Ippon</th>
                            <th class="center">Waza</th>
                            <th class="center">B5</th>
                            <th class="center">B10</th>
                            <th class="center">Yusei</th>
                            <th class="center">Mujo</th>
                            <th class="center">Total</th>
                            <th class="center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rekapRows as $index => $row)
                            <tr>
                                <td style="font-weight:700; color:var(--smoke); font-size:11px;">{{ $index + 1 }}</td>
                                <td style="font-size:11px; font-weight:700; color:var(--smoke); text-transform:uppercase;">{{ $row->babak }}</td>
                                <td>
                                    <div style="font-size:11px; font-weight:700; color:var(--ink); max-width:140px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $row->kelas }}">{{ $row->kelas }}</div>
                                </td>
                                <td style="text-align:center; font-weight:800; color:var(--ink); text-transform:uppercase;">{{ $row->pool }}</td>
                                <td style="text-align:center;">
                                    <span class="pita-badge {{ strtolower($row->pita) }}">{{ $row->pita }}</span>
                                </td>
                                <td style="font-weight:800; color:var(--ink); text-transform:uppercase; font-size:12px;">{{ $row->nama_atlet }}</td>
                                <td style="font-size:11px; color:var(--ink);">{{ $row->kontingen }}</td>
                                <td style="text-align:center; font-weight:700; color:{{ $row->peringatan > 0 ? '#f39c12' : 'var(--smoke)' }};">{{ $row->peringatan }}</td>
                                <td style="text-align:center; font-weight:800; color:var(--ink);">{{ $row->ippon }}</td>
                                <td style="text-align:center; font-weight:800; color:var(--ink);">{{ $row->waza_ari }}</td>
                                <td style="text-align:center; font-weight:700; color:#c0392b;">{{ $row->batsu_5 }}</td>
                                <td style="text-align:center; font-weight:700; color:#c0392b;">{{ $row->batsu_10 }}</td>
                                <td style="text-align:center; font-weight:800; color:var(--ink);">{{ $row->yusei_kachi }}</td>
                                <td style="text-align:center; font-weight:800; color:var(--ink);">{{ $row->mujoken }}</td>
                                <td style="text-align:center; background:rgba(39,174,96,.04);">
                                    <span class="score-circle">{{ number_format($row->total_nilai, 0) }}</span>
                                </td>
                                <td style="text-align:center;">
                                    <span class="status-badge {{ strtolower($row->status) }}">
                                        {{ $row->status }}{{ $row->win_method ? ' ('.$row->win_method.')' : '' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="16" style="padding:50px 20px; text-align:center; color:var(--smoke);">
                                    <i class="fas fa-database" style="font-size:28px; opacity:.2; margin-bottom:10px; display:block;"></i>
                                    <div style="font-size:12px; font-weight:700; text-transform:uppercase;">Tidak ada data rekapitulasi randori</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 12px 18px; border-top: 1px solid var(--paper2);">
                {{ $results->links('livewire.admin.pagination') }}
            </div>
        </div>
    </div>
</div>
