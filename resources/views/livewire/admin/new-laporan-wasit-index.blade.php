<div>
    @push('styles')
        <style>
            .tm-page { padding: 24px; padding-bottom: 100px; background: var(--paper, #F7F4EF); min-height: 100vh; font-family: 'DM Sans', sans-serif; }
            .tm-hdr { margin-bottom: 24px; display: flex; flex-direction: column; gap: 16px; }
            @media(min-width: 768px) { .tm-hdr { flex-direction: row; justify-content: space-between; align-items: flex-end; } }
            .tm-hdr h2 { font-family: 'Cinzel', serif; font-size: 24px; font-weight: 700; margin: 0 0 4px; color: var(--ink, #2c3e50); }
            .tm-hdr p { font-size: 13px; color: var(--smoke, #7f8c8d); margin: 0; }

            .tm-filter-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; padding: 20px; margin-bottom: 20px; }
            .tm-filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 12px; }
            .tm-filter-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: var(--smoke); margin-bottom: 6px; display: block; }
            .tm-filter-sel, .tm-filter-input { width: 100%; padding: 9px 12px; border: 1px solid var(--paper2); border-radius: 10px; font-size: 12.5px; color: var(--ink); background: #fdfbf7; outline: none; }
            .tm-filter-sel:focus, .tm-filter-input:focus { border-color: var(--ink); }

            .tm-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 24px; }
            .tm-stat-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 16px; }
            .tm-stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
            .tm-stat-info h4 { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--smoke); margin: 0 0 4px; letter-spacing: .05em; }
            .tm-stat-info p { font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; color: var(--ink); margin: 0; }

            .tm-chart-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; padding: 20px; margin-bottom: 24px; position: relative; }
            .tm-chart-title { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--ink); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }

            .tm-tabs { display: flex; gap: 8px; margin-bottom: 24px; overflow-x: auto; padding-bottom: 8px; }
            .tm-tabs::-webkit-scrollbar { display: none; }
            .tm-tab-btn { padding: 10px 20px; border-radius: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: all .2s; border: 1px solid var(--paper2); background: #fff; color: var(--smoke); white-space: nowrap; }
            .tm-tab-btn.active { background: var(--ink); color: #fff; border-color: var(--ink); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }

            .tm-table-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; overflow: hidden; }
            .tm-table { width: 100%; border-collapse: collapse; }
            .tm-table th { background: #fdfbf7; padding: 14px 18px; text-align: left; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .08em; color: var(--smoke); border-bottom: 1px solid var(--paper2); }
            .tm-table td { padding: 14px 18px; font-size: 13px; color: var(--ink); border-bottom: 1px solid var(--paper2); }
            .tm-table tr:hover td { background: #fdfbf7; }

            .badge-grade { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; background: var(--ink); color: #fff; }
            .val-iaw { color: #3498db; font-weight: 800; }
            .val-ik { color: #27ae60; font-weight: 800; }
            .val-iv { color: #e67e22; font-weight: 800; }
            .val-skw { font-size: 15px; font-weight: 900; color: var(--ink); }

            .btn-gen { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: all .2s; border: none; text-decoration: none; white-space: nowrap; }
            .btn-gen.primary { background: var(--ink); color: #fff; }
            .btn-gen.success { background: #27ae60; color: #fff; }
            .btn-gen.ghost { background: #fff; color: var(--smoke); border: 1px solid var(--paper2); }

            [x-cloak] { display: none !important; }
        </style>
    @endpush

    <div class="tm-page" x-data="refereeDashboard()">
        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-gavel" style="color:#c0392b; margin-right:8px;"></i>Laporan Penilaian Wasit</h2>
                <p>Analisis Performa Wasit Berdasarkan Akurasi, Konsistensi, dan Validitas</p>
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

        {{-- STATS SUMMARY --}}
        <div class="tm-stats-grid">
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background: rgba(192,57,43,.1); color: var(--red);">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="tm-stat-info">
                    <h4>Total Wasit Dinilai</h4>
                    <p>{{ $refereeAnalysis->count() }} Orang</p>
                </div>
            </div>
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background: rgba(52,152,219,.1); color: #3498db;">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="tm-stat-info">
                    <h4>Total Sesi Penilaian</h4>
                    <p>{{ $refereeAnalysis->sum('count') }} Sesi</p>
                </div>
            </div>
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background: rgba(39,174,96,.1); color: #27ae60;">
                    <i class="fas fa-star"></i>
                </div>
                <div class="tm-stat-info">
                    <h4>Rata-rata SKW</h4>
                    <p>{{ number_format($refereeAnalysis->avg('skw'), 2) }}</p>
                </div>
            </div>
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="tm-stat-info">
                    <h4>Rekap Kategori</h4>
                    <p>{{ $refereeAnalysis->where('count', '>', 0)->count() }} Wasit Aktif</p>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
            <div class="tm-chart-card" style="margin-bottom: 0;">
                <div class="tm-chart-title">
                    <i class="fas fa-chart-line" style="color:var(--red);"></i>
                    Top 10 Performa Wasit (SKW)
                </div>
                <div wire:ignore id="refereePerformanceChart" style="min-height: 350px; width: 100%;"></div>
                <div x-show="loadingChart" x-cloak style="position:absolute; inset:0; background:rgba(255,255,255,0.7); display:flex; align-items:center; justify-content:center; z-index:10;">
                    <i class="fas fa-spinner fa-spin text-2xl text-slate-400"></i>
                </div>
            </div>
            <div class="tm-chart-card" style="margin-bottom: 0;">
                <div class="tm-chart-title">
                    <i class="fas fa-chart-pie" style="color:#27ae60;"></i>
                    Distribusi Grade Wasit
                </div>
                <div wire:ignore id="gradeDistributionChart" style="min-height: 350px; width: 100%;"></div>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label">Cari Wasit</span>
                    <input type="text" wire:model.live.debounce.500ms="search" class="tm-filter-input" placeholder="Nama wasit...">
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
                    <span class="tm-filter-label">Nomor Pertandingan</span>
                    <select wire:model.live="matchNumberFilter" class="tm-filter-sel">
                        <option value="">Semua Nomor</option>
                        @foreach($matchNumbers as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->display_name }}</option>
                        @endforeach
                    </select>
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
                    <span class="tm-filter-label">Lapangan</span>
                    <select wire:model.live="courtFilter" class="tm-filter-sel">
                        <option value="">Semua Lapangan</option>
                        @foreach($courts as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Kategori</span>
                    <select wire:model.live="draftTypeFilter" class="tm-filter-sel">
                        <option value="">Semua Kategori</option>
                        <option value="embu">EMBU</option>
                        <option value="randori">RANDORI</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="tm-tabs no-scrollbar">
            <button wire:click="$set('tab', 'skw')" class="tm-tab-btn {{ $tab === 'skw' ? 'active' : '' }}">Ranking Kompetensi</button>
            <button wire:click="$set('tab', 'iaw')" class="tm-tab-btn {{ $tab === 'iaw' ? 'active' : '' }}">Analisis Akurasi</button>
            <button wire:click="$set('tab', 'ik')" class="tm-tab-btn {{ $tab === 'ik' ? 'active' : '' }}">Analisis Konsistensi</button>
            <button wire:click="$set('tab', 'iv')" class="tm-tab-btn {{ $tab === 'iv' ? 'active' : '' }}">Analisis Validitas</button>
            <button wire:click="$set('tab', 'detail')" class="tm-tab-btn {{ $tab === 'detail' ? 'active' : '' }}">Detail Penilaian</button>
        </div>

        {{-- TABLE CONTENT --}}
        <div class="tm-table-card">
            @if($tab === 'skw')
                <table class="tm-table">
                    <thead>
                        <tr>
                            <th width="60">Rank</th>
                            <th>Nama Wasit</th>
                            <th>Lapangan Utama</th>
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
                            <th>Lapangan</th>
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        function refereeDashboard() {
            return {
                performanceChart: null,
                gradeChart: null,
                loadingChart: false,
                initChart() {
                    const performanceData = @js($chartData);
                    const gradeData = this.calculateGradeDistribution(@js($refereeAnalysis));
                    
                    this.renderPerformanceChart(performanceData);
                    this.renderGradeChart(gradeData);

                    window.addEventListener('refreshChart', (e) => {
                        this.loadingChart = true;
                        const newData = e.detail.data || e.detail;
                        
                        // We need to fetch refereeAnalysis data for grade chart too
                        // For simplicity, we'll just update performance chart from event
                        // and re-calculate grade chart if needed. 
                        // But wait, the refreshChart event might not have all analysis data.
                        
                        setTimeout(() => {
                            if (newData && newData.performance) {
                                this.updatePerformanceChart(newData.performance);
                            }
                            if (newData && newData.grades) {
                                this.updateGradeChart(newData.grades);
                            }
                            this.loadingChart = false;
                        }, 300);
                    });
                },
                calculateGradeDistribution(analysis) {
                    const grades = { 'A': 0, 'B': 0, 'C': 0, 'D': 0, 'E': 0 };
                    Object.values(analysis).forEach(rf => {
                        if (grades[rf.grade] !== undefined) grades[rf.grade]++;
                    });
                    return Object.entries(grades).map(([name, value]) => ({ name, value }));
                },
                renderPerformanceChart(data) {
                    const options = {
                        series: [
                            { name: 'SKW', type: 'column', data: data.map(d => d.skw) },
                            { name: 'IAW', type: 'line', data: data.map(d => d.iaw) }
                        ],
                        chart: { height: 350, type: 'line', toolbar: { show: false }, fontFamily: 'DM Sans, sans-serif' },
                        stroke: { width: [0, 4] },
                        colors: ['#2c3e50', '#c0392b'],
                        dataLabels: { enabled: true, enabledOnSeries: [0] },
                        labels: data.map(d => d.name),
                        xaxis: { type: 'category' },
                        yaxis: [
                            { title: { text: 'Kompetensi (SKW)' }, min: 0, max: 100 },
                            { opposite: true, title: { text: 'Akurasi (IAW %)' }, min: 80, max: 120 }
                        ],
                        legend: { position: 'top' },
                        grid: { borderColor: '#ede9e1' }
                    };
                    this.performanceChart = new ApexCharts(document.querySelector("#refereePerformanceChart"), options);
                    this.performanceChart.render();
                },
                renderGradeChart(data) {
                    const options = {
                        series: data.map(d => d.value),
                        chart: { height: 350, type: 'pie', fontFamily: 'DM Sans, sans-serif' },
                        labels: data.map(d => d.name),
                        colors: ['#27ae60', '#2ecc71', '#f1c40f', '#e67e22', '#e74c3c'],
                        legend: { position: 'bottom' },
                        responsive: [{ breakpoint: 480, options: { chart: { width: 200 }, legend: { position: 'bottom' } } }]
                    };
                    this.gradeChart = new ApexCharts(document.querySelector("#gradeDistributionChart"), options);
                    this.gradeChart.render();
                },
                updatePerformanceChart(data) {
                    if (this.performanceChart) {
                        this.performanceChart.updateOptions({
                            series: [
                                { name: 'SKW', type: 'column', data: data.map(d => d.skw) },
                                { name: 'IAW', type: 'line', data: data.map(d => d.iaw) }
                            ],
                            labels: data.map(d => d.name)
                        });
                    }
                },
                updateGradeChart(data) {
                    if (this.gradeChart) {
                        this.gradeChart.updateOptions({
                            series: data.map(d => d.value),
                            labels: data.map(d => d.name)
                        });
                    }
                }
            }
        }
    </script>
    @endpush
</div>
