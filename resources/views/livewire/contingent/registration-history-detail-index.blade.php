<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       DETAIL REGISTRASI (HISTORY) — Premium Layout
    ══════════════════════════════════════════════════════ */
    .det-page { padding: 28px; background: var(--paper); color: var(--ink); min-height: 100vh; }

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

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) { .det-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px)  { .det-page { padding: 14px; } }
    </style>
    @endpush

    <div class="det-page">

        {{-- ── TOP BAR ── --}}
        <div class="det-topbar">
            <div class="det-breadcrumb">
                <a href="{{ route('contingent.registration-history') }}" class="back-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="det-title">
                        Detail Riwayat Pendaftaran
                        <span class="status-badge {{ $registration->status }}" style="vertical-align:middle;margin-left:8px;">
                            <span class="dot"></span>{{ strtoupper($registration->status) }}
                        </span>
                    </div>
                    <div class="det-sub">
                        Ref: <span style="font-family:monospace;color:var(--red);">{{ $registration->referral_code ?: 'REG-'.$registration->id }}</span>
                        &nbsp;·&nbsp;Terdaftar {{ $registration->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            <div class="det-actions">
                @if($registration->status === 'pending')
                    <a href="{{ route('register', ['draft_id' => $registration->id]) }}" class="btn-verify" style="text-decoration: none; background: var(--gold); border-radius: 10px; padding: 9px 20px; color: #fff; font-size: 12.5px; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                        <i class="fa-solid fa-edit"></i> Edit Pendaftaran
                    </a>
                @endif
            </div>
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
                            <div class="match-card-head">
                                <div class="match-num">{{ $loop->iteration }}</div>
                                <div class="match-info">
                                    <h4>{{ $data['details']->name }}
                                        {{ $data['details']->ageGroup?->name }}
                                        {{ $data['details']->gender_indo }}
                                    </h4>
                                    <p>{{ ucfirst($data['details']->draft_type ?? '-') }}</p>
                                </div>
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
                                                <div style="font-weight:600;">{{ $athletes[$i]->name }}</div>
                                                <div style="font-size:11px;color:var(--smoke);font-family:monospace;">{{ $athletes[$i]->nik }}</div>
                                                @endif
                                            </td>
                                            <td>{{ isset($athletes[$i]) ? ($athletes[$i]->pivot->rank ?? '-') : '' }}</td>
                                            <td>
                                                @if(isset($techniques[$i]))
                                                    {{ $i + 1 }}. {{ $allTechniques[$techniques[$i]] ?? '-' }}
                                                @endif
                                            </td>
                                            @if(($data['details']->draft_type ?? '') == 'randori')
                                            <td>{{ isset($athletes[$i]) ? rtrim(rtrim(number_format($athletes[$i]->pivot->weight, 2, '.', ''), '0'), '.') : '' }}</td>
                                            @endif
                                        </tr>
                                        @endfor
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
</div>
