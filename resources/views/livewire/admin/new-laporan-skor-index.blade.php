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
            /* MATCH BLOCK */
            .mn-block { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; overflow: hidden; margin-bottom: 20px; }
            .mn-block-head { background: var(--ink, #2c3e50); padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; }
            .mn-block-title { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: #fff; text-transform: uppercase; flex:1; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
            .mn-block-badges { display:flex; align-items:center; gap:6px; flex-shrink:0; }
            .type-badge { padding: 2px 8px; border-radius: 20px; font-size: 9px; font-weight: 700; text-transform: uppercase; }
            .type-badge.embu { background: rgba(243,156,18,.25); color: #f39c12; }
            .type-badge.randori { background: rgba(192,57,43,.25); color: #e74c3c; }
            .draw-table { width: 100%; border-collapse: collapse; font-size: 12px; }
            .draw-table th { padding: 9px 14px; background: #fdfbf7; font-size: 9px; color: var(--smoke); font-weight: 700; text-transform: uppercase; letter-spacing: .05em; text-align: left; border-bottom: 1px solid var(--paper2); }
            .draw-table th.center { text-align: center; }
            .draw-table td { padding: 11px 14px; border-bottom: 1px solid var(--paper2); vertical-align: middle; }
            .draw-table tr:last-child td { border-bottom: none; }
            .draw-table tr:hover td { background: #fdfbf7; }
            .score-pill { display: inline-flex; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 800; background: rgba(41,128,185,.1); color: #2980b9; }
            .winner-row td { background: rgba(39,174,96,.04) !important; }
            .vs-divider { text-align: center; font-style: italic; color: var(--paper2); font-weight: 800; font-size: 13px; }
            .athlete-winner { color: #27ae60 !important; }
            .athlete-loser { color: var(--smoke) !important; }
        </style>
    @endpush

    <div class="tm-page">
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-file-invoice" style="color:#8e44ad; margin-right:8px;"></i>Laporan Skor Menyeluruh</h2>
                <p>Semua Penilaian Peserta & Pertandingan · Embu & Randori</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button wire:click="exportExcel" class="btn-gen success"><i class="fas fa-file-excel"></i> Export Excel</button>
                <button onclick="window.print()" class="btn-gen ghost"><i class="fas fa-print"></i> Cetak</button>
            </div>
        </div>

        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label">Cari Nomor</span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input" placeholder="Nama nomor pertandingan...">
                </div>
                <div>
                    <span class="tm-filter-label">Kategori</span>
                    <select wire:model.live="draftTypeFilter" class="tm-filter-sel">
                        <option value="">Semua Kategori</option>
                        <option value="embu">Embu</option>
                        <option value="randori">Randori</option>
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Kelompok Umur</span>
                    <select wire:model.live="ageGroupFilter" class="tm-filter-sel">
                        <option value="">Semua Kelompok</option>
                        @foreach($ageGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
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

        @forelse($matchNumbers as $mn)
            @php $isEmbu = strtolower($mn->draft_type) === 'embu'; @endphp
            <div class="mn-block">
                <div class="mn-block-head">
                    <div class="mn-block-title">{{ $mn->display_name ?? $mn->name }}</div>
                    <div class="mn-block-badges">
                        <span class="type-badge {{ $isEmbu ? 'embu' : 'randori' }}">{{ $mn->draft_type }}</span>
                        @if($mn->ageGroup)
                            <span style="font-size:9px; color:rgba(255,255,255,.5); font-weight:700; text-transform:uppercase;">{{ $mn->ageGroup->name }} · {{ $mn->gender }}</span>
                        @endif
                    </div>
                </div>

                <div style="overflow-x: auto;">
                    @if($isEmbu)
                        <table class="draw-table">
                            <thead>
                                <tr>
                                    <th>Peserta / Kontingen</th>
                                    <th class="center">Penyisihan</th>
                                    <th class="center">Final</th>
                                    <th class="center">Akumulasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mn->all_scores as $score)
                                    <tr>
                                        <td>
                                            <div style="font-size:13px; font-weight:800; color:var(--ink); text-transform:uppercase;">{{ $score->athlete_names }}</div>
                                            <div style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; margin-top:2px;">{{ $score->contingent_name }}</div>
                                        </td>
                                        <td style="text-align:center; font-weight:700; color:var(--ink);">
                                            {{ $score->penyisihan_score > 0 ? number_format($score->penyisihan_score, 2) : '-' }}
                                        </td>
                                        <td style="text-align:center; font-weight:700; color:var(--ink);">
                                            {{ $score->final_score > 0 ? number_format($score->final_score, 2) : '-' }}
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="score-pill">{{ number_format($score->accumulated_score, 2) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="padding:30px; text-align:center; color:var(--smoke); font-style:italic; font-size:12px;">Belum ada data penilaian untuk nomor ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        {{-- Randori --}}
                        <table class="draw-table">
                            <thead>
                                <tr>
                                    <th>Bracket Node</th>
                                    <th style="text-align:right;">Merah (AKA)</th>
                                    <th class="center">VS</th>
                                    <th>Putih (SHIRO)</th>
                                    <th>Pemenang</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mn->all_scores as $res)
                                    <tr>
                                        <td style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase;">{{ strtoupper($res->bracket_node) }}</td>
                                        <td style="text-align:right;">
                                            <div style="font-size:12px; font-weight:800; text-transform:uppercase;" class="{{ $res->winner_color === 'red' ? 'athlete-winner' : 'athlete-loser' }}">{{ $res->athlete1['name'] ?? '—' }}</div>
                                            <div style="font-size:10px; font-weight:600; color:var(--smoke);">{{ $res->athlete1['contingent'] ?? '—' }}</div>
                                            <div style="font-family:'Outfit',sans-serif; font-size:16px; font-weight:800; margin-top:4px;" class="{{ $res->winner_color === 'red' ? 'athlete-winner' : '' }}" style="color:var(--smoke);">{{ number_format($res->score_red ?? 0, 2) }}</div>
                                        </td>
                                        <td class="vs-divider">VS</td>
                                        <td>
                                            <div style="font-size:12px; font-weight:800; text-transform:uppercase;" class="{{ $res->winner_color === 'blue' ? 'athlete-winner' : 'athlete-loser' }}">{{ $res->athlete2['name'] ?? '—' }}</div>
                                            <div style="font-size:10px; font-weight:600; color:var(--smoke);">{{ $res->athlete2['contingent'] ?? '—' }}</div>
                                            <div style="font-family:'Outfit',sans-serif; font-size:16px; font-weight:800; margin-top:4px;" class="{{ $res->winner_color === 'blue' ? 'athlete-winner' : '' }}">{{ number_format($res->score_blue ?? 0, 2) }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $winnerName = '-';
                                                $winnerSide = '';
                                                if ($res->winner_color === 'athlete1' || $res->winner_color === 'red') {
                                                    $winnerName = $res->athlete1['name'] ?? '-';
                                                    $winnerSide = 'AKA (MERAH)';
                                                } elseif ($res->winner_color === 'athlete2' || $res->winner_color === 'blue') {
                                                    $winnerName = $res->athlete2['name'] ?? '-';
                                                    $winnerSide = 'SHIRO (PUTIH)';
                                                } elseif ($res->winner) {
                                                    $winnerName = $res->winner->name;
                                                    $winnerSide = strtoupper($res->winner_color ?? '');
                                                }
                                            @endphp
                                            @if($winnerName !== '-')
                                                <div style="display:flex; align-items:center; gap:8px;">
                                                    <i class="fas fa-crown" style="color:#f39c12; font-size:12px;"></i>
                                                    <div>
                                                        <div style="font-size:11px; font-weight:800; color:var(--ink); text-transform:uppercase;">{{ $winnerName }}</div>
                                                        <div style="font-size:9px; font-weight:700; color:#27ae60; text-transform:uppercase; margin-top:1px;">MENANG {{ $winnerSide }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span style="font-size:10px; color:var(--smoke);">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="padding:30px; text-align:center; color:var(--smoke); font-style:italic; font-size:12px;">Belum ada data pertandingan randori untuk nomor ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @empty
            <div style="background:#fff; border:1px dashed var(--paper2); border-radius:16px; padding:60px 20px; text-align:center;">
                <i class="fas fa-file-invoice" style="font-size:36px; opacity:.15; margin-bottom:12px; display:block; color:var(--ink);"></i>
                <div style="font-size:13px; font-weight:700; text-transform:uppercase; color:var(--smoke);">Tidak Ada Data Ditemukan</div>
                <div style="font-size:12px; color:var(--smoke); margin-top:4px; opacity:.7;">Coba sesuaikan filter pencarian</div>
            </div>
        @endforelse

        <div style="margin-top: 24px;">
            {{ $matchNumbers->links('livewire.admin.pagination') }}
        </div>
    </div>
</div>
