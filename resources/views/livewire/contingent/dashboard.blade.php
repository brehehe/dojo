<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       CONTINGENT DASHBOARD — Mobile Premium
    ══════════════════════════════════════════════════════ */

    /* ── HERO BAND ── */
    .ctg-hero {
        background: var(--ink); padding: 20px 16px 24px;
        position: relative; overflow: hidden;
    }
    .ctg-hero::before {
        content: ''; position: absolute;
        top: -50px; right: -50px;
        width: 180px; height: 180px;
        background: radial-gradient(circle, rgba(192,57,43,.4) 0%, transparent 70%);
        pointer-events: none;
    }
    .ctg-hero-inner { position: relative; z-index: 1; }
    .ctg-hero-label {
        font-size: 9.5px; color: var(--smoke); font-weight: 700;
        letter-spacing: .2em; text-transform: uppercase; margin-bottom: 6px;
    }
    .ctg-hero-name {
        font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700;
        color: #fff; margin: 0 0 2px; line-height: 1.2;
    }
    .ctg-hero-sub { font-size: 12px; color: var(--smoke); margin: 0 0 16px; }
    .ctg-hero-cta {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 20px; background: var(--red); color: #fff;
        border: none; border-radius: 10px; font-size: 12.5px; font-weight: 700;
        text-decoration: none; font-family: 'DM Sans', sans-serif;
        transition: all .2s; cursor: pointer;
        box-shadow: 0 4px 16px rgba(192,57,43,.35);
    }
    .ctg-hero-cta:hover { background: var(--red-deep); color: #fff; }

    /* ── STATS STRIP ── */
    .ctg-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 16px; }
    .ctg-stat-card {
        background: #fff; border-radius: 14px; border: 1px solid var(--paper2);
        padding: 14px 12px; text-align: center;
        transition: transform .2s, box-shadow .2s;
    }
    .ctg-stat-card:active { transform: scale(.97); }
    .ctg-stat-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; margin: 0 auto 8px;
    }
    .ctg-stat-icon.red   { background: rgba(192,57,43,.1);  color: var(--red); }
    .ctg-stat-icon.blue  { background: rgba(52,152,219,.1); color: #2980b9; }
    .ctg-stat-icon.green { background: rgba(39,174,96,.1);  color: #27ae60; }
    .ctg-stat-val {
        font-family: 'Cinzel', serif; font-size: 22px; font-weight: 700;
        color: var(--ink); line-height: 1; margin-bottom: 4px;
    }
    .ctg-stat-lbl { font-size: 9.5px; color: var(--smoke); text-transform: uppercase; letter-spacing: .06em; font-weight: 600; }

    /* ── SECTION HEADER ── */
    .ctg-sec-hdr {
        display: flex; align-items: center; justify-content: space-between;
        padding: 4px 16px 10px; flex-wrap: wrap; gap: 6px;
    }
    .ctg-sec-hdr h3 { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: var(--ink); margin: 0; }
    .ctg-sec-badge {
        font-size: 10px; color: var(--smoke); background: var(--paper2);
        padding: 3px 10px; border-radius: 20px; font-weight: 600;
    }

    /* ── REGISTRATION CARDS (mobile-first, no table) ── */
    .ctg-reg-list { padding: 0 16px 16px; display: flex; flex-direction: column; gap: 10px; }
    .ctg-reg-card {
        background: #fff; border-radius: 14px; border: 1px solid var(--paper2);
        padding: 14px 16px; display: flex; gap: 14px; align-items: flex-start;
    }
    .ctg-reg-num {
        width: 34px; height: 34px; border-radius: 9px; background: var(--paper);
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700;
        color: var(--smoke); flex-shrink: 0;
    }
    .ctg-reg-body { flex: 1; min-width: 0; }
    .ctg-reg-code { font-size: 13px; font-weight: 700; color: var(--ink); margin: 0 0 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ctg-reg-date { font-size: 11px; color: var(--smoke); margin: 0 0 8px; }
    .ctg-reg-meta { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .ctg-reg-pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 9px; border-radius: 20px; font-size: 10.5px; font-weight: 600;
    }
    .ctg-reg-pill.blue  { background: rgba(52,152,219,.1);  color: #2980b9; }
    .ctg-reg-pill.green { background: rgba(39,174,96,.1);  color: #27ae60; }
    .ctg-reg-pill.pending  { background: rgba(245,158,11,.1); color: #d97706; }
    .ctg-reg-pill.verified { background: rgba(39,174,96,.1);  color: #27ae60; }
    .ctg-reg-pill.rejected { background: rgba(192,57,43,.1);  color: var(--red); }
    .ctg-reg-amount { font-family: 'Cinzel', serif; font-size: 12px; font-weight: 700; color: var(--ink); margin-left: auto; flex-shrink: 0; }

    /* ── EMPTY STATE ── */
    .ctg-empty {
        padding: 40px 24px; text-align: center;
        background: #fff; border-radius: 14px; border: 1px solid var(--paper2);
    }
    .ctg-empty i { font-size: 32px; color: var(--paper2); margin-bottom: 12px; display: block; }
    .ctg-empty h4 { font-family: 'Cinzel', serif; font-size: 13px; font-weight: 700; color: var(--ink); margin: 0 0 6px; }
    .ctg-empty p { font-size: 12px; color: var(--smoke); margin: 0 0 16px; }
    .ctg-empty-link { color: var(--red); font-size: 12px; font-weight: 700; text-decoration: none; }
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
    <div style="padding: 0 16px 12px; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 15px;">
        <a href="{{ route('contingent.athletes') }}" wire:navigate class="ctg-hero-cta" style="background: var(--ink); box-shadow: none; justify-content: center; padding: 12px;">
            <i class="fa-solid fa-user-plus"></i> Tambah Atlet
        </a>
        <a href="{{ route('contingent.officials') }}" wire:navigate class="ctg-hero-cta" style="background: var(--smoke); box-shadow: none; justify-content: center; padding: 12px;">
            <i class="fa-solid fa-user-tie"></i> Tambah Official
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
                                default    => 'pending',
                            };
                        @endphp
                        <span class="ctg-reg-pill {{ $statusClass }}">{{ strtoupper($reg->status) }}</span>
                        <span class="ctg-reg-amount">Rp {{ number_format($reg->final_amount, 0, ',', '.') }}</span>
                    </div>
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
