<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Riwayat Pendaftaran (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .prem-page { background: var(--paper); color: var(--ink); padding: 28px; min-height: 100vh; }
    .cinzel { font-family: 'Cinzel', serif; }

    /* ── PAGE HEADER ── */
    .page-hdr { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .page-hdr-left h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .page-hdr-left p  { font-size: 12px; color: var(--smoke); margin: 0; }

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

    /* ── EMPTY ── */
    .empty-state { padding: 64px 22px; text-align: center; }
    .empty-icon { width: 56px; height: 56px; background: var(--paper); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--smoke); font-size: 20px; }
    .empty-state p { font-size: 13px; color: var(--smoke); }

    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
    </style>
    @endpush

    <div class="prem-page">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-hdr">
            <div class="page-hdr-left">
                <h2>Riwayat Pendaftaran</h2>
                <p>Pantau status dan riwayat pendaftaran kontingen Anda</p>
            </div>
            <div class="page-hdr-right">
                <a href="{{ route('register') }}" class="btn-primary-prem" style="padding: 10px 20px; border-radius: 12px; background: var(--red); color: #fff; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                    <i class="fa-solid fa-plus"></i> Daftar Baru
                </a>
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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kode pendaftaran...">
                </div>
            </div>

            <div class="prem-table-wrap">
                <table class="prem-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Registrasi</th>
                            <th style="text-align:center">Peserta</th>
                            <th>Total Biaya</th>
                            <th>Tgl Daftar</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                        <tr>
                            <td style="color:var(--smoke);font-size:11px;">{{ $loop->iteration + $registrations->firstItem() - 1 }}</td>
                            <td>
                                <div style="font-weight:600;font-size:13px;">{{ $reg->referral_code ?: 'REG-'.$reg->id }}</div>
                                <div style="font-size:11px;color:var(--smoke);font-family:monospace;">Unique Code: {{ $reg->unique_code }}</div>
                            </td>
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
                                    <a href="{{ route('contingent.registration-history.show', $reg->id) }}" class="act-btn view" title="Lihat Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                    <p>Anda belum memiliki riwayat pendaftaran</p>
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
