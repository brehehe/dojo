<div>
    @push('styles')
        <style>
            .tm-page {
                padding: 24px;
                background: var(--paper, #F7F4EF);
                min-height: 100vh;
            }

            .form-card {
                background: white;
                border-radius: 32px;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
                padding: 28px 24px 32px;
                border: 1px solid #d9e6f2;
                margin-top: 20px;
            }

            /* ===== HEADER ===== */
            .header-kompilasi {
                margin-bottom: 24px;
            }

            .judul-utama {
                font-family: 'Cinzel', serif;
                font-size: 2rem;
                font-weight: 800;
                color: #0b2a44;
                letter-spacing: -0.02em;
                text-align: center;
                text-transform: uppercase;
                margin-bottom: 8px;
            }

            .sub-judul {
                font-size: 1.1rem;
                font-weight: 700;
                color: #1e5f9e;
                text-align: center;
                background: #e4eef8;
                display: inline-block;
                padding: 8px 32px;
                border-radius: 60px;
                margin: 0 auto 20px;
                width: fit-content;
                text-transform: uppercase;
            }

            .babak-info {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 30px;
                margin: 16px 0 20px;
                flex-wrap: wrap;
            }

            .babak-options {
                display: flex;
                gap: 24px;
                background: #f0f6fc;
                padding: 10px 24px;
                border-radius: 50px;
            }

            .babak-options .label-opt {
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 600;
                font-size: 1rem;
                color: var(--smoke);
            }

            .babak-options .label-opt.active {
                color: #1e3a5f;
                font-weight: 700;
            }

            .info-bar-top {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 16px;
                background: #f5faff;
                padding: 14px 24px;
                border-radius: 20px;
                margin: 16px 0 24px;
                border: 1px solid #cfe0ef;
                font-weight: 500;
            }

            .info-item {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 0.9rem;
                color: var(--ink);
            }

            .info-item i {
                color: #1e5f9e;
                width: 20px;
            }

            /* ===== TABEL KOMPILASI ===== */
            .table-wrapper {
                overflow-x: auto;
                margin: 24px 0 20px;
                border-radius: 20px;
                border: 1px solid #d0e0ee;
                background: white;
                -webkit-overflow-scrolling: touch;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                min-width: 1000px;
                font-size: 0.9rem;
            }

            th, td {
                padding: 12px 14px;
                border: 1px solid #d4e2f0;
                text-align: center;
                vertical-align: middle;
            }

            thead tr {
                background: #1e3a5f;
                color: white;
            }

            thead th {
                font-weight: 700;
                font-size: 0.85rem;
                letter-spacing: 0.3px;
                text-transform: uppercase;
            }

            tbody tr:hover {
                background: #f5faff;
            }

            .nama-regu-cell {
                font-weight: 600;
                text-align: left;
                padding-left: 16px;
            }

            /* Nilai yang dicoret (Min & Max) */
            .score-coret {
                text-decoration: line-through;
                color: #999;
                background: #f5f0f0;
                font-weight: 400;
            }

            /* Nilai yang dihitung (3 nilai tengah) */
            .score-dihitung {
                font-weight: 700;
                color: #1a3a5c;
                background: #d4edda;
            }

            .nilai-awal-cell {
                font-weight: 700;
                background: #e8f0fa;
                font-size: 1rem;
                color: #1e3a5f;
            }

            .nilai-akhir-cell {
                font-weight: 800;
                background: #d4e6f5;
                font-size: 1.1rem;
                color: #0b2a44;
            }

            /* Status wasit */
            .wasit-status {
                display: flex;
                align-items: center;
                gap: 20px;
                margin: 16px 0;
                padding: 12px 24px;
                background: #eaf3fb;
                border-radius: 50px;
                flex-wrap: wrap;
            }

            .wasit-indicator {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 0.9rem;
                font-weight: 600;
                color: #1e3a5f;
            }

            .indicator-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background: #27ae60;
                animation: pulse 1.5s infinite;
            }

            .indicator-dot.offline {
                background: #95a5a6;
                animation: none;
            }

            .legend-coret {
                display: flex;
                align-items: center;
                gap: 20px;
                font-size: 0.85rem;
                color: var(--smoke);
            }

            .legend-item {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .legend-coret-color {
                width: 16px;
                height: 16px;
                background: #f5f0f0;
                border: 1px solid #ccc;
            }

            .legend-hitung-color {
                width: 16px;
                height: 16px;
                background: #d4edda;
                border: 1px solid #27ae60;
            }

            .rumus-badge {
                background: #27ae60;
                color: white;
                padding: 4px 16px;
                border-radius: 30px;
                font-size: 0.85rem;
                font-weight: 600;
            }

            @keyframes pulse {
                0% { opacity: 1; }
                50% { opacity: 0.5; }
                100% { opacity: 1; }
            }

            /* ===== REKAP PERINGKAT ===== */
            .rekap-section {
                margin: 32px 0 24px;
                padding: 24px;
                background: linear-gradient(145deg, #f8fcff 0%, #eef4fa 100%);
                border-radius: 24px;
                border: 1px solid #cfe0ef;
            }

            .rekap-title {
                font-family: 'Cinzel', serif;
                font-size: 1.3rem;
                font-weight: 800;
                color: #0b2a44;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .rekap-title i {
                color: #f39c12;
            }

            .peringkat-table {
                width: 100%;
                border-collapse: collapse;
                min-width: 500px;
            }

            .peringkat-table th {
                background: #1e3a5f;
                color: white;
                padding: 12px 16px;
                font-weight: 600;
                text-align: left;
                font-size: 0.85rem;
            }

            .peringkat-table td {
                padding: 12px 16px;
                border-bottom: 1px solid #d4e2f0;
                text-align: left;
                font-size: 0.9rem;
            }

            .peringkat-table tr:last-child td {
                border-bottom: none;
            }

            /* Highlight 4 teratas */
            .rank-1 {
                background: linear-gradient(90deg, #FFD700 0%, #FFF8DC 100%);
                font-weight: 800;
                border-left: 6px solid #FFD700;
            }

            .rank-2 {
                background: linear-gradient(90deg, #C0C0C0 0%, #F5F5F5 100%);
                font-weight: 700;
                border-left: 6px solid #C0C0C0;
            }

            .rank-3 {
                background: linear-gradient(90deg, #CD7F32 0%, #FFF0E0 100%);
                font-weight: 700;
                border-left: 6px solid #CD7F32;
            }

            .rank-4 {
                background: linear-gradient(90deg, #3498db 0%, #EBF5FB 100%);
                font-weight: 600;
                border-left: 6px solid #3498db;
            }

            .rank-medal {
                font-size: 1.2rem;
                margin-right: 8px;
            }

            /* Officials */
            .officials-section {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
                margin-top: 32px;
                padding-top: 20px;
                border-top: 2px dashed #b8cee2;
            }

            .official-box {
                background: #fafdff;
                padding: 20px 24px;
                border-radius: 24px;
                border: 1px solid #d2e1f0;
            }

            .official-title {
                font-weight: 700;
                font-size: 1rem;
                margin-bottom: 16px;
                color: #1e3a5f;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .signature-line {
                border-bottom: 2px dashed #8dabc7;
                padding: 12px 8px 4px;
                margin-top: 20px;
                font-weight: 500;
                color: var(--ink);
            }

            .panitera-list {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .panitera-item {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 0.9rem;
                color: var(--ink);
            }

            .panitera-item span {
                font-weight: 600;
                min-width: 24px;
            }

            /* Action Buttons */
            .action-bar {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: flex-end;
                margin-top: 28px;
            }

            .btn-gen {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 12px 24px;
                border-radius: 50px;
                border: none;
                font-size: 0.9rem;
                font-weight: 700;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                transition: all .15s;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                text-decoration: none;
            }

            .btn-gen.primary {
                background: #1e3a5f;
                color: #fff;
            }

            .btn-gen.primary:hover {
                background: #112b44;
                transform: translateY(-1px);
            }

            .btn-gen.success {
                background: #27ae60;
                color: #fff;
            }

            .btn-gen.success:hover {
                background: #219150;
                transform: translateY(-1px);
            }

            .btn-gen.ghost {
                background: #fff;
                color: #1e3a5f;
                border: 2px solid #b7cee0;
            }

            .btn-gen.ghost:hover {
                background: #e6eef8;
            }

            .contoh-box {
                background: #f0f7ff;
                border-radius: 16px;
                padding: 12px 20px;
                margin: 16px 0 8px;
                font-size: 0.9rem;
                border-left: 4px solid #1e5f9e;
                color: var(--ink);
            }

            .contoh-box code {
                background: #1e3a5f;
                color: white;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 0.85rem;
            }
        </style>
    @endpush

    <div class="tm-page">
        {{-- HEADER TOMBOL KEMBALI --}}
        <div style="display: flex; justify-content: flex-start; margin-bottom: 10px;">
            <a href="{{ route('admin.arbitrase.new-rekapitulasi-embu-index') }}" class="btn-gen ghost" style="padding: 8px 16px; font-size: 0.8rem;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="form-card">
            <!-- HEADER -->
            <div class="header-kompilasi">
                <div class="judul-utama">DAFTAR KOMPILASI NILAI</div>
                <div style="text-align: center;">
                    <span class="sub-judul">{{ $displayName }}</span>
                </div>
                
                <div class="babak-info">
                    <div class="babak-options">
                        <span class="label-opt {{ $currentRound === 'Penyisihan' ? 'active' : '' }}">
                            <i class="fas fa-filter"></i> 1. Penyisihan / Pertama
                        </span>
                        <span class="label-opt {{ $currentRound === 'Final' ? 'active' : '' }}">
                            <i class="fas fa-trophy"></i> 2. Final / Kedua
                        </span>
                    </div>
                </div>

                @if (isset($availablePools) && $availablePools->count() > 1)
                    <div style="display:flex; justify-content:center; gap:8px; margin-bottom:20px;">
                        @foreach ($availablePools as $p)
                            <button wire:click="setPool({{ $p->id }})"
                                class="btn-gen {{ $selectedPoolId === $p->id ? 'primary' : 'ghost' }}"
                                style="padding: 8px 16px; font-size: 0.8rem;">
                                Pool {{ $p->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                @php
                    $drawing = $firstDrawing;
                    $sessionDate = $drawing?->sessionTime?->date ?? now();
                    $courtOrder = $drawing?->court?->order ?? '-';
                    $poolName = $drawing?->pool?->name ?? ($drawing?->metadata['pool'] ?? '-');
                @endphp
                <div class="info-bar-top">
                    <div class="info-item"><i class="far fa-calendar"></i> Hari: <strong>{{ \Carbon\Carbon::parse($sessionDate)->translatedFormat('l') }}</strong></div>
                    <div class="info-item"><i class="far fa-calendar-alt"></i> Tanggal: <strong>{{ \Carbon\Carbon::parse($sessionDate)->translatedFormat('d F Y') }}</strong></div>
                    <div class="info-item"><i class="fas fa-layer-group"></i> Tingkat: <strong>{{ $matchNumber->ageGroup?->name ?? '-' }}</strong></div>
                    <div class="info-item"><i class="fas fa-table"></i> Pool: <strong>{{ $poolName }}</strong></div>
                    <div class="info-item"><i class="fas fa-map-marker-alt"></i> Court: <strong>C{{ $courtOrder }}</strong></div>
                </div>
            </div>

            <!-- STATUS WASIT & METODE PENILAIAN -->
            <div class="wasit-status">
                @foreach([1,2,3,4,5] as $idx)
                    @php
                        $ref = $referees->firstWhere('judge_index', $idx);
                        $isOnline = $ref ? true : false; // Placeholder logic, adjust if you have actual online status
                    @endphp
                    <div class="wasit-indicator">
                        <span class="indicator-dot {{ $isOnline ? '' : 'offline' }}"></span> 
                        Wasit {{ $idx }} ({{ $ref?->referee?->name ?? 'Kosong' }})
                    </div>
                @endforeach
                
                <div class="legend-coret" style="margin-left: auto;">
                    <div class="legend-item"><span class="legend-coret-color"></span> Dicoret</div>
                    <div class="legend-item"><span class="legend-hitung-color"></span> Dihitung</div>
                    <span class="rumus-badge"><i class="fas fa-calculator"></i> Jumlah 3 nilai tengah</span>
                </div>
            </div>

            <!-- Contoh Perhitungan -->
            <div class="contoh-box">
                <i class="fas fa-lightbulb" style="color:#f39c12; margin-right:8px;"></i>
                <strong>Metode Penilaian:</strong> Nilai tertinggi dan terendah dicoret. 3 nilai tengah dijumlahkan untuk mendapatkan Nilai Awal. Nilai Akhir adalah Nilai Awal dikurangi Denda (jika ada).
            </div>

            <!-- TABEL KOMPILASI -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">NO.</th>
                            <th rowspan="2">NAMA REGU / KONTINGEN</th>
                            <th colspan="5">PENILAIAN WASIT</th>
                            <th rowspan="2">NILAI AWAL<br><small>(3 nilai tengah)</small></th>
                            <th rowspan="2">DENDA</th>
                            <th rowspan="2">DURASI</th>
                            <th rowspan="2">NILAI AKHIR</th>
                            @if ($currentRound === 'Final')
                                <th rowspan="2">PENYISIHAN</th>
                                <th rowspan="2">AKUMULASI</th>
                            @endif
                        </tr>
                        <tr>
                            <th>I</th><th>II</th><th>III</th><th>IV</th><th>V</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $no => $item)
                            @php
                                $s = $item['score'];
                                $rawVals = $s
                                    ? [
                                        1 => (float) ($s->judge_1 ?? 0),
                                        2 => (float) ($s->judge_2 ?? 0),
                                        3 => (float) ($s->judge_3 ?? 0),
                                        4 => (float) ($s->judge_4 ?? 0),
                                        5 => (float) ($s->judge_5 ?? 0),
                                    ]
                                    : [1=>0, 2=>0, 3=>0, 4=>0, 5=>0];

                                $minKey = null;
                                $maxKey = null;
                                if ($s) {
                                    $scoredJudges = array_filter($rawVals, fn($v) => $v > 0);
                                    if (count($scoredJudges) === 5) {
                                        $tempScored = $scoredJudges;
                                        asort($tempScored);
                                        $keys = array_keys($tempScored);
                                        $minKey = $keys[0];
                                        $maxKey = $keys[count($keys) - 1];
                                    }
                                }

                                $denda = $s?->denda ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $item['sequence_number'] ?? $no + 1 }}</td>
                                <td class="nama-regu-cell">
                                    <div style="font-weight:700; color:#1e3a5f;">
                                        @foreach ($item['athletes'] as $athlete)
                                            {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                        @endforeach
                                    </div>
                                    <div style="font-size:11px; color:var(--smoke); margin-top:2px;">
                                        {{ $item['contingent']?->name ?? '-' }}
                                    </div>
                                </td>
                                @foreach ([1, 2, 3, 4, 5] as $j)
                                    @php
                                        $val = $rawVals[$j] ?? 0;
                                        $isMin = $j === $minKey;
                                        $isMax = $j === $maxKey;
                                        $isCoret = $isMin || $isMax;
                                        $isDihitung = $s && !$isCoret && $val > 0;
                                    @endphp
                                    <td class="{{ $isCoret ? 'score-coret' : ($isDihitung ? 'score-dihitung' : '') }}">
                                        @if ($val > 0)
                                            {{ number_format($val, 1) }}
                                        @else
                                            <span style="color:var(--paper2);">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="nilai-awal-cell">{{ $item['nilai_awal'] > 0 ? number_format($item['nilai_awal'], 1) : '-' }}</td>
                                <td style="color: {{ $denda > 0 ? 'var(--red)' : 'var(--smoke)' }}; font-weight: {{ $denda > 0 ? '700' : '500' }};">
                                    {{ $denda > 0 ? number_format($denda, 1) : '-' }}
                                </td>
                                @php
                                    $waktu = $item['score']?->waktu;
                                @endphp
                                <td style="font-family:'DM Mono',monospace; font-size:0.88rem; color: {{ $waktu ? '#1e3a5f' : 'var(--smoke)' }}; font-weight: {{ $waktu ? '700' : '400' }};">
                                    {{ $waktu ?? '-' }}
                                </td>
                                <td class="nilai-akhir-cell">{{ $item['nilai_akhir'] > 0 ? number_format($item['nilai_akhir'], 1) : '-' }}</td>
                                @if ($currentRound === 'Final')
                                    <td style="font-weight: 600;">{{ isset($item['penyisihan_score']) && $item['penyisihan_score'] ? number_format($item['penyisihan_score']->nilai_akhir, 1) : '-' }}</td>
                                    <td style="font-weight:700; color:#27ae60;">
                                        {{ $item['accumulated_score'] > 0 ? number_format($item['accumulated_score'], 1) : '-' }}
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $currentRound === 'Final' ? 13 : 11 }}" style="padding:40px; color:var(--smoke); text-align:center;">Tidak ada data peserta</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ===== REKAP PERINGKAT ===== -->
            <div class="rekap-section" id="rekapPeringkat">
                <div class="rekap-title">
                    <i class="fas fa-trophy"></i> 
                    REKAP PERINGKAT - {{ strtoupper($currentRound) }}
                    <span style="font-size:0.9rem; font-weight:500; margin-left:16px; color:#5e778b;">
                        <i class="fas fa-info-circle"></i> 4 teratas mendapat highlight
                    </span>
                </div>
                <div style="overflow-x: auto;">
                    <table class="peringkat-table">
                        <thead>
                            <tr>
                                <th style="width: 120px;">PERINGKAT</th>
                                <th style="width: 80px;">NO URUT</th>
                                <th>NAMA REGU / KONTINGEN</th>
                                <th style="width: 100px;">DURASI</th>
                                <th style="width: 150px;">NILAI AKHIR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Sort by accumulated score if Final, otherwise by nilai_akhir
                                $sortedForRank = $registrations->sortByDesc(function($item) use ($currentRound) {
                                    return $currentRound === 'Final' ? $item['accumulated_score'] : $item['nilai_akhir'];
                                })->values();
                            @endphp
                            @forelse($sortedForRank as $index => $item)
                                @php
                                    $rank = $index + 1;
                                    $rankClass = '';
                                    $medalIcon = '';
                                    
                                    if ($rank === 1) {
                                        $rankClass = 'rank-1';
                                        $medalIcon = '<span class="rank-medal">🥇</span>';
                                    } else if ($rank === 2) {
                                        $rankClass = 'rank-2';
                                        $medalIcon = '<span class="rank-medal">🥈</span>';
                                    } else if ($rank === 3) {
                                        $rankClass = 'rank-3';
                                        $medalIcon = '<span class="rank-medal">🥉</span>';
                                    } else if ($rank === 4) {
                                        $rankClass = 'rank-4';
                                        $medalIcon = '<span class="rank-medal">🏅</span>';
                                    }
                                @endphp
                                <tr class="{{ $rankClass }}">
                                    <td><strong>{{ $rank }}</strong> {!! $medalIcon !!}</td>
                                    <td>{{ $item['sequence_number'] ?? $index + 1 }}</td>
                                    <td>
                                        <div style="font-weight:700;">
                                            @foreach ($item['athletes'] as $athlete)
                                                {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                            @endforeach
                                        </div>
                                        <div style="font-size:11px; color:var(--smoke); margin-top:2px;">
                                            {{ $item['contingent']?->name ?? '-' }}
                                        </div>
                                    </td>
                                    <td style="font-family:'DM Mono',monospace; font-size:0.9rem; font-weight:700; color:#1e3a5f;">
                                        {{ $item['score']?->waktu ?? '-' }}
                                    </td>
                                    <td><strong>{{ number_format(($currentRound === 'Final' ? $item['accumulated_score'] : $item['nilai_akhir']), 1) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding:20px; color:var(--smoke); text-align:center;">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- OFFICIALS -->
            <div class="officials-section">
                <div class="official-box">
                    <div class="official-title"><i class="fas fa-user-tie"></i> Koordinator Lapangan</div>
                    <div class="signature-line">{{ $koordinator }}</div>
                    @if($koordinator === 'Drs. H. Bambang Supriyanto, M.Pd.')
                        <div style="margin-top:8px; font-size:0.85rem; color: var(--smoke);">NIP. 19780512 200501 1 008</div>
                    @endif
                </div>
                <div class="official-box">
                    <div class="official-title"><i class="fas fa-users"></i> Para Panitera</div>
                    <div class="panitera-list">
                        @foreach($paniteras as $idx => $panitera)
                            <div class="panitera-item"><span>{{ $idx + 1 }}.</span> {{ $panitera }}</div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="note-footer" style="font-size: 0.8rem; color: var(--smoke); text-align: right; margin-top: 16px;">
                * Nilai tertinggi dan terendah dicoret, 3 nilai tengah dijumlahkan
            </div>

            <!-- ACTION BUTTONS -->
            <div class="action-bar">
                <button wire:click="exportToExcel" class="btn-gen success"><i class="fas fa-download"></i> Export Excel</button>
                <button onclick="window.print()" class="btn-gen ghost"><i class="fas fa-print"></i> Cetak</button>
            </div>
        </div>
    </div>
</div>
