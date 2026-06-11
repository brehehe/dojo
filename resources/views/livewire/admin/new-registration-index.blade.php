<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Data Registrasi (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .prem-page { background: var(--paper); color: var(--ink); padding: 28px; }
    .cinzel { font-family: 'Cinzel', serif; }

    /* ── PAGE HEADER ── */
    .page-hdr { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .page-hdr-left h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .page-hdr-left p  { font-size: 12px; color: var(--smoke); margin: 0; }
    .page-hdr-right { display: flex; gap: 8px; flex-wrap: wrap; }

    /* ── STAT CARDS ── */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
    .stat-card {
      background: #fff; border-radius: 16px; padding: 18px 20px;
      border: 1px solid var(--paper2); position: relative; overflow: hidden;
      transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(0,0,0,.08); }
    .stat-card::after {
      content: ''; position: absolute; bottom: 0; right: 0;
      width: 70px; height: 70px; border-radius: 50%; opacity: .08;
      transform: translate(20px, 20px);
    }
    .stat-card.red::after   { background: var(--red); }
    .stat-card.gold::after  { background: var(--gold); }
    .stat-card.green::after { background: #27ae60; }
    .stat-card.blue::after  { background: #3498db; }
    .stat-icon {
      width: 38px; height: 38px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; margin-bottom: 10px;
    }
    .stat-card.red   .stat-icon { background: rgba(192,57,43,.1); color: var(--red); }
    .stat-card.gold  .stat-icon { background: rgba(212,168,67,.12); color: #b8860b; }
    .stat-card.green .stat-icon { background: rgba(39,174,96,.1); color: #27ae60; }
    .stat-card.blue  .stat-icon { background: rgba(52,152,219,.1); color: #2980b9; }
    .stat-label { font-size: 10px; color: var(--smoke); font-weight: 500; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 4px; }
    .stat-value { font-family: 'Cinzel', serif; font-size: 24px; font-weight: 700; line-height: 1; }

    /* ── TABLE SECTION ── */
    .table-section { background: #fff; border-radius: 16px; border: 1px solid var(--paper2); overflow: hidden; }
    .table-toolbar {
      padding: 16px 20px; display: flex; align-items: center; gap: 10px;
      border-bottom: 1px solid var(--paper2); flex-wrap: wrap;
    }
    .table-toolbar h3 { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; flex: 1; min-width: 120px; margin: 0; }

    /* ── FILTER TABS ── */
    .filter-tabs { display: flex; gap: 3px; background: var(--paper); border-radius: 9px; padding: 3px; }
    .filter-tab {
      padding: 5px 13px; border-radius: 7px; border: none; background: none;
      font-size: 11.5px; font-family: 'DM Sans', sans-serif;
      cursor: pointer; color: var(--smoke); font-weight: 500; transition: all .15s;
    }
    .filter-tab.active { background: var(--ink); color: #fff; font-weight: 600; }

    /* ── SEARCH ── */
    .search-field {
      display: flex; align-items: center; gap: 7px;
      background: var(--paper); border: 1px solid var(--paper2);
      border-radius: 9px; padding: 7px 12px; flex: 1; max-width: 300px;
    }
    .search-field i { color: var(--smoke); font-size: 11px; }
    .search-field input {
      background: none; border: none; outline: none; flex: 1;
      font-size: 12.5px; color: var(--ink); font-family: 'DM Sans', sans-serif;
    }
    .search-field input::placeholder { color: var(--smoke); }

    /* ── PER PAGE ── */
    .perpage-select {
      padding: 7px 10px; border: 1px solid var(--paper2); border-radius: 9px;
      font-size: 12px; background: #fff; color: var(--ink); font-family: 'DM Sans', sans-serif;
      cursor: pointer; outline: none;
    }

    /* ── TABLE ── */
    .prem-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .prem-table { width: 100%; border-collapse: collapse; min-width: 720px; }
    .prem-table thead th {
      padding: 10px 16px; font-size: 10px; color: var(--smoke); font-weight: 600;
      letter-spacing: .08em; text-transform: uppercase;
      text-align: left; background: var(--paper);
      border-bottom: 1px solid var(--paper2); white-space: nowrap;
    }
    .prem-table thead th:first-child { padding-left: 22px; }
    .prem-table thead th:last-child  { padding-right: 22px; text-align: center; }
    .prem-table tbody tr { transition: background .12s; }
    .prem-table tbody tr:hover { background: rgba(247,244,239,.7); }
    .prem-table tbody td { padding: 13px 16px; font-size: 13px; border-bottom: 1px solid var(--paper2); color: var(--ink); }
    .prem-table tbody tr:last-child td { border-bottom: none; }
    .prem-table td:first-child { padding-left: 22px; }
    .prem-table td:last-child  { padding-right: 22px; text-align: center; }

    /* ── BADGE ── */
    .badge-prem { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 10.5px; font-weight: 600; white-space: nowrap; }
    .badge-prem.green  { background: rgba(39,174,96,.12); color: #1e8449; }
    .badge-prem.gold   { background: rgba(212,168,67,.15); color: #9a6e00; }
    .badge-prem.red    { background: rgba(192,57,43,.12); color: var(--red); }
    .badge-prem .dot { width: 5px; height: 5px; border-radius: 50%; }
    .badge-prem.green  .dot { background: #27ae60; }
    .badge-prem.gold   .dot { background: #d4a843; animation: pulse 1.5s infinite; }
    .badge-prem.red    .dot { background: var(--red); }

    /* ── ACTION BTNS ── */
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
    .act-btn.danger { color: var(--red); border-color: rgba(192,57,43,.2); background: rgba(192,57,43,.05); }
    .act-btn.danger:hover { background: rgba(192,57,43,.12); border-color: var(--red); }

    /* ── AVATAR ── */
    .mini-avatar {
      width: 28px; height: 28px; border-radius: 7px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 10px; font-weight: 700; color: #fff;
      background: linear-gradient(135deg, var(--red), #e67e22);
    }


    /* ── EMPTY ── */
    .empty-state { padding: 64px 22px; text-align: center; }
    .empty-icon { width: 56px; height: 56px; background: var(--paper); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--smoke); font-size: 20px; }
    .empty-state p { font-size: 13px; color: var(--smoke); }

    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }

    /* ── BULK ACTIONS ── */
    .bulk-actions-bar {
        background: rgba(247, 244, 239, 0.95);
        border-bottom: 1px solid var(--paper2);
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        animation: slideDown 0.2s ease;
    }
    @keyframes slideDown {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .bulk-btn {
        padding: 8px 16px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
        border: 1px solid transparent;
        font-family: 'DM Sans', sans-serif;
    }
    .bulk-btn.success {
        background: rgba(39, 174, 96, 0.08);
        color: #1e8449;
        border-color: rgba(39, 174, 96, 0.2);
    }
    .bulk-btn.success:hover {
        background: #27ae60;
        color: #fff;
        border-color: #27ae60;
    }
    .bulk-btn.warning {
        background: rgba(212, 168, 67, 0.12);
        color: #9a6e00;
        border-color: rgba(212, 168, 67, 0.25);
    }
    .bulk-btn.warning:hover {
        background: #d4a843;
        color: #fff;
        border-color: #d4a843;
    }
    .checkbox-col {
        width: 40px;
        text-align: center !important;
    }
    .prem-checkbox {
        width: 15px;
        height: 15px;
        border: 1.5px solid var(--smoke);
        border-radius: 4px;
        cursor: pointer;
        accent-color: var(--red);
        transition: all 0.15s;
        vertical-align: middle;
    }

    /* ── EXPANDED SECTION ── */
    .expanded-row td { background: rgba(247,244,239,.35); padding: 18px 22px !important; border-bottom: 2px solid var(--paper2) !important; }
    .expanded-card { background: #fff; border-radius: 12px; border: 1px solid var(--paper2); padding: 16px; box-shadow: inset 0 2px 8px rgba(0,0,0,.02); }
    .expanded-title { font-family: 'Cinzel', serif; font-size: 12px; font-weight: 700; margin-bottom: 12px; color: var(--ink); border-bottom: 1px solid var(--paper); padding-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }

    /* ── INNER ATHLETE TABLE ── */
    .ath-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .ath-table th { font-size: 10px; color: var(--smoke); text-transform: uppercase; letter-spacing: .05em; padding: 8px 10px; text-align: left; background: var(--paper); border-bottom: 1px solid var(--paper2); }
    .ath-table td { padding: 9px 10px; font-size: 12.5px; border-bottom: 1px solid var(--paper2); }
    .ath-table tr:last-child td { border-bottom: none; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; } .prem-page { padding: 14px; } }
    @media (max-width: 400px)  { .stats-grid { grid-template-columns: 1fr; } }
    </style>
    @endpush

    <div class="prem-page">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-hdr">
            <div class="page-hdr-left">
                <h2>Data Registrasi</h2>
                <p>Kelola dan verifikasi seluruh pendaftaran kontingen</p>
            </div>
            <div class="page-hdr-right">
                <a href="{{ route('admin.registrations.index') }}" class="act-btn" title="Tampilan Lama" style="width:auto;padding:0 12px;gap:6px;font-size:11.5px;color:var(--smoke);">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        </div>

        {{-- ── STAT CARDS ── --}}
        <div class="stats-grid">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-file-invoice"></i></div>
                <div class="stat-label">Total Pendaftaran</div>
                <div class="stat-value" style="color:var(--red)">{{ number_format($stats['total']) }}</div>
            </div>
            <div class="stat-card gold">
                <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-label">Menunggu Verifikasi</div>
                <div class="stat-value" style="color:#b8860b">{{ number_format($stats['pending']) }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-label">Terverifikasi</div>
                <div class="stat-value" style="color:#27ae60">{{ number_format($stats['verified']) }}</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fa-solid fa-wallet"></i></div>
                <div class="stat-label">Total Pendapatan</div>
                <div class="stat-value" style="color:#2980b9;font-size:16px;">Rp {{ number_format($stats['total_amount'], 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- ── TABLE ── --}}
        <div class="table-section">
            <div class="table-toolbar">
                <h3>Daftar Registrasi</h3>

                {{-- Filter Tabs --}}
                <div class="filter-tabs">
                    <button class="filter-tab {{ $status === '' ? 'active' : '' }}" wire:click="$set('status', '')">Semua</button>
                    <button class="filter-tab {{ $status === 'pending' ? 'active' : '' }}" wire:click="$set('status', 'pending')">Pending</button>
                    <button class="filter-tab {{ $status === 'verified' ? 'active' : '' }}" wire:click="$set('status', 'verified')">Verified</button>
                    <button class="filter-tab {{ $status === 'rejected' ? 'active' : '' }}" wire:click="$set('status', 'rejected')">Rejected</button>
                </div>

                {{-- Search --}}
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kontingen atau kode...">
                </div>

                {{-- Per Page --}}
                <select wire:model.live="perPage" class="perpage-select">
                    <option value="10">10 baris</option>
                    <option value="25">25 baris</option>
                    <option value="50">50 baris</option>
                </select>
            </div>

            {{-- Bulk Actions Bar --}}
            @if(count($selectedRows) > 0)
            <div class="bulk-actions-bar">
                <div style="font-size: 13.5px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-square-check" style="color: var(--red); font-size: 15px;"></i>
                    <span>Terpilih <strong style="color: var(--red);">{{ count($selectedRows) }}</strong> data registrasi</span>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button onclick="confirmBulkVerify()" class="bulk-btn success">
                        <i class="fa-solid fa-circle-check"></i> Verifikasi Terpilih
                    </button>
                    <button onclick="confirmBulkUnverify()" class="bulk-btn warning">
                        <i class="fa-solid fa-clock-rotate-left"></i> Set Pending Terpilih
                    </button>
                </div>
            </div>
            @endif

            <div class="prem-table-wrap">
                <table class="prem-table">
                    <thead>
                        <tr>
                            <th class="checkbox-col">
                                <input type="checkbox" wire:model.live="selectAll" class="prem-checkbox">
                            </th>
                            <th>#</th>
                            <th>Kontingen / Kode</th>
                            <th>Wilayah</th>
                            <th style="text-align:center">Peserta</th>
                            <th>Total Bayar</th>
                            <th>Tgl Daftar</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                        @php
                            $isExpanded = in_array($reg->id, $expanded, true);
                        @endphp
                        <tr wire:key="reg-row-{{ $reg->id }}">
                            <td class="checkbox-col">
                                <input type="checkbox" wire:model.live="selectedRows" value="{{ $reg->id }}" class="prem-checkbox">
                            </td>
                            <td style="color:var(--smoke);font-size:11px;">{{ $loop->iteration + $registrations->firstItem() - 1 }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:9px;">
                                    <div class="mini-avatar">{{ substr($reg->contingent->name ?? 'K', 0, 2) }}</div>
                                    <div>
                                        <div style="font-weight:600;font-size:13px;">{{ $reg->contingent->name ?? '-' }}</div>
                                        <div style="font-size:11px;color:var(--smoke);font-family:monospace;">{{ $reg->referral_code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size:12px;color:var(--smoke);">{{ $reg->contingent->kab_kota ?? '-' }}</td>
                            <td style="text-align:center;">
                                <div style="font-size:12px;">
                                    <span style="font-weight:600;">{{ $reg->athletes()->count() }}</span>
                                    <span style="color:var(--smoke);font-size:11px;"> Atlet</span><br>
                                    <span style="font-weight:600;">{{ $reg->officials()->count() }}</span>
                                    <span style="color:var(--smoke);font-size:11px;"> Official</span>
                                </div>
                            </td>
                            <td style="font-weight:600;">Rp {{ number_format($reg->final_amount, 0, ',', '.') }}</td>
                            <td style="font-size:12px;color:var(--smoke);">{{ $reg->created_at->format('d M Y') }}</td>
                            <td style="text-align:center;">
                                @php
                                    $badgeClass = match($reg->status) {
                                        'verified' => 'green',
                                        'pending'  => 'gold',
                                        default    => 'red'
                                    };
                                @endphp
                                <span class="badge-prem {{ $badgeClass }}">
                                    <span class="dot"></span>
                                    {{ strtoupper($reg->status) }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:5px;justify-content:center;">
                                    <button wire:click="toggleExpand({{ $reg->id }})" class="act-btn {{ $isExpanded ? 'view' : '' }}" title="Lihat Atlet" style="color:{{ $isExpanded ? '#2980b9' : '#888' }};">
                                        <i class="fa-solid {{ $isExpanded ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                    </button>
                                    <a href="{{ route('admin.new-registrations.show', $reg->id) }}" class="act-btn view" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button onclick="confirmDeleteRegistration({{ $reg->id }}, '{{ addslashes($reg->contingent->name ?? 'ini') }}')" class="act-btn danger" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- ── EXPANDED ATHLETE ROWS ── --}}
                        @if($isExpanded)
                        <tr class="expanded-row" wire:key="reg-expanded-{{ $reg->id }}">
                            <td colspan="9">
                                <div class="expanded-card">
                                    <div class="expanded-title">
                                        <span><i class="fa-solid fa-users" style="margin-right:6px;"></i>Daftar Atlet — {{ $reg->contingent->name }}</span>
                                        <span style="font-size:11px;font-family:'DM Sans',sans-serif;font-weight:500;color:var(--smoke);">
                                            {{ $reg->athletes->count() }} Atlet &nbsp;|&nbsp; {{ $reg->officials->count() }} Official
                                        </span>
                                    </div>

                                    <div style="overflow-x:auto;">
                                        <table class="ath-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama / NIK</th>
                                                    <th>Gender</th>
                                                    <th>Kelompok Umur</th>
                                                    <th>Dojo</th>
                                                    <th>BB (Kg)</th>
                                                    <th>Rank / Kyu</th>
                                                    <th>Nomor Tanding</th>
                                                    <th>BPJS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($reg->athletes as $idx => $ath)
                                                <tr wire:key="ath-{{ $reg->id }}-{{ $ath->id }}">
                                                    <td style="color:var(--smoke);font-size:11px;">{{ $idx + 1 }}</td>
                                                    <td>
                                                        <div style="font-weight:600;">{{ $ath->name }}</div>
                                                        <div style="font-size:11px;color:var(--smoke);font-family:monospace;">NIK: {{ $ath->nik ?: '-' }} | Kenshi: {{ $ath->nik_kenshi ?: '-' }}</div>
                                                    </td>
                                                    <td>{{ $ath->gender === 'Male' ? '🧍 L' : '🧍‍♀️ P' }}</td>
                                                    <td>
                                                        <span class="badge-prem gold" style="font-size:10px;">{{ $ath->pivot->age_group ?: '-' }}</span>
                                                    </td>
                                                    <td style="font-size:12px;">{{ $ath->pivot->dojo_origin ?: '-' }}</td>
                                                    <td style="font-weight:600;">{{ $ath->pivot->weight ? $ath->pivot->weight . ' kg' : '-' }}</td>
                                                    <td>
                                                        <span class="badge-prem green" style="font-size:10px;">{{ $ath->pivot->rank ?: '-' }}</span>
                                                    </td>
                                                    <td>
                                                        <div style="font-size:11.5px;max-width:220px;">
                                                            @php
                                                                $athMatches = $ath->matchNumbers->filter(fn($m) => $m->pivot->registration_id == $reg->id);
                                                            @endphp
                                                            @forelse($athMatches as $m)
                                                                <div style="margin-bottom:2px;"><span style="color:var(--smoke)">•</span> {{ $m->name }}</div>
                                                            @empty
                                                                <span style="color:var(--red);font-style:italic;font-size:11px;">Belum terdaftar</span>
                                                            @endforelse
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($ath->bpjs_status === 'Aktif')
                                                            <span style="color:#27ae60;font-weight:600;font-size:11.5px;"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                                        @else
                                                            <span style="color:var(--red);font-weight:600;font-size:11.5px;"><i class="fa-solid fa-circle-xmark"></i> Non-Aktif</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="9" style="text-align:center;color:var(--smoke);padding:20px;font-style:italic;">Belum ada atlet terdaftar dalam registrasi ini.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Footer Actions --}}
                                    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px;border-top:1px solid var(--paper);padding-top:12px;">
                                        <a href="{{ route('admin.new-registrations.show', $reg->id) }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;background:var(--ink);color:#fff;font-size:12.5px;font-weight:600;text-decoration:none;font-family:'DM Sans',sans-serif;">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Detail Lengkap
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                    <p>Tidak ada data registrasi ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($registrations->hasPages())
            <div style="padding:12px 16px;border-top:1px solid var(--paper2);">
                {{ $registrations->links('livewire.admin.pagination') }}
            </div>
            @endif
        </div>

    </div>
</div>

@push('scripts')
<script>
function confirmDeleteRegistration(id, nama) {
    Swal.fire({
        title: 'Hapus Pendaftaran?',
        html: `Data registrasi kontingen <b>${nama}</b> akan dihapus permanen beserta seluruh datanya.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c0392b',
        cancelButtonColor: '#7f8c8d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            @this.deleteRegistration(id);
        }
    });
}

function confirmBulkVerify() {
    Swal.fire({
        title: 'Verifikasi Terpilih?',
        html: 'Apakah Anda yakin ingin memverifikasi seluruh pendaftaran yang terpilih?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#27ae60',
        cancelButtonColor: '#7f8c8d',
        confirmButtonText: 'Ya, Verifikasi!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            @this.verifySelected();
        }
    });
}

function confirmBulkUnverify() {
    Swal.fire({
        title: 'Set Pending Terpilih?',
        html: 'Ubah status seluruh pendaftaran yang terpilih kembali menjadi Pending?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d4a843',
        cancelButtonColor: '#7f8c8d',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            @this.unverifySelected();
        }
    });
}
</script>
@endpush
