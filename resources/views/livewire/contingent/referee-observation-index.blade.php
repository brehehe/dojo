<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Observasi Wasit (Premium Layout)
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
    .badge-prem.gold   .dot { background: #d4a843; }
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
    
    .act-btn.edit  { color: #f39c12; border-color: rgba(243,156,18,.2); background: rgba(243,156,18,.06); }
    .act-btn.edit:hover { background: rgba(243,156,18,.15); border-color: #f39c12; }
    
    .act-btn.delete { color: var(--red); border-color: rgba(192,57,43,.2); background: rgba(192,57,43,.06); }
    .act-btn.delete:hover { background: rgba(192,57,43,.15); border-color: var(--red); }

    /* ── EMPTY ── */
    .empty-state { padding: 64px 22px; text-align: center; }
    .empty-icon { width: 56px; height: 56px; background: var(--paper); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--smoke); font-size: 20px; }
    .empty-state p { font-size: 13px; color: var(--smoke); }
    </style>
    @endpush

    <div class="prem-page">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-hdr">
            <div class="page-hdr-left">
                <h2>Observasi Wasit</h2>
                <p>Kelola formulir observasi kinerja wasit oleh Manager / Official kontingen</p>
            </div>
            <div class="page-hdr-right">
                <a href="{{ route('contingent.observasi-wasit.create') }}" class="btn-primary-prem" style="padding: 10px 20px; border-radius: 12px; background: var(--red); color: #fff; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                    <i class="fa-solid fa-plus"></i> Tambah Observasi
                </a>
            </div>
        </div>

        {{-- ── TABLE ── --}}
        <div class="table-section">
            <div class="table-toolbar">
                <h3>Daftar Observasi Wasit</h3>

                {{-- Search --}}
                <div class="search-field">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari wasit, observer, court...">
                </div>
            </div>

            <div class="prem-table-wrap">
                <table class="prem-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Wasit yang Diamati</th>
                            <th>No. Wasit</th>
                            <th>Pengamat / Observer</th>
                            <th>Court & Babak</th>
                            <th style="text-align:center">Nilai Kompetensi</th>
                            <th style="text-align:center">Kategori</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($observations as $obs)
                        <tr>
                            <td style="color:var(--smoke);font-size:11px;">{{ $loop->iteration + $observations->firstItem() - 1 }}</td>
                            <td>{{ $obs->observation_date->format('d M Y') }}</td>
                            <td>
                                <div style="font-weight:600;font-size:13px;">{{ $obs->referee ? $obs->referee->name : '-' }}</div>
                                <div style="font-size:11px;color:var(--smoke);">Lic. {{ $obs->referee ? $obs->referee->license_number : '-' }}</div>
                            </td>
                            <td>{{ $obs->referee_number ?: '-' }}</td>
                            <td>{{ $obs->observer_name }}</td>
                            <td>
                                <div style="font-weight:600;">{{ $obs->court }}</div>
                                <div style="font-size:11px;color:var(--smoke);">Babak: {{ $obs->round }}</div>
                            </td>
                            <td style="text-align:center;font-weight:700;font-size:15px;color:var(--red);">
                                {{ number_format($obs->total_score, 0) }} / 100
                            </td>
                            <td style="text-align:center;">
                                @php
                                    $cat = strtoupper($obs->category);
                                    $badgeClass = 'red';
                                    if (str_contains($cat, 'SANGAT BAIK')) {
                                        $badgeClass = 'green';
                                    } elseif (str_contains($cat, 'BAIK')) {
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
                                <div style="display:flex;gap:5px;justify-content:center;">
                                    <a href="{{ route('contingent.observasi-wasit.show', $obs->id) }}" class="act-btn view" title="Lihat & Cetak">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <a href="{{ route('contingent.observasi-wasit.edit', $obs->id) }}" class="act-btn edit" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button type="button" wire:click="deleteObservation({{ $obs->id }})" wire:confirm="Apakah Anda yakin ingin menghapus observasi ini?" class="act-btn delete" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                                    <p>Belum ada rekaman formulir observasi wasit.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($observations->hasPages())
            <div style="padding:12px 16px;border-top:1px solid var(--paper2);">
                {{ $observations->links('livewire.admin.pagination') }}
            </div>
            @endif
        </div>

    </div>
</div>
