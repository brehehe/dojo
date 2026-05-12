<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       CONTINGENT DASHBOARD — Mobile Premium
    ══════════════════════════════════════════════════════ */

    /* ── HERO BAND ── */
    .ctg-hero {
        background: linear-gradient(135deg, #0f0d0b 0%, #1a1a1a 100%); 
        padding: 28px 20px 32px;
        position: relative; overflow: hidden;
        border-radius: 0 0 24px 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .ctg-hero::after {
        content: ''; position: absolute;
        bottom: -20px; left: -20px;
        width: 150px; height: 150px;
        background: radial-gradient(circle, rgba(212,168,67,0.15) 0%, transparent 70%);
        pointer-events: none;
    }
    .ctg-hero-inner { position: relative; z-index: 1; }
    .ctg-hero-label {
        font-size: 10px; color: var(--gold); font-weight: 700;
        letter-spacing: .25em; text-transform: uppercase; margin-bottom: 8px;
        opacity: 0.9;
    }
    .ctg-hero-name {
        font-family: 'Cinzel', serif; font-size: 24px; font-weight: 800;
        color: #fff; margin: 0 0 4px; line-height: 1.2;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .ctg-hero-sub { font-size: 13px; color: var(--smoke); margin: 0 0 20px; font-weight: 500; }
    
    .ctg-hero-cta {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 12px 24px; background: linear-gradient(135deg, var(--red), var(--red-deep));
        color: #fff; border: none; border-radius: 12px; font-size: 13px; font-weight: 700;
        text-decoration: none; font-family: 'DM Sans', sans-serif;
        transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(192,57,43,.4);
    }
    .ctg-hero-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(192,57,43,.5); color: #fff; }
    .ctg-hero-cta:active { transform: translateY(0); }

    /* ── QUICK ACTIONS ── */
    .ctg-quick-actions {
        display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
        padding: 20px 16px 10px;
    }
    .btn-action {
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        padding: 16px; border-radius: 16px; border: 1px solid var(--paper2);
        background: #fff; text-decoration: none; transition: all .2s;
    }
    .btn-action i { font-size: 18px; }
    .btn-action span { font-size: 12px; font-weight: 700; color: var(--ink); }
    .btn-action.dark { background: var(--ink); border-color: var(--ink); }
    .btn-action.dark span { color: #fff; }
    .btn-action.dark i { color: var(--gold); }
    .btn-action:active { transform: scale(.96); }

    /* ── STATS STRIP ── */
    .ctg-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; padding: 16px; }
    .ctg-stat-card {
        background: #fff; border-radius: 18px; border: 1px solid var(--paper2);
        padding: 18px 12px; text-align: center;
        transition: all .2s;
        box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }
    .ctg-stat-icon {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; margin: 0 auto 10px;
    }
    .ctg-stat-icon.red   { background: rgba(192,57,43,.08); color: var(--red); }
    .ctg-stat-icon.blue  { background: rgba(52,152,219,.08); color: #2980b9; }
    .ctg-stat-icon.green { background: rgba(39,174,96,.08); color: #27ae60; }
    .ctg-stat-val {
        font-family: 'Cinzel', serif; font-size: 24px; font-weight: 800;
        color: var(--ink); line-height: 1; margin-bottom: 6px;
    }
    .ctg-stat-lbl { font-size: 10px; color: var(--smoke); text-transform: uppercase; letter-spacing: .08em; font-weight: 700; }

    /* ── SECTION HEADER ── */
    .ctg-sec-hdr {
        display: flex; align-items: center; justify-content: space-between;
        padding: 12px 16px 12px;
    }
    .ctg-sec-hdr h3 { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 800; color: var(--ink); margin: 0; }
    .ctg-sec-badge {
        font-size: 10.5px; color: var(--smoke); background: #fff; border: 1px solid var(--paper2);
        padding: 4px 12px; border-radius: 20px; font-weight: 600;
    }

    /* ── REGISTRATION CARDS ── */
    .ctg-reg-list { padding: 0 16px 24px; display: flex; flex-direction: column; gap: 12px; }
    .ctg-reg-card {
        background: #fff; border-radius: 18px; border: 1px solid var(--paper2);
        padding: 16px; display: flex; gap: 16px; align-items: center;
        transition: all .2s;
    }
    .ctg-reg-card:active { background: var(--paper); }
    .ctg-reg-num {
        width: 38px; height: 38px; border-radius: 12px; background: var(--paper);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cinzel', serif; font-size: 14px; font-weight: 800;
        color: var(--smoke); flex-shrink: 0;
    }
    .ctg-reg-body { flex: 1; min-width: 0; }
    .ctg-reg-code { font-size: 14px; font-weight: 800; color: var(--ink); margin: 0 0 3px; }
    .ctg-reg-date { font-size: 11.5px; color: var(--smoke); margin: 0 0 10px; }
    .ctg-reg-meta { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
    .ctg-reg-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px; font-size: 10.5px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.02em;
    }
    .ctg-reg-pill.blue  { background: rgba(52,152,219,.1); color: #2980b9; }
    .ctg-reg-pill.green { background: rgba(39,174,96,.1); color: #27ae60; }
    .ctg-reg-pill.pending  { background: rgba(245,158,11,.1); color: #d97706; }
    .ctg-reg-pill.verified { background: rgba(39,174,96,.1); color: #27ae60; }
    .ctg-reg-pill.rejected { background: rgba(192,57,43,.1); color: var(--red); }
    .ctg-reg-pill.draft { background: rgba(149, 165, 166, 0.1); color: #7f8c8d; }
    .ctg-reg-amount { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 800; color: var(--ink); margin-left: auto; }

    .ctg-empty {
        padding: 48px 24px; text-align: center;
        background: #fff; border-radius: 20px; border: 1px solid var(--paper2);
    }
    .ctg-empty i { font-size: 36px; color: var(--paper2); margin-bottom: 16px; display: block; }
    .ctg-empty h4 { font-family: 'Cinzel', serif; font-size: 15px; font-weight: 800; color: var(--ink); margin: 0 0 8px; }
    .ctg-empty p { font-size: 13px; color: var(--smoke); margin: 0 0 20px; }
    .ctg-empty-link { color: var(--red); font-size: 13px; font-weight: 800; text-decoration: none; }
    </style>
    @endpush

    {{-- ── HERO ── --}}
    <div class="ctg-hero">
        <div class="ctg-hero-inner">
            <div class="ctg-hero-label">Kontingen Terdaftar</div>
            <h2 class="ctg-hero-name">{{ $contingent->name }}</h2>
            <p class="ctg-hero-sub">{{ $contingent->kab_kota }}</p>
            <a href="/piala_walikotasby2026" class="ctg-hero-cta">
                <i class="fa-solid fa-file-signature"></i> Buka Registrasi Baru
            </a>
        </div>
    </div>

    {{-- ── QUICK ACTIONS ── --}}
    <div class="ctg-quick-actions">
        <a href="{{ route('contingent.athletes') }}" wire:navigate class="btn-action dark">
            <i class="fa-solid fa-user-plus"></i>
            <span>Tambah Atlet</span>
        </a>
        <a href="{{ route('contingent.officials') }}" wire:navigate class="btn-action">
            <i class="fa-solid fa-user-tie" style="color: var(--smoke);"></i>
            <span>Tambah Official</span>
        </a>
    </div>

    {{-- ── STATS ── --}}
    <div class="ctg-stats">
        <a href="{{ route('contingent.athletes') }}" wire:navigate style="text-decoration: none;">
            <div class="ctg-stat-card">
                <div class="ctg-stat-icon blue"><i class="fa-solid fa-users"></i></div>
                <div class="ctg-stat-val">{{ $contingent->athletes()->count() }}</div>
                <div class="ctg-stat-lbl">Master Atlet</div>
            </div>
        </a>
        <a href="{{ route('contingent.officials') }}" wire:navigate style="text-decoration: none;">
            <div class="ctg-stat-card">
                <div class="ctg-stat-icon green"><i class="fa-solid fa-user-tie"></i></div>
                <div class="ctg-stat-val">{{ $contingent->officials()->count() }}</div>
                <div class="ctg-stat-lbl">Master Official</div>
            </div>
        </a>
        <div class="ctg-stat-card">
            <div class="ctg-stat-icon red"><i class="fa-solid fa-trophy"></i></div>
            <div class="ctg-stat-val">{{ $registrations->count() }}</div>
            <div class="ctg-stat-lbl">Pendaftaran</div>
        </div>
    </div>

    {{-- ── REGISTRATION LIST ── --}}
    <div class="ctg-sec-hdr">
        <h3>Riwayat Pendaftaran</h3>
        <span class="ctg-sec-badge">Update: {{ now()->format('d M Y') }}</span>
    </div>

    <div class="ctg-reg-list">
        @forelse($registrations as $reg)
            <div class="ctg-reg-card">
                <div class="ctg-reg-num">{{ $loop->iteration }}</div>
                <div class="ctg-reg-body">
                    <p class="ctg-reg-code">{{ $reg->referral_code }}</p>
                    <p class="ctg-reg-date">{{ $reg->created_at->format('d M Y, H:i') }}</p>
                    <div class="ctg-reg-meta">
                        <span class="ctg-reg-pill blue">
                            <i class="fa-solid fa-users" style="font-size:9px;"></i>
                            {{ $reg->athletes_count }} Atlet
                        </span>
                        <span class="ctg-reg-pill green">
                            <i class="fa-solid fa-user-tie" style="font-size:9px;"></i>
                            {{ $reg->officials_count }} Official
                        </span>
                        @php
                            $statusClass = match($reg->status) {
                                'verified' => 'verified',
                                'rejected' => 'rejected',
                                'draft'    => 'draft',
                                default    => 'pending',
                            };
                        @endphp
                        <span class="ctg-reg-pill {{ $statusClass }}">{{ strtoupper($reg->status) }}</span>
                        <span class="ctg-reg-amount">Rp {{ number_format($reg->final_amount, 0, ',', '.') }}</span>
                    </div>
                    @if(in_array($reg->status, ['draft', 'pending']))
                    <div class="mt-3 flex justify-end">
                        <a href="/piala_walikotasby2026?draft_id={{ $reg->id }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-full transition-all">
                            <i class="fa-solid fa-pen-to-square mr-1"></i> Edit Data
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="ctg-empty">
                <i class="fa-solid fa-file-invoice"></i>
                <h4>Belum Ada Pendaftaran</h4>
                <p>Kontingen Anda belum memiliki riwayat pendaftaran turnamen.</p>
                <a href="/piala_walikotasby2026" class="ctg-empty-link">Daftar Sekarang &rarr;</a>
            </div>
        @endforelse
    </div>
</div>
