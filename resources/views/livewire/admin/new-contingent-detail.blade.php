<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Master Contingent Detail (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .prem-page-a { background: var(--paper); color: var(--ink); padding: 28px; }

    /* ── PAGE HEADER ── */
    .page-hdr-a { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .page-hdr-a h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .page-hdr-a p  { font-size: 12px; color: var(--smoke); margin: 0; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; background: #fff; color: var(--smoke);
      border: 1px solid var(--paper2); border-radius: 12px; font-size: 13px; font-weight: 700;
      cursor: pointer; text-decoration: none; font-family: 'DM Sans', sans-serif;
      transition: all .2s;
    }
    .btn-back:hover { background: var(--paper); color: var(--ink); }

    /* ── TABS ── */
    .tab-container { display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 2px solid var(--paper2); padding-bottom: 12px; overflow-x: auto; scrollbar-width: none; }
    .tab-container::-webkit-scrollbar { display: none; }
    .tab-item {
        padding: 10px 20px; font-size: 13px; font-weight: 700; color: var(--smoke);
        border-radius: 10px; cursor: pointer; transition: all .2s; white-space: nowrap;
        background: transparent; border: 1px solid transparent; text-transform: uppercase; letter-spacing: .05em;
    }
    .tab-item:hover { color: var(--ink); background: rgba(255,255,255,0.5); }
    .tab-item.active { background: var(--red); color: #fff; box-shadow: 0 4px 12px rgba(192,57,43,0.2); border-color: var(--red); }

    /* ── CARD LAYOUT ── */
    .detail-card { background: #fff; border-radius: 16px; border: 1px solid var(--paper2); padding: 24px; margin-bottom: 24px; }
    
    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:24px; }
    .grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:24px; }

    .info-group { margin-bottom: 16px; }
    .info-label { font-size: 11px; font-weight: 600; color: var(--smoke); text-transform: uppercase; margin-bottom: 4px; letter-spacing: .05em; }
    .info-val { font-size: 14px; font-weight: 700; color: var(--ink); }

    .section-title { font-family:'Cinzel',serif; font-size:14px; font-weight:700; color:var(--red); border-bottom:1px solid var(--paper2); padding-bottom:8px; margin-bottom:20px; margin-top:12px; text-transform:uppercase; }

    .btn-action { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:var(--paper); color:var(--ink); font-size:12px; font-weight:700; border-radius:8px; border:1px solid var(--paper2); cursor:pointer; transition:.2s; }
    .btn-action:hover { background:#fff; border-color:var(--ink); }

    @media (max-width: 768px) {
      .prem-page-a { padding: 16px; }
      .grid-2, .grid-3 { grid-template-columns: 1fr; }
    }
    </style>
    @endpush

    <div class="prem-page-a">
        <div class="page-hdr-a">
            <div>
                <h2>Profil & Data Kontingen</h2>
                <p>Informasi detail mengenai kontingen {{ $contingent->name }}</p>
            </div>
            <a href="{{ route('admin.new-contingents') }}" class="btn-back" wire:navigate>
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="tab-container">
            <button class="tab-item {{ $activeTab === 'profile' ? 'active' : '' }}" wire:click="switchTab('profile')">Profil & Info</button>
            <button class="tab-item {{ $activeTab === 'registration' ? 'active' : '' }}" wire:click="switchTab('registration')">Data Pendaftaran</button>
        </div>

        @if($activeTab === 'profile')
            <div class="detail-card">
                <div class="section-title">Identitas Kontingen / Dojo</div>
                <div class="grid-2">
                    <div class="info-group">
                        <div class="info-label">Nama Kontingen</div>
                        <div class="info-val">{{ $contingent->name }}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Asal Wilayah (Kab / Kota)</div>
                        <div class="info-val">{{ $contingent->kab_kota }}</div>
                    </div>
                    <div class="info-group" style="grid-column: 1 / -1;">
                        <div class="info-label">Alamat Sekretariat</div>
                        <div class="info-val">{{ $contingent->address ?: '-' }}</div>
                    </div>
                </div>

                <div class="section-title">Informasi Manajer Tim</div>
                <div class="grid-3">
                    <div class="info-group">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-val">{{ $contingent->leader_name }}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Nomor Kontak (HP/WA)</div>
                        <div class="info-val">{{ $contingent->leader_phone ?: '-' }}</div>
                    </div>
                    <div class="info-group">
                        <div class="info-label">Email Valid</div>
                        <div class="info-val">{{ $contingent->email }}</div>
                    </div>
                </div>
            </div>
        @elseif($activeTab === 'registration')
            <div class="detail-card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <div class="section-title" style="margin-bottom:0; border:none; padding:0;">Riwayat Registrasi</div>
                    <button wire:click="createNewRegistration" class="btn-action" style="background:var(--red); color:#fff; border-color:var(--red);">
                        <i class="fa-solid fa-plus"></i> Batch Baru
                    </button>
                </div>
                
                @if($this->registration)
                    <div style="background:var(--paper); padding:16px; border-radius:12px; margin-bottom:20px; border:1px solid var(--paper2);">
                        <div class="grid-3">
                            <div class="info-group">
                                <div class="info-label">Kode Referal Pendaftaran</div>
                                <div class="info-val" style="font-family:'Cinzel',serif; color:var(--red); font-size:18px;">{{ $this->registration->referral_code }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Status Pendaftaran</div>
                                <div class="info-val" style="text-transform:uppercase;">{{ $this->registration->status }}</div>
                            </div>
                            <div class="info-group">
                                <div class="info-label">Tanggal Pendaftaran</div>
                                <div class="info-val">{{ $this->registration->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    <p style="font-size:13px; color:var(--smoke);">Data atlet dan official terkait batch ini dapat dilihat di menu master Atlet dan Official.</p>
                @else
                    <div style="text-align:center; padding:30px; color:var(--smoke); font-size:13px;">
                        <i class="fa-solid fa-folder-open" style="font-size:24px; margin-bottom:10px;"></i><br>
                        Belum ada riwayat pendaftaran untuk kontingen ini.
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
