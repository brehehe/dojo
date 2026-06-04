<div>
    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
    /* ══════════════════════════════════════════════════════
       FORM STYLES — Observasi Wasit (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .prem-page { background: var(--paper); color: var(--ink); padding: 28px; min-height: 100vh; }
    .cinzel { font-family: 'Cinzel', serif; }

    /* ── PAGE HEADER ── */
    .page-hdr { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .page-hdr-left h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .page-hdr-left p  { font-size: 12px; color: var(--smoke); margin: 0; }

    /* ── TABS ── */
    .tabs-prem { display: flex; gap: 6px; border-bottom: 2px solid var(--paper2); padding-bottom: 2px; margin-bottom: 24px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .tab-btn {
        padding: 10px 18px; border: none; background: none; font-size: 13.5px; font-family: 'DM Sans', sans-serif;
        cursor: pointer; color: var(--smoke); font-weight: 500; border-bottom: 2px solid transparent; margin-bottom: -2px;
        transition: all .2s; white-space: nowrap; display: flex; align-items: center; gap: 8px;
    }
    .tab-btn.active { color: var(--red); border-bottom-color: var(--red); font-weight: 600; }
    .tab-btn:hover:not(.active) { color: var(--ink); }

    /* ── CARDS ── */
    .card-prem { background: #fff; border-radius: 16px; border: 1px solid var(--paper2); padding: 24px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.01); }
    .card-title { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--ink); border-bottom: 1px solid var(--paper2); padding-bottom: 12px; margin-bottom: 20px; }

    /* ── GRID & FORMS ── */
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group label { font-size: 12px; font-weight: 600; color: var(--ink); text-transform: uppercase; letter-spacing: 0.05em; }
    .form-group input, .form-group select, .form-group textarea {
        background: var(--paper); border: 1px solid var(--paper2); border-radius: 10px; padding: 10px 14px;
        font-size: 13.5px; color: var(--ink); font-family: 'DM Sans', sans-serif; outline: none; transition: border .2s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color: var(--red); }
    .form-group textarea { resize: vertical; min-height: 80px; }
    .error-msg { color: var(--red); font-size: 11px; margin-top: 2px; }

    /* ── PARAMETER SECTIONS ── */
    .param-header { background: var(--paper); padding: 12px 18px; border-radius: 10px; display: flex; align-items: center; justify-content: justify; gap: 10px; margin-bottom: 16px; border: 1px solid var(--paper2); }
    .param-header h4 { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: var(--ink); margin: 0; flex: 1; }
    .param-weight { background: var(--red); color: #fff; font-size: 10.5px; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
    .param-score-feedback { background: var(--gold-lt); color: #7d5a00; font-size: 11px; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; }

    /* ── RATING BUTTONS ── */
    .rating-container { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .rating-pill {
        width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--paper2); background: var(--paper);
        cursor: pointer; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; justify-content: center;
        transition: all .15s; color: var(--smoke);
    }
    .rating-pill:hover { background: var(--paper2); color: var(--ink); }
    .rating-pill.active { background: var(--red); border-color: var(--red); color: #fff; box-shadow: 0 4px 10px rgba(192, 57, 43, 0.2); }

    /* ── TABLES FOR INCIDENTS ── */
    .table-inputs { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 13px; }
    .table-inputs th, .table-inputs td { border: 1px solid var(--paper2); padding: 10px; text-align: left; }
    .table-inputs th { background: var(--paper); font-weight: 600; color: var(--smoke); text-transform: uppercase; font-size: 10.5px; }
    .table-inputs td input, .table-inputs td select { padding: 6px 10px; font-size: 12.5px; border-radius: 6px; width: 100%; }

    /* ── SCORE SUMMARY ── */
    .score-summary-box { background: var(--paper); border: 2px dashed var(--paper2); border-radius: 16px; padding: 24px; text-align: center; margin-bottom: 24px; }
    .score-summary-title { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: var(--smoke); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 10px; }
    .score-val { font-size: 48px; font-weight: 900; color: var(--red); font-family: 'Cinzel', serif; line-height: 1.1; margin-bottom: 8px; }
    .score-cat-badge { display: inline-block; padding: 6px 16px; border-radius: 30px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }
    .score-cat-badge.sangat_baik { background: rgba(46, 204, 113, 0.12); color: #27ae60; }
    .score-cat-badge.baik { background: rgba(39, 174, 96, 0.12); color: #27ae60; }
    .score-cat-badge.cukup { background: rgba(241, 196, 15, 0.15); color: #d4a843; }
    .score-cat-badge.kurang { background: rgba(192, 57, 43, 0.12); color: var(--red); }

    /* ── CHECKBOX CARD ── */
    .chk-card { display: flex; gap: 12px; align-items: flex-start; padding: 14px; border: 1px solid var(--paper2); border-radius: 10px; margin-bottom: 10px; transition: background .2s; }
    .chk-card:hover { background: var(--paper); }
    .chk-card input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--red); margin-top: 2px; cursor: pointer; }
    .chk-card label { font-size: 13px; color: var(--ink); cursor: pointer; line-height: 1.4; }

    /* ── BUTTONS ── */
    .btn-save-prem { background: var(--red); color: #fff; border: none; padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background .2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-save-prem:hover { background: var(--red-deep); }
    .btn-cancel-prem { background: #fff; border: 1px solid var(--paper2); color: var(--smoke); padding: 12px 24px; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background .2s; text-decoration: none; text-align: center; }
    .btn-cancel-prem:hover { background: var(--paper); color: var(--ink); }

    /* ══════════════════════════════════════════════════════
       PRINT TEMPLATE STYLES (MATCHES USER ATTACHED HTML)
    ══════════════════════════════════════════════════════ */
    .print-wrapper {
        display: none;
    }
    .print-only { display: none; }

    @media print {
        body { background: white !important; font-family: 'Times New Roman', Times, serif !important; color: black !important; }
        .no-print { display: none !important; }
        .print-only { display: block !important; }
        .print-wrapper { display: block !important; }
        .prem-page { padding: 0 !important; background: white !important; min-height: auto !important; }
        aside, header, nav, .mob-bottomnav, .tabs-prem, .card-prem, .page-hdr { display: none !important; }
        main.premium-main { margin-left: 0 !important; padding: 0 !important; }
        
        .page-break { page-break-before: always; }
        
        .form-page {
            background: white !important;
            padding: 0 !important;
            margin-bottom: 2.5cm !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a3a4a !important;
            padding-bottom: 15px;
        }
        .header h1 { font-size: 1.6rem !important; color: #1a3a4a !important; margin-bottom: 5px; }
        .header h2 { font-size: 1.1rem !important; color: #666 !important; font-weight: normal; }
        .header .event { background: #ffd700 !important; display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; margin-top: 10px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        .section { margin-bottom: 25px; border: 1px solid #ddd !important; border-radius: 8px; overflow: hidden; }
        .section-title { background: #1a3a4a !important; color: white !important; padding: 10px 15px; font-weight: bold; font-size: 1rem; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .section-content { padding: 15px; }
        .info-row { display: flex; flex-direction: row !important; gap: 15px; margin-bottom: 15px; }
        .info-field { flex: 1; min-width: 150px; }
        .info-field label { font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem; }
        .info-field .value { border-bottom: 1px solid #000 !important; padding: 5px 0; min-height: 30px; }
        
        table { width: 100% !important; border-collapse: collapse !important; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd !important; padding: 8px !important; text-align: left; vertical-align: top; }
        th { background: #f0f0f0 !important; font-weight: bold; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        .rating-box { width: 35px; height: 35px; border: 1px solid #ccc !important; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; }
        .score-total { background: #ffd700 !important; padding: 15px; text-align: center; border-radius: 8px; margin-top: 15px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .score-total h3 { font-size: 1.3rem !important; margin-bottom: 5px; }
        
        .signature { display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd !important; }
        .signature > div { text-align: center; width: 45%; }
        
        .checkbox-print { font-size: 14px; font-weight: normal; }
    }
    
    /* Displaying printable view layout directly in the tab in screen mode */
    .screen-preview-wrapper {
        background: #999;
        padding: 40px 20px;
        display: flex;
        flex-direction: column;
        gap: 30px;
        align-items: center;
        max-height: 800px;
        overflow-y: auto;
        border-radius: 16px;
    }
    .screen-preview-wrapper .form-page {
        background: white;
        width: 100%;
        max-width: 800px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        color: black;
        font-family: 'Times New Roman', Times, serif;
    }
    .screen-preview-wrapper .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1a3a4a; padding-bottom: 15px; }
    .screen-preview-wrapper .header h1 { font-size: 1.6rem; color: #1a3a4a; margin-bottom: 5px; }
    .screen-preview-wrapper .header h2 { font-size: 1.1rem; color: #666; font-weight: normal; }
    .screen-preview-wrapper .header .event { background: #ffd700; display: inline-block; padding: 5px 15px; border-radius: 20px; font-weight: bold; margin-top: 10px; }
    .screen-preview-wrapper .section { margin-bottom: 25px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
    .screen-preview-wrapper .section-title { background: #1a3a4a; color: white; padding: 10px 15px; font-weight: bold; font-size: 1rem; }
    .screen-preview-wrapper .section-content { padding: 15px; }
    .screen-preview-wrapper .info-row { display: flex; gap: 15px; margin-bottom: 15px; }
    .screen-preview-wrapper .info-field { flex: 1; min-width: 150px; }
    .screen-preview-wrapper .info-field label { font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.85rem; }
    .screen-preview-wrapper .info-field .value { border-bottom: 1px solid #000; padding: 5px 0; min-height: 30px; }
    .screen-preview-wrapper table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .screen-preview-wrapper th, .screen-preview-wrapper td { border: 1px solid #ddd; padding: 8px; text-align: left; vertical-align: top; }
    .screen-preview-wrapper th { background: #f0f0f0; font-weight: bold; }
    .screen-preview-wrapper .score-total { background: #ffd700; padding: 15px; text-align: center; border-radius: 8px; margin-top: 15px; }
    .screen-preview-wrapper .score-total h3 { font-size: 1.3rem; margin-bottom: 5px; }
    .screen-preview-wrapper .signature { display: flex; justify-content: space-between; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; }
    .screen-preview-wrapper .signature > div { text-align: center; width: 45%; }
    </style>
    @endpush

    <div class="prem-page no-print">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-hdr">
            <div class="page-hdr-left">
                <h2>{{ $mode === 'create' ? 'Tambah' : ($mode === 'edit' ? 'Edit' : 'Detail') }} Observasi Wasit</h2>
                <p>Mengisi formulir penilaian dan observasi kinerja wasit lapangan</p>
            </div>
            <div class="page-hdr-right" style="display:flex; gap:8px;">
                @if($mode === 'view')
                <button type="button" onclick="window.print()" class="btn-primary-prem" style="padding: 10px 20px; border-radius: 12px; background: #1a3a4a; color: #fff; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer;">
                    <i class="fa-solid fa-print"></i> Cetak Formulir
                </button>
                @endif
                <a href="{{ route('contingent.observasi-wasit.index') }}" class="btn-cancel-prem" style="padding: 10px 20px; border-radius: 12px;">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- ── TABS ── --}}
        <div class="tabs-prem">
            <button type="button" wire:click="setTab('identitas')" class="tab-btn {{ $currentTab === 'identitas' ? 'active' : '' }}">
                <i class="fa-solid fa-user-check"></i> Identitas & Kompetensi
            </button>
            <button type="button" wire:click="setTab('events')" class="tab-btn {{ $currentTab === 'events' ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list"></i> Event & Time Sampling
            </button>
            <button type="button" wire:click="setTab('rating_laporan')" class="tab-btn {{ $currentTab === 'rating_laporan' ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> Rating & Laporan
            </button>
            <button type="button" wire:click="setTab('etika')" class="tab-btn {{ $currentTab === 'etika' ? 'active' : '' }}">
                <i class="fa-solid fa-scale-balanced"></i> Etika & Deklarasi
            </button>
            @if($mode === 'view' || $mode === 'edit')
            <button type="button" wire:click="setTab('pratinjau')" class="tab-btn {{ $currentTab === 'pratinjau' ? 'active' : '' }}">
                <i class="fa-solid fa-print"></i> Pratinjau Cetak
            </button>
            @endif
        </div>

        {{-- ── MAIN FORM BODY ── --}}
        <form wire:submit.prevent="saveObservation">
            <div style="display: grid; grid-template-columns: 1fr 300px; gap: 24px; align-items: start;">
                <div>
                    {{-- ── TAB 1: IDENTITAS & PARAMETER ── --}}
                    <div x-show="$wire.currentTab === 'identitas'">
                        <div class="card-prem">
                            <div class="card-title">A. Identitas Pengamat & Wasit</div>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Nama Manager/Official</label>
                                    <input type="text" wire:model="observer_name" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('observer_name') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Kontingen</label>
                                    <input type="text" value="{{ $contingent ? $contingent->name : '-' }}" disabled style="background:#eee;">
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Tanggal Observasi</label>
                                    <input type="date" wire:model="observation_date" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('observation_date') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Court / Lapangan</label>
                                    <select wire:model="court" {{ $mode === 'view' ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Court --</option>
                                        @forelse($courts as $courtItem)
                                            <option value="{{ $courtItem->name }}">{{ $courtItem->name }}</option>
                                        @empty
                                            <option value="Court 1">Court 1</option>
                                            <option value="Court 2">Court 2</option>
                                            <option value="Court 3">Court 3</option>
                                            <option value="Court 4">Court 4</option>
                                            <option value="Court 5">Court 5</option>
                                        @endforelse
                                    </select>
                                    @error('court') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Babak Pertandingan</label>
                                    <select wire:model="round" {{ $mode === 'view' ? 'disabled' : '' }}>
                                        <option value="Penyisihan">Penyisihan</option>
                                        <option value="Semifinal">Semifinal</option>
                                        <option value="Final">Final</option>
                                    </select>
                                    @error('round') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Waktu Pertandingan (WIB)</label>
                                    <input type="text" wire:model="match_time" placeholder="Contoh: 10:30" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('match_time') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Nama Wasit yang Diamati</label>
                                    @if($mode === 'view')
                                        <select wire:model="referee_id" disabled style="background:#eee;">
                                            <option value="">-- Pilih Wasit --</option>
                                            @foreach($referees as $ref)
                                                <option value="{{ $ref->id }}">{{ $ref->name }} (Lic. {{ $ref->license_number ?: '-' }})</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <x-select wire:model="referee_id" placeholder="-- Pilih Wasit --" id="select-referee">
                                            <option value=""></option>
                                            @foreach($referees as $ref)
                                                <option value="{{ $ref->id }}">{{ $ref->name }} (Lic. {{ $ref->license_number ?: '-' }})</option>
                                            @endforeach
                                        </x-select>
                                    @endif
                                    @error('referee_id') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nomor Juri / Wasit</label>
                                    <select wire:model="referee_number" {{ $mode === 'view' ? 'disabled' : '' }}>
                                        <option value="I">Juri I</option>
                                        <option value="II">Juri II</option>
                                        <option value="III">Juri III</option>
                                        <option value="IV">Juri IV</option>
                                        <option value="V">Juri V</option>
                                    </select>
                                    @error('referee_number') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Kontingen Biru (Away)</label>
                                    <input type="text" wire:model="contingent_away" placeholder="Nama Kontingen Biru" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('contingent_away') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Kontingen Putih (Home)</label>
                                    <input type="text" wire:model="contingent_home" placeholder="Nama Kontingen Putih" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('contingent_home') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- PARAMETER 1 --}}
                        <div class="card-prem">
                            <div class="param-header">
                                <h4>📊 Parameter 1: Konsistensi Keputusan</h4>
                                <span class="param-weight">Bobot 30%</span>
                                <span class="param-score-feedback">Skor: {{ $p1['skor_konsistensi'] }} / 30</span>
                            </div>

                            <div class="prem-table-wrap">
                                <table class="table-inputs">
                                    <thead>
                                        <tr>
                                            <th width="10%">No</th>
                                            <th width="20%">Waktu</th>
                                            <th width="35%">Jenis Kejadian</th>
                                            <th width="25%">Keputusan Wasit</th>
                                            <th width="10%">Konsisten?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($p1['rows'] as $index => $row)
                                        <tr>
                                            <td style="font-weight:bold; text-align:center; vertical-align:middle;">{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text" wire:model.live="p1.rows.{{ $index }}.waktu" placeholder="Menit ke-" {{ $mode === 'view' ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <select wire:model.live="p1.rows.{{ $index }}.jenis" {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    <option value="Pelanggaran ringan">Pelanggaran ringan</option>
                                                    <option value="Pelanggaran berat">Pelanggaran berat</option>
                                                    <option value="Poin/Gol">Poin/Gol</option>
                                                    <option value="Protes">Protes</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" wire:model.live="p1.rows.{{ $index }}.keputusan" placeholder="Keputusan" {{ $mode === 'view' ? 'disabled' : '' }}>
                                            </td>
                                            <td style="text-align:center; vertical-align:middle;">
                                                <div style="display:flex; gap:10px; justify-content:center;">
                                                    <label style="display:inline-flex; align-items:center; gap:4px; font-weight:normal; font-size:12px;">
                                                        <input type="radio" value="ya" wire:model.live="p1.rows.{{ $index }}.konsisten" {{ $mode === 'view' ? 'disabled' : '' }}> Ya
                                                    </label>
                                                    <label style="display:inline-flex; align-items:center; gap:4px; font-weight:normal; font-size:12px;">
                                                        <input type="radio" value="tidak" wire:model.live="p1.rows.{{ $index }}.konsisten" {{ $mode === 'view' ? 'disabled' : '' }}> Tdk
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-grid" style="margin-top: 15px;">
                                <div class="form-group">
                                    <label>Jumlah Keputusan Konsisten</label>
                                    <input type="text" value="{{ $p1['jumlah_konsisten'] }} dari 6 kejadian" disabled style="background:#eee;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Catatan Khusus (Konsistensi)</label>
                                <textarea wire:model.live="p1.catatan" {{ $mode === 'view' ? 'disabled' : '' }} placeholder="Masukkan catatan tambahan..."></textarea>
                            </div>
                        </div>

                        {{-- PARAMETER 2 --}}
                        <div class="card-prem">
                            <div class="param-header">
                                <h4>📢 Parameter 2: Kejelasan Komunikasi</h4>
                                <span class="param-weight">Bobot 20%</span>
                                <span class="param-score-feedback">Skor: {{ $p2['skor_komunikasi'] }} / 20</span>
                            </div>

                            @php
                                $p2_indicators = [
                                    1 => 'Isyarat tangan jelas dan mudah dipahami',
                                    2 => 'Isyarat tangan sesuai standar WSKO',
                                    3 => 'Suara cukup keras dan jelas terdengar',
                                    4 => 'Instruksi kepada atlet jelas dan tegas',
                                    5 => 'Komunikasi dengan panitera berjalan baik',
                                    6 => 'Komunikasi dengan atlet berjalan baik',
                                ];
                            @endphp

                            <table class="table-inputs">
                                <thead>
                                    <tr>
                                        <th width="80%">Indikator Penilaian</th>
                                        <th width="20%" style="text-align:center;">Skor (1 - 5)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p2_indicators as $idx => $label)
                                    <tr>
                                        <td style="vertical-align:middle; font-weight:500;">{{ $label }}</td>
                                        <td style="text-align:center;">
                                            <div class="rating-container" style="justify-content:center;">
                                                @for($v=1; $v<=5; $v++)
                                                <button type="button" wire:click="$set('p2.ratings.{{ $idx }}', {{ $v }})" 
                                                        class="rating-pill {{ ($p2['ratings'][$idx] ?? 0) == $v ? 'active' : '' }}"
                                                        {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    {{ $v }}
                                                </button>
                                                @endfor
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="form-group" style="margin-top:15px;">
                                <label>Catatan Tambahan (Komunikasi)</label>
                                <textarea wire:model.live="p2.catatan" {{ $mode === 'view' ? 'disabled' : '' }} placeholder="Masukkan catatan..."></textarea>
                            </div>
                        </div>

                        {{-- PARAMETER 3 --}}
                        <div class="card-prem">
                            <div class="param-header">
                                <h4>🏃 Parameter 3: Posisi dan Pergerakan</h4>
                                <span class="param-weight">Bobot 15%</span>
                                <span class="param-score-feedback">Skor: {{ $p3['skor_posisi'] }} / 15</span>
                            </div>

                            @php
                                $p3_indicators = [
                                    1 => 'Positioning (posisi di lapangan) tepat',
                                    2 => 'Tidak menghalangi pandangan atlet/penonton',
                                    3 => 'Pergerakan aktif mengikuti alur pertandingan',
                                    4 => 'Pergerakan efisien (tidak kelelahan berlebihan)',
                                    5 => 'Memiliki sudut pandang yang jelas terhadap area pertandingan',
                                ];
                            @endphp

                            <table class="table-inputs">
                                <thead>
                                    <tr>
                                        <th width="80%">Indikator Penilaian</th>
                                        <th width="20%" style="text-align:center;">Skor (1 - 5)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p3_indicators as $idx => $label)
                                    <tr>
                                        <td style="vertical-align:middle; font-weight:500;">{{ $label }}</td>
                                        <td style="text-align:center;">
                                            <div class="rating-container" style="justify-content:center;">
                                                @for($v=1; $v<=5; $v++)
                                                <button type="button" wire:click="$set('p3.ratings.{{ $idx }}', {{ $v }})" 
                                                        class="rating-pill {{ ($p3['ratings'][$idx] ?? 0) == $v ? 'active' : '' }}"
                                                        {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    {{ $v }}
                                                </button>
                                                @endfor
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- PARAMETER 4 --}}
                        <div class="card-prem">
                            <div class="param-header">
                                <h4>⚖️ Parameter 4: Pengendalian Pertandingan</h4>
                                <span class="param-weight">Bobot 20%</span>
                                <span class="param-score-feedback">Skor: {{ $p4['skor_pengendalian'] }} / 20</span>
                            </div>

                            @php
                                $p4_indicators = [
                                    1 => 'Kemampuan meredakan ketegangan saat situasi memanas',
                                    2 => 'Ketegasan dalam memberikan sanksi',
                                    3 => 'Manajemen waktu pertandingan (start, stop, dll.)',
                                    4 => 'Penanganan protes dari atlet/official',
                                    5 => 'Tidak terprovokasi oleh protes berlebihan',
                                ];
                            @endphp

                            <table class="table-inputs">
                                <thead>
                                    <tr>
                                        <th width="80%">Indikator Penilaian</th>
                                        <th width="20%" style="text-align:center;">Skor (1 - 5)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p4_indicators as $idx => $label)
                                    <tr>
                                        <td style="vertical-align:middle; font-weight:500;">{{ $label }}</td>
                                        <td style="text-align:center;">
                                            <div class="rating-container" style="justify-content:center;">
                                                @for($v=1; $v<=5; $v++)
                                                <button type="button" wire:click="$set('p4.ratings.{{ $idx }}', {{ $v }})" 
                                                        class="rating-pill {{ ($p4['ratings'][$idx] ?? 0) == $v ? 'active' : '' }}"
                                                        {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    {{ $v }}
                                                </button>
                                                @endfor
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- PARAMETER 5 --}}
                        <div class="card-prem">
                            <div class="param-header">
                                <h4>🎯 Parameter 5: Sikap dan Profesionalisme</h4>
                                <span class="param-weight">Bobot 15%</span>
                                <span class="param-score-feedback">Skor: {{ $p5['skor_profesionalisme'] }} / 15</span>
                            </div>

                            @php
                                $p5_indicators = [
                                    1 => 'Penampilan rapi, seragam sesuai standar',
                                    2 => 'Sikap hormat kepada atlet',
                                    3 => 'Sikap hormat kepada official kontingen',
                                    4 => 'Netralitas (tidak memihak salah satu kontingen)',
                                    5 => 'Tidak menunjukkan emosi negatif berlebihan',
                                ];
                            @endphp

                            <table class="table-inputs">
                                <thead>
                                    <tr>
                                        <th width="80%">Indikator Penilaian</th>
                                        <th width="20%" style="text-align:center;">Skor (1 - 5)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($p5_indicators as $idx => $label)
                                    <tr>
                                        <td style="vertical-align:middle; font-weight:500;">{{ $label }}</td>
                                        <td style="text-align:center;">
                                            <div class="rating-container" style="justify-content:center;">
                                                @for($v=1; $v<=5; $v++)
                                                <button type="button" wire:click="$set('p5.ratings.{{ $idx }}', {{ $v }})" 
                                                        class="rating-pill {{ ($p5['ratings'][$idx] ?? 0) == $v ? 'active' : '' }}"
                                                        {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    {{ $v }}
                                                </button>
                                                @endfor
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- ── TAB 2: EVENTS & TIME SAMPLING ── --}}
                    <div x-show="$wire.currentTab === 'events'">
                        {{-- EVENT RECORDING SHEET --}}
                        <div class="card-prem">
                            <div class="card-title">📝 Event Recording Sheet (Catatan Kejadian Spesifik)</div>
                            <p style="font-size:12px; color:var(--smoke); margin-top:-10px; margin-bottom:15px;">Pencatatan kejadian-kejadian penting selama pertandingan berlangsung.</p>

                            <div class="prem-table-wrap">
                                <table class="table-inputs">
                                    <thead>
                                        <tr>
                                            <th width="8%">No</th>
                                            <th width="15%">Waktu</th>
                                            <th width="40%">Jenis Kejadian</th>
                                            <th width="25%">Keputusan Wasit</th>
                                            <th width="12%">Evaluasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event_recording['rows'] as $index => $row)
                                        <tr>
                                            <td style="font-weight:bold; text-align:center; vertical-align:middle;">{{ $index + 1 }}</td>
                                            <td>
                                                <input type="text" wire:model.live="event_recording.rows.{{ $index }}.waktu" placeholder="Menit ke-" {{ $mode === 'view' ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                <select wire:model.live="event_recording.rows.{{ $index }}.jenis" {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    <option value="Pelanggaran ringan">Pelanggaran ringan</option>
                                                    <option value="Pelanggaran berat">Pelanggaran berat</option>
                                                    <option value="Poin/Gol">Poin/Gol</option>
                                                    <option value="Protes">Protes</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" wire:model.live="event_recording.rows.{{ $index }}.keputusan" placeholder="Keputusan" {{ $mode === 'view' ? 'disabled' : '' }}>
                                            </td>
                                            <td style="text-align:center; vertical-align:middle;">
                                                <div style="display:flex; gap:10px; justify-content:center;">
                                                    <label style="display:inline-flex; align-items:center; gap:4px; font-weight:normal; font-size:12px;">
                                                        <input type="radio" value="ya" wire:model.live="event_recording.rows.{{ $index }}.evaluasi" {{ $mode === 'view' ? 'disabled' : '' }}> Tepat
                                                    </label>
                                                    <label style="display:inline-flex; align-items:center; gap:4px; font-weight:normal; font-size:12px;">
                                                        <input type="radio" value="tidak" wire:model.live="event_recording.rows.{{ $index }}.evaluasi" {{ $mode === 'view' ? 'disabled' : '' }}> Tdk
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-grid" style="margin-top: 15px;">
                                <div class="form-group">
                                    <label>Total Kejadian Tercatat</label>
                                    <input type="text" value="{{ $event_recording['total_kejadian'] }} kejadian" disabled style="background:#eee;">
                                </div>
                                <div class="form-group">
                                    <label>Keputusan Tepat / Evaluasi Tepat</label>
                                    <input type="text" value="{{ $event_recording['total_tepat'] }} dari {{ $event_recording['total_kejadian'] }}" disabled style="background:#eee;">
                                </div>
                            </div>
                        </div>

                        {{-- TIME SAMPLING SHEET --}}
                        <div class="card-prem">
                            <div class="card-title">🏃 Time Sampling Sheet (Observasi Berkala 5 Menit)</div>
                            <p style="font-size:12px; color:var(--smoke); margin-top:-10px; margin-bottom:15px;">Catat performa berkala wasit untuk setiap interval 5 menit.</p>

                            <div class="prem-table-wrap">
                                <table class="table-inputs">
                                    <thead>
                                        <tr>
                                            <th width="15%">Interval (Menit)</th>
                                            <th width="25%">Posisi Wasit</th>
                                            <th width="25%">Komunikasi Wasit</th>
                                            <th width="25%">Pengendalian</th>
                                            <th width="20%">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($time_sampling['rows'] as $index => $row)
                                        <tr>
                                            <td style="font-weight:bold; text-align:center; vertical-align:middle;">{{ $row['interval'] }}</td>
                                            <td>
                                                <select wire:model.live="time_sampling.rows.{{ $index }}.posisi" {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    <option value="Baik">Baik</option>
                                                    <option value="Cukup">Cukup</option>
                                                    <option value="Kurang">Kurang</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select wire:model.live="time_sampling.rows.{{ $index }}.komunikasi" {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    <option value="Jelas">Jelas</option>
                                                    <option value="Kurang Jelas">Kurang Jelas</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select wire:model.live="time_sampling.rows.{{ $index }}.pengendalian" {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    <option value="Baik">Baik</option>
                                                    <option value="Cukup">Cukup</option>
                                                    <option value="Kurang">Kurang</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" wire:model.live="time_sampling.rows.{{ $index }}.catatan" placeholder="Catatan..." {{ $mode === 'view' ? 'disabled' : '' }}>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB 3: RATING SCALE & LAPORAN ── --}}
                    <div x-show="$wire.currentTab === 'rating_laporan'">
                        {{-- RATING SCALE SHEET --}}
                        <div class="card-prem">
                            <div class="card-title">📈 Rating Scale Sheet (Skala Penilaian Kinerja)</div>
                            <p style="font-size:12px; color:var(--smoke); margin-top:-10px; margin-bottom:15px;">Berikan skor 1-5 untuk setiap aspek performa kerja berikut:</p>

                            @php
                                $scale_indicators = [
                                    1 => 'Konsistensi keputusan pelanggaran ringan',
                                    2 => 'Konsistensi keputusan pelanggaran berat',
                                    3 => 'Konsistensi keputusan antar babak',
                                    4 => 'Kejelasan isyarat tangan',
                                    5 => 'Kejelasan komunikasi verbal',
                                    6 => 'Positioning (posisi di lapangan)',
                                    7 => 'Pergerakan (movement)',
                                    8 => 'Kemampuan meredakan ketegangan',
                                    9 => 'Ketegasan memberikan sanksi',
                                    10 => 'Penampilan dan kerapian seragam',
                                    11 => 'Sikap hormat kepada atlet',
                                    12 => 'Netralitas (tidak memihak)',
                                ];
                            @endphp

                            <table class="table-inputs">
                                <thead>
                                    <tr>
                                        <th width="75%">Indikator Penilaian</th>
                                        <th width="25%" style="text-align:center;">Skor (1 - 5)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($scale_indicators as $idx => $label)
                                    <tr>
                                        <td style="vertical-align:middle; font-weight:500;">{{ $label }}</td>
                                        <td style="text-align:center;">
                                            <div class="rating-container" style="justify-content:center;">
                                                @for($v=1; $v<=5; $v++)
                                                <button type="button" wire:click="$set('rating_scale.ratings.{{ $idx }}', {{ $v }})" 
                                                        class="rating-pill {{ ($rating_scale['ratings'][$idx] ?? 0) == $v ? 'active' : '' }}"
                                                        {{ $mode === 'view' ? 'disabled' : '' }}>
                                                    {{ $v }}
                                                </button>
                                                @endfor
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="form-grid" style="margin-top: 15px;">
                                <div class="form-group">
                                    <label>Total Skor Rating Scale</label>
                                    <input type="text" value="{{ $rating_scale['total_skor'] }} / 60" disabled style="background:#eee;">
                                </div>
                                <div class="form-group">
                                    <label>Nilai Akhir Rating Scale</label>
                                    <input type="text" value="{{ $rating_scale['nilai_akhir'] }} / 100" disabled style="background:#eee;">
                                </div>
                            </div>
                        </div>

                        {{-- LAPORAN OBSERVASI WASIT --}}
                        <div class="card-prem">
                            <div class="card-title">📝 Ringkasan Laporan Observasi</div>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Kepada (Penerima Laporan)</label>
                                    <input type="text" wire:model="kepada" placeholder="Contoh: Manager Kontingen..." {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('kepada') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group">
                                    <label>Dari (Pembuat Laporan)</label>
                                    <input type="text" wire:model="dari" placeholder="Nama Pembuat Laporan" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('dari') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Tanggal Laporan</label>
                                    <input type="date" wire:model="tanggal_laporan" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    @error('tanggal_laporan') <span class="error-msg">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group" style="margin-bottom:15px;">
                                <label>✅ Kelebihan (Strengths)</label>
                                <textarea wire:model="kelebihan" placeholder="Tulis kelebihan wasit..." {{ $mode === 'view' ? 'disabled' : '' }}></textarea>
                                @error('kelebihan') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group" style="margin-bottom:15px;">
                                <label>⚠️ Area yang Perlu Diperhatikan (Improvements)</label>
                                <textarea wire:model="area_perbaikan" placeholder="Tulis area kelemahan..." {{ $mode === 'view' ? 'disabled' : '' }}></textarea>
                                @error('area_perbaikan') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>💡 Rekomendasi</label>
                                <textarea wire:model="rekomendasi" placeholder="Tulis rekomendasi perbaikan..." {{ $mode === 'view' ? 'disabled' : '' }}></textarea>
                                @error('rekomendasi') <span class="error-msg">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── TAB 4: ETIKA & KONFIRMASI ── --}}
                    <div x-show="$wire.currentTab === 'etika'">
                        <div class="card-prem">
                            <div class="card-title">📋 Konfirmasi Kepatuhan Etika</div>

                            @php
                                $etika_items = [
                                    1 => 'Saya tidak mengintervensi keputusan wasit selama pertandingan.',
                                    2 => 'Saya tidak memprovokasi atlet atau penonton.',
                                    3 => 'Saya tidak menyebarkan informasi tidak benar tentang wasit.',
                                    4 => 'Saya menghormati keputusan wasit meskipun tidak setuju.',
                                    5 => 'Saya menggunakan instrumen observasi terstandar.',
                                    6 => 'Saya mencatat kejadian secara real-time.',
                                    7 => 'Saya berada di posisi yang tidak mengganggu jalannya pertandingan.',
                                ];
                            @endphp

                            <div style="margin-bottom:24px;">
                                @foreach($etika_items as $idx => $label)
                                <div class="chk-card">
                                    <input type="checkbox" id="etika_{{ $idx }}" wire:model.live="etika.checks.{{ $idx }}" {{ $mode === 'view' ? 'disabled' : '' }}>
                                    <label for="etika_{{ $idx }}">{{ $label }}</label>
                                </div>
                                @endforeach
                            </div>

                            <div class="card-title" style="border-top: 1px solid var(--paper2); padding-top:20px;">📌 Pernyataan Kepatuhan</div>
                            
                            <div style="background:var(--paper); border:1px solid var(--paper2); border-radius:10px; padding:16px; margin-bottom:20px; font-size:13.5px; line-height:1.5;">
                                Saya menyatakan bahwa observasi ini dilakukan secara profesional, objektif, dan sesuai dengan kode etik yang berlaku dalam kejuaraan Shorinji Kempo. Hasil observasi ini akan digunakan untuk evaluasi internal kontingen, bukan untuk menekan atau mendelegitimasi wasit.
                            </div>

                            <div class="chk-card" style="border: 2px solid var(--red-glow); background: rgba(192,57,43,0.03);">
                                <input type="checkbox" id="pernyataan_konfirm" wire:model.live="etika.pernyataan" {{ $mode === 'view' ? 'disabled' : '' }}>
                                <label for="pernyataan_konfirm" style="font-weight:700; color:var(--red);">Saya menyetujui pernyataan kepatuhan di atas secara sadar dan tanpa paksaan.</label>
                            </div>
                            @error('etika.pernyataan') <span class="error-msg" style="display:block; margin-top:5px;">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- ── TAB 5: SCREEN PRATINJAU CETAK ── --}}
                    @if($mode === 'view' || $mode === 'edit')
                    <div x-show="$wire.currentTab === 'pratinjau'">
                        <div class="screen-preview-wrapper">
                            
                            <!-- HALAMAN 1 -->
                            <div class="form-page">
                                <div class="header">
                                    <h1>FORMULIR OBSERVASI WASIT</h1>
                                    <h2>Untuk Manager dan Official Kontingen</h2>
                                    <div class="event">⚡ SMART PERKEMI ⚡</div>
                                    <div style="font-size: 13px; font-weight:bold; margin-top:5px;">Kejuaraan Piala Walikota Surabaya 2026 - Shorinji Kempo</div>
                                </div>

                                <div class="section">
                                    <div class="section-title">A. IDENTITAS PENGAMAT & WASIT</div>
                                    <div class="section-content">
                                        <div class="info-row">
                                            <div class="info-field"><label>Nama Manager/Official</label><div class="value">{{ $observer_name }}</div></div>
                                            <div class="info-field"><label>Kontingen</label><div class="value">{{ $contingent ? $contingent->name : '-' }}</div></div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-field"><label>Tanggal Observasi</label><div class="value">{{ $observation_date ? date('d M Y', strtotime($observation_date)) : '' }}</div></div>
                                            <div class="info-field"><label>Court</label><div class="value">{{ $court }}</div></div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-field"><label>Babak Pertandingan</label><div class="value">{{ $round }}</div></div>
                                            <div class="info-field"><label>Waktu Pertandingan</label><div class="value">{{ $match_time }} WIB</div></div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-field">
                                                <label>Nama Wasit yang Diamati</label>
                                                <div class="value">
                                                    @php
                                                        $refObj = $referees->firstWhere('id', $referee_id);
                                                    @endphp
                                                    {{ $refObj ? $refObj->name : '-' }}
                                                </div>
                                            </div>
                                            <div class="info-field"><label>Nomor Juri / Wasit</label><div class="value">Juri {{ $referee_number }}</div></div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-field"><label>Kontingen Biru (Away)</label><div class="value">{{ $contingent_away ?: '-' }}</div></div>
                                            <div class="info-field"><label>Kontingen Putih (Home)</label><div class="value">{{ $contingent_home ?: '-' }}</div></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PARAMETER 1 -->
                                <div class="section">
                                    <div class="section-title">📊 PARAMETER 1: KONSISTENSI KEPUTUSAN (Bobot 30%)</div>
                                    <div class="section-content">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th width="10%">No.</th>
                                                    <th width="20%">Waktu</th>
                                                    <th width="40%">Jenis Kejadian</th>
                                                    <th width="20%">Keputusan Wasit</th>
                                                    <th width="10%">Konsisten?</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($p1['rows'] as $idx => $row)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $row['waktu'] ?: '-' }}</td>
                                                    <td>{{ $row['jenis'] ?: '-' }}</td>
                                                    <td>{{ $row['keputusan'] ?: '-' }}</td>
                                                    <td>{{ $row['konsisten'] ? strtoupper($row['konsisten']) : '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="info-row">
                                            <div class="info-field"><label>Jumlah keputusan konsisten</label><div class="value">{{ $p1['jumlah_konsisten'] }} dari 6 kejadian</div></div>
                                            <div class="info-field"><label>Skor Konsistensi (x30%)</label><div class="value">{{ $p1['skor_konsistensi'] }} / 30</div></div>
                                        </div>
                                        @if(!empty($p1['catatan']))
                                        <div><label style="font-weight:bold;">Catatan Khusus:</label><div class="value" style="border:1px solid #ddd; padding:8px; min-height:40px;">{{ $p1['catatan'] }}</div></div>
                                        @endif
                                    </div>
                                </div>

                                <!-- PARAMETER 2 -->
                                <div class="section">
                                    <div class="section-title">📢 PARAMETER 2: KEJELASAN KOMUNIKASI (Bobot 20%)</div>
                                    <div class="section-content">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Indikator</th>
                                                    <th style="text-align:center;">Skor (1-5)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($p2_indicators as $idx => $label)
                                                <tr>
                                                    <td>{{ $idx }}</td>
                                                    <td>{{ $label }}</td>
                                                    <td style="text-align:center; font-weight:bold;">{{ $p2['ratings'][$idx] ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="info-row">
                                            <div class="info-field"><label>Total Skor (maks 30)</label><div class="value">{{ $p2['total_skor'] }} ({{ $p2['nilai_persen'] }}%)</div></div>
                                            <div class="info-field"><label>Skor Komunikasi (x20%)</label><div class="value">{{ $p2['skor_komunikasi'] }} / 20</div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- HALAMAN 2 -->
                            <div class="form-page">
                                <div class="header">
                                    <h1>EVENT RECORDING SHEET</h1>
                                    <div>Pencatatan Kejadian Spesifik Wasit</div>
                                </div>
                                <div class="section">
                                    <div class="section-content">
                                        <div class="info-row">
                                            <div class="info-field"><label>Court</label><div class="value">{{ $court }}</div></div>
                                            <div class="info-field"><label>Babak</label><div class="value">{{ $round }}</div></div>
                                            <div class="info-field"><label>Waktu Pertandingan</label><div class="value">{{ $match_time }} WIB</div></div>
                                        </div>
                                        <table>
                                            <thead>
                                                <tr style="background:#1a3a4a; color:white;">
                                                    <th width="8%">No.</th>
                                                    <th width="15%">Waktu</th>
                                                    <th width="40%">Jenis Kejadian</th>
                                                    <th width="25%">Keputusan Wasit</th>
                                                    <th width="12%">Evaluasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($event_recording['rows'] as $idx => $row)
                                                <tr>
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>{{ $row['waktu'] ?: '-' }}</td>
                                                    <td>{{ $row['jenis'] ?: '-' }}</td>
                                                    <td>{{ $row['keputusan'] ?: '-' }}</td>
                                                    <td>{{ $row['evaluasi'] ? ($row['evaluasi'] == 'ya' ? 'Tepat' : 'Tidak') : '-' }}</td>
                                                </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="4"><strong>Total Kejadian Tercatat:</strong> {{ $event_recording['total_kejadian'] }}</td>
                                                    <td><strong>Tepat:</strong> {{ $event_recording['total_tepat'] }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- HALAMAN 3 -->
                            <div class="form-page">
                                <div class="header">
                                    <h1>TIME SAMPLING SHEET</h1>
                                    <div>Observasi Berkala Per 5 Menit</div>
                                </div>
                                <div class="section">
                                    <div class="section-content">
                                        <table>
                                            <thead>
                                                <tr style="background:#1a3a4a; color:white;">
                                                    <th>Interval (Menit ke-)</th>
                                                    <th>Posisi Wasit</th>
                                                    <th>Komunikasi Wasit</th>
                                                    <th>Pengendalian Pertandingan</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($time_sampling['rows'] as $row)
                                                <tr>
                                                    <td>{{ $row['interval'] }}</td>
                                                    <td>{{ $row['posisi'] }}</td>
                                                    <td>{{ $row['komunikasi'] }}</td>
                                                    <td>{{ $row['pengendalian'] }}</td>
                                                    <td>{{ $row['catatan'] ?: '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- HALAMAN 4 -->
                            <div class="form-page">
                                <div class="header">
                                    <h1>RATING SCALE SHEET</h1>
                                    <div>Skala Penilaian Kinerja Wasit</div>
                                </div>
                                <div class="section">
                                    <div class="section-content">
                                        <table>
                                            <thead>
                                                <tr style="background:#1a3a4a; color:white;">
                                                    <th width="10%">No.</th>
                                                    <th width="70%">Indikator Penilaian</th>
                                                    <th width="20%" style="text-align:center;">Skor (1-5)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($scale_indicators as $idx => $label)
                                                <tr>
                                                    <td>{{ $idx }}</td>
                                                    <td>{{ $label }}</td>
                                                    <td style="text-align:center; font-weight:bold;">{{ $rating_scale['ratings'][$idx] ?? '-' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="score-total">
                                            <strong>Total Skor: {{ $rating_scale['total_skor'] }} / 60 → Nilai Akhir: {{ $rating_scale['nilai_akhir'] }} / 100</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- HALAMAN 5 -->
                            <div class="form-page">
                                <div class="header">
                                    <h1>LAPORAN OBSERVASI WASIT</h1>
                                    <div>Untuk Keperluan Evaluasi Kontingen</div>
                                </div>
                                <div class="section bg-white text-black">
                                    <div class="section-content">
                                        <div class="info-row">
                                            <div class="info-field"><label>Kepada</label><div class="value">{{ $kepada }}</div></div>
                                            <div class="info-field"><label>Dari</label><div class="value">{{ $dari }}</div></div>
                                            <div class="info-field"><label>Tanggal Laporan</label><div class="value">{{ $tanggal_laporan ? date('d M Y', strtotime($tanggal_laporan)) : '' }}</div></div>
                                        </div>

                                        <h3 style="font-size:14px; font-weight:bold; margin-top:20px; border-bottom:1px solid #ddd; padding-bottom:5px;">📊 RINGKASAN SKOR EFEKTIF</h3>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Parameter Kompetensi</th>
                                                    <th>Skor Capaian</th>
                                                    <th>Bobot</th>
                                                    <th>Kategori</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1. Konsistensi Keputusan</td>
                                                    <td>{{ $p1['jumlah_konsisten'] }} / 6</td>
                                                    <td>{{ $p1['skor_konsistensi'] }} / 30</td>
                                                    <td>{{ $p1['skor_konsistensi'] >= 22.5 ? 'Baik' : ($p1['skor_konsistensi'] >= 18 ? 'Cukup' : 'Kurang') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>2. Kejelasan Komunikasi</td>
                                                    <td>{{ $p2['total_skor'] }} / 30</td>
                                                    <td>{{ $p2['skor_komunikasi'] }} / 20</td>
                                                    <td>{{ $p2['skor_komunikasi'] >= 15 ? 'Baik' : ($p2['skor_komunikasi'] >= 12 ? 'Cukup' : 'Kurang') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>3. Posisi dan Pergerakan</td>
                                                    <td>{{ $totalP3 }} / 25</td>
                                                    <td>{{ $p3['skor_posisi'] }} / 15</td>
                                                    <td>{{ $p3['skor_posisi'] >= 11.25 ? 'Baik' : ($p3['skor_posisi'] >= 9 ? 'Cukup' : 'Kurang') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>4. Pengendalian Pertandingan</td>
                                                    <td>{{ $totalP4 }} / 25</td>
                                                    <td>{{ $p4['skor_pengendalian'] }} / 20</td>
                                                    <td>{{ $p4['skor_pengendalian'] >= 15 ? 'Baik' : ($p4['skor_pengendalian'] >= 12 ? 'Cukup' : 'Kurang') }}</td>
                                                </tr>
                                                <tr>
                                                    <td>5. Sikap dan Profesionalisme</td>
                                                    <td>{{ $totalP5 }} / 25</td>
                                                    <td>{{ $p5['skor_profesionalisme'] }} / 15</td>
                                                    <td>{{ $p5['skor_profesionalisme'] >= 11.25 ? 'Baik' : ($p5['skor_profesionalisme'] >= 9 ? 'Cukup' : 'Kurang') }}</td>
                                                </tr>
                                                <tr style="background:#ffd700; font-weight:bold;">
                                                    <td>TOTAL SKOR KOMPETENSI</td>
                                                    <td>-</td>
                                                    <td>{{ $total_score }} / 100</td>
                                                    <td>{{ $category }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <h3 style="font-size:14px; font-weight:bold; margin-top:25px;">✅ KELEBIHAN (Strengths)</h3>
                                        <div style="border:1px solid #ddd; padding:10px; border-radius:6px; min-height:60px; background:#fafafa;">
                                            {!! nl2br(e($kelebihan)) ?: '<em>Tidak ada catatan kelebihan.</em>' !!}
                                        </div>

                                        <h3 style="font-size:14px; font-weight:bold; margin-top:15px;">⚠️ AREA YANG PERLU DIPERHATIKAN (Improvements)</h3>
                                        <div style="border:1px solid #ddd; padding:10px; border-radius:6px; min-height:60px; background:#fafafa;">
                                            {!! nl2br(e($area_perbaikan)) ?: '<em>Tidak ada catatan perbaikan.</em>' !!}
                                        </div>

                                        <h3 style="font-size:14px; font-weight:bold; margin-top:15px;">💡 REKOMENDASI</h3>
                                        <div style="border:1px solid #ddd; padding:10px; border-radius:6px; min-height:60px; background:#fafafa;">
                                            {!! nl2br(e($rekomendasi)) ?: '<em>Tidak ada catatan rekomendasi.</em>' !!}
                                        </div>

                                        <div class="signature" style="margin-top:35px;">
                                            <div>
                                                <div>Observer / Pengamat</div>
                                                <div style="margin-top: 50px; font-weight:bold; text-decoration:underline;">{{ $dari }}</div>
                                            </div>
                                            <div>
                                                <div>Manager Kontingen</div>
                                                <div style="margin-top: 50px; border-bottom:1px solid #000; width:150px; margin-left:auto; margin-right:auto;"></div>
                                                <div>Stempel & Nama Jelas</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- HALAMAN 6 -->
                            <div class="form-page">
                                <div class="header">
                                    <h1>CHECKLIST ETIKA OBSERVASI</h1>
                                    <div>Pernyataan Kepatuhan Observer</div>
                                </div>
                                <div class="section">
                                    <div class="section-content">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th width="85%">Konfirmasi Butir Kepatuhan</th>
                                                    <th width="15%" style="text-align:center;">Persetujuan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($etika_items as $idx => $label)
                                                <tr>
                                                    <td>{{ $label }}</td>
                                                    <td style="text-align:center; font-weight:bold; color:green;">
                                                        {{ !empty($etika['checks'][$idx]) ? 'SETUJU' : 'TIDAK' }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <div style="border:1px solid #ddd; padding:15px; border-radius:6px; margin:20px 0; background:#f9f9f9; line-height:1.5; font-size:13px;">
                                            <strong>PERNYATAAN RESMI:</strong><br>
                                            Saya menyatakan bahwa observasi ini dilakukan secara profesional, objektif, dan sesuai dengan kode etik yang berlaku dalam kejuaraan Shorinji Kempo. Hasil observasi ini akan digunakan untuk evaluasi internal kontingen, bukan untuk menekan atau mendelegitimasi wasit.
                                        </div>

                                        <div style="margin-top:15px; font-weight:bold; color:{{ $etika['pernyataan'] ? 'green' : 'red' }}; text-align:center; border: 1px solid #ddd; padding:10px; border-radius:6px;">
                                            STATUS PERNYATAAN: {{ $etika['pernyataan'] ? '✓ TELAH DISETUJUI & DITANDATANGANI SECARA DIGITAL' : '✗ BELUM DISETUJUI' }}
                                        </div>

                                        <div class="signature" style="margin-top:40px;">
                                            <div>
                                                <div>Observer / Pengamat</div>
                                                <div style="margin-top: 50px; font-weight:bold; text-decoration:underline;">{{ $dari }}</div>
                                            </div>
                                            <div>
                                                <div>Manager Kontingen</div>
                                                <div style="margin-top: 50px; border-bottom:1px solid #000; width:150px; margin-left:auto; margin-right:auto;"></div>
                                                <div>Tanda Tangan & Stempel</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    @endif
                </div>

                {{-- ── SIDE BAR PREVIEW & SUBMIT ── --}}
                <div>
                    <div class="card-prem">
                        <div class="card-title" style="margin-bottom:12px; padding-bottom:6px;">Hasil Penilaian</div>
                        
                        <div class="score-summary-box">
                            <div class="score-summary-title">Skor Akhir</div>
                            <div class="score-val">{{ number_format($total_score, 0) }}</div>
                            <div class="score-cat-badge {{ str_replace(' ', '_', strtolower($category)) }}">
                                {{ $category }}
                            </div>
                        </div>

                        <div style="display:flex; flex-direction:column; gap:10px;">
                            <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0; border-bottom:1px solid var(--paper2);">
                                <span>P1: Konsistensi</span>
                                <span style="font-weight:700;">{{ $p1['skor_konsistensi'] }} / 30</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0; border-bottom:1px solid var(--paper2);">
                                <span>P2: Komunikasi</span>
                                <span style="font-weight:700;">{{ $p2['skor_komunikasi'] }} / 20</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0; border-bottom:1px solid var(--paper2);">
                                <span>P3: Positioning</span>
                                <span style="font-weight:700;">{{ $p3['skor_posisi'] }} / 15</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0; border-bottom:1px solid var(--paper2);">
                                <span>P4: Pengendalian</span>
                                <span style="font-weight:700;">{{ $p4['skor_pengendalian'] }} / 20</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0; border-bottom:1px solid var(--paper2);">
                                <span>P5: Sikap Juri</span>
                                <span style="font-weight:700;">{{ $p5['skor_profesionalisme'] }} / 15</span>
                            </div>
                        </div>

                        @if($mode !== 'view')
                        <div style="margin-top:24px; display:flex; flex-direction:column; gap:10px;">
                            <button type="submit" class="btn-save-prem">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Simpan Observasi
                            </button>
                            <a href="{{ route('contingent.observasi-wasit.index') }}" class="btn-cancel-prem">
                                Batal
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- ══════════════════════════════════════════════════════
       PRINT ONLY DOM TEMPLATE (HIDDEN ON SCREEN, SHOW ON PRINT)
    ══════════════════════════════════════════════════════ --}}
    @if($mode === 'view' || $mode === 'edit')
    <div class="print-wrapper print-only">
        
        <!-- HALAMAN 1 -->
        <div class="form-page">
            <div class="header">
                <h1>FORMULIR OBSERVASI WASIT</h1>
                <h2>Untuk Manager dan Official Kontingen</h2>
                <div class="event">⚡ SMART PERKEMI ⚡</div>
                <div style="font-size: 13px; font-weight:bold; margin-top:5px;">Kejuaraan Piala Walikota Surabaya 2026 - Shorinji Kempo</div>
            </div>

            <div class="section">
                <div class="section-title">A. IDENTITAS PENGAMAT & WASIT</div>
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-field"><label>Nama Manager/Official</label><div class="value">{{ $observer_name }}</div></div>
                        <div class="info-field"><label>Kontingen</label><div class="value">{{ $contingent ? $contingent->name : '-' }}</div></div>
                    </div>
                    <div class="info-row">
                        <div class="info-field"><label>Tanggal Observasi</label><div class="value">{{ $observation_date ? date('d M Y', strtotime($observation_date)) : '' }}</div></div>
                        <div class="info-field"><label>Court</label><div class="value">{{ $court }}</div></div>
                    </div>
                    <div class="info-row">
                        <div class="info-field"><label>Babak Pertandingan</label><div class="value">{{ $round }}</div></div>
                        <div class="info-field"><label>Waktu Pertandingan</label><div class="value">{{ $match_time }} WIB</div></div>
                    </div>
                    <div class="info-row">
                        <div class="info-field">
                            <label>Nama Wasit yang Diamati</label>
                            <div class="value">
                                @php
                                    $refObj = $referees->firstWhere('id', $referee_id);
                                @endphp
                                {{ $refObj ? $refObj->name : '-' }}
                            </div>
                        </div>
                        <div class="info-field"><label>Nomor Juri / Wasit</label><div class="value">Juri {{ $referee_number }}</div></div>
                    </div>
                    <div class="info-row">
                        <div class="info-field"><label>Kontingen Biru (Away)</label><div class="value">{{ $contingent_away ?: '-' }}</div></div>
                        <div class="info-field"><label>Kontingen Putih (Home)</label><div class="value">{{ $contingent_home ?: '-' }}</div></div>
                    </div>
                </div>
            </div>

            <!-- PARAMETER 1 -->
            <div class="section">
                <div class="section-title">📊 PARAMETER 1: KONSISTENSI KEPUTUSAN (Bobot 30%)</div>
                <div class="section-content">
                    <table>
                        <thead>
                            <tr>
                                <th width="10%">No.</th>
                                <th width="20%">Waktu</th>
                                <th width="40%">Jenis Kejadian</th>
                                <th width="20%">Keputusan Wasit</th>
                                <th width="10%">Konsisten?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($p1['rows'] as $idx => $row)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $row['waktu'] ?: '-' }}</td>
                                <td>{{ $row['jenis'] ?: '-' }}</td>
                                <td>{{ $row['keputusan'] ?: '-' }}</td>
                                <td>{{ $row['konsisten'] ? strtoupper($row['konsisten']) : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="info-row">
                        <div class="info-field"><label>Jumlah keputusan konsisten</label><div class="value">{{ $p1['jumlah_konsisten'] }} dari 6 kejadian</div></div>
                        <div class="info-field"><label>Skor Konsistensi (x30%)</label><div class="value">{{ $p1['skor_konsistensi'] }} / 30</div></div>
                    </div>
                    @if(!empty($p1['catatan']))
                    <div><label style="font-weight:bold;">Catatan Khusus:</label><div class="value" style="border:1px solid #ddd; padding:8px; min-height:40px;">{{ $p1['catatan'] }}</div></div>
                    @endif
                </div>
            </div>

            <!-- PARAMETER 2 -->
            <div class="section">
                <div class="section-title">📢 PARAMETER 2: KEJELASAN KOMUNIKASI (Bobot 20%)</div>
                <div class="section-content">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Indikator</th>
                                <th style="text-align:center;">Skor (1-5)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($p2_indicators as $idx => $label)
                            <tr>
                                <td>{{ $idx }}</td>
                                <td>{{ $label }}</td>
                                <td style="text-align:center; font-weight:bold;">{{ $p2['ratings'][$idx] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="info-row">
                        <div class="info-field"><label>Total Skor (maks 30)</label><div class="value">{{ $p2['total_skor'] }} ({{ $p2['nilai_persen'] }}%)</div></div>
                        <div class="info-field"><label>Skor Komunikasi (x20%)</label><div class="value">{{ $p2['skor_komunikasi'] }} / 20</div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HALAMAN 2 -->
        <div class="form-page page-break">
            <div class="header">
                <h1>EVENT RECORDING SHEET</h1>
                <div>Pencatatan Kejadian Spesifik Wasit</div>
            </div>
            <div class="section">
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-field"><label>Court</label><div class="value">{{ $court }}</div></div>
                        <div class="info-field"><label>Babak</label><div class="value">{{ $round }}</div></div>
                        <div class="info-field"><label>Waktu Pertandingan</label><div class="value">{{ $match_time }} WIB</div></div>
                    </div>
                    <table>
                        <thead>
                            <tr style="background:#1a3a4a; color:white;">
                                <th width="8%">No.</th>
                                <th width="15%">Waktu</th>
                                <th width="40%">Jenis Kejadian</th>
                                <th width="25%">Keputusan Wasit</th>
                                <th width="12%">Evaluasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($event_recording['rows'] as $idx => $row)
                            <tr>
                                <td>{{ $idx + 1 }}</td>
                                <td>{{ $row['waktu'] ?: '-' }}</td>
                                <td>{{ $row['jenis'] ?: '-' }}</td>
                                <td>{{ $row['keputusan'] ?: '-' }}</td>
                                <td>{{ $row['evaluasi'] ? ($row['evaluasi'] == 'ya' ? 'Tepat' : 'Tidak') : '-' }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4"><strong>Total Kejadian Tercatat:</strong> {{ $event_recording['total_kejadian'] }}</td>
                                <td><strong>Tepat:</strong> {{ $event_recording['total_tepat'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- HALAMAN 3 -->
        <div class="form-page page-break">
            <div class="header">
                <h1>TIME SAMPLING SHEET</h1>
                <div>Observasi Berkala Per 5 Menit</div>
            </div>
            <div class="section">
                <div class="section-content">
                    <table>
                        <thead>
                            <tr style="background:#1a3a4a; color:white;">
                                <th>Interval (Menit ke-)</th>
                                <th>Posisi Wasit</th>
                                <th>Komunikasi Wasit</th>
                                <th>Pengendalian Pertandingan</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($time_sampling['rows'] as $row)
                            <tr>
                                <td>{{ $row['interval'] }}</td>
                                <td>{{ $row['posisi'] }}</td>
                                <td>{{ $row['komunikasi'] }}</td>
                                <td>{{ $row['pengendalian'] }}</td>
                                <td>{{ $row['catatan'] ?: '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- HALAMAN 4 -->
        <div class="form-page page-break">
            <div class="header">
                <h1>RATING SCALE SHEET</h1>
                <div>Skala Penilaian Kinerja Wasit</div>
            </div>
            <div class="section">
                <div class="section-content">
                    <table>
                        <thead>
                            <tr style="background:#1a3a4a; color:white;">
                                <th width="10%">No.</th>
                                <th width="70%">Indikator Penilaian</th>
                                <th width="20%" style="text-align:center;">Skor (1-5)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($scale_indicators as $idx => $label)
                            <tr>
                                <td>{{ $idx }}</td>
                                <td>{{ $label }}</td>
                                <td style="text-align:center; font-weight:bold;">{{ $rating_scale['ratings'][$idx] ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="score-total">
                        <strong>Total Skor: {{ $rating_scale['total_skor'] }} / 60 → Nilai Akhir: {{ $rating_scale['nilai_akhir'] }} / 100</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- HALAMAN 5 -->
        <div class="form-page page-break">
            <div class="header">
                <h1>LAPORAN OBSERVASI WASIT</h1>
                <div>Untuk Keperluan Evaluasi Kontingen</div>
            </div>
            <div class="section">
                <div class="section-content">
                    <div class="info-row">
                        <div class="info-field"><label>Kepada</label><div class="value">{{ $kepada }}</div></div>
                        <div class="info-field"><label>Dari</label><div class="value">{{ $dari }}</div></div>
                        <div class="info-field"><label>Tanggal Laporan</label><div class="value">{{ $tanggal_laporan ? date('d M Y', strtotime($tanggal_laporan)) : '' }}</div></div>
                    </div>

                    <h3 style="font-size:14px; font-weight:bold; margin-top:20px; border-bottom:1px solid #ddd; padding-bottom:5px;">📊 RINGKASAN SKOR EFEKTIF</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Parameter Kompetensi</th>
                                <th>Skor Capaian</th>
                                <th>Bobot</th>
                                <th>Kategori</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1. Konsistensi Keputusan</td>
                                <td>{{ $p1['jumlah_konsisten'] }} / 6</td>
                                <td>{{ $p1['skor_konsistensi'] }} / 30</td>
                                <td>{{ $p1['skor_konsistensi'] >= 22.5 ? 'Baik' : ($p1['skor_konsistensi'] >= 18 ? 'Cukup' : 'Kurang') }}</td>
                            </tr>
                            <tr>
                                <td>2. Kejelasan Komunikasi</td>
                                <td>{{ $p2['total_skor'] }} / 30</td>
                                <td>{{ $p2['skor_komunikasi'] }} / 20</td>
                                <td>{{ $p2['skor_komunikasi'] >= 15 ? 'Baik' : ($p2['skor_komunikasi'] >= 12 ? 'Cukup' : 'Kurang') }}</td>
                            </tr>
                            <tr>
                                <td>3. Posisi dan Pergerakan</td>
                                <td>{{ $totalP3 }} / 25</td>
                                <td>{{ $p3['skor_posisi'] }} / 15</td>
                                <td>{{ $p3['skor_posisi'] >= 11.25 ? 'Baik' : ($p3['skor_posisi'] >= 9 ? 'Cukup' : 'Kurang') }}</td>
                            </tr>
                            <tr>
                                <td>4. Pengendalian Pertandingan</td>
                                <td>{{ $totalP4 }} / 25</td>
                                <td>{{ $p4['skor_pengendalian'] }} / 20</td>
                                <td>{{ $p4['skor_pengendalian'] >= 15 ? 'Baik' : ($p4['skor_pengendalian'] >= 12 ? 'Cukup' : 'Kurang') }}</td>
                            </tr>
                            <tr>
                                <td>5. Sikap dan Profesionalisme</td>
                                <td>{{ $totalP5 }} / 25</td>
                                <td>{{ $p5['skor_profesionalisme'] }} / 15</td>
                                <td>{{ $p5['skor_profesionalisme'] >= 11.25 ? 'Baik' : ($p5['skor_profesionalisme'] >= 9 ? 'Cukup' : 'Kurang') }}</td>
                            </tr>
                            <tr style="background:#ffd700; font-weight:bold;">
                                <td>TOTAL SKOR KOMPETENSI</td>
                                <td>-</td>
                                <td>{{ $total_score }} / 100</td>
                                <td>{{ $category }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 style="font-size:14px; font-weight:bold; margin-top:25px;">✅ KELEBIHAN (Strengths)</h3>
                    <div style="border:1px solid #ddd; padding:10px; border-radius:6px; min-height:80px; background:#fafafa;">
                        {!! nl2br(e($kelebihan)) ?: '<em>Tidak ada catatan kelebihan.</em>' !!}
                    </div>

                    <h3 style="font-size:14px; font-weight:bold; margin-top:15px;">⚠️ AREA YANG PERLU DIPERHATIKAN (Improvements)</h3>
                    <div style="border:1px solid #ddd; padding:10px; border-radius:6px; min-height:80px; background:#fafafa;">
                        {!! nl2br(e($area_perbaikan)) ?: '<em>Tidak ada catatan perbaikan.</em>' !!}
                    </div>

                    <h3 style="font-size:14px; font-weight:bold; margin-top:15px;">💡 REKOMENDASI</h3>
                    <div style="border:1px solid #ddd; padding:10px; border-radius:6px; min-height:80px; background:#fafafa;">
                        {!! nl2br(e($rekomendasi)) ?: '<em>Tidak ada catatan rekomendasi.</em>' !!}
                    </div>

                    <div class="signature" style="margin-top:45px;">
                        <div>
                            <div>Observer / Pengamat</div>
                            <div style="margin-top: 50px; font-weight:bold; text-decoration:underline;">{{ $dari }}</div>
                        </div>
                        <div>
                            <div>Manager Kontingen</div>
                            <div style="margin-top: 50px; border-bottom:1px solid #000; width:150px; margin-left:auto; margin-right:auto;"></div>
                            <div>Stempel & Nama Jelas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HALAMAN 6 -->
        <div class="form-page page-break">
            <div class="header">
                <h1>CHECKLIST ETIKA OBSERVASI</h1>
                <div>Pernyataan Kepatuhan Observer</div>
            </div>
            <div class="section">
                <div class="section-content">
                    <table>
                        <thead>
                            <tr>
                                <th width="85%">Konfirmasi Butir Kepatuhan</th>
                                <th width="15%" style="text-align:center;">Persetujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($etika_items as $idx => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td style="text-align:center; font-weight:bold; color:green;">
                                    {{ !empty($etika['checks'][$idx]) ? 'SETUJU' : 'TIDAK' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="border:1px solid #ddd; padding:15px; border-radius:6px; margin:25px 0; background:#f9f9f9; line-height:1.5; font-size:13px;">
                        <strong>PERNYATAAN RESMI:</strong><br>
                        Saya menyatakan bahwa observasi ini dilakukan secara profesional, objektif, dan sesuai dengan kode etik yang berlaku dalam kejuaraan Shorinji Kempo. Hasil observasi ini akan digunakan untuk evaluasi internal kontingen, bukan untuk menekan atau mendelegitimasi wasit.
                    </div>

                    <div style="margin-top:15px; font-weight:bold; color:{{ $etika['pernyataan'] ? 'green' : 'red' }}; text-align:center; border: 1px solid #ddd; padding:10px; border-radius:6px;">
                        STATUS PERNYATAAN: {{ $etika['pernyataan'] ? '✓ TELAH DISETUJUI & DITANDATANGANI SECARA DIGITAL' : '✗ BELUM DISETUJUI' }}
                    </div>

                    <div class="signature" style="margin-top:45px;">
                        <div>
                            <div>Observer / Pengamat</div>
                            <div style="margin-top: 50px; font-weight:bold; text-decoration:underline;">{{ $dari }}</div>
                        </div>
                        <div>
                            <div>Manager Kontingen</div>
                            <div style="margin-top: 50px; border-bottom:1px solid #000; width:150px; margin-left:auto; margin-right:auto;"></div>
                            <div>Tanda Tangan & Stempel</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
