<div>
    @push('styles')
        <style>
            /* ---------- Page Layout ---------- */
            .lap-page { padding: 28px; padding-bottom: 100px; background: var(--paper, #F7F4EF); min-height: 100vh; font-family: 'DM Sans', sans-serif; }

            /* ---------- Header ---------- */
            .lap-hdr { margin-bottom: 24px; display: flex; flex-direction: column; gap: 16px; }
            @media(min-width: 768px) { .lap-hdr { flex-direction: row; justify-content: space-between; align-items: flex-end; } }
            .lap-hdr h2 { font-family: 'Cinzel', serif; font-size: 22px; font-weight: 700; margin: 0 0 4px; color: var(--ink, #2c3e50); }
            .lap-hdr p  { font-size: 13px; color: var(--smoke, #7f8c8d); margin: 0; }

            /* ---------- Filter Card ---------- */
            .filter-card { background: #fff; border: 1px solid #e8e3da; border-radius: 16px; padding: 20px; margin-bottom: 20px; }
            .filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; }
            .filter-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--smoke); margin-bottom: 5px; display: block; }
            .filter-sel, .filter-input { width: 100%; padding: 9px 12px; border: 1px solid #e8e3da; border-radius: 10px; font-size: 12.5px; color: var(--ink); background: #fdfbf7; outline: none; transition: border .2s; }
            .filter-sel:focus, .filter-input:focus { border-color: #c0392b; }

            /* ---------- Chart Card ---------- */
            .chart-card { background: #fff; border: 1px solid #e8e3da; border-radius: 16px; padding: 24px; margin-bottom: 20px; }
            .chart-card-title { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 18px; display: flex; align-items: center; gap: 8px; }

            /* ---------- Table Card ---------- */
            .tbl-card { background: #fff; border: 1px solid #e8e3da; border-radius: 16px; overflow-x: auto; }
            .tbl { width: 100%; border-collapse: collapse; min-width: 1080px; }
            .tbl th { background: #fdfbf7; padding: 13px 16px; text-align: left; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: #7f8c8d; border-bottom: 1px solid #e8e3da; white-space: nowrap; }
            .tbl td { padding: 13px 16px; font-size: 13px; color: var(--ink); border-bottom: 1px solid #f0ece4; vertical-align: middle; }
            .tbl tr:last-child td { border-bottom: none; }
            .tbl tr:hover td { background: #fdfbf7; }

            /* ---------- Referee Chip ---------- */
            .rf-chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px 4px 6px; border-radius: 20px; background: rgba(192,57,43,.08); border: 1px solid rgba(192,57,43,.18); }
            .rf-avatar { width: 24px; height: 24px; border-radius: 50%; background: #c0392b; color: #fff; font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
            .rf-name  { font-size: 12px; font-weight: 700; color: #c0392b; }

            /* ---------- Score Badge ---------- */
            .score-badge { display: inline-block; padding: 4px 10px; border-radius: 8px; font-size: 14px; font-weight: 900; background: #f7f4ef; color: var(--ink); min-width: 48px; text-align: center; }
            .score-badge.high { background: rgba(39,174,96,.1); color: #27ae60; }
            .score-badge.low  { background: rgba(231,76,60,.1);  color: #e74c3c; }

            /* ---------- Misc ---------- */
            .badge-round { display: inline-block; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; background: #f0ece4; color: #7f8c8d; }
            .btn-print { display: inline-flex; align-items: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; cursor: pointer; border: 1px solid #e8e3da; background: #fff; color: #7f8c8d; transition: all .2s; }
            .btn-print:hover { background: #f7f4ef; }
        </style>
    @endpush

    <div class="lap-page">
        {{-- HEADER --}}
        <div class="lap-hdr">
            <div>
                <h2><i class="fas fa-chart-bar" style="color:#c0392b; margin-right:8px;"></i>Analisis Penilaian Per Juri</h2>
                <p>Laporan lengkap per-wasit: Nomor Pertandingan, Kontingen, Lokasi &amp; Penilaian</p>
            </div>
            <button onclick="window.print()" class="btn-print"><i class="fas fa-print"></i> Cetak</button>
        </div>

        {{-- FILTERS --}}
        <div class="filter-card">
            <div class="filter-grid">
                <div>
                    <span class="filter-label">Cari Wasit / Kontingen</span>
                    <input type="text" wire:model.live.debounce.500ms="search" class="filter-input" placeholder="Ketik nama...">
                </div>
                <div>
                    <span class="filter-label">Wasit</span>
                    <select wire:model.live="refereeFilter" class="filter-sel">
                        <option value="">Semua Wasit</option>
                        @foreach($referees as $rf)
                            <option value="{{ $rf->id }}">{{ $rf->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="filter-label">Kelompok Umur</span>
                    <select wire:model.live="ageGroupFilter" class="filter-sel">
                        <option value="">Semua Umur</option>
                        @foreach($ageGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="filter-label">
                        Nomor Pertandingan
                        @if(empty($ageGroupFilter))
                            <span style="font-weight:400; opacity:.6;">(pilih umur dulu)</span>
                        @endif
                    </span>
                    <select wire:model.live="matchNumberFilter" class="filter-sel" @if(empty($ageGroupFilter)) disabled @endif style="{{ empty($ageGroupFilter) ? 'opacity:.5;cursor:not-allowed;' : '' }}">
                        <option value="">Semua Nomor</option>
                        @foreach($matchNumbers as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="filter-label">Jenis Kelamin</span>
                    <select wire:model.live="genderFilter" class="filter-sel">
                        <option value="">Semua</option>
                        <option value="Male">Putra</option>
                        <option value="Female">Putri</option>
                    </select>
                </div>
                <div>
                    <span class="filter-label">Court</span>
                    <select wire:model.live="courtFilter" class="filter-sel">
                        <option value="">Semua Court</option>
                        @foreach($courts as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="filter-label">Pool</span>
                    <select wire:model.live="poolFilter" class="filter-sel">
                        <option value="">Semua Pool</option>
                        @foreach($pools as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="filter-label">Rundown</span>
                    <select wire:model.live="rundownFilter" class="filter-sel">
                        <option value="">Semua Rundown</option>
                        @foreach($rundowns as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- CHART --}}
        @if($chartData->isNotEmpty())
            <div class="chart-card">
                <div class="chart-card-title">
                    <i class="fas fa-chart-column" style="color:#c0392b;"></i>
                    Rata-rata Penilaian per Wasit
                    <span style="font-size:11px; font-weight:400; font-family:'DM Sans',sans-serif; color:var(--smoke); margin-left:4px;">(halaman saat ini)</span>
                </div>
                <div wire:ignore id="refereeScoreChart" style="min-height:320px;"></div>
            </div>
        @endif

        {{-- TABLE --}}
        <div class="tbl-card">
            <table class="tbl">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Wasit</th>
                        <th>Nomor Pertandingan</th>
                        <th>Kontingen</th>
                        <th>Court</th>
                        <th>Pool</th>
                        <th>Waktu Sesi</th>
                        <th>Rundown</th>
                        <th style="text-align:center;">Juri Ke-</th>
                        <th style="text-align:center;">Penilaian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scoreRows as $i => $row)
                        @php
                            $drawing = $drawings[$row->drawing_id] ?? null;
                        @endphp
                        <tr>
                            <td style="color:var(--smoke); font-size:11px;">{{ $scoreRows->firstItem() + $i }}</td>
                            <td>
                                <div class="rf-chip">
                                    <div class="rf-avatar">{{ strtoupper(substr($row->referee?->name ?? '?', 0, 1)) }}</div>
                                    <span class="rf-name">{{ $row->referee?->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td>
                                <div style="font-family:'Cinzel',serif; font-size:11px; font-weight:700; color:#c0392b;">
                                    {{ $row->matchNumber?->name ?? '—' }}
                                </div>
                                @if($row->round)
                                    <div class="badge-round" style="margin-top:4px;">{{ $row->round }}</div>
                                @endif
                            </td>
                            <td style="font-weight:700; text-transform:uppercase; font-size:12px;">
                                {{ $row->contingent_name }}
                            </td>
                            <td>
                                @if($drawing?->court)
                                    <span style="font-weight:600; font-size:12px;">{{ $drawing->court->name }}</span>
                                @else
                                    <span style="color:var(--smoke);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($drawing?->pool)
                                    <span style="font-size:12px;">{{ $drawing->pool->name }}</span>
                                @else
                                    <span style="color:var(--smoke);">—</span>
                                @endif
                            </td>
                            <td style="font-size:12px;">
                                {{ $drawing?->sessionTime?->name ?? '—' }}
                            </td>
                            <td style="font-size:12px; max-width:160px;">
                                {{ $drawing?->rundown?->name ?? '—' }}
                            </td>
                            <td align="center">
                                <span style="font-size:13px; font-weight:800; color:var(--ink);">
                                    Juri {{ $row->judge_index }}
                                </span>
                            </td>
                            <td align="center">
                                @php
                                    $score = (float) $row->total_calculated_score;
                                    $cls = $score >= 20 ? 'high' : ($score < 10 ? 'low' : '');
                                @endphp
                                <span class="score-badge {{ $cls }}">{{ number_format($score, 2) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" align="center" style="padding:50px; color:var(--smoke);">
                                <i class="fas fa-inbox" style="font-size:24px; margin-bottom:8px; display:block;"></i>
                                Tidak ada data penilaian yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $scoreRows->links('livewire.admin.pagination') }}
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script>
        let deviationChart = null;

        function renderRefereeChart() {
            const chartData = @js($chartData);
            const el = document.getElementById('refereeScoreChart');
            if (!el || !chartData || chartData.length === 0) return;

            if (deviationChart) { deviationChart.destroy(); deviationChart = null; }

            deviationChart = new ApexCharts(el, {
                series: [
                    { name: 'Rata-rata Skor', data: chartData.map(d => d.avg) },
                    { name: 'Total Skor', data: chartData.map(d => d.total) },
                ],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: { show: false },
                    fontFamily: 'DM Sans, sans-serif',
                },
                plotOptions: {
                    bar: { borderRadius: 6, columnWidth: '55%', grouped: true }
                },
                colors: ['#c0392b', '#2c3e50'],
                dataLabels: {
                    enabled: true,
                    style: { fontSize: '10px', colors: ['#fff'] }
                },
                xaxis: {
                    categories: chartData.map(d => d.name),
                    labels: { rotate: -30, style: { fontSize: '10px' } }
                },
                yaxis: { title: { text: 'Skor' } },
                legend: { position: 'top' },
                grid: { borderColor: '#e8e3da' },
                tooltip: {
                    y: {
                        formatter: (val, { seriesIndex, dataPointIndex }) => {
                            const d = chartData[dataPointIndex];
                            if (seriesIndex === 0) return val.toFixed(2) + ' (dari ' + d.count + ' pertandingan)';
                            return val.toFixed(2);
                        }
                    }
                }
            });
            deviationChart.render();
        }

        document.addEventListener('DOMContentLoaded', renderRefereeChart);
        document.addEventListener('lived', renderRefereeChart);
        Livewire.hook('morph.updated', () => { setTimeout(renderRefereeChart, 100); });
    </script>
    @endpush
</div>
