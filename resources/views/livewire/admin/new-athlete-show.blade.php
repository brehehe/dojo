<div>
    @push('styles')
    <style>
    .ath-page { padding: 28px; background: var(--paper); color: var(--ink); }

    /* ── TOPBAR ── */
    .ath-topbar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 24px; }
    .back-btn-a { width:36px;height:36px;border-radius:9px;border:1px solid var(--paper2);background:#fff;display:flex;align-items:center;justify-content:center;color:var(--ink);font-size:13px;text-decoration:none;transition:all .15s; }
    .back-btn-a:hover { background:var(--paper2); }
    .ath-title { font-family:'Cinzel',serif;font-size:17px;font-weight:700; }
    .ath-sub   { font-size:11.5px;color:var(--smoke);margin-top:2px; }
    .btn-edit { padding:8px 16px;background:#fff;border:1px solid var(--paper2);border-radius:9px;font-size:12.5px;font-weight:600;color:var(--ink);text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:all .15s; }
    .btn-edit:hover { background:var(--paper2); }

    /* ── GRID ── */
    .ath-grid { display:grid;grid-template-columns:280px 1fr;gap:20px; }

    /* ── LEFT SIDEBAR ── */
    .ath-sidebar { display:flex;flex-direction:column;gap:16px; }
    .ath-profile-card { background:#fff;border-radius:16px;border:1px solid var(--paper2);overflow:hidden; }
    .ath-avatar-wrap { background:var(--ink);padding:32px;display:flex;flex-direction:column;align-items:center;gap:10px;position:relative;overflow:hidden; }
    .ath-avatar-wrap::before { content:'';position:absolute;top:-30px;right:-30px;width:100px;height:100px;background:radial-gradient(circle,rgba(192,57,43,.4),transparent 70%); }
    .ath-avatar-big { width:64px;height:64px;border-radius:14px;background:linear-gradient(135deg,var(--red),var(--gold));display:flex;align-items:center;justify-content:center;font-family:'Cinzel',serif;font-size:24px;font-weight:700;color:#fff;position:relative;z-index:1; }
    .ath-name { font-family:'Cinzel',serif;font-size:13px;font-weight:700;color:#fff;text-align:center;position:relative;z-index:1; }
    .ath-nik  { font-size:10.5px;color:var(--smoke);position:relative;z-index:1;font-family:monospace; }
    .ath-chips { display:flex;gap:5px;flex-wrap:wrap;justify-content:center;position:relative;z-index:1; }
    .ath-chip { padding:3px 9px;border-radius:20px;font-size:10px;font-weight:600; }
    .chip-gender { background:rgba(52,152,219,.2);color:#7fc4f4; }
    .chip-gender.female { background:rgba(192,57,43,.2);color:#f4a7a7; }
    .chip-weight { background:rgba(212,168,67,.2);color:var(--gold-lt); }

    .sidebar-info { padding:0; }
    .sinfo-row { padding:11px 18px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--paper2);font-size:12.5px; }
    .sinfo-row:last-child { border-bottom:none; }
    .sinfo-label { color:var(--smoke);font-size:10.5px;font-weight:500;text-transform:uppercase;letter-spacing:.05em; }
    .sinfo-value { font-weight:600;color:var(--ink);font-size:12.5px; }

    .bpjs-badge { background:#27ae60;border-radius:12px;padding:12px 18px;display:flex;align-items:center;gap:10px; }
    .bpjs-badge i { color:#fff;font-size:16px; }
    .bpjs-badge .btext { font-size:11px;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:.08em; }
    .bpjs-badge .bval  { font-size:13px;font-weight:700;color:#fff; }

    /* ── RIGHT CONTENT ── */
    .ath-right { display:flex;flex-direction:column;gap:16px; }

    /* ── TABS ── */
    .tab-nav { background:#fff;border-radius:12px;border:1px solid var(--paper2);padding:5px;display:flex;gap:3px; }
    .tab-btn { flex:1;padding:9px 10px;border:none;border-radius:9px;background:none;font-size:11.5px;font-family:'DM Sans',sans-serif;font-weight:500;color:var(--smoke);cursor:pointer;transition:all .15s;text-align:center; }
    .tab-btn.active { background:var(--ink);color:#fff;font-weight:600; }
    .tab-btn:hover:not(.active) { background:var(--paper);color:var(--ink); }

    /* ── TAB PANELS ── */
    .tab-panel { background:#fff;border-radius:16px;border:1px solid var(--paper2);padding:22px; }
    .panel-grid { display:grid;grid-template-columns:1fr 1fr;gap:18px; }
    .field-block { }
    .field-label { font-size:10.5px;color:var(--smoke);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px; }
    .field-value { font-size:13.5px;font-weight:600;color:var(--ink); }
    .section-title { font-family:'Cinzel',serif;font-size:12px;font-weight:700;margin:20px 0 12px;padding-top:18px;border-top:1px solid var(--paper2); }

    /* ── TIMELINE ── */
    .timeline { padding-left:24px;position:relative; }
    .timeline::before { content:'';position:absolute;left:7px;top:6px;bottom:6px;width:2px;background:var(--paper2); }
    .tl-item { position:relative;padding-bottom:20px; }
    .tl-item::before { content:'';position:absolute;left:-21px;top:4px;width:10px;height:10px;border-radius:50%;background:#fff;border:2px solid var(--red); }
    .tl-name { font-size:13px;font-weight:600; }
    .tl-date { font-size:11px;color:var(--smoke);margin-top:2px; }
    .tl-notes { font-size:11.5px;color:var(--smoke);font-style:italic;margin-top:4px; }

    /* ── CATEGORY GRID ── */
    .cat-grid { display:grid;grid-template-columns:1fr 1fr;gap:10px; }
    .cat-item { background:var(--paper);border-radius:11px;border:1px solid var(--paper2);padding:14px;display:flex;align-items:center;justify-content:space-between;transition:all .15s; }
    .cat-item:hover { background:#fff;box-shadow:0 4px 16px rgba(0,0,0,.06); }
    .cat-name { font-size:13px;font-weight:600; }
    .cat-type { font-size:10.5px;color:var(--smoke);margin-top:2px; }

    /* ── REGISTRATIONS ── */
    .reg-item { padding:13px 0;border-bottom:1px solid var(--paper2);display:flex;align-items:center;justify-content:space-between; }
    .reg-item:last-child { border-bottom:none; }
    .reg-code { font-size:11.5px;font-family:monospace;color:var(--red); }
    .reg-contingent { font-size:13px;font-weight:600; }
    .reg-date { font-size:11px;color:var(--smoke); }
    .status-dot { display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:20px;font-size:10.5px;font-weight:600; }
    .status-dot.verified { background:rgba(39,174,96,.12);color:#1e8449; }
    .status-dot.pending  { background:rgba(212,168,67,.15);color:#9a6e00; }
    .status-dot.rejected { background:rgba(192,57,43,.12);color:var(--red); }

    /* ── ACHIEVEMENT ── */
    .ach-item { display:flex;gap:10px;padding:10px 0;border-bottom:1px solid var(--paper2); }
    .ach-item:last-child { border-bottom:none; }
    .ach-dot { width:7px;height:7px;border-radius:50%;background:var(--red);margin-top:5px;flex-shrink:0; }
    .ach-text { font-size:13px;color:var(--ink); }

    /* ── EMPTY ── */
    .empty-block { padding:40px;text-align:center;color:var(--smoke); }
    .empty-block i { font-size:26px;display:block;margin-bottom:10px;opacity:.3; }
    .empty-block p { font-size:12px; }

    @media (max-width:1024px) { .ath-grid { grid-template-columns:1fr; } }
    @media (max-width:640px)  { .ath-page { padding:14px; } .panel-grid { grid-template-columns:1fr; } .cat-grid { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="ath-page">

        {{-- ── TOPBAR ── --}}
        <div class="ath-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('admin.new-athletes') }}" class="back-btn-a">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="ath-title">{{ $athlete->name }}</div>
                    <div class="ath-sub">Detail Profil Atlet &nbsp;·&nbsp; NIK: <span style="font-family:monospace;">{{ $athlete->nik ?? '-' }}</span></div>
                </div>
            </div>
            <div style="display:flex;gap:10px;">
                <button onclick="window.print()" class="btn-edit" style="background:var(--paper);border-color:var(--paper2);">
                    <i class="fa-solid fa-print"></i> Cetak Profil
                </button>
                <a href="{{ route('admin.new-athletes.edit', $athlete->id) }}" class="btn-edit" style="background:var(--red);color:#fff;border:none;">
                    <i class="fa-solid fa-pen"></i> Edit Profil
                </a>
            </div>
        </div>

        {{-- ── MAIN GRID ── --}}
        <div class="ath-grid">

            {{-- LEFT SIDEBAR --}}
            <div class="ath-sidebar">
                <div class="ath-profile-card">
                    <div class="ath-avatar-wrap">
                        <div class="ath-avatar-big">{{ substr($athlete->name, 0, 1) }}</div>
                        <div class="ath-name">{{ $athlete->name }}</div>
                        <div class="ath-nik">{{ $athlete->nik ?? 'NIK belum ada' }}</div>
                        <div class="ath-chips">
                            <span class="ath-chip chip-gender {{ $athlete->gender === 'P' ? 'female' : '' }}">
                                <i class="fa-solid {{ $athlete->gender === 'L' ? 'fa-mars' : 'fa-venus' }}" style="font-size:8px;"></i>
                                {{ $athlete->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                            @if($athlete->weight)
                            <span class="ath-chip chip-weight">{{ $athlete->weight }} kg</span>
                            @endif
                        </div>
                    </div>
                    <div class="sidebar-info">
                        <div class="sinfo-row">
                            <span class="sinfo-label">Kontingen</span>
                            <span class="sinfo-value">{{ $athlete->contingent?->name ?? '-' }}</span>
                        </div>
                        <div class="sinfo-row">
                            <span class="sinfo-label">Tingkat / Kyu</span>
                            <span class="sinfo-value">{{ $athlete->kyu ?? '-' }}</span>
                        </div>
                        <div class="sinfo-row">
                            <span class="sinfo-label">Golongan Darah</span>
                            <span class="sinfo-value">{{ $athlete->blood_type ?? '-' }}</span>
                        </div>
                        <div class="sinfo-row">
                            <span class="sinfo-label">Telepon</span>
                            <span class="sinfo-value" style="font-family:monospace;">{{ $athlete->phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="bpjs-badge">
                    <i class="fa-solid fa-shield-heart"></i>
                    <div>
                        <div class="btext">Status BPJS</div>
                        <div class="bval">{{ $athlete->bpjs_status ?? 'Aktif' }}</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT CONTENT --}}
            <div class="ath-right">
                {{-- TABS --}}
                <div class="tab-nav">
                    <button class="tab-btn {{ $activeTab === 'identity' ? 'active' : '' }}" wire:click="switchTab('identity')">
                        <i class="fa-solid fa-id-card" style="font-size:10px;"></i> Identitas
                    </button>
                    <button class="tab-btn {{ $activeTab === 'medical' ? 'active' : '' }}" wire:click="switchTab('medical')">
                        <i class="fa-solid fa-heart-pulse" style="font-size:10px;"></i> Kesehatan
                    </button>
                    <button class="tab-btn {{ $activeTab === 'categories' ? 'active' : '' }}" wire:click="switchTab('categories')">
                        <i class="fa-solid fa-medal" style="font-size:10px;"></i> Kategori
                    </button>
                    <button class="tab-btn {{ $activeTab === 'registrations' ? 'active' : '' }}" wire:click="switchTab('registrations')">
                        <i class="fa-solid fa-file-invoice" style="font-size:10px;"></i> Registrasi
                    </button>
                    <button class="tab-btn {{ $activeTab === 'history' ? 'active' : '' }}" wire:click="switchTab('history')">
                        <i class="fa-solid fa-clock-rotate-left" style="font-size:10px;"></i> Riwayat
                    </button>
                </div>

                {{-- IDENTITY TAB --}}
                @if($activeTab === 'identity')
                <div class="tab-panel">
                    <div class="panel-grid">
                        <div class="field-block">
                            <div class="field-label">Nama Lengkap</div>
                            <div class="field-value">{{ $athlete->name }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-label">NIK</div>
                            <div class="field-value" style="font-family:monospace;">{{ $athlete->nik ?? '-' }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-label">Tempat Lahir</div>
                            <div class="field-value">{{ $athlete->birth_place ?? '-' }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-label">Tanggal Lahir</div>
                            <div class="field-value">{{ $athlete->birth_date?->format('d F Y') ?? '-' }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-label">Jenis Kelamin</div>
                            <div class="field-value">{{ $athlete->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-label">Golongan Darah</div>
                            <div class="field-value">{{ $athlete->blood_type ?? '-' }}</div>
                        </div>
                        <div class="field-block" style="grid-column:span 2;">
                            <div class="field-label">Alamat</div>
                            <div class="field-value">{{ $athlete->address ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="section-title">Riwayat Prestasi</div>
                    @forelse($athlete->achievement_history ?? [] as $ach)
                        @if($ach)
                        <div class="ach-item">
                            <div class="ach-dot"></div>
                            <div class="ach-text">{{ $ach }}</div>
                        </div>
                        @endif
                    @empty
                        <div style="color:var(--smoke);font-size:12.5px;font-style:italic;">Belum ada riwayat prestasi.</div>
                    @endforelse
                </div>
                @endif

                {{-- MEDICAL TAB --}}
                @if($activeTab === 'medical')
                <div class="tab-panel">
                    <div class="panel-grid">
                        <div class="field-block">
                            <div class="field-label">Nomor BPJS</div>
                            <div class="field-value" style="font-family:monospace;">{{ $athlete->bpjs_number ?? '-' }}</div>
                        </div>
                        <div class="field-block">
                            <div class="field-label">Status Kepesertaan</div>
                            <div class="field-value" style="display:flex;align-items:center;gap:6px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#27ae60;display:inline-block;"></span>
                                {{ $athlete->bpjs_status ?? 'Aktif' }}
                            </div>
                        </div>
                    </div>
                    <div class="section-title">Berkas Dokumen</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <div style="background:var(--paper);border:2px dashed var(--paper2);border-radius:10px;padding:24px;text-align:center;">
                            <i class="fa-solid fa-id-card" style="font-size:22px;color:var(--smoke);display:block;margin-bottom:8px;"></i>
                            <div style="font-size:11.5px;color:var(--smoke);">Kartu Identitas</div>
                        </div>
                        <div style="background:var(--paper);border:2px dashed var(--paper2);border-radius:10px;padding:24px;text-align:center;">
                            <i class="fa-solid fa-shield-heart" style="font-size:22px;color:var(--smoke);display:block;margin-bottom:8px;"></i>
                            <div style="font-size:11.5px;color:var(--smoke);">Kartu BPJS</div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- CATEGORIES TAB --}}
                @if($activeTab === 'categories')
                <div class="tab-panel">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                        <div style="font-family:'Cinzel',serif;font-size:12.5px;font-weight:700;">Kategori Lomba</div>
                        <span style="background:var(--red);color:#fff;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600;">
                            {{ $athlete->categories->count() }} Kategori
                        </span>
                    </div>
                    <div class="cat-grid">
                        @forelse($athlete->categories as $cat)
                        <div class="cat-item">
                            <div>
                                <div class="cat-name">{{ $cat->name }}</div>
                                <div class="cat-type">{{ $cat->type ?? '-' }} · {{ $cat->age_group ?? '-' }}</div>
                            </div>
                            <i class="fa-solid fa-medal" style="color:var(--paper2);font-size:18px;"></i>
                        </div>
                        @empty
                        <div class="empty-block" style="grid-column:span 2;">
                            <i class="fa-solid fa-layer-group"></i>
                            <p>Belum terdaftar di kategori manapun</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endif

                {{-- REGISTRATIONS TAB --}}
                @if($activeTab === 'registrations')
                <div class="tab-panel">
                    <div style="font-family:'Cinzel',serif;font-size:12.5px;font-weight:700;margin-bottom:16px;">
                        Riwayat Pendaftaran ({{ $athlete->registrations->count() }})
                    </div>
                    @forelse($athlete->registrations as $reg)
                    <div class="reg-item">
                        <div>
                            <div class="reg-contingent">{{ $reg->contingent?->name ?? '-' }}</div>
                            <div class="reg-code">{{ $reg->referral_code }}</div>
                            <div class="reg-date">{{ $reg->created_at->format('d M Y') }}</div>
                        </div>
                        <span class="status-dot {{ $reg->status }}">{{ strtoupper($reg->status) }}</span>
                    </div>
                    @empty
                    <div class="empty-block">
                        <i class="fa-solid fa-file-circle-xmark"></i>
                        <p>Belum ada data pendaftaran</p>
                    </div>
                    @endforelse
                </div>
                @endif

                {{-- HISTORY TAB --}}
                @if($activeTab === 'history')
                <div class="tab-panel">
                    <div style="font-family:'Cinzel',serif;font-size:12.5px;font-weight:700;margin-bottom:18px;">
                        Riwayat Perpindahan Kontingen
                    </div>
                    @forelse($athlete->contingentHistories as $hist)
                    <div class="timeline">
                        <div class="tl-item">
                            <div class="tl-name">{{ $hist->contingent?->name ?? '-' }}</div>
                            <div class="tl-date">{{ $hist->moved_at?->format('d M Y') }}</div>
                            <div class="tl-notes">{{ $hist->notes ?? 'Tidak ada catatan.' }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-block">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <p>Belum ada riwayat perpindahan kontingen</p>
                    </div>
                    @endforelse
                </div>
                @endif

            </div>{{-- /.ath-right --}}
        </div>{{-- /.ath-grid --}}
    </div>
</div>
