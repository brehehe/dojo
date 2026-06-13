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

    /* MODAL */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.5); display: flex; align-items: center; justify-content: center; z-index: 1000; backdrop-filter: blur(4px); }
    .modal-card { background: #fff; border-radius: 16px; width: calc(100% - 32px); max-width: 580px; border: 1px solid var(--paper2); box-shadow: 0 20px 50px rgba(0,0,0,.15); overflow: hidden; animation: slideUp .25s ease-out; box-sizing: border-box; }
    .modal-header { padding: 16px 20px; background: var(--ink); color: #fff; display: flex; align-items: center; justify-content: space-between; }
    .modal-header h3 { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; margin: 0; color: var(--gold-lt); }
    .modal-body { padding: 20px; display: grid; grid-template-columns: 1fr; gap: 14px; max-height: 480px; overflow-y: auto; overflow-x: hidden; }
    .modal-body select, .modal-body input { max-width: 100%; width: 100%; box-sizing: border-box; }
    .modal-footer { padding: 14px 20px; background: var(--paper); border-top: 1px solid var(--paper2); display: flex; justify-content: flex-end; gap: 8px; }

    .form-group { display: flex; flex-direction: column; gap: 5px; }
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
                                     <div style="padding:16px 22px 8px;">
                        @forelse($groupedMatches as $data)
                        <div class="match-card">
                            <div class="match-card-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div class="match-num">{{ $loop->iteration }}</div>
                                    <div class="match-info">
                                        <h4>{{ $data['details']->name }}
                                            {{ $data['details']->ageGroup?->name }}
                                            {{ $data['details']->gender_indo }}
                                            @if($data['team_number'])
                                                <span style="color:var(--red); font-weight:800;">(Tim {{ $data['team_number'] }})</span>
                                            @endif
                                        </h4>
                                        <p>{{ ucfirst($data['details']->draft_type ?? '-') }}</p>
                                    </div>
                                </div>
                                @if(($data['details']->draft_type ?? '') == 'embu')
                                <button wire:click="openEditTechniques({{ json_encode($data['pivot_ids']) }})" class="btn-verify" style="padding: 6px 12px; font-size: 11px; background: #9b59b6; color: white; border: none; border-radius: 8px; cursor:pointer;">
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
                                                    <div style="display:flex; justify-content:space-between; align-items:center; gap:8px;">
                                                        <div>
                                                            <div style="font-weight:600;">{{ $athletes[$i]['model']->name }}</div>
                                                            <div style="font-size:11px;color:var(--smoke);font-family:monospace;">{{ $athletes[$i]['model']->nik }}</div>
                                                        </div>
                                                        @if(isset($athletes[$i]['team_number']))
                                                        <div style="margin-left:auto;">
                                                            <select wire:change="updateAthleteTeam({{ $athletes[$i]['pivot_id'] }}, $event.target.value)" style="padding: 4px 8px; font-size: 11px; border: 1px solid var(--paper2); border-radius: 6px; background: #fff; color: var(--ink); font-weight: 600; cursor: pointer;">
                                                                @for($t = 1; $t <= 5; $t++)
                                                                    <option value="{{ $t }}" {{ $athletes[$i]['team_number'] == $t ? 'selected' : '' }}>Tim {{ $t }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        @endif
                                                    </div>
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

    {{-- EDIT MODAL FOR TECHNIQUES --}}
    @if(!empty($editingPivotIds))
    <div class="modal-overlay">
        <div class="modal-card" style="max-width: 480px;">
            <div class="modal-header">
                <h3>Edit Komposisi Teknik</h3>
                <button wire:click="$set('editingPivotIds', [])" style="background:none;border:none;color:#fff;cursor:pointer;font-size:16px;">&times;</button>
            </div>
            <div class="modal-body" style="grid-template-columns: 1fr; gap: 12px;">
                <div class="form-group" style="border-bottom:1px solid var(--paper2);padding-bottom:10px;margin-bottom:10px;">
                    <label>Tambah Teknik Baru</label>
                    <div style="display:flex; gap:8px; margin-top:4px; overflow: hidden;">
                        <select wire:model="newTechniqueId" style="width: 0; flex: 1; padding: 8px; border: 1px solid var(--paper2); border-radius: 8px; background: #fff; color: var(--ink); box-sizing: border-box;">
                            <option value="">-- Pilih Teknik --</option>
                            @foreach($allTechniques as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="addTechnique" class="btn-prem primary" style="padding:0 18px; height: 38px; flex-shrink: 0;">Tambah</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Urutan Komposisi Teknik</label>
                    <div style="display:flex; flex-direction:column; gap:8px; margin-top:6px;">
                        @forelse($selectedTechniqueIds as $tIdx => $tId)
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:8px; background:var(--paper); padding:8px 10px; border-radius:8px; border:1px solid var(--paper2);" wire:key="tech-item-{{ $tIdx }}">
                            <div style="display:flex; align-items:center; gap:8px; min-width: 0; flex: 1;">
                                <div style="width:20px; height:20px; border-radius:50%; background:var(--ink); color:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:bold; flex-shrink: 0;">
                                    {{ $tIdx + 1 }}
                                </div>
                                <span style="font-size:13px; font-weight:600; white-space: normal; word-break: break-word; min-width: 0;">{{ $allTechniques[$tId] ?? '-' }}</span>
                            </div>
                            <div style="display:flex; gap:4px; flex-shrink: 0;">
                                <button type="button" wire:click="moveTechniqueUp({{ $tIdx }})" style="width: 30px; height: 30px; border-radius: 6px; border: 1px solid var(--paper2); background: #fff; display: inline-flex; align-items: center; justify-content: center; color: var(--ink); font-size: 11px; cursor: pointer; opacity: {{ $tIdx == 0 ? '0.3' : '1' }};" @if($tIdx == 0) disabled @endif>
                                    <i class="fa-solid fa-arrow-up"></i>
                                </button>
                                <button type="button" wire:click="moveTechniqueDown({{ $tIdx }})" style="width: 30px; height: 30px; border-radius: 6px; border: 1px solid var(--paper2); background: #fff; display: inline-flex; align-items: center; justify-content: center; color: var(--ink); font-size: 11px; cursor: pointer; opacity: {{ $tIdx == count($selectedTechniqueIds) - 1 ? '0.3' : '1' }};" @if($tIdx == count($selectedTechniqueIds) - 1) disabled @endif>
                                    <i class="fa-solid fa-arrow-down"></i>
                                </button>
                                <button type="button" wire:click="removeTechnique({{ $tIdx }})" style="width: 30px; height: 30px; border-radius: 6px; border: 1px solid rgba(192,57,43,0.2); background: #fff; display: inline-flex; align-items: center; justify-content: center; color: var(--red); font-size: 11px; cursor: pointer;">
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
                <button wire:click="$set('editingPivotIds', [])" class="btn-prem secondary">Batal</button>
                <button wire:click="saveTechniques" class="btn-prem primary"><i class="fa-solid fa-save"></i> Simpan Teknik</button>
            </div>
        </div>
    </div>
    @endif
</div>
