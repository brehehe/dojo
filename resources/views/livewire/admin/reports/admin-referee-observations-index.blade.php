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

            /* BADGES */
            .badge-prem { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 10.5px; font-weight: 600; white-space: nowrap; }
            .badge-prem.green  { background: rgba(39,174,96,.12); color: #1e8449; }
            .badge-prem.gold   { background: rgba(212,168,67,.15); color: #9a6e00; }
            .badge-prem.red    { background: rgba(192,57,43,.12); color: var(--red); }
            .badge-prem .dot { width: 5px; height: 5px; border-radius: 50%; }
            .badge-prem.green  .dot { background: #27ae60; }
            .badge-prem.gold   .dot { background: #d4a843; }
            .badge-prem.red    .dot { background: var(--red); }

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
            .btn-gen.ghost { background: #fff; color: var(--smoke); border: 1px solid var(--paper2); }

            .act-btn {
                width: 32px; height: 32px; border-radius: 8px;
                border: 1px solid var(--paper2); background: #fff;
                cursor: pointer; font-size: 12px; color: #888;
                transition: all .15s; display: inline-flex; align-items: center; justify-content: center;
                text-decoration: none;
            }
            .act-btn:hover { background: var(--paper2); }
            .act-btn.view  { color: #2980b9; border-color: rgba(41,128,185,.2); background: rgba(41,128,185,.06); }
            .act-btn.view:hover { background: rgba(41,128,185,.15); border-color: #2980b9; }
            
            .act-btn.delete { color: var(--red); border-color: rgba(192,57,43,.2); background: rgba(192,57,43,.06); }
            .act-btn.delete:hover { background: rgba(192,57,43,.15); border-color: var(--red); }

            .empty-state { padding: 64px 22px; text-align: center; }
            .empty-icon { width: 56px; height: 56px; background: var(--paper); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--smoke); font-size: 20px; }
            .empty-state p { font-size: 13px; color: var(--smoke); }

            /* STATS CARDS */
            .tm-stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }
            .tm-stat-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 18px;
                display: flex;
                align-items: center;
                gap: 14px;
            }
            .tm-stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
            }
            .tm-stat-info {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }
            .tm-stat-label {
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--smoke);
            }
            .tm-stat-val {
                font-size: 22px;
                font-weight: 950;
                color: var(--ink);
                line-height: 1;
            }

            /* CHARTS */
            .tm-charts-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 20px;
                margin-bottom: 24px;
            }
            @media(min-width: 992px) {
                .tm-charts-grid {
                    grid-template-columns: 1fr 1.3fr;
                }
            }
            .tm-chart-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 20px;
                position: relative;
            }
            .tm-chart-title {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                font-weight: 700;
                color: var(--ink);
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
        </style>
    @endpush

    <div class="tm-page">
        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-eye" style="color:#c0392b; margin-right:8px;"></i>Laporan Observasi Wasit Kontingen</h2>
                <p>Kompilasi Formulir Observasi Wasit Yang Diinput Oleh Manager dan Official Kontingen</p>
            </div>
            <div>
                <button onclick="window.print()" class="btn-gen ghost">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="tm-stats-grid">
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background:#ebf5ff; color:#3182ce;"><i class="fas fa-eye"></i></div>
                <div class="tm-stat-info">
                    <span class="tm-stat-label">Total Observasi</span>
                    <span class="tm-stat-val">{{ $totalObservations }}</span>
                </div>
            </div>
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background:#fffaf0; color:#dd6b20;"><i class="fas fa-gauge-high"></i></div>
                <div class="tm-stat-info">
                    <span class="tm-stat-label">Rata-rata Nilai</span>
                    <span class="tm-stat-val">{{ number_format($averageScore, 1) }}</span>
                </div>
            </div>
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background:#f0fff4; color:#38a169;"><i class="fas fa-face-smile"></i></div>
                <div class="tm-stat-info">
                    <span class="tm-stat-label">Kategori Baik/Sangat Baik</span>
                    <span class="tm-stat-val">{{ $excellentOrGoodCount }}</span>
                </div>
            </div>
            <div class="tm-stat-card">
                <div class="tm-stat-icon" style="background:#fff5f5; color:#e53e3e;"><i class="fas fa-triangle-exclamation"></i></div>
                <div class="tm-stat-info">
                    <span class="tm-stat-label">Kategori Kurang</span>
                    <span class="tm-stat-val">{{ $poorCount }}</span>
                </div>
            </div>
        </div>

        {{-- CHARTS SECTIONS --}}
        <div class="tm-charts-grid">
            <div class="tm-chart-card">
                <div class="tm-chart-title">
                    <i class="fas fa-chart-pie" style="color:#27ae60; margin-right:8px;"></i>Distribusi Kategori Penilaian
                </div>
                <div wire:ignore style="position: relative; height: 320px; width: 100%;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="tm-chart-card">
                <div class="tm-chart-title">
                    <i class="fas fa-chart-bar" style="color:#c0392b; margin-right:8px;"></i>Rata-rata Nilai per Court
                </div>
                <div wire:ignore style="position: relative; height: 320px; width: 100%;">
                    <canvas id="courtChart"></canvas>
                </div>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label"><i class="fas fa-search" style="margin-right:4px;"></i>Cari Pengamat</span>
                    <input type="text" wire:model.live.debounce.500ms="search" class="tm-filter-input" placeholder="Nama pengamat...">
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
                    <span class="tm-filter-label">Kontingen</span>
                    <select wire:model.live="contingentFilter" class="tm-filter-sel">
                        <option value="">Semua Kontingen</option>
                        @foreach($contingents as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Court / Lapangan</span>
                    <select wire:model.live="courtFilter" class="tm-filter-sel">
                        <option value="">Semua Court</option>
                        <option value="Court 1">Court 1</option>
                        <option value="Court 2">Court 2</option>
                        <option value="Court 3">Court 3</option>
                        <option value="Court 4">Court 4</option>
                        <option value="Court 5">Court 5</option>
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Tanggal Observasi</span>
                    <input type="date" wire:model.live="dateFilter" class="tm-filter-input">
                </div>
            </div>
        </div>

        {{-- CONTENT SECTIONS --}}
        <div class="tm-table-card">
            <table class="tm-table">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Tanggal</th>
                        <th>Kontingen Pengamat</th>
                        <th>Observer / Pengamat</th>
                        <th>Wasit yang Diamati</th>
                        <th>No. Wasit</th>
                        <th>Court & Babak</th>
                        <th style="text-align:center">Nilai Kompetensi</th>
                        <th style="text-align:center">Kategori</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($observations as $obs)
                        <tr>
                            <td style="color:var(--smoke); font-weight:600">{{ $loop->iteration + $observations->firstItem() - 1 }}</td>
                            <td>{{ $obs->observation_date->format('d M Y') }}</td>
                            <td style="font-weight:700; text-transform:uppercase">{{ $obs->contingent ? $obs->contingent->name : '-' }}</td>
                            <td>{{ $obs->observer_name }}</td>
                            <td>
                                <div style="font-weight:600;">{{ $obs->referee ? $obs->referee->name : '-' }}</div>
                                <div style="font-size:11px;color:var(--smoke);">Lic. {{ $obs->referee ? $obs->referee->license_number : '-' }}</div>
                            </td>
                            <td>{{ $obs->referee_number ?: '-' }}</td>
                            <td>
                                <div style="font-weight:600;">{{ $obs->court }}</div>
                                <div style="font-size:11px;color:var(--smoke);">Babak: {{ $obs->round }}</div>
                            </td>
                            <td align="center" style="font-weight:800; font-size:15px; color:var(--red);">
                                {{ number_format($obs->total_score, 0) }} / 100
                            </td>
                            <td align="center">
                                @php
                                    $cat = strtoupper($obs->category);
                                    $badgeClass = 'red';
                                    if (str_contains($cat, 'SANGAT BAIK') || str_contains($cat, 'BAIK')) {
                                        $badgeClass = 'green';
                                    } elseif (str_contains($cat, 'CUKUP')) {
                                        $badgeClass = 'gold';
                                    }
                                @endphp
                                <span class="badge-prem {{ $badgeClass }}">
                                    <span class="dot"></span>
                                    {{ $cat }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; gap:5px; justify-content:center;">
                                    <a href="{{ route('contingent.observasi-wasit.show', $obs->id) }}" class="act-btn view" title="Lihat & Cetak">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <button type="button" wire:click="deleteObservation({{ $obs->id }})" wire:confirm="Apakah Anda yakin ingin menghapus observasi ini?" class="act-btn delete" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                    <p>Tidak ada observasi wasit kontingen yang sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if($observations->hasPages())
                <div style="padding:16px;">
                    {{ $observations->links('livewire.admin.pagination') }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let categoryChartInstance = null;
            let courtChartInstance = null;

            function initRefereeObservationCharts(categoryData, courtData) {
                const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
                const textColor = isDark ? '#94a3b8' : '#64748b';
                const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

                // 1. Category Doughnut Chart
                const ctxCategory = document.getElementById('categoryChart');
                if (ctxCategory) {
                    if (categoryChartInstance) {
                        categoryChartInstance.destroy();
                    }
                    categoryChartInstance = new Chart(ctxCategory, {
                        type: 'doughnut',
                        data: {
                            labels: Object.keys(categoryData),
                            datasets: [{
                                data: Object.values(categoryData),
                                backgroundColor: [
                                    '#1e8449', // SANGAT BAIK
                                    '#27ae60', // BAIK
                                    '#d4a843', // CUKUP
                                    '#c0392b'  // KURANG
                                ],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        font: { family: 'DM Sans', size: 11 },
                                        padding: 15,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    padding: 10,
                                    backgroundColor: '#0f0d0b',
                                    titleColor: '#fff',
                                    bodyColor: '#fff'
                                }
                            }
                        }
                    });
                }

                // 2. Court Average Score Bar Chart
                const ctxCourt = document.getElementById('courtChart');
                if (ctxCourt) {
                    if (courtChartInstance) {
                        courtChartInstance.destroy();
                    }
                    courtChartInstance = new Chart(ctxCourt, {
                        type: 'bar',
                        data: {
                            labels: Object.keys(courtData),
                            datasets: [{
                                label: 'Rata-rata Nilai',
                                data: Object.values(courtData),
                                backgroundColor: 'rgba(192, 57, 43, 0.85)',
                                borderColor: '#c0392b',
                                borderWidth: 1,
                                borderRadius: 8,
                                barThickness: 32
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    padding: 10,
                                    backgroundColor: '#0f0d0b',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    callbacks: {
                                        label: function(context) {
                                            return ` ${context.parsed.y} / 100`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { color: textColor, font: { family: 'DM Sans', size: 11 } }
                                },
                                y: {
                                    min: 0,
                                    max: 100,
                                    grid: { color: gridColor },
                                    ticks: { color: textColor, font: { family: 'DM Sans', size: 11 } }
                                }
                            }
                        }
                    });
                }
            }

            // Initial load data
            const initialCategoryData = @js($categoryChartData);
            const initialCourtData = @js($courtChartData);
            initRefereeObservationCharts(initialCategoryData, initialCourtData);

            // Handle live update events from Livewire
            window.addEventListener('refreshRefereeObservationCharts', (event) => {
                const data = event.detail[0] || event.detail;
                if (data && data.categoryData && data.courtData) {
                    initRefereeObservationCharts(data.categoryData, data.courtData);
                }
            });

            // Re-initialize on wire:navigate SPA routing
            document.addEventListener('livewire:navigated', () => {
                // If elements are present, re-render with whatever static or current data
                const navigatedCategoryData = @js($categoryChartData);
                const navigatedCourtData = @js($courtChartData);
                initRefereeObservationCharts(navigatedCategoryData, navigatedCourtData);
            });
        });
    </script>
    @endpush
</div>
