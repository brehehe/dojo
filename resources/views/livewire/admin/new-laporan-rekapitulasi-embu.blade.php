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
            .tm-filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; }
            .tm-filter-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--smoke); margin-bottom: 6px; display: block; }
            .tm-filter-sel, .tm-filter-input { width: 100%; padding: 9px 12px; border: 1px solid var(--paper2); border-radius: 10px; font-size: 12.5px; color: var(--ink); background: #fdfbf7; outline: none; }
            .tm-filter-sel:focus, .tm-filter-input:focus { border-color: var(--ink); }
            .tm-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; overflow: hidden; }
            .tm-card-head { padding: 14px 20px; border-bottom: 1px solid var(--paper2); display: flex; align-items: center; justify-content: space-between; background: #fdfbf7; }
            .tm-card-title { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--ink); text-transform: uppercase; letter-spacing: .05em; display: flex; align-items: center; gap: 8px; }
            .tm-card-title i { color: var(--red, #c0392b); }
            .draw-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
            .draw-table th { padding: 10px 14px; background: #fdfbf7; font-size: 10px; color: var(--smoke); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; text-align: left; border-bottom: 1px solid var(--paper2); }
            .draw-table th.center { text-align: center; }
            .draw-table td { padding: 12px 14px; border-bottom: 1px solid var(--paper2); vertical-align: middle; }
            .draw-table tr:last-child td { border-bottom: none; }
            .draw-table tr:hover td { background: #fdfbf7; }
            .draw-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 20px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
            .draw-badge.penyisihan { background: rgba(41,128,185,.1); color: #2980b9; }
            .draw-badge.final { background: rgba(243,156,18,.1); color: #f39c12; }
            .rank-circle { width: 28px; height: 28px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; }
            .rank-circle.gold { background: #f1c40f; color: #fff; }
            .rank-circle.silver { background: #bdc3c7; color: #fff; }
            .rank-circle.bronze { background: #e67e22; color: #fff; }
            .rank-circle.other { background: var(--paper2); color: var(--smoke); }
        </style>
    @endpush

    <div class="tm-page">
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-chart-bar" style="color:#3498db; margin-right:8px;"></i>Rekapitulasi Embu</h2>
                <p>Hasil Perolehan Skor & Ranking · Semua Babak</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button wire:click="exportExcel" class="btn-gen success"><i class="fas fa-file-excel"></i> Export Excel</button>
                <button onclick="window.print()" class="btn-gen ghost"><i class="fas fa-print"></i> Cetak</button>
            </div>
        </div>

        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label">Cari Atlet</span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input" placeholder="Nama atlet / nomor...">
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
                    <span class="tm-filter-label">Babak</span>
                    <select wire:model.live="roundFilter" class="tm-filter-sel">
                        <option value="">Semua Babak</option>
                        <option value="Penyisihan">Penyisihan</option>
                        <option value="Final">Final</option>
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
                <div class="tm-card-title"><i class="fas fa-list-ol"></i> Data Skor Embu</div>
                <span style="font-size:12px; color:var(--smoke);">{{ $scores->total() }} entri</span>
            </div>
            <div style="overflow-x: auto;">
                <table class="draw-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Info Pertandingan</th>
                            <th>Peserta / Kontingen</th>
                            <th class="center">J1</th><th class="center">J2</th><th class="center">J3</th><th class="center">J4</th><th class="center">J5</th>
                            <th class="center">Denda</th>
                            <th class="center">Nilai Akhir</th>
                            <th class="center">Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scores as $index => $score)
                            <tr>
                                <td style="font-weight:700; color:var(--smoke); font-size:11px;">
                                    {{ ($scores->currentPage() - 1) * $scores->perPage() + $index + 1 }}
                                </td>
                                <td>
                                    <div style="font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase;">{{ $score->matchNumber->name }}</div>
                                    <div style="display:flex; align-items:center; gap:6px; margin-top:4px;">
                                        <span class="draw-badge {{ strtolower($score->round_label) }}">{{ $score->round_label }}</span>
                                        <span style="font-size:9px; color:var(--smoke); font-weight:700; text-transform:uppercase;">{{ $score->matchNumber->ageGroup->name ?? '' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:12px; font-weight:800; color:var(--ink); text-transform:uppercase;">
                                        {{ $score->matchNumber->athletes->where('pivot.registration_id', $score->registration_id)->pluck('name')->join(' & ') }}
                                    </div>
                                    <div style="font-size:10px; font-weight:700; color:#2980b9; text-transform:uppercase; margin-top:2px;">{{ $score->registration->contingent?->name ?? '-' }}</div>
                                </td>
                                <td style="text-align:center; font-weight:700;">{{ number_format($score->judge_1, 1) }}</td>
                                <td style="text-align:center; font-weight:700;">{{ number_format($score->judge_2, 1) }}</td>
                                <td style="text-align:center; font-weight:700;">{{ number_format($score->judge_3, 1) }}</td>
                                <td style="text-align:center; font-weight:700;">{{ number_format($score->judge_4, 1) }}</td>
                                <td style="text-align:center; font-weight:700;">{{ number_format($score->judge_5, 1) }}</td>
                                <td style="text-align:center; font-weight:700; color:#c0392b;">{{ $score->denda > 0 ? '-'.number_format($score->denda,1) : '0' }}</td>
                                <td style="text-align:center; background:rgba(41,128,185,.04);">
                                    <span style="font-family:'Outfit',sans-serif; font-size:16px; font-weight:800; color:var(--ink);">{{ number_format($score->nilai_akhir, 1) }}</span>
                                </td>
                                <td style="text-align:center;">
                                    @php $rank = $score->rank ?? 0; $cls = match(true) { $rank===1=>'gold', $rank===2=>'silver', $rank===3=>'bronze', default=>'other' }; @endphp
                                    @if($rank > 0)
                                        <span class="rank-circle {{ $cls }}">{{ $rank }}</span>
                                    @else
                                        <span style="font-size:11px; color:var(--smoke);">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" style="padding:50px 20px; text-align:center; color:var(--smoke);">
                                    <i class="fas fa-database" style="font-size:28px; opacity:.2; margin-bottom:10px; display:block;"></i>
                                    <div style="font-size:12px; font-weight:700; text-transform:uppercase;">Tidak ada data</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 12px 18px; border-top: 1px solid var(--paper2);">
                {{ $scores->links('livewire.admin.pagination') }}
            </div>
        </div>
    </div>
</div>
