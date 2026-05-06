<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Portal Kenshi (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .portal-page { background: var(--paper); color: var(--ink); padding: 28px; }

    /* ── WELCOME HEADER ── */
    .portal-header {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 14px; margin-bottom: 28px;
    }
    .portal-header-left h2 {
        font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700;
        color: var(--ink); margin: 0 0 4px;
    }
    .portal-header-left p { font-size: 13px; color: var(--smoke); margin: 0; }
    .portal-header-left span { color: var(--red); font-weight: 600; }

    .status-pill {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fff; border: 1px solid var(--paper2);
        border-radius: 20px; padding: 7px 16px;
        font-size: 11.5px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .1em; color: var(--ink);
        box-shadow: 0 2px 8px rgba(0,0,0,.06);
    }
    .status-dot {
        width: 8px; height: 8px; border-radius: 50%;
        background: #27ae60; flex-shrink: 0;
        box-shadow: 0 0 0 3px rgba(39,174,96,.2);
        animation: pulse-green 1.8s ease-in-out infinite;
    }
    @keyframes pulse-green {
        0%, 100% { box-shadow: 0 0 0 3px rgba(39,174,96,.2); }
        50% { box-shadow: 0 0 0 6px rgba(39,174,96,.08); }
    }

    /* ── PORTAL CARDS GRID ── */
    .portal-grid {
        display: grid; grid-template-columns: repeat(3, 1fr);
        gap: 18px; margin-bottom: 24px;
    }
    .portal-card {
        background: #fff; border-radius: 16px;
        border: 1px solid var(--paper2); overflow: hidden;
        padding: 24px 22px 20px; position: relative;
        transition: transform .2s, box-shadow .2s;
        display: flex; flex-direction: column;
    }
    .portal-card:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,.09); }

    .portal-card-icon {
        width: 46px; height: 46px; border-radius: 13px;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; margin-bottom: 14px; flex-shrink: 0;
    }
    .portal-card-icon.red    { background: rgba(192,57,43,.1);  color: var(--red); }
    .portal-card-icon.blue   { background: rgba(52,152,219,.1); color: #2980b9; }
    .portal-card-icon.green  { background: rgba(39,174,96,.1);  color: #27ae60; }
    .portal-card-icon.gold   { background: rgba(212,168,67,.12); color: #b8860b; }

    .portal-card-glow {
        position: absolute; bottom: 0; right: 0;
        width: 80px; height: 80px; border-radius: 50%;
        opacity: .06; transform: translate(25px, 25px);
        pointer-events: none;
    }
    .portal-card-glow.red   { background: var(--red); }
    .portal-card-glow.blue  { background: #3498db; }
    .portal-card-glow.green { background: #27ae60; }
    .portal-card-glow.gold  { background: var(--gold); }

    .portal-card h3 {
        font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700;
        color: var(--ink); margin: 0 0 6px;
    }
    .portal-card p { font-size: 12px; color: var(--smoke); margin: 0 0 18px; flex: 1; line-height: 1.6; }

    .portal-card-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        width: 100%; padding: 10px 16px; border-radius: 10px;
        border: 1px solid var(--paper2); background: var(--paper);
        font-family: 'DM Sans', sans-serif; font-size: 12.5px; font-weight: 600;
        color: var(--ink); cursor: pointer; text-decoration: none;
        transition: all .18s;
    }
    .portal-card-btn:hover { background: var(--ink); color: #fff; border-color: var(--ink); }
    .portal-card-btn.red:hover   { background: var(--red);   border-color: var(--red);   color: #fff; }
    .portal-card-btn.blue:hover  { background: #2980b9;      border-color: #2980b9;      color: #fff; }
    .portal-card-btn.green:hover { background: #27ae60;      border-color: #27ae60;      color: #fff; }

    /* ── ANNOUNCEMENT BANNER ── */
    .announcement-banner {
        background: var(--ink); border-radius: 16px;
        padding: 28px 28px; position: relative; overflow: hidden;
        display: flex; align-items: center; gap: 24px;
        border: 1px solid rgba(255,255,255,.04);
    }
    .announcement-banner::before {
        content: ''; position: absolute;
        top: -60px; right: -60px;
        width: 220px; height: 220px;
        background: radial-gradient(circle, rgba(192,57,43,.35) 0%, transparent 70%);
        pointer-events: none;
    }
    .ann-icon-wrap {
        width: 64px; height: 64px; border-radius: 16px; flex-shrink: 0;
        background: var(--red); display: flex; align-items: center;
        justify-content: center; font-size: 24px; color: #fff;
        box-shadow: 0 6px 20px rgba(192,57,43,.4);
        position: relative; z-index: 1;
    }
    .ann-body { flex: 1; position: relative; z-index: 1; }
    .ann-body h3 {
        font-family: 'Cinzel', serif; font-size: 16px; font-weight: 700;
        color: #fff; margin: 0 0 6px;
    }
    .ann-body p { font-size: 12.5px; color: rgba(255,255,255,.55); margin: 0 0 16px; line-height: 1.65; }
    .ann-cta {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px; background: var(--red); color: #fff;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 600;
        text-decoration: none; font-family: 'DM Sans', sans-serif;
        transition: all .2s; cursor: pointer;
        box-shadow: 0 4px 16px rgba(192,57,43,.35);
    }
    .ann-cta:hover { background: var(--red-deep); transform: translateY(-1px); color: #fff; box-shadow: 0 6px 22px rgba(192,57,43,.45); }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .portal-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 600px) {
        .portal-page { padding: 14px; }
        .portal-grid { grid-template-columns: 1fr; }
        .announcement-banner { flex-direction: column; text-align: center; }
        .ann-icon-wrap { width: 52px; height: 52px; font-size: 20px; }
    }
    </style>
    @endpush

    <div class="portal-page">

        {{-- ── WELCOME HEADER ── --}}
        <div class="portal-header">
            <div class="portal-header-left">
                <h2>Portal Kenshi</h2>
                <p>Selamat datang di Smart-Perkemi, <span>{{ auth()->user()->name }}</span></p>
            </div>
            <div class="status-pill">
                <span class="status-dot"></span>
                Sesi Aktif
            </div>
        </div>

        {{-- ── PORTAL CARDS ── --}}
        <div class="portal-grid">
            {{-- Sertifikat --}}
            <div class="portal-card">
                <div class="portal-card-glow red"></div>
                <div class="portal-card-icon red">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <h3>Sertifikat Saya</h3>
                <p>Lihat dan unduh sertifikat prestasi digital Anda.</p>
                <button class="portal-card-btn red">
                    <i class="fa-solid fa-eye"></i> Lihat Semua
                </button>
            </div>

            {{-- Event --}}
            <div class="portal-card">
                <div class="portal-card-glow blue"></div>
                <div class="portal-card-icon blue">
                    <i class="fa-solid fa-calendar-alt"></i>
                </div>
                <h3>Event Terdekat</h3>
                <p>Pantau jadwal kejuaraan dan gashuku Nasional.</p>
                <button class="portal-card-btn blue">
                    <i class="fa-solid fa-calendar-days"></i> Buka Kalender
                </button>
            </div>

            {{-- Kartu Kenshi --}}
            <div class="portal-card">
                <div class="portal-card-glow green"></div>
                <div class="portal-card-icon green">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <h3>Kartu Kenshi</h3>
                <p>Lihat kartu tanda anggota digital PERKEMI Anda.</p>
                <button class="portal-card-btn green">
                    <i class="fa-solid fa-address-card"></i> Lihat Kartu
                </button>
            </div>
        </div>

        {{-- ── ANNOUNCEMENT BANNER ── --}}
        <div class="announcement-banner">
            <div class="ann-icon-wrap">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="ann-body">
                <h3>Piala Walikota Surabaya 2026</h3>
                <p>Jangan lewatkan kesempatan untuk berprestasi di tingkat Kota! Pendaftaran atlet kolektif sedang dibuka hingga 30 Mei 2026.</p>
                <a href="/piala_walikotasby2026" class="ann-cta">
                    <i class="fa-solid fa-pen-to-square"></i> Daftar Sekarang
                </a>
            </div>
        </div>

    </div>
</div>
