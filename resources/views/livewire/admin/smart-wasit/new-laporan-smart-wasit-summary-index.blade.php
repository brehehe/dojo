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

            /* FILTER CARD */
            .tm-filter-card {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .tm-filter-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 12px;
                margin-bottom: 12px;
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
            .tm-filter-sel {
                width: 100%;
                padding: 9px 12px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12.5px;
                color: var(--ink);
                background: #fdfbf7;
                outline: none;
                cursor: pointer;
            }
            .tm-filter-sel:focus { border-color: var(--ink); }
            .tm-filter-input {
                width: 100%;
                padding: 9px 12px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12.5px;
                color: var(--ink);
                background: #fdfbf7;
                outline: none;
            }
            .tm-filter-input:focus { border-color: var(--ink); }

            /* TABS */
            .tm-tabs {
                display: flex;
                gap: 8px;
                margin-bottom: 24px;
                overflow-x: auto;
                padding-bottom: 8px;
                no-scrollbar::-webkit-scrollbar { display: none; }
                -ms-overflow-style: none; scrollbar-width: none;
            }
            .tm-tab-btn {
                padding: 10px 20px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                cursor: pointer;
                transition: all .2s;
                border: 1px solid var(--paper2);
                background: #fff;
                color: var(--smoke);
                white-space: nowrap;
            }
            .tm-tab-btn.active {
                background: var(--ink);
                color: #fff;
                border-color: var(--ink);
                box-shadow: 0 4px 12px rgba(44,62,80,.15);
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
            .tm-table tr:last-child td { border-bottom: none; }
            .tm-table tr:hover td { background: #fdfbf7; }

            .badge-grade {
                padding: 4px 10px;
                border-radius: 6px;
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                background: var(--ink);
                color: #fff;
            }

            .val-iaw { color: #3498db; font-weight: 800; }
            .val-ik { color: #27ae60; font-weight: 800; }
            .val-iv { color: #e67e22; font-weight: 800; }
            .val-skw { font-size: 15px; font-weight: 900; color: var(--ink); }

            /* BUTTONS */
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
            .btn-gen.primary { background: var(--ink); color: #fff; }
            .btn-gen.success { background: #27ae60; color: #fff; }
            .btn-gen.ghost { background: #fff; color: var(--smoke); border: 1px solid var(--paper2); }

            .no-scrollbar::-webkit-scrollbar { display: none; }
        </style>
    @endpush

    <div class="tm-page">
        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-balance-scale" style="color:#c0392b; margin-right:8px;"></i>Laporan Smart Wasit</h2>
                <p>Analisis Performa Komprehensif · Akurasi (IAW), Konsistensi (IK), Validitas (IV)</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button wire:click="exportExcel" class="btn-gen success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button onclick="window.print()" class="btn-gen ghost">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label"><i class="fas fa-search" style="margin-right:4px;"></i>Cari Atlet</span>
                    <input type="text" wire:model.live.debounce.500ms="search" class="tm-filter-input" placeholder="Nama atlet...">
                </div>
                <div>
                    <span class="tm-filter-label">Kelompok Umur</span>
                    <select wire:model.live="ageGroupFilter" class="tm-filter-sel">
                        <option value="">Semua Umur</option>
                        @foreach($ageGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Nomor Pertandingan</span>
                    <select wire:model.live="matchNumberFilter" class="tm-filter-sel">
                        <option value="">Semua Nomor</option>
                        @foreach($matchNumbers as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Wasit / Juri</span>
                    <select wire:model.live="refereeFilter" class="tm-filter-sel">
                        <option value="">Semua Wasit</option>
                        @foreach($referees as $rf)
                            <option value="{{ $rf->id }}">{{ $rf->name }}</option>
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
                <div>
                    <span class="tm-filter-label">Babak</span>
                    <select wire:model.live="roundFilter" class="tm-filter-sel">
                        <option value="">Semua Babak</option>
                        <option value="Penyisihan">Penyisihan</option>
                        <option value="Semifinal">Semifinal</option>
                        <option value="Final">Final</option>
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Court / Lapangan</span>
                    <select wire:model.live="courtFilter" class="tm-filter-sel">
                        <option value="">Semua Lapangan</option>
                        @foreach($courts as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="tm-tabs no-scrollbar">
            <button wire:click=\"$set('tab', 'skw')\" class="tm-tab-btn {{ $tab === 'skw' ? 'active' : '' }}">Ranking Kompetensi</button>
            <button wire:click=\"$set('tab', 'iaw')\" class="tm-tab-btn {{ $tab === 'iaw' ? 'active' : '' }}">Analisis Akurasi</button>
            <button wire:click=\"$set('tab', 'ik')\" class="tm-tab-btn {{ $tab === 'ik' ? 'active' : '' }}">Analisis Konsistensi</button>
            <button wire:click=\"$set('tab', 'iv')\" class="tm-tab-btn {{ $tab === 'iv' ? 'active' : '' }}">Analisis Validitas</button>
            <button wire:click=\"$set('tab', 'detail')\" class="tm-tab-btn {{ $tab === 'detail' ? 'active' : '' }}">Detail Penilaian</button>
        </div>

        {{-- CONTENT SECTIONS --}}
        <div class="tm-table-card">
            @if($tab === 'skw')
                <table class="tm-table">
                    <thead>
                        <tr>
                            <th width="60">Rank</th>
                            <th>Nama Wasit</th>
                            <th>Court</th>
                            <th style="text-align:center">Jumlah</th>
                            <th style="text-align:center">IAW (%)</th>
                            <th style="text-align:center">IK</th>
                            <th style="text-align:center">IV</th>
                            <th style="text-align:center">SKW</th>
                            <th style="text-align:center">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rank = 1; @endphp
                        @foreach($refereeAnalysis->sortByDesc('skw') as $rf)
                            <tr>
                                <td style="font-weight:800; color:var(--smoke)">{{ $rank++ }}</td>
                                <td style="font-weight:700; text-transform:uppercase">{{ $rf['name'] }}</td>
                                <td><span style="font-size:10px; font-weight:700; background:var(--paper); padding:2px 6px; border-radius:4px">{{ $rf['primary_court'] }}</span></td>
                                <td align="center" style="color:var(--smoke)">{{ $rf['count'] }}</td>
                                <td align="center" class="val-iaw">{{ number_format($rf['iaw'], 2) }}%</td>
                                <td align="center" class="val-ik">{{ number_format($rf['ik'], 3) }}</td>
                                <td align="center" class="val-iv">{{ number_format($rf['iv'], 3) }}</td>
                                <td align="center" class="val-skw">{{ number_format($rf['skw'], 2) }}</td>
                                <td align="center"><span class="badge-grade">{{ $rf['grade'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($tab === 'iaw')
                <table class="tm-table">
                    <thead>
                        <tr>
                            <th>Wasit</th>
                            <th style="text-align:center">Jumlah</th>
                            <th style="text-align:center">Rata-rata</th>
                            <th style="text-align:center">Referensi</th>
                            <th style="text-align:center">IAW (%)</th>
                            <th style="text-align:center">Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refereeAnalysis->sortByDesc('iaw') as $rf)
                            <tr>
                                <td style="font-weight:700; text-transform:uppercase">{{ $rf['name'] }}</td>
                                <td align="center" style="color:var(--smoke)">{{ $rf['count'] }}</td>
                                <td align="center" style="font-weight:700">{{ number_format($rf['avg_total'], 2) }}</td>
                                <td align="center" style="color:var(--smoke)">{{ number_format($rf['avg_ref'], 2) }}</td>
                                <td align="center" class="val-iaw">{{ number_format($rf['iaw'], 2) }}%</td>
                                <td align="center"><span style="font-size:10px; font-weight:800; text-transform:uppercase; color:#3498db">{{ $rf['iaw_category'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($tab === 'ik')
                <table class="tm-table">
                    <thead>
                        <tr>
                            <th>Wasit</th>
                            <th style="text-align:center">Jumlah</th>
                            <th style="text-align:center">Max</th>
                            <th style="text-align:center">Min</th>
                            <th style="text-align:center">Std Dev</th>
                            <th style="text-align:center">IK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refereeAnalysis->sortByDesc('ik') as $rf)
                            <tr>
                                <td style="font-weight:700; text-transform:uppercase">{{ $rf['name'] }}</td>
                                <td align="center" style="color:var(--smoke)">{{ $rf['count'] }}</td>
                                <td align="center" style="font-weight:700">{{ number_format($rf['max'], 1) }}</td>
                                <td align="center" style="font-weight:700">{{ number_format($rf['min'], 1) }}</td>
                                <td align="center" style="color:var(--smoke); font-style:italic">{{ number_format($rf['std_dev'], 3) }}</td>
                                <td align="center" class="val-ik">{{ number_format($rf['ik'], 3) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($tab === 'iv')
                <table class="tm-table">
                    <thead>
                        <tr>
                            <th>Wasit</th>
                            <th style="text-align:center">Korelasi (r)</th>
                            <th>Interpretasi</th>
                            <th style="text-align:center">Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($refereeAnalysis->sortByDesc('iv') as $rf)
                            <tr>
                                <td style="font-weight:700; text-transform:uppercase">{{ $rf['name'] }}</td>
                                <td align="center" class="val-iv">{{ number_format($rf['iv'], 3) }}</td>
                                <td style="font-style:italic; color:var(--smoke)">{{ $rf['iv_interpretation'] }}</td>
                                <td align="center"><span style="font-size:10px; font-weight:800; text-transform:uppercase; color:#e67e22">{{ $rf['iv_category'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif($tab === 'detail')
                <table class="tm-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Court</th>
                            <th>Wasit</th>
                            <th>Kontingen</th>
                            <th style="text-align:center">Teknik</th>
                            <th style="text-align:center">Ekspresi</th>
                            <th style="text-align:center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessments as $item)
                            <tr>
                                <td style="color:var(--smoke); font-weight:600">{{ $item->date }}</td>
                                <td><span style="font-size:9px; font-weight:800; background:var(--paper); padding:2px 6px; border-radius:4px">{{ $item->court }}</span></td>
                                <td style="font-weight:700; text-transform:uppercase">{{ $item->referee }}</td>
                                <td style="color:var(--smoke); font-weight:600; text-transform:uppercase">{{ $item->contingent }}</td>
                                <td align="center" style="color:#3498db; font-weight:700">{{ number_format($item->teknik, 2) }}</td>
                                <td align="center" style="color:#e67e22; font-weight:700">{{ number_format($item->ekspresi, 2) }}</td>
                                <td align="center" style="font-weight:900">{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="padding:16px;">
                    {{ $assessments->links('livewire.admin.pagination') }}
                </div>
            @endif
        </div>
    </div>
</div>
