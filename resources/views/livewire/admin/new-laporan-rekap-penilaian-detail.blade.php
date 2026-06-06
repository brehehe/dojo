<div>
<style>
/* ===========================
   BASE STYLES
=========================== */
* { box-sizing: border-box; margin: 0; padding: 0; }
body { background: #f0f2f5; font-family: 'Inter', 'Arial', sans-serif; color: #0f172a; }

.print-wrap {
    max-width: 900px;
    margin: 0 auto;
    padding: 24px;
}

/* ===========================
   PRINT CONTROLS (no-print)
=========================== */
.print-controls {
    background: #1e293b;
    border-radius: 16px;
    padding: 14px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    gap: 12px;
    flex-wrap: wrap;
}
.print-controls .ctrl-label {
    color: #94a3b8;
    font-size: 13px;
    font-weight: 600;
}
.print-controls .ctrl-label strong { color: white; }
.btn-print {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    background: linear-gradient(135deg, #b22234, #e63946);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}
.btn-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(178,34,52,0.4);
    color: white;
}
.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    background: rgba(255,255,255,0.1);
    color: white;
    border: 1.5px solid rgba(255,255,255,0.2);
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-back:hover { background: rgba(255,255,255,0.18); color: white; }

/* ===========================
   DOCUMENT / PAPER
=========================== */
.document {
    background: white;
    border-radius: 4px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.12);
    padding: 36px 40px 40px;
}

/* ===========================
   KOP SURAT / HEADER
=========================== */
.doc-header {
    text-align: center;
    border-bottom: 3px solid #0f172a;
    padding-bottom: 14px;
    margin-bottom: 20px;
}
.doc-header .org-name {
    font-size: 16px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #0f172a;
}
.doc-header .event-name {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-top: 2px;
}

.doc-title-box {
    text-align: center;
    margin-bottom: 18px;
}
.doc-title-box h1 {
    font-size: 14px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #0f172a;
    border-bottom: none;
    padding-left: 0;
    margin-bottom: 4px;
}
.doc-title-box .match-title {
    font-size: 13px;
    font-weight: 700;
    color: #b22234;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ===========================
   META TABLE
=========================== */
.meta-table {
    width: 100%;
    font-size: 12px;
    margin-bottom: 20px;
    border-collapse: collapse;
}
.meta-table td {
    padding: 3px 8px 3px 0;
    vertical-align: top;
    color: #374151;
    line-height: 1.5;
}
.meta-table td:first-child {
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    width: 180px;
}
.meta-table td:nth-child(2) { width: 10px; color: #6b7280; }

/* ===========================
   SCORING TABLE
=========================== */
.section-label {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #0f172a;
    background: #f1f5f9;
    border-left: 4px solid #b22234;
    padding: 6px 12px;
    margin-bottom: 0;
}
.score-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
    margin-bottom: 24px;
}
.score-table thead th {
    background: #0f172a;
    color: white;
    padding: 8px 10px;
    text-align: center;
    font-weight: 700;
    font-size: 10.5px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border: 1px solid #1e293b;
}
.score-table thead th:first-child,
.score-table thead th:nth-child(2),
.score-table thead th:nth-child(3) { text-align: left; }
.score-table tbody td {
    padding: 7px 10px;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
    color: #1e293b;
}
.score-table tbody td { text-align: center; }
.score-table tbody td:first-child,
.score-table tbody td:nth-child(2),
.score-table tbody td:nth-child(3) { text-align: left; }
.score-table tbody tr:nth-child(even) { background: #f8fafc; }
.score-table tbody tr:hover { background: #f0f9ff; }
.rank-gold { background: #fef3c7 !important; font-weight: 800; color: #92400e !important; }
.rank-silver { background: #f1f5f9 !important; font-weight: 800; }
.rank-bronze { background: #fef9eb !important; font-weight: 700; }

/* ===========================
   CHAMPION SECTION
=========================== */
.champion-section {
    margin-bottom: 28px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    overflow: hidden;
}
.champion-section .champ-header {
    background: #0f172a;
    color: white;
    padding: 8px 14px;
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}
.champion-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0;
}
.champion-item {
    padding: 12px 14px;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
}
.champion-item:last-child { border-right: none; }
.champion-rank {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 4px;
}
.champion-rank.gold { color: #d97706; }
.champion-rank.silver { color: #64748b; }
.champion-rank.bronze { color: #92400e; }
.champion-name {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
.champion-contingent {
    font-size: 11px;
    color: #6b7280;
    margin-top: 2px;
}
.champion-score {
    font-size: 11px;
    font-weight: 700;
    color: #b22234;
    margin-top: 3px;
}

/* ===========================
   RANDORI BRACKET TABLE
=========================== */
.randori-section-title {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    background: #1e3a5f;
    color: white;
    padding: 7px 12px;
    margin-bottom: 0;
}
.bracket-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
    margin-bottom: 20px;
}
.bracket-table thead th {
    background: #334155;
    color: white;
    padding: 7px 10px;
    text-align: center;
    font-weight: 700;
    font-size: 10.5px;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    border: 1px solid #475569;
}
.bracket-table tbody td {
    padding: 7px 10px;
    border: 1px solid #e2e8f0;
    vertical-align: middle;
    color: #1e293b;
    text-align: center;
}
.bracket-table tbody td:nth-child(2),
.bracket-table tbody td:nth-child(4) { text-align: left; }
.winner-cell { font-weight: 800; color: #065f46; background: #d1fae5 !important; }
.score-cell { font-weight: 800; font-size: 14px; }
.aka-score { color: #b22234; }
.shiro-score { color: #1e40af; }

/* ===========================
   SIGNATURE SECTION
=========================== */
.sig-section {
    margin-top: 32px;
    page-break-inside: avoid;
}
.sig-section-title {
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #0f172a;
    background: #f1f5f9;
    border-left: 4px solid #b22234;
    padding: 6px 12px;
    margin-bottom: 16px;
}
.sig-grid {
    display: grid;
    gap: 16px;
}
.sig-grid-5 { grid-template-columns: repeat(5, 1fr); }
.sig-grid-4 { grid-template-columns: repeat(4, 1fr); }
.sig-grid-3 { grid-template-columns: repeat(3, 1fr); }
.sig-grid-2 { grid-template-columns: repeat(2, 1fr); }
.sig-box {
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 10px 12px;
    text-align: center;
}
.sig-role {
    font-size: 9.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    margin-bottom: 2px;
}
.sig-name {
    font-size: 11px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 48px;
    min-height: 16px;
    word-break: break-word;
}
.sig-line {
    border-top: 1.5px solid #0f172a;
    margin-top: 0;
    padding-top: 4px;
    font-size: 9.5px;
    color: #64748b;
    min-height: 20px;
}
.sig-index {
    font-size: 9px;
    color: #94a3b8;
    margin-top: 2px;
}

/* Print Mode styles */
.tab-btn {
    background: transparent;
    border: none;
    color: #94a3b8;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}
.tab-btn:hover {
    color: white;
    background: rgba(255,255,255,0.05);
}
.tab-btn.active {
    color: white;
    background: linear-gradient(135deg, #b22234, #e63946);
    box-shadow: 0 2px 8px rgba(178,34,52,0.3);
}
.select-input {
    background: #1e293b;
    border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    padding: 6px 10px;
    color: white;
    font-size: 12px;
    font-weight: 600;
    outline: none;
    cursor: pointer;
    max-width: 320px;
    transition: border-color 0.2s;
}
.select-input:focus {
    border-color: #e63946;
}
.select-input option {
    background: #1e293b;
    color: white;
}
.page-break {
    page-break-after: always;
    break-after: page;
}
.score-detail-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    margin-bottom: 20px;
}
.score-detail-table th {
    background: #f1f5f9;
    color: #1e293b;
    font-weight: 700;
    font-size: 10px;
    text-transform: uppercase;
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
}
.score-detail-table td {
    border: 1px solid #cbd5e1;
    padding: 6px 8px;
    text-align: center;
}
.score-detail-table td:first-child {
    text-align: left;
    font-weight: 600;
    color: #334155;
}

/* ===========================
   PRINT STYLES
=========================== */
@media print {
    body { background: white !important; }
    .print-controls { display: none !important; }
    .print-wrap { padding: 0; max-width: 100%; }
    .document {
        box-shadow: none;
        border-radius: 0;
        padding: 16px 20px;
    }
    .score-table, .bracket-table { page-break-inside: auto; }
    .score-table tr, .bracket-table tr { page-break-inside: avoid; }
    .sig-section { page-break-inside: avoid; }
    .page-break { page-break-after: always; break-after: page; }
    @page { margin: 1cm; size: A4 portrait; }
}
</style>

<div class="print-wrap">

    {{-- ========================= --}}
    {{-- PRINT CONTROLS (no-print) --}}
    {{-- ========================= --}}
    @php
        $allRegs = collect();
        if ($matchNumber->draft_type === 'embu' && isset($rounds)) {
            foreach ($rounds as $rName => $rData) {
                if (isset($rData['registrations'])) {
                    foreach ($rData['registrations'] as $reg) {
                        if (isset($reg['registration_id'])) {
                            $allRegs->put($reg['registration_id'], $reg);
                        }
                    }
                }
            }
        }

        // Initialize maps for referee score details
        $scoresMap = collect();
        $randoriScoresMap = collect();
        if (isset($refereeScoreDetails)) {
            $scoresMap = $refereeScoreDetails->groupBy(function($item) {
                return $item->scorable_type . '_' . $item->scorable_id . '_' . $item->judge_index;
            });
            $randoriScoresMap = $refereeScoreDetails->where('scorable_type', \App\Models\RandoriMatchResult::class)
                ->groupBy('scorable_id');
        }
    @endphp
    <div class="print-controls no-print" style="display: flex; flex-direction: column; align-items: stretch; gap: 16px; margin-bottom: 20px;">
        <!-- Row 1: Header / Action -->
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
            <div class="ctrl-label">
                Rekap Penilaian — <strong>{{ $displayName }}</strong>
                &nbsp;•&nbsp;
                <span style="
                    display:inline-block;
                    padding:2px 10px;
                    border-radius:999px;
                    font-size:11px;
                    font-weight:700;
                    background:{{ $matchNumber->draft_type === 'embu' ? '#3b82f6' : '#ec4899' }};
                    color:white;">
                    {{ strtoupper($matchNumber->draft_type) }}
                </span>
            </div>
            <div style="display:flex; gap:10px; align-items:center;">
                <a href="{{ route('admin.laporan-rekap-penilaian') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button onclick="window.print()" class="btn-print">
                    <i class="fas fa-print"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <!-- Row 2: Mode Tabs & Dynamic Selectors -->
        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 14px; display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
            <div style="display: flex; gap: 6px; background: rgba(0,0,0,0.2); padding: 4px; border-radius: 8px;">
                @if($matchNumber->draft_type === 'embu')
                    <button wire:click="$set('printMode', 'rekap')" class="tab-btn {{ $printMode === 'rekap' ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i> Rekap Hasil
                    </button>
                    <button wire:click="$set('printMode', 'juri')" class="tab-btn {{ $printMode === 'juri' ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i> Lembar Juri
                    </button>
                    <button wire:click="$set('printMode', 'atlet')" class="tab-btn {{ $printMode === 'atlet' ? 'active' : '' }}">
                        <i class="fas fa-user-friends"></i> Lembar Per Atlet
                    </button>
                @else
                    <button wire:click="$set('printMode', 'rekap')" class="tab-btn {{ $printMode === 'rekap' ? 'active' : '' }}">
                        <i class="fas fa-sitemap"></i> Rekap Bracket
                    </button>
                    <button wire:click="$set('printMode', 'per-match')" class="tab-btn {{ $printMode === 'per-match' ? 'active' : '' }}">
                        <i class="fas fa-gavel"></i> Berita Acara Per Match
                    </button>
                @endif
            </div>

            <!-- Contextual Filter Selectors -->
            @if($printMode === 'juri')
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="color: #94a3b8; font-size: 13px; font-weight: 600;">Pilih Juri:</span>
                    <select wire:model.live="selectedJuri" class="select-input">
                        <option value="1">Juri 1</option>
                        <option value="2">Juri 2</option>
                        <option value="3">Juri 3</option>
                        <option value="4">Juri 4</option>
                        <option value="5">Juri 5</option>
                        <option value="all">Semua Juri (Halaman Terpisah)</option>
                    </select>
                </div>
            @endif

            @if($printMode === 'atlet')
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="color: #94a3b8; font-size: 13px; font-weight: 600;">Pilih Atlet:</span>
                    <select wire:model.live="selectedAthleteReg" class="select-input">
                        <option value="all">Semua Atlet (Halaman Terpisah)</option>
                        @foreach($allRegs as $rId => $regInfo)
                            <option value="{{ $rId }}">
                                {{ $regInfo['sequence'] ?? '' }}. 
                                @foreach($regInfo['athletes'] as $a)
                                    {{ $a->name }}{{ !$loop->last ? ' & ' : '' }}
                                @endforeach
                                ({{ $regInfo['contingent']?->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($printMode === 'per-match')
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="color: #94a3b8; font-size: 13px; font-weight: 600;">Pilih Pertandingan:</span>
                    <select wire:model.live="selectedMatch" class="select-input">
                        <option value="all">Semua Match (Halaman Terpisah)</option>
                        @foreach($results as $idx => $res)
                            @php
                                $mMeta = is_string($res->metadata) ? json_decode($res->metadata, true) : (array)($res->metadata ?? []);
                                $mAka = $mMeta['aka_name'] ?? $mMeta['red_name'] ?? 'Merah';
                                $mShiro = $mMeta['shiro_name'] ?? $mMeta['blue_name'] ?? 'Biru';
                                $mParts = explode('_', $res->bracket_node_index);
                                $mRound = (int)($mParts[0] ?? 0);
                                $mMatchIdx = (int)($mParts[1] ?? 0);
                                $mLabel = strtoupper($res->bracket_section) . ' - Babak ' . ($mRound + 1) . ' - Match ' . ($mMatchIdx + 1);
                            @endphp
                            <option value="{{ $res->bracket_node }}">
                                {{ $mLabel }}: {{ $mAka }} vs {{ $mShiro }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    {{-- ========================= --}}
    {{-- DOCUMENT / PAPER          --}}
    {{-- ========================= --}}
    @if($printMode === 'rekap')
    <div class="document">

        {{-- KOP SURAT --}}
        <div class="doc-header">
            <div class="org-name">Persatuan Kempo Indonesia (Perkemi)</div>
            <div class="event-name">Laporan Rekapitulasi Hasil Penilaian Pertandingan</div>
        </div>

        {{-- JUDUL --}}
        <div class="doc-title-box">
            <h1>Berita Acara Pertandingan</h1>
            <div class="match-title">{{ $displayName }}</div>
        </div>

        {{-- META INFO --}}
        <table class="meta-table">
            <tr>
                <td>Nomor Pertandingan</td>
                <td>:</td>
                <td>{{ $displayName }}</td>
                <td style="width:180px; font-weight:700; color:#0f172a;">Kelompok Usia</td>
                <td style="width:10px; color:#6b7280;">:</td>
                <td>{{ $matchNumber->ageGroup?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tipe Pertandingan</td>
                <td>:</td>
                <td>{{ strtoupper($matchNumber->draft_type) }}</td>
                <td>Kelamin</td>
                <td>:</td>
                <td>{{ $matchNumber->gender_indo }}</td>
            </tr>
            <tr>
                <td>Lapangan / Court</td>
                <td>:</td>
                <td>{{ $court }}</td>
                <td>Hari / Tanggal</td>
                <td>:</td>
                <td>{{ $day }}, {{ $date }}</td>
            </tr>
        </table>

        {{-- ========================= --}}
        {{-- EMBU SECTION              --}}
        {{-- ========================= --}}
        @if($matchNumber->draft_type === 'embu')

            @foreach($rounds as $roundName => $roundData)
                @php $regs = $roundData['registrations']; @endphp

                <div class="section-label">📋 Babak: {{ $roundName }}</div>
                @if($regs->isEmpty())
                    <div style="padding:16px 12px; font-size:12px; color:#94a3b8; text-align:center; border:1px solid #e2e8f0; margin-bottom:20px;">
                        Belum ada data penilaian untuk babak {{ $roundName }}.
                    </div>
                @else
                    <table class="score-table">
                        <thead>
                            <tr>
                                <th style="width:36px;">No.</th>
                                <th style="min-width:140px;">Nama Peserta</th>
                                <th style="min-width:120px;">Kontingen</th>
                                <th>J1</th>
                                <th>J2</th>
                                <th>J3</th>
                                <th>J4</th>
                                <th>J5</th>
                                <th>Nilai Awal</th>
                                <th>Denda</th>
                                <th>Nilai Akhir</th>
                                @if($roundName === 'Final')<th>P+F (Total)</th>@endif
                                <th>Peringkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($regs as $seq => $reg)
                                @php
                                    $rank = is_numeric($reg['rank']) ? (int)$reg['rank'] : null;
                                    $rowClass = match($rank) { 1 => 'rank-gold', 2 => 'rank-silver', 3 => 'rank-bronze', default => '' };
                                @endphp
                                <tr class="{{ $rowClass }}">
                                    <td>{{ $reg['sequence'] ?? ($seq + 1) }}</td>
                                    <td style="text-align:left;">
                                        @foreach($reg['athletes'] as $ath)
                                            <div style="font-weight:700; font-size:11.5px;">{{ $ath->name }}</div>
                                        @endforeach
                                    </td>
                                    <td style="text-align:left; font-size:11px;">{{ $reg['contingent']?->name ?? '-' }}</td>
                                    <td>{{ $reg['score']?->judge_1 > 0 ? number_format($reg['score']->judge_1, 2) : '-' }}</td>
                                    <td>{{ $reg['score']?->judge_2 > 0 ? number_format($reg['score']->judge_2, 2) : '-' }}</td>
                                    <td>{{ $reg['score']?->judge_3 > 0 ? number_format($reg['score']->judge_3, 2) : '-' }}</td>
                                    <td>{{ $reg['score']?->judge_4 > 0 ? number_format($reg['score']->judge_4, 2) : '-' }}</td>
                                    <td>{{ $reg['score']?->judge_5 > 0 ? number_format($reg['score']->judge_5, 2) : '-' }}</td>
                                    <td style="font-weight:700;">{{ $reg['nilai_awal'] > 0 ? number_format($reg['nilai_awal'], 2) : '-' }}</td>
                                    <td style="color:{{ ($reg['denda'] ?? 0) > 0 ? '#b22234' : '#94a3b8' }};">
                                        {{ ($reg['denda'] ?? 0) > 0 ? '-'.number_format($reg['denda'], 2) : '0' }}
                                    </td>
                                    <td style="font-weight:800; color:#065f46;">
                                        {{ $reg['nilai_akhir'] > 0 ? number_format($reg['nilai_akhir'], 2) : '-' }}
                                    </td>
                                    @if($roundName === 'Final')
                                        <td style="font-weight:800; color:#1e40af;">
                                            {{ $reg['accumulated'] > 0 ? number_format($reg['accumulated'], 2) : '-' }}
                                        </td>
                                    @endif
                                    <td style="font-weight:800; font-size:13px;">
                                        @if($rank === 1) 🥇 1
                                        @elseif($rank === 2) 🥈 2
                                        @elseif($rank === 3) 🥉 3
                                        @elseif($rank) {{ $rank }}
                                        @else -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach

            {{-- CHAMPIONS --}}
            @if($champions->isNotEmpty())
                <div class="champion-section">
                    <div class="champ-header">🏆 Hasil Akhir / Pemenang</div>
                    <div class="champion-list">
                        @foreach($champions->take(4) as $champ)
                            @php
                                $rankMedal = match($champ->rank) { 1 => 'gold', 2 => 'silver', 3 => 'bronze', default => '' };
                                $rankLabel = match($champ->rank) { 1 => '🥇 JUARA 1', 2 => '🥈 JUARA 2', 3 => '🥉 JUARA 3', 4 => '🥉 JUARA 3 BERSAMA', default => 'PERINGKAT '.$champ->rank };
                            @endphp
                            <div class="champion-item">
                                <div class="champion-rank {{ $rankMedal }}">{{ $rankLabel }}</div>
                                <div class="champion-name">{{ $champ->athlete_names }}</div>
                                <div class="champion-contingent">{{ $champ->contingent_name ?? $champ->registration?->contingent?->name ?? '-' }}</div>
                                @if($champ->accumulated_score > 0)
                                    <div class="champion-score">Skor: {{ number_format($champ->accumulated_score, 2) }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif

        {{-- ========================= --}}
        {{-- RANDORI SECTION           --}}
        {{-- ========================= --}}
        @if($matchNumber->draft_type === 'randori')

            @if(empty($sections) || $results->isEmpty())
                <div style="padding:24px; font-size:12px; color:#94a3b8; text-align:center; border:1px solid #e2e8f0; border-radius:8px; margin-bottom:20px;">
                    Belum ada data hasil pertandingan Randori untuk nomor ini.
                </div>
            @else
                @foreach($sections as $sectionName => $sectionResults)
                    <div class="randori-section-title">Bagian: {{ $sectionName }}</div>
                    <table class="bracket-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">No.</th>
                                <th style="min-width:130px; text-align:left;">Merah (AKA)</th>
                                <th style="width:90px;">Skor AKA</th>
                                <th style="min-width:130px; text-align:left;">Biru (SHIRO)</th>
                                <th style="width:90px;">Skor SHIRO</th>
                                <th style="min-width:120px;">Pemenang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectionResults as $idx => $result)
                                @php
                                    $meta = is_string($result->metadata) ? json_decode($result->metadata, true) : (array)($result->metadata ?? []);
                                    $akaName = $meta['aka_name'] ?? $meta['red_name'] ?? ('Merah '.($idx+1));
                                    $shiroName = $meta['shiro_name'] ?? $meta['blue_name'] ?? ('Biru '.($idx+1));
                                    $winnerName = $result->winner?->name ?? ($result->winner_color === 'aka' ? $akaName : ($result->winner_color === 'shiro' ? $shiroName : '-'));
                                @endphp
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td style="text-align:left; font-weight:{{ $result->winner_color === 'aka' ? '800' : '400' }}; color:{{ $result->winner_color === 'aka' ? '#065f46' : '#1e293b' }}">
                                        {{ $akaName }}
                                    </td>
                                    <td class="score-cell aka-score">{{ $result->score_red }}</td>
                                    <td style="text-align:left; font-weight:{{ $result->winner_color === 'shiro' ? '800' : '400' }}; color:{{ $result->winner_color === 'shiro' ? '#065f46' : '#1e293b' }}">
                                        {{ $shiroName }}
                                    </td>
                                    <td class="score-cell shiro-score">{{ $result->score_blue }}</td>
                                    <td class="winner-cell">
                                        @if($result->winner_color === 'aka') 🔴 @elseif($result->winner_color === 'shiro') 🔵 @endif
                                        {{ $winnerName }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            @endif

            {{-- CHAMPIONS RANDORI --}}
            @if($champions->isNotEmpty())
                <div class="champion-section">
                    <div class="champ-header">🏆 Hasil Akhir / Pemenang</div>
                    <div class="champion-list">
                        @foreach($champions->take(4) as $champ)
                            @php
                                $rankMedal = match($champ->rank) { 1 => 'gold', 2 => 'silver', 3 => 'bronze', default => '' };
                                $rankLabel = match($champ->rank) { 1 => '🥇 JUARA 1', 2 => '🥈 JUARA 2', 3 => '🥉 JUARA 3', 4 => '🥉 JUARA 3 BERSAMA', default => 'PERINGKAT '.$champ->rank };
                            @endphp
                            <div class="champion-item">
                                <div class="champion-rank {{ $rankMedal }}">{{ $rankLabel }}</div>
                                <div class="champion-name">{{ $champ->athlete_names }}</div>
                                <div class="champion-contingent">{{ $champ->contingent_name ?? $champ->registration?->contingent?->name ?? '-' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif

        {{-- ========================= --}}
        {{-- TANDA TANGAN SECTION      --}}
        {{-- ========================= --}}
        <div class="sig-section">
            <div class="sig-section-title">✍️ Tanda Tangan Pejabat Pertandingan</div>

            @if($matchNumber->draft_type === 'embu')
                {{-- EMBU: Koordinator + Panitera --}}
                <div style="margin-bottom:16px;">
                    <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Koordinator &amp; Panitera</div>
                    <div class="sig-grid sig-grid-3">
                        <div class="sig-box">
                            <div class="sig-role">Koordinator Lapangan</div>
                            <div class="sig-name">{{ $koordinator ?: '...................................' }}</div>
                            <div class="sig-line">{{ $koordinator ?: '' }}</div>
                        </div>
                        @foreach(array_values(array_filter(is_array($paniteras) ? $paniteras : [$paniteras])) as $pi => $pan)
                            <div class="sig-box">
                                <div class="sig-role">Panitera {{ $pi > 0 ? ($pi + 1) : '' }}</div>
                                <div class="sig-name">{{ $pan }}</div>
                                <div class="sig-line">{{ $pan }}</div>
                            </div>
                        @endforeach
                        @if(empty($paniteras))
                            <div class="sig-box">
                                <div class="sig-role">Panitera</div>
                                <div class="sig-name">...................................</div>
                                <div class="sig-line"></div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- EMBU: Juri/Wasit --}}
                @if($referees->isNotEmpty())
                    <div>
                        <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Juri Penilai</div>
                        <div class="sig-grid sig-grid-5">
                            @foreach($referees as $ref)
                                <div class="sig-box">
                                    <div class="sig-role">Juri {{ $ref->judge_index }}</div>
                                    <div class="sig-name">{{ $ref->referee?->name ?? '.....................' }}</div>
                                    <div class="sig-line">{{ $ref->referee?->name ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div>
                        <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Juri Penilai</div>
                        <div class="sig-grid sig-grid-5">
                            @for($ji = 1; $ji <= 5; $ji++)
                                <div class="sig-box">
                                    <div class="sig-role">Juri {{ $ji }}</div>
                                    <div class="sig-name">...................................</div>
                                    <div class="sig-line"></div>
                                </div>
                            @endfor
                        </div>
                    </div>
                @endif

            @else
                {{-- RANDORI: Koordinator + Wasit Utama + Arbitrase + Panitera --}}
                <div class="sig-grid sig-grid-4">
                    <div class="sig-box">
                        <div class="sig-role">Koordinator Lapangan</div>
                        <div class="sig-name">{{ $koordinator ?: '...................................' }}</div>
                        <div class="sig-line">{{ $koordinator ?: '' }}</div>
                    </div>
                    <div class="sig-box">
                        <div class="sig-role">Wasit Utama</div>
                        @php $wasitUtama = $referees->where('judge_index', 0)->first() ?? $referees->first(); @endphp
                        <div class="sig-name">{{ $wasitUtama?->referee?->name ?? '...................................' }}</div>
                        <div class="sig-line">{{ $wasitUtama?->referee?->name ?? '' }}</div>
                    </div>
                    <div class="sig-box">
                        <div class="sig-role">Arbitrase</div>
                        <div class="sig-name">{{ $arbitrase ?: '...................................' }}</div>
                        <div class="sig-line">{{ $arbitrase ?: '' }}</div>
                    </div>
                    <div class="sig-box">
                        <div class="sig-role">Panitera</div>
                        @php $pan1 = is_array($paniteras) ? ($paniteras[0] ?? '') : ($paniteras ?? ''); @endphp
                        <div class="sig-name">{{ $pan1 ?: '...................................' }}</div>
                        <div class="sig-line">{{ $pan1 ?: '' }}</div>
                    </div>
                </div>

                {{-- Additional referee TTDs for randori --}}
                @if($referees->count() > 1)
                    <div style="margin-top:14px;">
                        <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Wasit / Juri Pinggir</div>
                        <div class="sig-grid sig-grid-4">
                            @foreach($referees->skip(1)->take(4) as $ref)
                                <div class="sig-box">
                                    <div class="sig-role">Wasit/Juri {{ $ref->judge_index }}</div>
                                    <div class="sig-name">{{ $ref->referee?->name ?? '.....................' }}</div>
                                    <div class="sig-line">{{ $ref->referee?->name ?? '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            {{-- FOOTER NOTE --}}
            <div style="margin-top:20px; text-align:right; font-size:10px; color:#94a3b8;">
                Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB &nbsp;|&nbsp; Smart Perkemi System
            </div>
        </div>

    </div>
    @elseif($printMode === 'juri')
        @php
            $jurisToPrint = $selectedJuri === 'all' ? [1, 2, 3, 4, 5] : [(int)$selectedJuri];
        @endphp

        @foreach($jurisToPrint as $juriNum)
            <div class="document {{ !$loop->last ? 'page-break' : '' }}" style="margin-bottom: 24px;">
                {{-- KOP SURAT --}}
                <div class="doc-header">
                    <div class="org-name">Persatuan Kempo Indonesia (Perkemi)</div>
                    <div class="event-name">Lembar Penilaian Juri / Wasit</div>
                </div>

                {{-- JUDUL --}}
                <div class="doc-title-box">
                    <h1>Lembar Penilaian Juri {{ $juriNum }}</h1>
                    <div class="match-title">{{ $displayName }}</div>
                </div>

                {{-- META INFO --}}
                <table class="meta-table">
                    <tr>
                        <td>Nomor Pertandingan</td>
                        <td>:</td>
                        <td>{{ $displayName }}</td>
                        <td style="width:180px; font-weight:700; color:#0f172a;">Kelompok Usia</td>
                        <td style="width:10px; color:#6b7280;">:</td>
                        <td>{{ $matchNumber->ageGroup?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tipe Pertandingan</td>
                        <td>:</td>
                        <td>{{ strtoupper($matchNumber->draft_type) }}</td>
                        <td>Kelamin</td>
                        <td>:</td>
                        <td>{{ $matchNumber->gender_indo }}</td>
                    </tr>
                    <tr>
                        <td>Lapangan / Court</td>
                        <td>:</td>
                        <td>{{ $court }}</td>
                        <td>Hari / Tanggal</td>
                        <td>:</td>
                        <td>{{ $day }}, {{ $date }}</td>
                    </tr>
                </table>

                @foreach($rounds as $roundName => $roundData)
                    @php $regs = $roundData['registrations']; @endphp

                    <div class="section-label">📋 Babak: {{ $roundName }}</div>
                    @if($regs->isEmpty())
                        <div style="padding:16px 12px; font-size:12px; color:#94a3b8; text-align:center; border:1px solid #e2e8f0; margin-bottom:20px;">
                            Belum ada data penilaian untuk babak {{ $roundName }}.
                        </div>
                    @else
                        <table class="score-table">
                            <thead>
                                <tr>
                                    <th style="width:36px;">No.</th>
                                    <th style="min-width:140px;">Nama Peserta</th>
                                    <th style="min-width:120px;">Kontingen</th>
                                    <th style="font-size: 9px; padding: 4px;">G1</th>
                                    <th style="font-size: 9px; padding: 4px;">G2</th>
                                    <th style="font-size: 9px; padding: 4px;">G3</th>
                                    <th style="font-size: 9px; padding: 4px;">J1</th>
                                    <th style="font-size: 9px; padding: 4px;">J2</th>
                                    <th style="font-size: 9px; padding: 4px;">J3</th>
                                    <th style="font-size: 9px; padding: 4px;">E1</th>
                                    <th style="font-size: 9px; padding: 4px;">E2</th>
                                    <th style="font-size: 9px; padding: 4px;">E3</th>
                                    <th style="font-size: 9px; padding: 4px;">E4</th>
                                    <th style="width:100px;">Nilai Juri {{ $juriNum }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($regs as $seq => $reg)
                                    @php
                                        $drawingId = $reg['score']?->drawing_id ?? null;
                                        $regId = $reg['registration_id'];
                                        $dKey = $drawingId 
                                            ? 'App\\Models\\DrawingMatchNumber_' . $drawingId . '_' . $juriNum
                                            : 'App\\Models\\Registration_' . $regId . '_' . $juriNum;
                                        $sd = $scoresMap->get($dKey)?->first();
                                        $details = $sd ? $sd->details : null;
                                        $juriVal = $reg['score']?->{'judge_' . $juriNum} ?? ($sd ? $sd->total_calculated_score : null);
                                    @endphp
                                    <tr>
                                        <td>{{ $reg['sequence'] ?? ($seq + 1) }}</td>
                                        <td style="text-align:left;">
                                            @foreach($reg['athletes'] as $ath)
                                                <div style="font-weight:700; font-size:11.5px;">{{ $ath->name }}</div>
                                            @endforeach
                                        </td>
                                        <td style="text-align:left; font-size:11px;">{{ $reg['contingent']?->name ?? '-' }}</td>
                                        <td>{{ $details['goho_1'] ?? '-' }}</td>
                                        <td>{{ $details['goho_2'] ?? '-' }}</td>
                                        <td>{{ $details['goho_3'] ?? '-' }}</td>
                                        <td>{{ $details['juho_1'] ?? '-' }}</td>
                                        <td>{{ $details['juho_2'] ?? '-' }}</td>
                                        <td>{{ $details['juho_3'] ?? '-' }}</td>
                                        <td>{{ $details['ekspresi_1'] ?? '-' }}</td>
                                        <td>{{ $details['ekspresi_2'] ?? '-' }}</td>
                                        <td>{{ $details['ekspresi_3'] ?? '-' }}</td>
                                        <td>{{ $details['ekspresi_4'] ?? '-' }}</td>
                                        <td style="font-weight:800; font-size:13px; color:#1e40af;">
                                            {{ $juriVal > 0 ? number_format($juriVal, 2) : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                @endforeach

                {{-- TANDA TANGAN --}}
                <div class="sig-section">
                    <div class="sig-section-title">✍️ Tanda Tangan Pejabat Pertandingan</div>
                    @php
                        $juriSig = null;
                        foreach($regs as $r) {
                            $dId = $r['score']?->drawing_id ?? null;
                            $rId = $r['registration_id'];
                            $dKey = $dId 
                                ? 'App\\Models\\DrawingMatchNumber_' . $dId . '_' . $juriNum
                                : 'App\\Models\\Registration_' . $rId . '_' . $juriNum;
                            $sd = $scoresMap->get($dKey)?->first();
                            if ($sd && !empty($sd->signature)) {
                                $juriSig = $sd->signature;
                                break;
                            }
                        }
                    @endphp
                    <div class="sig-grid sig-grid-3">
                        <div class="sig-box">
                            <div class="sig-role">Koordinator Lapangan</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;"></div>
                            <div class="sig-line">{{ $koordinator ?: '...................................' }}</div>
                        </div>
                        <div class="sig-box">
                            <div class="sig-role">Panitera</div>
                            @php $pan1 = is_array($paniteras) ? ($paniteras[0] ?? '') : ($paniteras ?? ''); @endphp
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;"></div>
                            <div class="sig-line">{{ $pan1 ?: '...................................' }}</div>
                        </div>
                        <div class="sig-box">
                            <div class="sig-role">Juri {{ $juriNum }}</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @if($juriSig)
                                    <img src="{{ $juriSig }}" style="max-height: 55px; max-width: 120px; display: block;" />
                                @else
                                    <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                @endif
                            </div>
                            @php
                                $assignedJuri = $referees->where('judge_index', $juriNum)->first();
                                $juriName = $assignedJuri?->referee?->name ?? '...................................';
                            @endphp
                            <div class="sig-line">{{ $juriName }}</div>
                        </div>
                    </div>
                    <div style="margin-top:20px; text-align:right; font-size:10px; color:#94a3b8;">
                        Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB &nbsp;|&nbsp; Smart Perkemi System
                    </div>
                </div>
            </div>
        @endforeach
    @elseif($printMode === 'atlet')
        @php
            $regsToPrint = $selectedAthleteReg === 'all' ? $allRegs : $allRegs->filter(fn($r) => (string)$r['registration_id'] === (string)$selectedAthleteReg);
        @endphp

        @foreach($regsToPrint as $rId => $reg)
            <div class="document {{ !$loop->last ? 'page-break' : '' }}" style="margin-bottom: 24px;">
                {{-- KOP SURAT --}}
                <div class="doc-header">
                    <div class="org-name">Persatuan Kempo Indonesia (Perkemi)</div>
                    <div class="event-name">Lembar Penilaian Atlet / Pasangan</div>
                </div>

                {{-- JUDUL --}}
                <div class="doc-title-box">
                    <h1>Lembar Hasil Penilaian Atlet</h1>
                    <div class="match-title">{{ $displayName }}</div>
                </div>

                {{-- META INFO --}}
                <table class="meta-table">
                    <tr>
                        <td>Nomor Pertandingan</td>
                        <td>:</td>
                        <td>{{ $displayName }}</td>
                        <td style="width:180px; font-weight:700; color:#0f172a;">Kelompok Usia</td>
                        <td style="width:10px; color:#6b7280;">:</td>
                        <td>{{ $matchNumber->ageGroup?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kontingen</td>
                        <td>:</td>
                        <td>{{ $reg['contingent']?->name ?? '-' }}</td>
                        <td>Gender</td>
                        <td>:</td>
                        <td>{{ $matchNumber->gender_indo }}</td>
                    </tr>
                    <tr>
                        <td>Nama Peserta</td>
                        <td>:</td>
                        <td>
                            @foreach($reg['athletes'] as $ath)
                                <strong>{{ $ath->name }}</strong>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>Nomor Undian</td>
                        <td>:</td>
                        <td>{{ $reg['sequence'] }}</td>
                    </tr>
                </table>

                <div class="section-label">📋 Rincian Nilai</div>
                <table class="score-table">
                    <thead>
                        <tr>
                            <th>Juri 1</th>
                            <th>Juri 2</th>
                            <th>Juri 3</th>
                            <th>Juri 4</th>
                            <th>Juri 5</th>
                            <th>Nilai Awal</th>
                            <th>Denda</th>
                            <th>Nilai Akhir</th>
                            @if(isset($reg['penyisihan_score']))
                                <th>Nilai Penyisihan</th>
                                <th>Total Akumulasi</th>
                            @endif
                            <th>Peringkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $reg['score']?->judge_1 > 0 ? number_format($reg['score']->judge_1, 2) : '-' }}</td>
                            <td>{{ $reg['score']?->judge_2 > 0 ? number_format($reg['score']->judge_2, 2) : '-' }}</td>
                            <td>{{ $reg['score']?->judge_3 > 0 ? number_format($reg['score']->judge_3, 2) : '-' }}</td>
                            <td>{{ $reg['score']?->judge_4 > 0 ? number_format($reg['score']->judge_4, 2) : '-' }}</td>
                            <td>{{ $reg['score']?->judge_5 > 0 ? number_format($reg['score']->judge_5, 2) : '-' }}</td>
                            <td style="font-weight:700;">{{ $reg['nilai_awal'] > 0 ? number_format($reg['nilai_awal'], 2) : '-' }}</td>
                            <td style="color:{{ ($reg['denda'] ?? 0) > 0 ? '#b22234' : '#94a3b8' }};">
                                {{ ($reg['denda'] ?? 0) > 0 ? '-'.number_format($reg['denda'], 2) : '0' }}
                            </td>
                            <td style="font-weight:800; color:#065f46; font-size:13px;">
                                {{ $reg['nilai_akhir'] > 0 ? number_format($reg['nilai_akhir'], 2) : '-' }}
                            </td>
                            @if(isset($reg['penyisihan_score']))
                                <td>{{ $reg['penyisihan_score']?->nilai_akhir > 0 ? number_format($reg['penyisihan_score']->nilai_akhir, 2) : '-' }}</td>
                                <td style="font-weight:800; color:#1e40af;">{{ $reg['accumulated'] > 0 ? number_format($reg['accumulated'], 2) : '-' }}</td>
                            @endif
                            <td style="font-weight:800; font-size:13px;">
                                {{ $reg['rank'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                {{-- TANDA TANGAN --}}
                <div class="sig-section">
                    <div class="sig-section-title">✍️ Tanda Tangan Pejabat Pertandingan</div>
                    <div style="margin-bottom:16px;">
                        <div class="sig-grid sig-grid-3">
                            <div class="sig-box">
                                <div class="sig-role">Koordinator Lapangan</div>
                                <div style="height: 60px; display: flex; align-items: center; justify-content: center;"></div>
                                <div class="sig-line">{{ $koordinator ?: '...................................' }}</div>
                            </div>
                            <div class="sig-box">
                                <div class="sig-role">Panitera</div>
                                @php $pan1 = is_array($paniteras) ? ($paniteras[0] ?? '') : ($paniteras ?? ''); @endphp
                                <div style="height: 60px; display: flex; align-items: center; justify-content: center;"></div>
                                <div class="sig-line">{{ $pan1 ?: '...................................' }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Juri Penilai</div>
                        <div class="sig-grid sig-grid-5">
                            @for($ji = 1; $ji <= 5; $ji++)
                                @php
                                    $assignedJuri = $referees->where('judge_index', $ji)->first();
                                    
                                    $drawingId = $reg['score']?->drawing_id ?? null;
                                    $regId = $reg['registration_id'];
                                    $dKey = $drawingId 
                                        ? 'App\\Models\\DrawingMatchNumber_' . $drawingId . '_' . $ji
                                        : 'App\\Models\\Registration_' . $regId . '_' . $ji;
                                    $sd = $scoresMap->get($dKey)?->first();
                                    $juriSig = $sd?->signature ?? null;
                                    
                                    $juriName = $sd?->referee?->name ?? $sd?->referee?->user?->name ?? $assignedJuri?->referee?->name ?? $assignedJuri?->referee?->user?->name ?? '...................................';
                                @endphp
                                <div class="sig-box">
                                    <div class="sig-role">Juri {{ $ji }}</div>
                                    <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                        @if($juriSig)
                                            <img src="{{ $juriSig }}" style="max-height: 55px; max-width: 120px; display: block;" />
                                        @else
                                            <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                        @endif
                                    </div>
                                    <div class="sig-line">{{ $juriName }}</div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div style="margin-top:20px; text-align:right; font-size:10px; color:#94a3b8;">
                        Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB &nbsp;|&nbsp; Smart Perkemi System
                    </div>
                </div>
            </div>
        @endforeach
    @elseif($printMode === 'per-match')
        @php
            $matchesToPrint = $selectedMatch === 'all' 
                ? $results 
                : $results->filter(fn($res) => (string)$res->bracket_node === (string)$selectedMatch);
        @endphp

        @foreach($matchesToPrint as $idx => $res)
            @php
                $meta = is_string($res->metadata) ? json_decode($res->metadata, true) : (array)($res->metadata ?? []);
                
                // Retrieve match node details from drawing_data
                $parts = explode('_', $res->bracket_node_index);
                $roundIdx = (int)($parts[0] ?? 0);
                $matchIdx = (int)($parts[1] ?? 0);
                
                $matchData = null;
                if ($res->bracket_section === 'ub') {
                    $matchData = $drawingData['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
                } elseif ($res->bracket_section === 'lb') {
                    $matchData = $drawingData['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
                } elseif ($res->bracket_section === 'gf') {
                    $matchData = $drawingData['grand_final'] ?? null;
                }
                
                $athlete1 = $matchData['athlete1'] ?? null;
                $athlete2 = $matchData['athlete2'] ?? null;
                
                $akaName = $athlete1['name'] ?? $meta['aka_name'] ?? $meta['red_name'] ?? 'Merah';
                $akaContingent = $athlete1['contingent'] ?? $meta['red_contingent'] ?? $meta['aka_contingent'] ?? '';
                $shiroName = $athlete2['name'] ?? $meta['shiro_name'] ?? $meta['blue_name'] ?? 'Biru';
                $shiroContingent = $athlete2['contingent'] ?? $meta['blue_contingent'] ?? $meta['shiro_contingent'] ?? '';
                
                $label = strtoupper($res->bracket_section) . ' - Babak ' . ($roundIdx + 1) . ' - Match ' . ($matchIdx + 1);
            @endphp
            <div class="document {{ !$loop->last ? 'page-break' : '' }}" style="margin-bottom: 24px;">
                {{-- KOP SURAT --}}
                <div class="doc-header">
                    <div class="org-name">Persatuan Kempo Indonesia (Perkemi)</div>
                    <div class="event-name">Berita Acara Pertandingan Randori</div>
                </div>

                {{-- JUDUL --}}
                <div class="doc-title-box">
                    <h1>Berita Acara Pertandingan</h1>
                    <div class="match-title">{{ $displayName }}</div>
                    <div style="font-size:12px; font-weight:700; color:#475569; text-transform:uppercase; margin-top:4px;">
                        {{ $label }}
                    </div>
                </div>

                {{-- META INFO --}}
                <table class="meta-table">
                    <tr>
                        <td>Nomor Pertandingan</td>
                        <td>:</td>
                        <td>{{ $displayName }}</td>
                        <td style="width:180px; font-weight:700; color:#0f172a;">Kelompok Usia</td>
                        <td style="width:10px; color:#6b7280;">:</td>
                        <td>{{ $matchNumber->ageGroup?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Babak / Pertandingan</td>
                        <td>:</td>
                        <td>{{ $label }}</td>
                        <td>Kelamin</td>
                        <td>:</td>
                        <td>{{ $matchNumber->gender_indo }}</td>
                    </tr>
                    <tr>
                        <td>Lapangan / Court</td>
                        <td>:</td>
                        <td>{{ $court }}</td>
                        <td>Hari / Tanggal</td>
                        <td>:</td>
                        <td>{{ $day }}, {{ $date }}</td>
                    </tr>
                </table>

                {{-- COMPETITORS BOX --}}
                <div style="display:flex; justify-content:space-between; align-items:stretch; gap:16px; margin-bottom:24px;">
                    <!-- AKA -->
                    <div style="flex:1; border:2px solid #b22234; border-radius:8px; padding:12px; background:rgba(178,34,52,0.03); display:flex; flex-direction:column; justify-content:space-between;">
                        <div>
                            <span style="display:inline-block; padding:2px 8px; background:#b22234; color:white; font-size:10px; font-weight:800; border-radius:4px; text-transform:uppercase; margin-bottom:6px;">AKA (MERAH)</span>
                            <div style="font-size:14px; font-weight:800; color:#0f172a;">{{ $akaName }}</div>
                            <div style="font-size:11px; color:#64748b; margin-top:2px;">{{ $akaContingent }}</div>
                        </div>
                        <div style="font-size:32px; font-weight:900; color:#b22234; text-align:right; margin-top:10px; line-height:1;">
                            {{ $res->score_red }}
                        </div>
                    </div>

                    <!-- VS -->
                    <div style="display:flex; align-items:center; justify-content:center; font-weight:900; font-size:18px; color:#64748b; padding:0 10px;">
                        VS
                    </div>

                    <!-- SHIRO -->
                    <div style="flex:1; border:2px solid #1e40af; border-radius:8px; padding:12px; background:rgba(30,64,175,0.03); display:flex; flex-direction:column; justify-content:space-between;">
                        <div>
                            <span style="display:inline-block; padding:2px 8px; background:#1e40af; color:white; font-size:10px; font-weight:800; border-radius:4px; text-transform:uppercase; margin-bottom:6px;">SHIRO (BIRU)</span>
                            <div style="font-size:14px; font-weight:800; color:#0f172a;">{{ $shiroName }}</div>
                            <div style="font-size:11px; color:#64748b; margin-top:2px;">{{ $shiroContingent }}</div>
                        </div>
                        <div style="font-size:32px; font-weight:900; color:#1e40af; text-align:right; margin-top:10px; line-height:1;">
                            {{ $res->score_blue }}
                        </div>
                    </div>
                </div>

                {{-- WINNER SECTION --}}
                @php
                    $winnerAthleteName = $res->winner?->name ?? ($res->winner_color === 'aka' ? $akaName : ($res->winner_color === 'shiro' ? $shiroName : '-'));
                    $winnerContingent = $res->winner_color === 'aka' ? $akaContingent : ($res->winner_color === 'shiro' ? $shiroContingent : '');
                @endphp
                <div style="background:#d1fae5; border:1px solid #065f46; border-radius:8px; padding:12px; margin-bottom:24px; text-align:center;">
                    <div style="font-size:10px; font-weight:800; color:#065f46; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Pemenang Pertandingan</div>
                    <div style="font-size:15px; font-weight:900; color:#065f46;">
                        @if($res->winner_color === 'aka') 🔴 @elseif($res->winner_color === 'shiro') 🔵 @endif
                        {{ $winnerAthleteName }}
                        @if($winnerContingent) ({{ $winnerContingent }}) @endif
                    </div>
                </div>

                {{-- SCORING DETAILS TABLE --}}
                <div class="section-label">📋 Rincian Perolehan Poin & Hukuman</div>
                <table class="score-detail-table">
                    <thead>
                        <tr>
                            <th>Indikator Penilaian / Pelanggaran</th>
                            <th style="background:#b22234; color:white; width:180px;">AKA (Merah)</th>
                            <th style="background:#1e40af; color:white; width:180px;">SHIRO (Biru)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $scoringAka = $meta['scoringAka'] ?? [];
                            $scoringShiro = $meta['scoringShiro'] ?? [];
                        @endphp
                        <tr>
                            <td>Ippon (10 Poin)</td>
                            <td style="font-weight:700;">{{ $scoringAka['ippon'] ?? 0 }}</td>
                            <td style="font-weight:700;">{{ $scoringShiro['ippon'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Waza-ari (5 Poin)</td>
                            <td style="font-weight:700;">{{ $scoringAka['waza_ari'] ?? 0 }}</td>
                            <td style="font-weight:700;">{{ $scoringShiro['waza_ari'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Hukuman Batsu 5 (-5 Poin)</td>
                            <td style="color:#b22234; font-weight:700;">{{ $scoringAka['hasil_batsu_5'] ?? 0 }}</td>
                            <td style="color:#b22234; font-weight:700;">{{ $scoringShiro['hasil_batsu_5'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Hukuman Batsu 10 (-10 Poin)</td>
                            <td style="color:#b22234; font-weight:700;">{{ $scoringAka['hasil_batsu_10'] ?? 0 }}</td>
                            <td style="color:#b22234; font-weight:700;">{{ $scoringShiro['hasil_batsu_10'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td>Mujoken Kachi (Menang Mutlak)</td>
                            <td style="font-weight:700;">{{ ($scoringAka['mujoken_kachi'] ?? 0) ? 'Ya' : '-' }}</td>
                            <td style="font-weight:700;">{{ ($scoringShiro['mujoken_kachi'] ?? 0) ? 'Ya' : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Yusei Kachi (Menang Keunggulan)</td>
                            <td style="font-weight:700;">{{ ($scoringAka['yusei_kachi'] ?? 0) ? 'Ya' : '-' }}</td>
                            <td style="font-weight:700;">{{ ($scoringShiro['yusei_kachi'] ?? 0) ? 'Ya' : '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- PENILAIAN JURI LAPANGAN (CORNER JUDGES) --}}
                <div class="section-label">⚖️ Penilaian Juri Lapangan (Corner Judges)</div>
                <table class="score-detail-table" style="margin-bottom: 24px;">
                    <thead>
                        <tr>
                            <th style="width: 140px;">Juri</th>
                            <th>Nama Juri</th>
                            <th style="background: #b22234; color: white; width: 200px;">AKA (Merah)</th>
                            <th style="background: #1e40af; color: white; width: 200px;">SHIRO (Biru)</th>
                            <th style="width: 150px;">Tanda Tangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $refereeScores = $randoriScoresMap->get($res->id) ?? collect();
                        @endphp
                        @for($ji = 1; $ji <= 4; $ji++)
                            @php
                                $juriScore = $refereeScores->where('judge_index', $ji)->first();
                                $assignedJuri = $referees->where('judge_index', $ji)->first();
                                $juriName = $juriScore?->referee?->name ?? $juriScore?->referee?->user?->name ?? $assignedJuri?->referee?->name ?? $assignedJuri?->referee?->user?->name ?? '-';
                                $juriSig = $juriScore?->signature ?? null;
                                $details = $juriScore?->details ?? null;
                                
                                $akaDetails = [];
                                $shiroDetails = [];
                                if ($details) {
                                    if (isset($details['aka'])) {
                                        if (($details['aka']['ippon'] ?? 0) > 0) $akaDetails[] = 'Ippon: ' . $details['aka']['ippon'];
                                        if (($details['aka']['wazaari'] ?? 0) > 0) $akaDetails[] = 'Waza-ari: ' . $details['aka']['wazaari'];
                                        if (($details['aka']['batsu5'] ?? 0) > 0) $akaDetails[] = 'Batsu 5: ' . $details['aka']['batsu5'];
                                        if (($details['aka']['batsu10'] ?? 0) > 0) $akaDetails[] = 'Batsu 10: ' . $details['aka']['batsu10'];
                                        if (($details['aka']['mujoken'] ?? 0) > 0) $akaDetails[] = 'Mujoken: ' . $details['aka']['mujoken'];
                                        if (($details['aka']['yusei'] ?? 0) > 0) $akaDetails[] = 'Yusei: ' . $details['aka']['yusei'];
                                    }
                                    if (isset($details['shiro'])) {
                                        if (($details['shiro']['ippon'] ?? 0) > 0) $shiroDetails[] = 'Ippon: ' . $details['shiro']['ippon'];
                                        if (($details['shiro']['wazaari'] ?? 0) > 0) $shiroDetails[] = 'Waza-ari: ' . $details['shiro']['wazaari'];
                                        if (($details['shiro']['batsu5'] ?? 0) > 0) $shiroDetails[] = 'Batsu 5: ' . $details['shiro']['batsu5'];
                                        if (($details['shiro']['batsu10'] ?? 0) > 0) $shiroDetails[] = 'Batsu 10: ' . $details['shiro']['batsu10'];
                                        if (($details['shiro']['mujoken'] ?? 0) > 0) $shiroDetails[] = 'Mujoken: ' . $details['shiro']['mujoken'];
                                        if (($details['shiro']['yusei'] ?? 0) > 0) $shiroDetails[] = 'Yusei: ' . $details['shiro']['yusei'];
                                    }
                                }
                                $akaStr = count($akaDetails) > 0 ? implode(', ', $akaDetails) : '-';
                                $shiroStr = count($shiroDetails) > 0 ? implode(', ', $shiroDetails) : '-';
                            @endphp
                            <tr>
                                <td style="font-weight: 700;">Juri Lapangan {{ $ji }}</td>
                                <td style="text-align: left;">{{ $juriName }}</td>
                                <td style="color: #b22234; font-weight: 600;">{{ $akaStr }}</td>
                                <td style="color: #1e40af; font-weight: 600;">{{ $shiroStr }}</td>
                                <td style="text-align: center;">
                                    @if($juriSig)
                                        <img src="{{ $juriSig }}" style="max-height: 35px; max-width: 90px; display: block; margin: 0 auto;" />
                                    @else
                                        <span style="color: #cbd5e1; font-size: 10px;">(Belum TTD)</span>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                {{-- SIGNATURES WITH IMAGES --}}
                @php
                    $sigs = $meta['signatures'] ?? [];
                    $arbSig = $sigs['arbitrase'] ?? [];
                    $koorSig = $sigs['koordinator'] ?? [];
                    $wasSig = $sigs['wasit'] ?? [];
                    $panSigArray = $sigs['panitera'] ?? [];
                    $mgrRedSig = $sigs['manager_red'] ?? [];
                    $mgrWhiteSig = $sigs['manager_white'] ?? [];
                @endphp
                <div class="sig-section">
                    <div class="sig-section-title">✍️ Tanda Tangan Digital Pejabat &amp; Offisial</div>
                    
                    <!-- Row 1: Pejabat Teras -->
                    <div class="sig-grid sig-grid-4" style="margin-bottom: 20px;">
                        <!-- Arbitrase -->
                        <div class="sig-box">
                            <div class="sig-role">Arbitrase</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @if(!empty($arbSig['signature']))
                                    <img src="{{ $arbSig['signature'] }}" style="max-height: 55px; max-width: 120px; display: block;" />
                                @else
                                    <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                @endif
                            </div>
                            <div class="sig-line">{{ ($arbSig['name'] ?? null) ?: '...................................' }}</div>
                        </div>

                        <!-- Koordinator Lapangan -->
                        <div class="sig-box">
                            <div class="sig-role">Koordinator Lapangan</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @if(!empty($koorSig['signature']))
                                    <img src="{{ $koorSig['signature'] }}" style="max-height: 55px; max-width: 120px; display: block;" />
                                @else
                                    <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                @endif
                            </div>
                            <div class="sig-line">{{ ($koorSig['name'] ?? null) ?: '...................................' }}</div>
                        </div>

                        <!-- Wasit Utama -->
                        <div class="sig-box">
                            <div class="sig-role">Wasit Utama</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @if(!empty($wasSig['signature']))
                                    <img src="{{ $wasSig['signature'] }}" style="max-height: 55px; max-width: 120px; display: block;" />
                                @else
                                    <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                @endif
                            </div>
                            <div class="sig-line">{{ ($wasSig['name'] ?? null) ?: '...................................' }}</div>
                        </div>

                        <!-- Panitera -->
                        <div class="sig-box">
                            <div class="sig-role">Panitera</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @php
                                    $p1 = null;
                                    if (is_array($panSigArray)) {
                                        $p1 = count($panSigArray) > 0 ? reset($panSigArray) : null;
                                    }
                                @endphp
                                @if($p1 && !empty($p1['signature']))
                                    <img src="{{ $p1['signature'] }}" style="max-height: 55px; max-width: 120px; display: block;" />
                                @else
                                    <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                @endif
                            </div>
                            <div class="sig-line">{{ ($p1['name'] ?? null) ?: '...................................' }}</div>
                        </div>
                    </div>

                    <!-- Row 2: Manajer Official Pit -->
                    <div class="sig-grid sig-grid-2" style="margin-bottom: 20px;">
                        <!-- Manajer Pita Merah -->
                        <div class="sig-box" style="border-color:#fca5a5; background:rgba(239,68,68,0.01);">
                            <div class="sig-role" style="color:#ef4444;">Manajer AKA (Merah)</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @if(!empty($mgrRedSig['signature']))
                                    <img src="{{ $mgrRedSig['signature'] }}" style="max-height: 55px; max-width: 150px; display: block;" />
                                @else
                                    <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                @endif
                            </div>
                            <div class="sig-line">{{ ($mgrRedSig['name'] ?? null) ?: '...................................' }}</div>
                        </div>

                        <!-- Manajer Pita Putih -->
                        <div class="sig-box" style="border-color:#93c5fd; background:rgba(59,130,246,0.01);">
                            <div class="sig-role" style="color:#3b82f6;">Manajer SHIRO (Biru)</div>
                            <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                @if(!empty($mgrWhiteSig['signature']))
                                    <img src="{{ $mgrWhiteSig['signature'] }}" style="max-height: 55px; max-width: 150px; display: block;" />
                                @else
                                    <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                @endif
                            </div>
                            <div class="sig-line">{{ ($mgrWhiteSig['name'] ?? null) ?: '...................................' }}</div>
                        </div>
                    </div>

                    <!-- Row 3: Juri Lapangan (Corner Judges) -->
                    <div style="margin-top: 20px;">
                        <div style="font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:8px;">Juri Lapangan (Corner Judges)</div>
                        <div class="sig-grid sig-grid-4">
                            @for($ji = 1; $ji <= 4; $ji++)
                                @php
                                    $juriScore = $refereeScores->where('judge_index', $ji)->first();
                                    $assignedJuri = $referees->where('judge_index', $ji)->first();
                                    $juriName = $juriScore?->referee?->name ?? $juriScore?->referee?->user?->name ?? $assignedJuri?->referee?->name ?? $assignedJuri?->referee?->user?->name ?? '...................................';
                                    $juriSig = $juriScore?->signature ?? null;
                                @endphp
                                <div class="sig-box">
                                    <div class="sig-role">Juri {{ $ji }}</div>
                                    <div style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                        @if($juriSig)
                                            <img src="{{ $juriSig }}" style="max-height: 55px; max-width: 120px; display: block;" />
                                        @else
                                            <span style="color:#cbd5e1; font-size:10px;">(Belum TTD)</span>
                                        @endif
                                    </div>
                                    <div class="sig-line">{{ $juriName }}</div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <div style="margin-top:20px; text-align:right; font-size:10px; color:#94a3b8;">
                        Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} WIB &nbsp;|&nbsp; Smart Perkemi System
                    </div>
                </div>
            </div>
        @endforeach
    @endif{{-- end .document --}}
</div>{{-- end .print-wrap --}}
</div>
