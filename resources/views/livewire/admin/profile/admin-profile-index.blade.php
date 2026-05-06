<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Pengaturan Akun (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .profile-page { background: var(--paper); color: var(--ink); padding: 28px; }

    /* ── PAGE HEADER ── */
    .page-hdr-profile {
        display: flex; align-items: flex-start; justify-content: space-between;
        flex-wrap: wrap; gap: 14px; margin-bottom: 28px;
    }
    .page-hdr-profile h2 {
        font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700;
        color: var(--ink); margin: 0 0 4px;
    }
    .page-hdr-profile p { font-size: 12px; color: var(--smoke); margin: 0; text-transform: uppercase; letter-spacing: .1em; }

    /* ── SECTION ROW ── */
    .profile-section-row {
        display: grid; grid-template-columns: 220px 1fr; gap: 28px;
        margin-bottom: 28px; align-items: flex-start;
    }
    .profile-section-label h3 {
        font-family: 'Cinzel', serif; font-size: 12px; font-weight: 700;
        color: var(--ink); text-transform: uppercase; letter-spacing: .05em; margin: 0 0 6px;
    }
    .profile-section-label p { font-size: 11.5px; color: var(--smoke); margin: 0; line-height: 1.6; }

    /* ── CARD ── */
    .profile-card {
        background: #fff; border-radius: 16px; border: 1px solid var(--paper2);
        overflow: hidden; padding: 24px;
    }

    /* ── DIVIDER ── */
    .profile-divider { height: 1px; background: var(--paper2); margin: 0 0 28px; }

    /* ── FORM FIELDS ── */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .form-field { margin-bottom: 0; }
    .form-field.full { grid-column: 1 / -1; }
    .form-label {
        display: block; font-size: 10.5px; font-weight: 700; color: var(--smoke);
        text-transform: uppercase; letter-spacing: .08em; margin-bottom: 7px;
    }
    .form-input {
        width: 100%; padding: 10px 14px;
        border: 1px solid var(--paper2); border-radius: 10px;
        font-family: 'DM Sans', sans-serif; font-size: 13px;
        outline: none; transition: border .15s, box-shadow .15s;
        background: var(--paper); color: var(--ink); box-sizing: border-box;
    }
    .form-input:focus { border-color: var(--red); background: #fff; box-shadow: 0 0 0 3px rgba(192,57,43,.08); }
    .form-textarea {
        width: 100%; padding: 10px 14px; border: 1px solid var(--paper2); border-radius: 10px;
        font-family: 'DM Sans', sans-serif; font-size: 13px; outline: none;
        transition: border .15s, box-shadow .15s; background: var(--paper);
        color: var(--ink); resize: vertical; min-height: 90px; box-sizing: border-box;
    }
    .form-textarea:focus { border-color: var(--red); background: #fff; box-shadow: 0 0 0 3px rgba(192,57,43,.08); }
    .form-error { color: var(--red); font-size: 11px; font-style: italic; margin-top: 4px; display: block; }

    /* ── SUB SECTION (Referee / Contingent) ── */
    .sub-section-hdr {
        display: flex; align-items: center; gap: 10px;
        padding: 14px 0 12px; border-top: 1px solid var(--paper2); margin-top: 20px;
    }
    .sub-section-hdr .sub-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 12px; border-radius: 20px; font-size: 10.5px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .08em;
    }
    .sub-badge.referee { background: rgba(192,57,43,.1); color: var(--red); }
    .sub-badge.contingent { background: rgba(52,152,219,.1); color: #2980b9; }

    /* ── FORM ACTIONS ── */
    .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 22px; }
    .btn-save-profile {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px; background: var(--ink); color: #fff;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 600;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        transition: all .2s; box-shadow: 0 4px 14px rgba(0,0,0,.15);
    }
    .btn-save-profile:hover { background: #1e1c18; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,.2); }
    .btn-save-password {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 22px; background: var(--red); color: #fff;
        border: none; border-radius: 10px; font-size: 13px; font-weight: 600;
        cursor: pointer; font-family: 'DM Sans', sans-serif;
        transition: all .2s; box-shadow: 0 4px 14px rgba(192,57,43,.25);
    }
    .btn-save-password:hover { background: var(--red-deep); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(192,57,43,.35); }

    /* ── AVATAR BLOCK ── */
    .profile-avatar-block {
        display: flex; align-items: center; gap: 18px; margin-bottom: 22px;
        padding-bottom: 20px; border-bottom: 1px solid var(--paper2);
    }
    .profile-avatar-lg {
        width: 62px; height: 62px; border-radius: 16px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--red), var(--gold));
        display: flex; align-items: center; justify-content: center;
        font-family: 'Cinzel', serif; color: #fff; font-size: 22px; font-weight: 700;
        box-shadow: 0 6px 20px rgba(192,57,43,.3);
    }
    .profile-avatar-info h4 {
        font-family: 'Cinzel', serif; font-size: 15px; font-weight: 700;
        color: var(--ink); margin: 0 0 4px;
    }
    .profile-avatar-info p { font-size: 12px; color: var(--smoke); margin: 0; }

    @media (max-width: 900px) {
        .profile-section-row { grid-template-columns: 1fr; gap: 12px; }
        .profile-section-label { display: none; }
    }
    @media (max-width: 640px) {
        .profile-page { padding: 14px; }
        .form-grid-2 { grid-template-columns: 1fr; }
    }
    </style>
    @endpush

    <div class="profile-page">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-hdr-profile">
            <div>
                <h2>Pengaturan Akun</h2>
                <p>Kelola informasi profil dan keamanan Anda</p>
            </div>
        </div>

        {{-- ══════════════════════ PROFILE INFO SECTION ══════════════════════ --}}
        <div class="profile-section-row">
            <div class="profile-section-label">
                <h3>Informasi Profil</h3>
                <p>Perbarui nama dan alamat email akun Anda.</p>
            </div>

            <div class="profile-card">
                {{-- Avatar Block --}}
                <div class="profile-avatar-block">
                    <div class="profile-avatar-lg">{{ substr(Auth::user()->name, 0, 2) }}</div>
                    <div class="profile-avatar-info">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <form wire:submit="updateProfile">
                    <div class="form-grid-2">
                        <div class="form-field">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="form-input" placeholder="Masukkan nama lengkap...">
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-field">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" wire:model="email" class="form-input" placeholder="Masukkan email...">
                            @error('email') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- ── Referee Fields ── --}}
                    @if($is_referee)
                        <div class="sub-section-hdr">
                            <span class="sub-badge referee"><i class="fa-solid fa-gavel"></i> Detail Wasit Juri</span>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-field">
                                <label class="form-label">NIK</label>
                                <input type="text" wire:model="referee_data.nik" class="form-input" placeholder="Nomor Induk Kependudukan">
                            </div>
                            <div class="form-field">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" wire:model="referee_data.phone" class="form-input" placeholder="08xx-xxxx-xxxx">
                            </div>
                            <div class="form-field">
                                <label class="form-label">Provinsi</label>
                                <input type="text" wire:model="referee_data.province" class="form-input" placeholder="Provinsi...">
                            </div>
                            <div class="form-field">
                                <label class="form-label">Kota / Kabupaten</label>
                                <input type="text" wire:model="referee_data.city" class="form-input" placeholder="Kota atau Kabupaten...">
                            </div>
                            <div class="form-field full">
                                <label class="form-label">Tingkat Sertifikasi</label>
                                <input type="text" wire:model="referee_data.certification_level" class="form-input" placeholder="Contoh: Nasional, Daerah...">
                            </div>
                        </div>
                    @endif

                    {{-- ── Contingent Fields ── --}}
                    @if($is_contingent)
                        <div class="sub-section-hdr">
                            <span class="sub-badge contingent"><i class="fa-solid fa-flag"></i> Detail Kontingen</span>
                        </div>
                        <div class="form-grid-2">
                            <div class="form-field">
                                <label class="form-label">Nama Ketua</label>
                                <input type="text" wire:model="contingent_data.leader_name" class="form-input" placeholder="Nama ketua kontingen...">
                            </div>
                            <div class="form-field">
                                <label class="form-label">No. HP Ketua</label>
                                <input type="text" wire:model="contingent_data.leader_phone" class="form-input" placeholder="08xx-xxxx-xxxx">
                            </div>
                            <div class="form-field full">
                                <label class="form-label">Kota / Kabupaten (Cabang)</label>
                                <input type="text" wire:model="contingent_data.kab_kota" class="form-input" placeholder="Kota atau Kabupaten cabang...">
                            </div>
                            <div class="form-field full">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea wire:model="contingent_data.address" class="form-textarea" placeholder="Alamat lengkap kontingen..."></textarea>
                            </div>
                        </div>
                    @endif

                    <div class="form-actions">
                        <button type="submit" class="btn-save-profile">
                            <span wire:loading.remove wire:target="updateProfile"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</span>
                            <span wire:loading wire:target="updateProfile"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="profile-divider"></div>

        {{-- ══════════════════════ PASSWORD SECTION ══════════════════════ --}}
        <div class="profile-section-row">
            <div class="profile-section-label">
                <h3>Ubah Password</h3>
                <p>Pastikan akun Anda menggunakan password yang panjang dan acak untuk tetap aman.</p>
            </div>

            <div class="profile-card">
                <form wire:submit="updatePassword">
                    <div class="form-grid-2">
                        <div class="form-field full">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" wire:model="current_password" class="form-input" placeholder="Password lama Anda...">
                            @error('current_password') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-field">
                            <label class="form-label">Password Baru</label>
                            <input type="password" wire:model="new_password" class="form-input" placeholder="Password baru...">
                            @error('new_password') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-field">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" wire:model="new_password_confirmation" class="form-input" placeholder="Ulangi password baru...">
                            @error('new_password_confirmation') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save-password">
                            <span wire:loading.remove wire:target="updatePassword"><i class="fa-solid fa-key"></i> Update Password</span>
                            <span wire:loading wire:target="updatePassword"><i class="fa-solid fa-circle-notch fa-spin"></i> Memperbarui...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>