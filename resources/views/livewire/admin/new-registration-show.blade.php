<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       DETAIL REGISTRASI — Premium Layout
    ══════════════════════════════════════════════════════ */
    .det-page { padding: 28px; background: var(--paper); color: var(--ink); }

    /* ── BREADCRUMB / TOPBAR ── */
    .det-topbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
    .det-breadcrumb { display: flex; align-items: center; gap: 10px; }
    .back-btn {
      width: 36px; height: 36px; border-radius: 9px;
      border: 1px solid var(--paper2); background: #fff;
      display: flex; align-items: center; justify-content: center;
      color: var(--ink); font-size: 13px; cursor: pointer;
      text-decoration: none; transition: all .15s; flex-shrink: 0;
    }
    .back-btn:hover { background: var(--paper2); }
    .det-title { font-family: 'Cinzel', serif; font-size: 17px; font-weight: 700; }
    .det-sub   { font-size: 11.5px; color: var(--smoke); margin-top: 2px; }

    /* ── STATUS BADGE ── */
    .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: .05em; }
    .status-badge .dot { width: 6px; height: 6px; border-radius: 50%; }
    .status-badge.verified { background: rgba(39,174,96,.12); color: #1e8449; }
    .status-badge.verified .dot { background: #27ae60; }
    .status-badge.pending  { background: rgba(212,168,67,.15); color: #9a6e00; }
    .status-badge.pending  .dot { background: #d4a843; animation: blink 1.5s infinite; }
    .status-badge.rejected { background: rgba(192,57,43,.12); color: var(--red); }
    .status-badge.rejected .dot { background: var(--red); }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: .35; } }

    /* ── ACTION BUTTONS ── */
    .det-actions { display: flex; gap: 8px; }
    .btn-verify {
      padding: 9px 20px; background: #27ae60; color: #fff;
      border: none; border-radius: 10px; font-size: 12.5px; font-weight: 600;
      cursor: pointer; font-family: 'DM Sans', sans-serif;
      display: flex; align-items: center; gap: 7px; transition: background .15s;
    }
    .btn-verify:hover { background: #1e8449; }
    .btn-reject {
      padding: 9px 20px; background: #fff; color: var(--red);
      border: 1px solid rgba(192,57,43,.25); border-radius: 10px; font-size: 12.5px; font-weight: 600;
      cursor: pointer; font-family: 'DM Sans', sans-serif;
      display: flex; align-items: center; gap: 7px; transition: all .15s;
    }
    .btn-reject:hover { background: rgba(192,57,43,.07); }

    /* ── GRID LAYOUT ── */
    .det-grid { display: grid; grid-template-columns: 320px 1fr; gap: 20px; }

    /* ── CARDS ── */
    .det-card {
      background: #fff; border-radius: 16px;
      border: 1px solid var(--paper2); overflow: hidden;
    }
    .det-card-head {
      padding: 18px 22px 14px; border-bottom: 1px solid var(--paper2);
      display: flex; align-items: center; gap: 9px;
    }
    .det-card-head .card-icon {
      width: 32px; height: 32px; border-radius: 8px;
      display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;
    }
    .det-card-head h3 { font-family: 'Cinzel', serif; font-size: 12.5px; font-weight: 700; flex: 1; margin: 0; }

    /* ── PROFILE BLOCK ── */
    .profile-emblem {
      width: 52px; height: 52px; border-radius: 12px;
      background: var(--ink); display: flex; align-items: center; justify-content: center;
      color: var(--gold-lt); font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700;
      flex-shrink: 0;
    }
    .profile-block { padding: 18px 22px; display: flex; align-items: center; gap: 14px; }
    .profile-block h4 { font-size: 15px; font-weight: 700; margin: 0 0 3px; }
    .profile-block p  { font-size: 11.5px; color: var(--smoke); margin: 0; }

    /* ── INFO ROWS ── */
    .info-row { padding: 11px 22px; display: flex; align-items: flex-start; gap: 8px; border-bottom: 1px solid var(--paper2); }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 10.5px; color: var(--smoke); font-weight: 500; letter-spacing: .05em; text-transform: uppercase; min-width: 110px; padding-top: 1px; flex-shrink: 0; }
    .info-value { font-size: 13px; font-weight: 500; color: var(--ink); flex: 1; }

    /* ── FEE LIST ── */
    .fee-row { padding: 12px 22px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--paper2); }
    .fee-row:last-child { border-bottom: none; }
    .fee-label { font-size: 12.5px; font-weight: 500; }
    .fee-sub   { font-size: 11px; color: var(--smoke); margin-top: 1px; }
    .fee-amount { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; }
    .fee-total-row { padding: 14px 22px; display: flex; justify-content: space-between; align-items: center; background: var(--ink); }
    .fee-total-row .fee-label { color: rgba(255,255,255,.7); font-size: 12px; }
    .fee-total-row .fee-amount { color: var(--gold-lt); font-size: 16px; }

    /* ── PAYMENT PROOF ── */
    .proof-img-wrap { padding: 16px 22px; }
    .proof-img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; cursor: pointer; transition: transform .2s; }
    .proof-img:hover { transform: scale(1.02); }
    .proof-empty { height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; border: 2px dashed var(--paper2); border-radius: 10px; margin: 16px 22px; }
    .proof-empty i { font-size: 24px; color: var(--smoke); }
    .proof-empty p { font-size: 12px; color: var(--smoke); }

    /* ── OFFICIALS TABLE ── */
    .official-card { margin: 0 22px 14px; padding: 12px 14px; background: var(--paper); border-radius: 11px; display: flex; align-items: center; justify-content: space-between; border: 1px solid var(--paper2); }
    .official-name { font-size: 13px; font-weight: 600; }
    .official-role { font-size: 10.5px; color: var(--smoke); margin-top: 2px; }
    .official-phone { font-size: 12px; color: var(--smoke); font-family: monospace; }

    /* ── MATCH CARDS ── */
    .match-card { margin: 0 0 14px; background: var(--paper); border-radius: 12px; border: 1px solid var(--paper2); overflow: hidden; }
    .match-card-head { padding: 14px 18px; display: flex; align-items: center; gap: 12px; background: #fff; border-bottom: 1px solid var(--paper2); }
    .match-num { width: 36px; height: 36px; border-radius: 9px; background: var(--red); display: flex; align-items: center; justify-content: center; color: #fff; font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; flex-shrink: 0; }
    .match-info h4 { font-size: 13.5px; font-weight: 700; margin: 0 0 2px; }
    .match-info p  { font-size: 11px; color: var(--smoke); margin: 0; }

    .match-table { width: 100%; border-collapse: collapse; }
    .match-table th { padding: 8px 14px; font-size: 10px; color: var(--smoke); font-weight: 600; letter-spacing: .07em; text-transform: uppercase; background: var(--paper); border-bottom: 1px solid var(--paper2); text-align: left; white-space: nowrap; }
    .match-table td { padding: 11px 14px; font-size: 12.5px; border-bottom: 1px solid var(--paper2); color: var(--ink); }
    .match-table tr:last-child td { border-bottom: none; }
    .match-table tr:nth-child(even) td { background: rgba(247,244,239,.4); }

    /* ── RIGHT COLUMN PADDING ── */
    .det-right { display: flex; flex-direction: column; gap: 18px; }
    .det-right-inner { padding: 14px 22px; }

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
    .form-group input, .form-group select { padding: 8px 12px; border: 1px solid var(--paper2); border-radius: 8px; font-size: 13px; font-family: 'DM Sans', sans-serif; outline: none; background: #fff; color: var(--ink); }
    .form-group input:focus, .form-group select:focus { border-color: var(--ink); }

    .btn-prem { padding: 8px 18px; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; border: none; font-family: 'DM Sans', sans-serif; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
    .btn-prem.primary { background: var(--ink); color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,.1); }
    .btn-prem.primary:hover { background: #000; }
    .btn-prem.secondary { background: var(--paper2); color: var(--ink); }
    .btn-prem.secondary:hover { background: var(--paper); }
    .btn-prem.success { background: #27ae60; color: #fff; box-shadow: 0 4px 12px rgba(39,174,96,.25); }
    .btn-prem.success:hover { background: #1e8449; }

    @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) { .det-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px)  { .det-page { padding: 14px; } }
    </style>
    @endpush

    <div class="det-page">

        {{-- ── TOP BAR ── --}}
        <div class="det-topbar">
            <div class="det-breadcrumb">
                <a href="{{ route('admin.new-registrations') }}" class="back-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="det-title">
                        Detail Pendaftaran
                        <span class="status-badge {{ $registration->status }}" style="vertical-align:middle;margin-left:8px;">
                            <span class="dot"></span>{{ strtoupper($registration->status) }}
                        </span>
                    </div>
                    <div class="det-sub">
                        Ref: <span style="font-family:monospace;color:var(--red);">{{ $registration->referral_code }}</span>
                        &nbsp;·&nbsp;Terdaftar {{ $registration->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            @if($registration->status === 'pending')
            <div class="det-actions">
                <button wire:click="reject" class="btn-reject">
                    <i class="fa-solid fa-xmark"></i> Tolak
                </button>
                <button wire:click="verify" class="btn-verify">
                    <i class="fa-solid fa-check"></i> Verifikasi
                </button>
            </div>
            @endif
        </div>

        {{-- ── MAIN GRID ── --}}
        <div class="det-grid">

            {{-- ─── LEFT COLUMN ─── --}}
            <div style="display:flex;flex-direction:column;gap:18px;">

                {{-- Profil Kontingen --}}
                <div class="det-card">
                    <div class="det-card-head">
                        <div class="card-icon" style="background:rgba(192,57,43,.1);color:var(--red);">
                            <i class="fa-solid fa-flag"></i>
                        </div>
                        <h3>Profil Kontingen</h3>
                    </div>
                    <div class="profile-block">
                        <div class="profile-emblem">{{ substr($registration->contingent?->name ?? 'K', 0, 1) }}</div>
                        <div>
                            <h4>{{ $registration->contingent?->name }}</h4>
                            <p>{{ $registration->contingent?->kab_kota }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Manager Tim</span>
                        <span class="info-value">{{ $registration->contingent?->leader_name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. HP</span>
                        <span class="info-value" style="font-family:monospace;">{{ $registration->contingent?->leader_phone ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $registration->contingent?->email ?? '-' }}</span>
                    </div>
                </div>

                {{-- Rincian Biaya --}}
                <div class="det-card">
                    <div class="det-card-head">
                        <div class="card-icon" style="background:rgba(39,174,96,.1);color:#27ae60;">
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h3>Rincian Biaya</h3>
                    </div>
                    <div class="fee-row">
                        <div>
                            <div class="fee-label">Iuran Kontingen</div>
                            <div class="fee-sub">Pendaftaran Institusi</div>
                        </div>
                        <div class="fee-amount">Rp {{ number_format($this->feeDetails['contingent_fee'], 0, ',', '.') }}</div>
                    </div>
                    @foreach($this->feeDetails['athlete_fees'] as $groupName => $fee)
                    <div class="fee-row">
                        <div>
                            <div class="fee-label">{{ $fee['count'] }}× Atlet {{ $groupName }}</div>
                            <div class="fee-sub">@ Rp {{ number_format($fee['price'], 0, ',', '.') }}</div>
                        </div>
                        <div class="fee-amount">Rp {{ number_format($fee['total'], 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                    @if($this->feeDetails['unique_code'] > 0)
                    <div class="fee-row">
                        <div>
                            <div class="fee-label" style="color:var(--red)">Kode Unik</div>
                            <div class="fee-sub">Identifikasi Transfer</div>
                        </div>
                        <div class="fee-amount" style="color:var(--red)">+{{ number_format($this->feeDetails['unique_code'], 0, ',', '.') }}</div>
                    </div>
                    @endif
                    <div class="fee-total-row">
                        <div class="fee-label">Total Akhir</div>
                        <div class="fee-amount">Rp {{ number_format($this->feeDetails['final_amount'], 0, ',', '.') }}</div>
                    </div>
                </div>

                {{-- Bukti Pembayaran --}}
                <div class="det-card">
                    <div class="det-card-head">
                        <div class="card-icon" style="background:rgba(212,168,67,.12);color:#b8860b;">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <h3>Bukti Pembayaran</h3>
                    </div>
                    @if($registration->transfer_proof_path)
                    <div class="proof-img-wrap">
                        <img src="{{ asset('storage/' . $registration->transfer_proof_path) }}"
                            class="proof-img"
                            alt="Bukti Transfer"
                            onclick="window.open('{{ asset('storage/' . $registration->transfer_proof_path) }}', '_blank')">
                    </div>
                    @else
                    <div class="proof-empty">
                        <i class="fa-solid fa-image"></i>
                        <p>Belum ada bukti pembayaran</p>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Metode</span>
                        <span class="info-value">{{ $registration->payment_method ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total Bayar</span>
                        <span class="info-value" style="font-family:'Cinzel',serif;font-weight:700;font-size:15px;">
                            Rp {{ number_format($registration->final_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- ─── RIGHT COLUMN ─── --}}
            <div class="det-right">

                {{-- Official Pendamping --}}
                <div class="det-card">
                    <div class="det-card-head">
                        <div class="card-icon" style="background:rgba(52,152,219,.1);color:#2980b9;">
                            <i class="fa-solid fa-id-badge"></i>
                        </div>
                        <h3>Official Pendamping
                            <span style="font-family:'DM Sans';font-size:11px;color:var(--smoke);font-weight:400;margin-left:6px;">
                                ({{ $registration->officials->count() }} orang)
                            </span>
                        </h3>
                    </div>
                    <div style="padding:14px 0 6px;">
                        @forelse($registration->officials as $off)
                        <div class="official-card">
                            <div>
                                <div class="official-name">{{ $off->name }}</div>
                                <div class="official-role">{{ $off->pivot?->role ?? 'Official' }}</div>
                            </div>
                            <div class="official-phone">{{ $off->phone }}</div>
                        </div>
                        @empty
                        <div style="padding:28px;text-align:center;color:var(--smoke);font-size:12px;">
                            <i class="fa-solid fa-users-slash" style="font-size:22px;display:block;margin-bottom:8px;opacity:.4;"></i>
                            Tidak ada official yang terdaftar
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Daftar Atlet --}}
                <div class="det-card">
                    <div class="det-card-head" style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:9px;">
                            <div class="card-icon" style="background:rgba(243,156,18,.1);color:#e67e22;">
                                <i class="fa-solid fa-users"></i>
                            </div>
                            <h3>Daftar Atlet
                                <span style="font-family:'DM Sans';font-size:11px;color:var(--smoke);font-weight:400;margin-left:6px;">
                                    ({{ $registration->athletes->count() }} orang)
                                </span>
                            </h3>
                        </div>
                        <button wire:click="openAddAthlete" class="btn-verify" style="padding: 6px 12px; font-size: 11px; background: #e67e22; color: white; border: none; border-radius: 8px;">
                            <i class="fa-solid fa-user-plus"></i> Tambah Atlet
                        </button>
                    </div>
                    <div style="padding:14px 0 6px;">
                        @forelse($registration->athletes as $ath)
                        <div class="official-card" style="align-items: center; justify-content: space-between; display: flex; gap: 10px; margin-bottom: 8px;">
                            <div>
                                <div class="official-name">{{ $ath->name }}</div>
                                <div class="official-role" style="display: flex; gap: 4px; flex-wrap: wrap; margin-top: 4px;">
                                    <span class="badge" style="background: var(--paper2); padding: 2px 6px; border-radius: 4px; font-size: 10px;">{{ $ath->pivot->age_group }}</span>
                                    <span class="badge" style="background: var(--paper2); padding: 2px 6px; border-radius: 4px; font-size: 10px;">{{ $ath->pivot->rank }}</span>
                                    <span class="badge" style="background: var(--paper2); padding: 2px 6px; border-radius: 4px; font-size: 10px;">{{ $ath->gender_indo }}</span>
                                </div>
                            </div>
                            <div style="display: flex; gap: 6px;">
                                <button wire:click="openEditAthlete({{ $ath->id }})" class="btn-verify" style="padding: 6px 10px; font-size: 11px; background: #3498db; color: white;">
                                    <i class="fa-solid fa-pencil"></i> Edit
                                </button>
                                <button wire:confirm="Apakah Anda yakin ingin menghapus atlet {{ $ath->name }} dari pendaftaran ini? Tindakan ini juga akan menghapusnya dari seluruh nomor pertandingan pendaftaran ini." wire:click="deleteAthlete({{ $ath->id }})" class="btn-reject" style="padding: 6px 10px; font-size: 11px; border-color: rgba(192,57,43,.4); color: var(--red);">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                        @empty
                        <div style="padding:28px;text-align:center;color:var(--smoke);font-size:12px;">
                            <i class="fa-solid fa-users-slash" style="font-size:22px;display:block;margin-bottom:8px;opacity:.4;"></i>
                            Tidak ada atlet yang terdaftar
                        </div>
                        @endforelse
                    </div>
                </div>

                {{-- Nomor Pertandingan --}}
                <div class="det-card">
                    <div class="det-card-head">
                        <div class="card-icon" style="background:rgba(192,57,43,.1);color:var(--red);">
                            <i class="fa-solid fa-list-ol"></i>
                        </div>
                        <h3>Nomor Pertandingan
                            <span style="font-family:'DM Sans';font-size:11px;color:var(--smoke);font-weight:400;margin-left:6px;">
                                ({{ count($groupedMatches) }} nomor)
                            </span>
                        </h3>
                    </div>

                    <div style="padding:16px 22px 8px;">
                        @forelse($groupedMatches as $data)
                        <div class="match-card">
                            <div class="match-card-head" style="display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div class="match-num">{{ $loop->iteration }}</div>
                                    <div class="match-info">
                                        <h4>{{ $data['details']->name }}
                                            {{ $data['details']->ageGroup?->name }}
                                            {{ $data['details']->gender_indo }}
                                        </h4>
                                        <p>{{ ucfirst($data['details']->draft_type ?? '-') }}</p>
                                    </div>
                                </div>
                                @if(($data['details']->draft_type ?? '') == 'embu')
                                <button wire:click="openEditTechniques({{ $data['details']->id }})" class="btn-verify" style="padding: 6px 12px; font-size: 11px; background: #9b59b6; color: white; border: none; border-radius: 8px;">
                                    <i class="fa-solid fa-list-check"></i> Edit Teknik
                                </button>
                                @endif
                            </div>
                            <div style="overflow-x:auto;">
                                <table class="match-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Atlet</th>
                                            <th>Tingkat</th>
                                            <th>Komposisi / Teknik</th>
                                            @if(($data['details']->draft_type ?? '') == 'randori')
                                            <th>BB (kg)</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(($data['max_athletes'] ?? 2) == 1)
                                            @foreach($data['athletes'] as $i => $athData)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    <div style="font-weight:600;">{{ $athData['model']->name }}</div>
                                                    <div style="font-size:11px;color:var(--smoke);font-family:monospace;">{{ $athData['model']->nik }}</div>
                                                </td>
                                                <td>{{ $athData['model']->pivot->rank ?? '-' }}</td>
                                                <td>
                                                    @forelse($athData['techniques'] as $tIdx => $tId)
                                                        <div>{{ $tIdx + 1 }}. {{ $allTechniques[$tId] ?? '-' }}</div>
                                                    @empty
                                                        <span style="color:var(--smoke);font-style:italic;font-size:12px;">Belum ada teknik</span>
                                                    @endforelse
                                                </td>
                                                @if(($data['details']->draft_type ?? '') == 'randori')
                                                <td>{{ rtrim(rtrim(number_format($athData['model']->pivot->weight, 2, '.', ''), '0'), '.') }}</td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        @else
                                            @php
                                                $athletes = $data['athletes'];
                                                $techniques = $data['techniques'] ?? [];
                                                $rowCount = max(count($athletes), count($techniques));
                                            @endphp
                                            @for($i = 0; $i < $rowCount; $i++)
                                            <tr>
                                                <td>{{ $i < count($athletes) ? $i + 1 : '' }}</td>
                                                <td>
                                                    @if(isset($athletes[$i]))
                                                    <div style="font-weight:600;">{{ $athletes[$i]['model']->name }}</div>
                                                    <div style="font-size:11px;color:var(--smoke);font-family:monospace;">{{ $athletes[$i]['model']->nik }}</div>
                                                    @endif
                                                </td>
                                                <td>{{ isset($athletes[$i]) ? ($athletes[$i]['model']->pivot->rank ?? '-') : '' }}</td>
                                                <td>
                                                    @if(isset($techniques[$i]))
                                                        {{ $i + 1 }}. {{ $allTechniques[$techniques[$i]] ?? '-' }}
                                                    @endif
                                                </td>
                                                @if(($data['details']->draft_type ?? '') == 'randori')
                                                <td>{{ isset($athletes[$i]) ? rtrim(rtrim(number_format($athletes[$i]['model']->pivot->weight, 2, '.', ''), '0'), '.') : '' }}</td>
                                                @endif
                                            </tr>
                                            @endfor
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @empty
                        <div style="padding:40px;text-align:center;color:var(--smoke);">
                            <i class="fa-solid fa-layer-group" style="font-size:28px;display:block;margin-bottom:10px;opacity:.3;"></i>
                            <p style="font-size:12px;">Belum ada nomor pertandingan</p>
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- EDIT/ADD MODAL FOR ATHLETE --}}
    @if($editingAthleteId || $isAddingAthlete)
    <div class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>{{ $isAddingAthlete ? 'Tambah Atlet Baru' : 'Edit Data Atlet / Kenshi' }}</h3>
                <button wire:click="$this->isAddingAthlete ? $set('isAddingAthlete', false) : $set('editingAthleteId', null)" style="background:none;border:none;color:#fff;cursor:pointer;font-size:16px;">&times;</button>
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
                    <select wire:model.live="editGender">
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
                    <label>Kelompok Usia (Kategori Usia)</label>
                    <select wire:model="editAgeGroup">
                        <option value="">-- Pilih Kelompok Usia --</option>
                        @foreach($this->ageGroupsList as $group)
                            <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>
                    @error('editAgeGroup') <span style="color:var(--red);font-size:11px;">{{ $message }}</span> @enderror
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

                {{-- EVENT SELECTIONS --}}
                <div class="form-group span-2" style="margin-top:10px;border-top:1px solid var(--paper);padding-top:10px;">
                    <label style="color:var(--red);font-weight:700;">Kategori Pertandingan yang Diikuti</label>
                    <span style="font-size:11px;color:var(--smoke);margin-bottom:8px;">Pilih kategori pertandingan yang sesuai dengan gender dan kelompok usia.</span>
                </div>
                <div class="form-group span-2">
                    <label>Kategori 1</label>
                    <select wire:model="editEvent1">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($this->availableEvents as $ev)
                            <option value="{{ $ev['id'] }}">{{ $ev['name'] }} ({{ $ev['gender'] === 'Male' ? 'Putra' : ($ev['gender'] === 'Female' ? 'Putri' : 'Mix') }} - {{ ucfirst($ev['draft_type']) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group span-2">
                    <label>Kategori 2</label>
                    <select wire:model="editEvent2">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($this->availableEvents as $ev)
                            <option value="{{ $ev['id'] }}">{{ $ev['name'] }} ({{ $ev['gender'] === 'Male' ? 'Putra' : ($ev['gender'] === 'Female' ? 'Putri' : 'Mix') }} - {{ ucfirst($ev['draft_type']) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group span-2">
                    <label>Kategori 3</label>
                    <select wire:model="editEvent3">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($this->availableEvents as $ev)
                            <option value="{{ $ev['id'] }}">{{ $ev['name'] }} ({{ $ev['gender'] === 'Male' ? 'Putra' : ($ev['gender'] === 'Female' ? 'Putri' : 'Mix') }} - {{ ucfirst($ev['draft_type']) }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$this->isAddingAthlete ? $set('isAddingAthlete', false) : $set('editingAthleteId', null)" class="btn-prem secondary">Batal</button>
                <button wire:click="saveAthlete" class="btn-prem primary">
                    <i class="fa-solid fa-save"></i> {{ $isAddingAthlete ? 'Tambah Atlet' : 'Simpan & Reset Verifikasi' }}
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- EDIT MODAL FOR TECHNIQUES --}}
    @if($editingMatchNumberId)
    <div class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3>Edit Komposisi Teknik</h3>
                <button wire:click="$set('editingMatchNumberId', null)" style="background:none;border:none;color:#fff;cursor:pointer;font-size:16px;">&times;</button>
            </div>
            <div class="modal-body" style="grid-template-columns: 1fr; gap: 12px;">
                <div class="form-group" style="border-bottom:1px solid var(--paper2);padding-bottom:10px;margin-bottom:10px;">
                    <label>Tambah Teknik Baru</label>
                    <div style="display:flex; gap:8px; margin-top:4px;">
                        <select wire:model="newTechniqueId" style="flex:1; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; background: #fff; color: var(--ink);">
                            <option value="">-- Pilih Teknik --</option>
                            @foreach($allTechniques as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="addTechnique" class="btn-prem primary" style="padding:0 18px; height: 38px;">Tambah</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Urutan Komposisi Teknik</label>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-top:6px;">
                        @forelse($selectedTechniqueIds as $tIdx => $tId)
                        <div style="display:flex; align-items:center; justify-content:space-between; background:var(--paper); padding:8px 12px; border-radius:8px; border:1px solid var(--paper2);" wire:key="tech-item-{{ $tIdx }}">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="width:20px; height:20px; border-radius:50%; background:var(--ink); color:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:bold;">
                                    {{ $tIdx + 1 }}
                                </div>
                                <span style="font-size:13px; font-weight:600;">{{ $allTechniques[$tId] ?? '-' }}</span>
                            </div>
                            <div style="display:flex; gap:4px;">
                                <button type="button" wire:click="moveTechniqueUp({{ $tIdx }})" class="act-btn" @if($tIdx == 0) disabled style="opacity:0.3; cursor:default;" @endif>
                                    <i class="fa-solid fa-arrow-up"></i>
                                </button>
                                <button type="button" wire:click="moveTechniqueDown({{ $tIdx }})" class="act-btn" @if($tIdx == count($selectedTechniqueIds) - 1) disabled style="opacity:0.3; cursor:default;" @endif>
                                    <i class="fa-solid fa-arrow-down"></i>
                                </button>
                                <button type="button" wire:click="removeTechnique({{ $tIdx }})" class="act-btn" style="color:var(--red); border-color:rgba(192,57,43,0.2);">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; color:var(--smoke); padding:20px; border:2px dashed var(--paper2); border-radius:8px; font-size:12.5px;">
                            Belum ada teknik yang ditambahkan.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button wire:click="$set('editingMatchNumberId', null)" class="btn-prem secondary">Batal</button>
                <button wire:click="saveTechniques" class="btn-prem primary"><i class="fa-solid fa-save"></i> Simpan Teknik</button>
            </div>
        </div>
    </div>
    @endif
</div>

