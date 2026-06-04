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

            .tm-info-card {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                padding: 20px;
                margin-bottom: 24px;
                border-left: 4px solid #3498db;
            }
            .tm-info-card h4 {
                font-size: 12px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 8px;
                color: var(--ink);
            }
            .tm-info-card p {
                font-size: 13px;
                color: var(--smoke);
                margin-bottom: 12px;
            }
            .tm-info-formula {
                font-family: 'Courier New', Courier, monospace;
                background: #fdfbf7;
                padding: 8px 12px;
                border-radius: 8px;
                font-weight: 700;
                color: var(--ink);
                font-size: 14px;
                display: inline-block;
            }

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
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-bullseye" style="color:#3498db; margin-right:8px;"></i>Ranking Akurasi (IAW)</h2>
                <p>Kedekatan Nilai Wasit terhadap Rata-rata Penilaian Juri (Index of Accuracy)</p>
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

        <div class="tm-info-card">
            <h4>Metode Perhitungan IAW</h4>
            <p>IAW mengukur seberapa dekat penilaian seorang wasit dengan nilai rata-rata (μ) dari seluruh juri pada suatu partai yang sama. Semakin mendekati 100%, maka penilaian wasit tersebut semakin akurat terhadap standar kelompoknya.</p>
            <div class="tm-info-formula">IAW = (μ Penilaian Wasit / μ Nilai Referensi) × 100%</div>
        </div>

        <div class="tm-table-card">
            <table class="tm-table">
                <thead>
                    <tr>
                        <th width="60">Rank</th>
                        <th>Wasit</th>
                        <th style="text-align:center">Jumlah Partai</th>
                        <th style="text-align:center">Avg Nilai Wasit</th>
                        <th style="text-align:center">Avg Nilai Ref</th>
                        <th style="text-align:center">IAW (%)</th>
                        <th style="text-align:center">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rank = 1; @endphp
                    @foreach($refereeAnalysis->sortByDesc('iaw') as $rf)
                        <tr>
                            <td style="font-weight:800; color:var(--smoke)">{{ $rank++ }}</td>
                            <td style="font-weight:700; text-transform:uppercase">
                                {{ $rf['name'] }}
                                @if($rf['obs_count'] > 0)
                                    <div style="font-size:10px; font-weight:normal; text-transform:none; color:#c0392b; margin-top:2px;">
                                        <i class="fas fa-eye"></i> {{ $rf['obs_count'] }} Observasi Kontingen (Avg: {{ number_format($rf['obs_avg'], 1) }})
                                    </div>
                                @endif
                            </td>
                            <td align="center" style="color:var(--smoke)">{{ $rf['count'] }}</td>
                            <td align="center" style="font-weight:700">{{ number_format($rf['avg_total'], 2) }}</td>
                            <td align="center" style="color:var(--smoke)">{{ number_format($rf['avg_ref'], 2) }}</td>
                            <td align="center" style="color:#3498db; font-weight:900; font-size:15px">{{ number_format($rf['iaw'], 2) }}%</td>
                            <td align="center">
                                <span style="font-size:10px; font-weight:800; text-transform:uppercase; color:#3498db; background:#ebf5ff; padding:4px 8px; border-radius:6px">
                                    {{ $rf['iaw_category'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
