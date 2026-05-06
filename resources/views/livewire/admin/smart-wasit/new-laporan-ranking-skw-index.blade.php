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

            /* LEGEND GRID */
            .tm-legend-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 16px;
                margin-bottom: 24px;
            }
            .tm-legend-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 16px;
            }
            .tm-legend-hdr {
                display: flex;
                align-items: center;
                gap: 10px;
                margin-bottom: 10px;
            }
            .tm-legend-icon {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
            }
            .tm-legend-title {
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--ink);
            }
            .tm-legend-desc {
                font-size: 11px;
                color: var(--smoke);
                line-height: 1.5;
            }
            .tm-legend-formula {
                font-weight: 700;
                color: var(--ink);
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
                color: #fff;
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
            .btn-gen.primary { background: var(--ink); color: #fff; }
            .btn-gen.success { background: #27ae60; color: #fff; }
            .btn-gen.ghost { background: #fff; color: var(--smoke); border: 1px solid var(--paper2); }

            .rank-bubble {
                width: 28px;
                height: 28px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 12px;
            }
        </style>
    @endpush

    <div class="tm-page">
        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-trophy" style="color:#d4a843; margin-right:8px;"></i>Ranking Kompetensi Wasit</h2>
                <p>Evaluasi Menyeluruh Performa Wasit Berdasarkan Skor Kompetensi Wasit (SKW)</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button wire:click="exportExcel" class="btn-gen success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button onclick="window.print()" class="btn-gen ghost">
                    <i class="fas fa-print"></i> Cetak Ranking
                </button>
            </div>
        </div>

        {{-- LEGEND --}}
        <div class="tm-legend-grid">
            <div class="tm-legend-card">
                <div class="tm-legend-hdr">
                    <div class="tm-legend-icon" style="background:#ebf5ff; color:#3182ce;"><i class="fas fa-bullseye"></i></div>
                    <span class="tm-legend-title">IAW (Akurasi)</span>
                </div>
                <p class="tm-legend-desc">
                    <span class="tm-legend-formula">(μ Wasit / μ Ref) × 100%</span>. Kedekatan nilai terhadap rata-rata juri (Akurasi).
                </p>
            </div>
            <div class="tm-legend-card">
                <div class="tm-legend-hdr">
                    <div class="tm-legend-icon" style="background:#f0fff4; color:#38a169;"><i class="fas fa-sync"></i></div>
                    <span class="tm-legend-title">IK (Konsistensi)</span>
                </div>
                <p class="tm-legend-desc">
                    <span class="tm-legend-formula">1 - (σ / μ)</span>. Kestabilan penilaian. Semakin tinggi semakin konsisten.
                </p>
            </div>
            <div class="tm-legend-card">
                <div class="tm-legend-hdr">
                    <div class="tm-legend-icon" style="background:#fffaf0; color:#dd6b20;"><i class="fas fa-check-double"></i></div>
                    <span class="tm-legend-title">IV (Validitas)</span>
                </div>
                <p class="tm-legend-desc">
                    <span class="tm-legend-formula">Pearson Correlation</span>. Kevalidan penilaian terhadap pola nilai standar.
                </p>
            </div>
            <div class="tm-legend-card" style="border-left: 4px solid var(--ink)">
                <div class="tm-legend-hdr">
                    <div class="tm-legend-icon" style="background:var(--ink); color:#fff;"><i class="fas fa-star"></i></div>
                    <span class="tm-legend-title">SKW (Kompetensi)</span>
                </div>
                <p class="tm-legend-desc">
                    <span class="tm-legend-formula">Weighted Score</span>. Bobot: Akurasi 40%, Konsistensi 35%, Validitas 25%.
                </p>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="tm-table-card">
            <table class="tm-table">
                <thead>
                    <tr>
                        <th width="60">Rank</th>
                        <th>Wasit</th>
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
                        @php
                            $gradeColor = match($rf['grade']) {
                                'A' => '#27ae60',
                                'B' => '#3498db',
                                'C' => '#f1c40f',
                                'D' => '#e67e22',
                                'E' => '#e74c3c',
                                default => '#95a5a6',
                            };
                            $rankBg = match($rank) {
                                1 => '#fef3c7',
                                2 => '#f1f5f9',
                                3 => '#ffedd5',
                                default => '#f8fafc',
                            };
                            $rankColor = match($rank) {
                                1 => '#d97706',
                                2 => '#475569',
                                3 => '#ea580c',
                                default => '#94a3b8',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="rank-bubble" style="background:{{ $rankBg }}; color:{{ $rankColor }}">
                                    {{ $rank++ }}
                                </div>
                            </td>
                            <td style="font-weight:700; text-transform:uppercase">{{ $rf['name'] }}</td>
                            <td><span style="font-size:10px; font-weight:700; background:var(--paper); padding:2px 6px; border-radius:4px">{{ $rf['primary_court'] }}</span></td>
                            <td align="center" style="color:var(--smoke)">{{ $rf['count'] }}</td>
                            <td align="center" style="color:#3498db; font-weight:800">{{ number_format($rf['iaw'], 2) }}%</td>
                            <td align="center" style="color:#27ae60; font-weight:800">{{ number_format($rf['ik'], 3) }}</td>
                            <td align="center" style="color:#e67e22; font-weight:800">{{ number_format($rf['iv'], 3) }}</td>
                            <td align="center" style="font-size:16px; font-weight:900; background:#fdfbf7">{{ number_format($rf['skw'], 2) }}</td>
                            <td align="center">
                                <div style="display:flex; flex-direction:column; align-items:center; gap:2px">
                                    <span class="badge-grade" style="background:{{ $gradeColor }}">{{ $rf['grade'] }}</span>
                                    <span style="font-size:8px; font-weight:700; color:var(--smoke); text-transform:uppercase">{{ $rf['grade_label'] }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
