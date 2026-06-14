<div>
    @push('styles')
        <style>
            .tm-page {
                padding: 24px;
                padding-bottom: 100px;
                background: var(--paper, #F7F4EF);
                min-height: 100vh;
                font-family: 'Inter', sans-serif;
            }

            /* HEADER */
            .tm-hdr {
                margin-bottom: 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            @media(min-width: 768px) {
                .tm-hdr { flex-direction: row; justify-content: space-between; align-items: flex-end; }
            }
            .tm-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 24px;
                font-weight: 700;
                margin: 0 0 4px;
                color: var(--ink, #2c3e50);
            }
            .tm-hdr p {
                font-size: 13px;
                color: var(--smoke, #7f8c8d);
                margin: 0;
            }

            /* STATS CARDS */
            .tm-stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }
            .tm-stat-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 16px;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }
            .tm-stat-label {
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--smoke);
            }
            .tm-stat-val {
                font-size: 20px;
                font-weight: 900;
                color: var(--ink);
            }
            .tm-stat-footer {
                font-size: 11px;
                font-weight: 700;
                color: #27ae60;
            }

            /* FILTER CARD */
            .tm-filter-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 20px;
                margin-bottom: 24px;
            }
            .tm-filter-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 12px;
            }
            .tm-filter-label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke);
                margin-bottom: 6px;
                display: block;
            }
            .tm-filter-sel, .tm-filter-input {
                width: 100%;
                padding: 9px 12px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12.5px;
                color: var(--ink);
                background: #fdfbf7;
                outline: none;
            }

            /* TABLE SECTION */
            .tm-table-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                overflow: hidden;
            }
            .tm-table {
                width: 100%;
                border-collapse: collapse;
            }
            .tm-table th {
                background: #fdfbf7;
                padding: 14px 18px;
                text-align: left;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: .08em;
                color: var(--smoke);
                border-bottom: 1px solid var(--paper2);
            }
            .tm-table td {
                padding: 14px 18px;
                font-size: 13px;
                color: var(--ink);
                border-bottom: 1px solid var(--paper);
            }
            .tm-table tr:hover td { background: #fdfbf7; }

            .badge-round {
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                background: var(--paper);
                color: var(--smoke);
            }

            .btn-gen {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 10px;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                cursor: pointer;
                transition: all .2s;
                border: none;
                text-decoration: none;
                white-space: nowrap;
            }
            .btn-gen.success { background: #27ae60; color: #fff; }
            .btn-gen.ghost { background: #fff; color: var(--smoke); border: 1px solid var(--paper2); }
        </style>
    @endpush

    <div class="tm-page">
        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-layer-group" style="color:#c0392b; margin-right:8px;"></i>Laporan Perbabak</h2>
                <p>Log Detail Penilaian Wasit Setiap Babak Pertandingan · Penyisihan, Semifinal, Final</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button wire:click="exportExcel" class="btn-gen success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button onclick="window.print()" class="btn-gen ghost">
                    <i class="fas fa-print"></i> Cetak Log
                </button>
            </div>
        </div>

        {{-- STATS --}}
        <div class="tm-stats-grid">
            @foreach($roundStats as $stat)
                <div class="tm-stat-card">
                    <span class="tm-stat-label">Babak {{ $stat['name'] }}</span>
                    <span class="tm-stat-val">{{ number_format($stat['avg'], 2) }}</span>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:10px; font-weight:700; color:var(--smoke)">{{ $stat['count'] }} Penilaian</span>
                        <span class="tm-stat-footer">{{ $stat['trend'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FILTERS --}}
        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label">Cari Peserta/Nomor</span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input" placeholder="Search...">
                </div>
                <div>
                    <span class="tm-filter-label">Kelompok Umur</span>
                    <select wire:model.live="ageGroupFilter" class="tm-filter-sel">
                        <option value="">Semua</option>
                        @foreach($ageGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Nomor Pertandingan</span>
                    <select wire:model.live="matchNumberFilter" class="tm-filter-sel">
                        <option value="">Semua</option>
                        @foreach($matchNumbersForFilter as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Wasit / Juri</span>
                    <select wire:model.live="refereeFilter" class="tm-filter-sel">
                        <option value="">Semua</option>
                        @foreach($referees as $rf)
                            <option value="{{ $rf->id }}">{{ $rf->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Gender</span>
                    <select wire:model.live="genderFilter" class="tm-filter-sel">
                        <option value="">Semua</option>
                        <option value="Male">Putra</option>
                        <option value="Female">Putri</option>
                        <option value="Mix">Campuran</option>
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Babak</span>
                    <select wire:model.live="roundFilter" class="tm-filter-sel">
                        <option value="">Semua</option>
                        <option value="Penyisihan">Penyisihan</option>
                        <option value="Semifinal">Semifinal</option>
                        <option value="Final">Final</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="tm-table-card">
            <table class="tm-table">
                <thead>
                    <tr>
                        <th>Babak</th>
                        <th>Info Pertandingan</th>
                        <th>Peserta & Wasit</th>
                        <th style="text-align:center">Teknik</th>
                        <th style="text-align:center">Ekspresi</th>
                        <th style="text-align:center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assessments as $item)
                        @php
                            $details = $item->details ?? [];
                            $teknik = 0;
                            $ekspresi = 0;

                            foreach ($details as $key => $val) {
                                if (str_starts_with($key, 'goho_') || str_starts_with($key, 'juho_')) {
                                    $teknik += (float) $val;
                                } elseif (str_starts_with($key, 'ekspresi_')) {
                                    $ekspresi += (float) $val;
                                }
                            }
                        @endphp
                        <tr>
                            <td><span class="badge-round">{{ $item->round_name }}</span></td>
                            <td>
                                <div style="font-weight:800; text-transform:uppercase; font-size:12px">{{ $item->matchNumber->name }}</div>
                                <div style="font-size:10px; font-weight:700; color:var(--smoke); text-transform:uppercase; margin-top:2px">
                                    {{ $item->matchNumber->ageGroup->name ?? '' }} · {{ $item->matchNumber->gender }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px">
                                    <div style="width:32px; height:32px; border-radius:50%; background:var(--paper); display:flex; align-items:center; justify-content:center; font-weight:900; color:var(--smoke); font-size:12px; flex-shrink:0">
                                        {{ $item->judge_index }}
                                    </div>
                                    <div>
                                        <div style="font-weight:700; text-transform:uppercase; font-size:11px; line-height:1.2">
                                             @php
                                                 $regId = $item->scorable instanceof \App\Models\DrawingMatchNumber ? $item->scorable->registration_id : $item->scorable_id;
                                             @endphp
                                             {{ $item->matchNumber->athletes->where('pivot.registration_id', $regId)->pluck('name')->join(' & ') }}
                                        </div>
                                         <div style="font-size:10px; font-weight:800; color:#3498db; text-transform:uppercase; margin-top:2px">
                                             Juri: {{ $item->referee->name }}
                                             @php
                                                 $obsCount = $item->referee->observations()->count();
                                             @endphp
                                             @if($obsCount > 0)
                                                 @php
                                                     $obsAvg = $item->referee->observations()->avg('total_score');
                                                 @endphp
                                                 <span style="color:#c0392b; font-weight:bold; text-transform:none; margin-left:6px;">
                                                     <i class="fas fa-eye"></i> {{ $obsCount }} Obs (Avg: {{ number_format($obsAvg, 0) }})
                                                 </span>
                                             @endif
                                         </div>
                                    </div>
                                </div>
                            </td>
                            <td align="center" style="font-weight:700; color:#3498db">{{ number_format($teknik, 2) }}</td>
                            <td align="center" style="font-weight:700; color:#e67e22">{{ number_format($ekspresi, 2) }}</td>
                            <td align="center" style="font-weight:900; background:#fdfbf7; font-size:14px">
                                <span style="background:var(--ink); color:#fff; padding:4px 10px; border-radius:8px">
                                    {{ number_format($item->total_calculated_score, 1) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" align="center" style="padding:60px 20px">
                                <i class="fas fa-database" style="font-size:32px; color:var(--paper2); display:block; margin-bottom:12px"></i>
                                <span style="font-size:12px; font-weight:800; text-transform:uppercase; color:var(--smoke)">Tidak ada data penilaian</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div style="padding:16px">
                {{ $assessments->links('livewire.admin.pagination') }}
            </div>
        </div>
    </div>
</div>
