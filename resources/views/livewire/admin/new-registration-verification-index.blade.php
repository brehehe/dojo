<div>
    @push('styles')
    <style>
    .prem-page { background: var(--paper); color: var(--ink); padding: 28px; }
    .cinzel { font-family: 'Cinzel', serif; }
    
    /* HEADER */
    .page-hdr { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .page-hdr-left h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .page-hdr-left p  { font-size: 12px; color: var(--smoke); margin: 0; }

    /* STAT CARDS */
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
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
    .stat-icon {
      width: 38px; height: 38px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; margin-bottom: 10px;
    }
    .stat-card.red   .stat-icon { background: rgba(192,57,43,.1); color: var(--red); }
    .stat-card.gold  .stat-icon { background: rgba(212,168,67,.12); color: #b8860b; }
    .stat-card.green .stat-icon { background: rgba(39,174,96,.1); color: #27ae60; }
    .stat-label { font-size: 10px; color: var(--smoke); font-weight: 500; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 4px; }
    .stat-value { font-family: 'Cinzel', serif; font-size: 24px; font-weight: 700; line-height: 1; }

    /* TABLE SECTION */
    .table-section { background: #fff; border-radius: 16px; border: 1px solid var(--paper2); overflow: hidden; }
    .table-toolbar {
      padding: 16px 20px; display: flex; align-items: center; gap: 10px;
      border-bottom: 1px solid var(--paper2); flex-wrap: wrap;
    }
    .table-toolbar h3 { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; flex: 1; min-width: 120px; margin: 0; }

    /* FILTER TABS */
    .filter-tabs { display: flex; gap: 3px; background: var(--paper); border-radius: 9px; padding: 3px; }
    .filter-tab {
      padding: 5px 13px; border-radius: 7px; border: none; background: none;
      font-size: 11.5px; font-family: 'DM Sans', sans-serif;
      cursor: pointer; color: var(--smoke); font-weight: 500; transition: all .15s;
    }
    .filter-tab.active { background: var(--ink); color: #fff; font-weight: 600; }

    /* SEARCH */
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

    .perpage-select {
      padding: 7px 10px; border: 1px solid var(--paper2); border-radius: 9px;
      font-size: 12px; background: #fff; color: var(--ink); font-family: 'DM Sans', sans-serif;
      cursor: pointer; outline: none;
    }

    /* MAIN TABLE */
    .prem-table-wrap { overflow-x: auto; }
    .prem-table { width: 100%; border-collapse: collapse; }
    .prem-table thead th {
      padding: 10px 16px; font-size: 10px; color: var(--smoke); font-weight: 600;
      letter-spacing: .08em; text-transform: uppercase;
      text-align: left; background: var(--paper);
      border-bottom: 1px solid var(--paper2); white-space: nowrap;
    }
    .prem-table thead th:first-child { padding-left: 22px; }
    .prem-table tbody td { padding: 13px 16px; font-size: 13px; border-bottom: 1px solid var(--paper2); color: var(--ink); }
    .prem-table td:first-child { padding-left: 22px; }

    /* BADGES */
    .badge-prem { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 10.5px; font-weight: 600; white-space: nowrap; }
    .badge-prem.green  { background: rgba(39,174,96,.12); color: #1e8449; }
    .badge-prem.gold   { background: rgba(212,168,67,.15); color: #9a6e00; }
    .badge-prem.red    { background: rgba(192,57,43,.12); color: var(--red); }
    .badge-prem .dot { width: 5px; height: 5px; border-radius: 50%; }
    .badge-prem.green  .dot { background: #27ae60; }
    .badge-prem.gold   .dot { background: #d4a843; }
    .badge-prem.red    .dot { background: var(--red); }

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

    .mini-avatar {
      width: 28px; height: 28px; border-radius: 7px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 10px; font-weight: 700; color: #fff;
      background: linear-gradient(135deg, var(--red), #e67e22);
    }

    /* EXPANDED SECTION */
    .expanded-row td { background: rgba(247,244,239,.35); padding: 18px 22px !important; border-bottom: 2px solid var(--paper2) !important; }
    .expanded-card { background: #fff; border-radius: 12px; border: 1px solid var(--paper2); padding: 16px; box-shadow: inset 0 2px 8px rgba(0,0,0,.02); }
    .expanded-title { font-family: 'Cinzel', serif; font-size: 12px; font-weight: 700; margin-bottom: 12px; color: var(--ink); border-bottom: 1px solid var(--paper); padding-bottom: 8px; display: flex; justify-content: space-between; align-items: center; }

    /* INNER ATHLETE TABLE */
    .ath-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    .ath-table th { font-size: 10px; color: var(--smoke); text-transform: uppercase; letter-spacing: .05em; padding: 8px 10px; text-align: left; background: var(--paper); border-bottom: 1px solid var(--paper2); }
    .ath-table td { padding: 9px 10px; font-size: 12.5px; border-bottom: 1px solid var(--paper2); }
    .ath-table tr:last-child td { border-bottom: none; }

    /* MODAL */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); }
    .modal-card { background: #fff; border-radius: 16px; width: 100%; max-width: 580px; border: 1px solid var(--paper2); box-shadow: 0 20px 50px rgba(0,0,0,.15); overflow: hidden; animation: slideUp .25s ease-out; }
    .modal-header { padding: 16px 20px; background: var(--ink); color: #fff; display: flex; align-items: center; justify-content: space-between; }
    .modal-header h3 { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; margin: 0; color: var(--gold-lt); }
    .modal-body { padding: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 14px; max-height: 480px; overflow-y: auto; }
    .modal-footer { padding: 14px 20px; background: var(--paper); border-top: 1px solid var(--paper2); display: flex; justify-content: flex-end; gap: 8px; }

    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group.span-2 { grid-column: span 2; }
    .form-group label { font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--smoke); letter-spacing: .05em; }
    .form-group input, .form-group select { padding: 8px 12px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 13px; font-family: 'DM Sans', sans-serif; outline: none; }
    .form-group input:focus, .form-group select:focus { border-color: var(--ink); }

    .btn-prem { padding: 8px 16px; border-radius: 8px; font-size: 12.5px; font-weight: 600; cursor: pointer; border: none; font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; gap: 6px; }
    .btn-prem.primary { background: var(--ink); color: #fff; }
    .btn-prem.primary:hover { background: #000; }
    .btn-prem.secondary { background: var(--paper2); color: var(--ink); }
    .btn-prem.secondary:hover { background: var(--paper); }
    .btn-prem.success { background: #27ae60; color: #fff; }
    .btn-prem.success:hover { background: #1e8449; }
    .btn-prem.warning { background: #d4a843; color: #fff; }
    .btn-prem.warning:hover { background: #b8860b; }

    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

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
    </style>
    @endpush

    <div class="prem-page">
        {{-- HEADER --}}
        <div class="page-hdr">
            <div class="page-hdr-left">
                <h2>Verifikasi Data Kenshi</h2>
                <p>Periksa keabsahan data atlet/kenshi agar dapat tampil di Drawing Technical Meeting</p>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="stats-grid">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-flag"></i></div>
                <div class="stat-label">Total Kontingen Pembayar</div>
                <div class="stat-value" style="color:var(--red)">{{ number_format($stats['total']) }}</div>
            </div>
            <div class="stat-card gold">
                <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-label">Menunggu Verifikasi Data</div>
                <div class="stat-value" style="color:#b8860b">{{ number_format($stats['pending']) }}</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-label">Data Atlet Terverifikasi</div>
                <div class="stat-value" style="color:#27ae60">{{ number_format($stats['verified']) }}</div>
            </div>
        </div>

        {{-- TABLE SECTION --}}
        <div class="table-section">
            <div class="table-toolbar">
                <h3>Daftar Verifikasi Kontingen</h3>

                {{-- Filters --}}
                <div class="filter-tabs">
                    <button class="filter-tab {{ $status === '' ? 'active' : '' }}" wire:click="$set('status', '')">Semua</button>
                    <button class="filter-tab {{ $status === 'pending' ? 'active' : '' }}" wire:click="$set('status', 'pending')">Pending</button>
                    <button class="filter-tab {{ $status === 'verified' ? 'active' : '' }}" wire:click="$set('status', 'verified')">Verified</button>
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
                        <i class="fa-solid fa-circle-check"></i> Verifikasi Atlet Terpilih
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
                            <th style="text-align:center">Jumlah Atlet</th>
                            <th style="text-align:center">Verifikasi Atlet</th>
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
                            <td style="text-align:center;font-weight:600;">
                                {{ $reg->athletes()->count() }} Atlet
                            </td>
                            <td style="text-align:center;">
                                @php
                                    $badgeClass = $reg->athlete_status === 'verified' ? 'green' : 'gold';
                                @endphp
                                <span class="badge-prem {{ $badgeClass }}">
                                    <span class="dot"></span>
                                    {{ strtoupper($reg->athlete_status) }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <button wire:click="toggleExpand({{ $reg->id }})" class="act-btn view" title="Lihat Atlet & Kelola">
                                    <i class="fa-solid {{ $isExpanded ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- EXPANDED AREA --}}
                        @if($isExpanded)
                        <tr class="expanded-row" wire:key="reg-expanded-{{ $reg->id }}">
                            <td colspan="7">
                                <div class="expanded-card">
                                    <div class="expanded-title">
                                        <span>Daftar Kenshi - {{ $reg->contingent->name }}</span>
                                        <span>Status: {{ strtoupper($reg->athlete_status) }}</span>
                                    </div>
                                    
                                    <div style="overflow-x:auto;">
                                        <table class="ath-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Lengkap / NIK</th>
                                                    <th>Gender</th>
                                                    <th>Dojo</th>
                                                    <th>BB (Kg)</th>
                                                    <th>Rank / Kyu</th>
                                                    <th>Nomor Tanding</th>
                                                    <th>BPJS</th>
                                                    <th>Berkas</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($reg->athletes as $idx => $ath)
                                                <tr wire:key="ath-row-{{ $ath->id }}">
                                                    <td>{{ $idx + 1 }}</td>
                                                    <td>
                                                        <div style="font-weight:600;">{{ $ath->name }}</div>
                                                        <div style="font-size:11px;color:var(--smoke);font-family:monospace;">
                                                            NIK: {{ $ath->nik }} | Kenshi: {{ $ath->nik_kenshi ?: '-' }}
                                                        </div>
                                                    </td>
                                                    <td>{{ $ath->gender === 'Male' ? 'L' : 'P' }}</td>
                                                    <td>{{ $ath->pivot->dojo_origin ?? '-' }}</td>
                                                    <td>{{ $ath->pivot->weight ? $ath->pivot->weight . ' kg' : '-' }}</td>
                                                    <td><span class="badge-prem green">{{ $ath->pivot->rank ?: '-' }}</span></td>
                                                    <td>
                                                        <div style="font-size:11.5px;max-width:200px;">
                                                            @php
                                                                $matches = $ath->matchNumbers()->wherePivot('registration_id', $reg->id)->get();
                                                            @endphp
                                                            @forelse($matches as $m)
                                                                <div style="margin-bottom:2px;">• {{ $m->name }}</div>
                                                            @empty
                                                                <span style="color:var(--red);font-style:italic;">Belum terdaftar</span>
                                                            @endforelse
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if($ath->bpjs_status === 'Aktif')
                                                            <span style="color:#27ae60;font-weight:600;"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                                        @else
                                                            <span style="color:var(--red);font-weight:600;"><i class="fa-solid fa-circle-xmark"></i> Non-Aktif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div style="display:flex;gap:4px;">
                                                            @if($ath->photo_path)
                                                                <a href="{{ asset('storage/' . $ath->photo_path) }}" target="_blank" class="act-btn" title="Foto" style="width:24px;height:24px;font-size:9px;"><i class="fa-solid fa-image"></i></a>
                                                            @endif
                                                            @if($ath->bpjs_card_path)
                                                                <a href="{{ asset('storage/' . $ath->bpjs_card_path) }}" target="_blank" class="act-btn" title="Kartu BPJS" style="width:24px;height:24px;font-size:9px;"><i class="fa-solid fa-address-card"></i></a>
                                                            @endif
                                                            @if($ath->identity_document_path)
                                                                <a href="{{ asset('storage/' . $ath->identity_document_path) }}" target="_blank" class="act-btn" title="Identitas" style="width:24px;height:24px;font-size:9px;"><i class="fa-solid fa-id-card"></i></a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button wire:click="openEditAthlete({{ $ath->id }}, {{ $reg->id }})" class="act-btn view" title="Edit Atlet ini" style="width:26px;height:26px;">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="10" style="text-align:center;color:var(--smoke);padding:20px;">Tidak ada kenshi terdaftar.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- ACTION BUTTONS AT BOTTOM OF COLLAPSIBLE CARD --}}
                                    <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:14px;border-top:1px solid var(--paper);padding-top:12px;">
                                        @if($reg->athlete_status === 'verified')
                                            <button wire:click="toggleVerification({{ $reg->id }})" class="btn-prem warning">
                                                <i class="fa-solid fa-rotate-left"></i> Batalkan Verifikasi
                                            </button>
                                        @else
                                            <button wire:click="toggleVerification({{ $reg->id }})" class="btn-prem success">
                                                <i class="fa-solid fa-check-double"></i> Verifikasi Data Kenshi
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif

                        @empty
                        <tr>
                            <td colspan="7">
                                <div style="text-align:center;padding:40px;color:var(--smoke);">
                                    <i class="fa-solid fa-inbox" style="font-size:24px;display:block;margin-bottom:10px;"></i>
                                    Tidak ada data pendaftaran ditemukan.
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

        {{-- EDIT ATHLETE MODAL --}}
        @if($editingAthleteId)
        <div class="modal-overlay">
            <div class="modal-card">
                <div class="modal-header">
                    <h3>Edit Data Kenshi</h3>
                    <button wire:click="$set('editingAthleteId', null)" style="background:none;border:none;color:#fff;cursor:pointer;font-size:16px;">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group span-2">
                        <label>Nama Lengkap</label>
                        <input type="text" wire:model="editName">
                        @error('editName') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>NIK (16 Digit)</label>
                        <input type="text" wire:model="editNik" maxlength="16">
                        @error('editNik') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>NIK Kenshi (SIM Perkemi)</label>
                        <input type="text" wire:model="editNikKenshi">
                        @error('editNikKenshi') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select wire:model="editGender">
                            <option value="Male">Laki-laki</option>
                            <option value="Female">Perempuan</option>
                        </select>
                        @error('editGender') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Berat Badan (Kg)</label>
                        <input type="number" step="0.1" wire:model="editWeight">
                        @error('editWeight') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Tingkatan / Kyu</label>
                        <input type="text" wire:model="editRank" placeholder="Contoh: Kyu 5, Kyu 1, Dan I">
                        @error('editRank') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Asal Dojo</label>
                        <input type="text" wire:model="editDojo">
                        @error('editDojo') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor BPJS</label>
                        <input type="text" wire:model="editBpjsNumber">
                        @error('editBpjsNumber') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Status BPJS</label>
                        <select wire:model="editBpjsStatus">
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                        </select>
                        @error('editBpjsStatus') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click="$set('editingAthleteId', null)" class="btn-prem secondary">Batal</button>
                    <button wire:click="saveAthlete" class="btn-prem primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmBulkVerify() {
    Swal.fire({
        title: 'Verifikasi Atlet Terpilih?',
        html: 'Apakah Anda yakin ingin memverifikasi data atlet pada seluruh pendaftaran yang terpilih?',
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
        html: 'Ubah status verifikasi atlet seluruh pendaftaran yang terpilih kembali menjadi Pending?',
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
