<div>
    @push('styles')
    <style>
    .create-page { padding:28px;background:var(--paper); }
    .create-topbar { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px; }
    .back-btn-c { width:36px;height:36px;border-radius:9px;border:1px solid var(--paper2);background:#fff;display:flex;align-items:center;justify-content:center;color:var(--ink);font-size:13px;text-decoration:none;transition:all .15s; }
    .back-btn-c:hover { background:var(--paper2); }
    .create-title { font-family:'Cinzel',serif;font-size:17px;font-weight:700; }
    .create-sub { font-size:11.5px;color:var(--smoke);margin-top:2px; }

    /* NIK STEP */
    .nik-card { background:#fff;border-radius:16px;border:1px solid var(--paper2);padding:36px;text-align:center;margin-bottom:20px; }
    .nik-card-title { font-family:'Cinzel',serif;font-size:14px;font-weight:700;margin-bottom:6px; }
    .nik-card-sub { font-size:12px;color:var(--smoke);margin-bottom:22px; }
    .nik-input-wrap { position:relative;max-width:360px;margin:0 auto 16px; }
    .nik-input { width:100%;padding:14px 18px;border:2px solid var(--paper2);border-radius:12px;font-size:22px;font-family:'Cinzel',serif;font-weight:700;letter-spacing:.2em;text-align:center;outline:none;transition:border .15s;box-sizing:border-box; }
    .nik-input:focus { border-color:var(--red); }
    .nik-actions { display:flex;gap:12px;justify-content:center; }
    .btn-search { padding:12px 24px;background:var(--red);color:#fff;border:none;border-radius:12px;font-family:'Cinzel',serif;font-size:12px;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;transition:all .2s;box-shadow: 0 4px 12px rgba(192,57,43,0.15); }
    .btn-search:hover { background:var(--ink); transform:translateY(-1px); box-shadow: 0 6px 16px rgba(192,57,43,0.25); }
    .btn-reset-c { padding:12px 20px;background:#fff;border:1px solid var(--paper2);border-radius:12px;font-family:'DM Sans',sans-serif;font-size:12.5px;font-weight:500;cursor:pointer;color:var(--smoke);transition:all .2s; }
    .btn-reset-c:hover { background:var(--paper);color:var(--ink); border-color:var(--smoke); }

    /* STATUS BANNER */
    .status-banner { border-radius:11px;padding:12px 18px;display:flex;align-items:center;gap:12px;margin-bottom:18px;font-size:13px; }
    .status-banner.edit { background:rgba(52,152,219,.08);border:1px solid rgba(52,152,219,.2); }
    .status-banner.new  { background:rgba(39,174,96,.08);border:1px solid rgba(39,174,96,.2); }
    .status-banner i { font-size:16px; }
    .status-banner.edit i { color:#2980b9; }
    .status-banner.new  i { color:#27ae60; }
    .status-banner strong { font-size:12.5px;font-weight:700; }
    .status-banner p { font-size:11.5px;color:var(--smoke);margin:2px 0 0; }

    /* FORM GRID */
    .create-grid { display:grid;grid-template-columns:1fr 320px;gap:20px; }
    .e-card { background:#fff;border-radius:16px;border:1px solid var(--paper2);margin-bottom:16px;overflow:hidden; }
    .e-card-head { padding:16px 22px;border-bottom:1px solid var(--paper2);display:flex;align-items:center;gap:10px; }
    .e-card-head .e-icon { width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0; }
    .e-card-head h3 { font-family:'Cinzel',serif;font-size:12.5px;font-weight:700;margin:0;flex:1; }
    .e-card-body { padding:20px 22px; }
    .form-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
    .form-grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px; }
    .field { display:flex;flex-direction:column;gap:5px; }
    .field.full { grid-column:span 2; }
    .field label { font-size:10.5px;color:var(--smoke);font-weight:600;text-transform:uppercase;letter-spacing:.06em; }
    .field-input { padding:9px 13px;border:1px solid var(--paper2);border-radius:9px;font-size:13px;color:var(--ink);background:#fff;font-family:'DM Sans',sans-serif;outline:none;transition:border .15s;width:100%;box-sizing:border-box; }
    .field-input:focus { border-color:var(--red); }
    .field-input::placeholder { color:var(--smoke); }
    .field-select { appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23b5afa6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;padding-right:32px; }
    .field-error { font-size:11px;color:var(--red);font-style:italic; }
    .ach-row { display:flex;gap:8px;margin-bottom:8px;align-items:center; }
    .ach-del { width:32px;height:32px;border:1px solid var(--paper2);border-radius:8px;background:none;cursor:pointer;color:var(--smoke);font-size:11px;flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .15s; }
    .ach-del:hover { background:rgba(192,57,43,.08);color:var(--red);border-color:rgba(192,57,43,.2); }

    /* SIDEBAR RIGHT */
    .create-sidebar { display:flex;flex-direction:column;gap:16px; }
    .cat-grid { display:grid;grid-template-columns:1fr 1fr;gap:7px;max-height:220px;overflow-y:auto; }
    .cat-item { display:flex;align-items:center;gap:8px;padding:8px 11px;border:1px solid var(--paper2);border-radius:9px;cursor:pointer;transition:all .15s;user-select:none; }
    .cat-item:hover { border-color:var(--red);background:rgba(192,57,43,.04); }
    .cat-item.selected { border-color:var(--red);background:rgba(192,57,43,.07); }
    .cat-check { width:15px;height:15px;border-radius:4px;border:2px solid var(--paper2);flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .15s; }
    .cat-item.selected .cat-check { border-color:var(--red);background:var(--red); }
    .cat-label { font-size:12px;font-weight:500;color:var(--ink); }
    .btn-save-c { width:100%;padding:13px;background:var(--ink);color:#fff;border:none;border-radius:11px;font-family:'Cinzel',serif;font-size:13px;font-weight:700;cursor:pointer;transition:all .15s;display:flex;align-items:center;justify-content:center;gap:9px; }
    .btn-save-c:hover { background:var(--red); }

    @media (max-width:1100px) { .create-grid { grid-template-columns:1fr; } }
    @media (max-width:640px) { .create-page { padding:14px; } .form-grid-2,.form-grid-3 { grid-template-columns:1fr; } .field.full { grid-column:span 1; } .cat-grid { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="create-page">

        {{-- TOPBAR --}}
        <div class="create-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('admin.new-athletes') }}" class="back-btn-c">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="create-title">Tambah Atlet Baru</div>
                    <div class="create-sub">Cari berdasarkan NIK atau daftarkan atlet baru</div>
                </div>
            </div>
        </div>

        {{-- STEP 1: NIK Search --}}
        <div class="nik-card">
            <div class="nik-card-title">Langkah 1 — Verifikasi NIK</div>
            <div class="nik-card-sub">Masukkan 16 digit NIK untuk mencari data existing atau mendaftarkan profil baru</div>

            <div class="nik-input-wrap">
                <input wire:model.live.debounce.500ms="nik"
                    wire:keydown.enter="searchByNik"
                    type="text" maxlength="16"
                    placeholder="0000 0000 0000 0000"
                    class="nik-input">
                <div wire:loading wire:target="nik,searchByNik"
                    style="position:absolute;right:14px;top:50%;transform:translateY(-50%);color:var(--red);">
                    <i class="fa-solid fa-circle-notch fa-spin"></i>
                </div>
            </div>
            @error('nik')<p style="font-size:12px;color:var(--red);margin-bottom:12px;">{{ $message }}</p>@enderror

            <div class="nik-actions">
                <button type="button" wire:click="searchByNik" class="btn-search">
                    <i class="fa-solid fa-magnifying-glass"></i> Cek Database
                </button>
                <button type="button" wire:click="resetForm" class="btn-reset-c">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </button>
            </div>
        </div>

        {{-- STEP 2: Form --}}
        @if($showForm)
        <div>
            {{-- Status Banner --}}
            @if($isEdit)
            <div class="status-banner edit">
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <strong>Profil Ditemukan — Mode Edit</strong>
                    <p>Data atlet NIK {{ $nik }} berhasil dimuat. Perbarui jika ada perubahan.</p>
                </div>
            </div>
            @else
            <div class="status-banner new">
                <i class="fa-solid fa-user-plus"></i>
                <div>
                    <strong>NIK Baru — Daftarkan Atlet</strong>
                    <p>NIK {{ $nik }} belum terdaftar. Lengkapi data di bawah untuk mendaftar.</p>
                </div>
            </div>
            @endif

            <form wire:submit="save">
            <div class="create-grid">

                {{-- KIRI --}}
                <div>
                    {{-- BIODATA --}}
                    <div class="e-card">
                        <div class="e-card-head">
                            <div class="e-icon" style="background:rgba(192,57,43,.1);color:var(--red);"><i class="fa-solid fa-id-card"></i></div>
                            <h3>Biodata Lengkap</h3>
                        </div>
                        <div class="e-card-body">
                            <div class="form-grid-2">
                                <div class="field full">
                                    <label>Nama Lengkap *</label>
                                    <input wire:model="name" type="text" class="field-input" placeholder="Sesuai dokumen identitas">
                                    @error('name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <label>Jenis Kelamin *</label>
                                    <select wire:model="gender" class="field-input field-select">
                                        <option value="">Pilih...</option>
                                        <option value="Male">Laki-laki</option>
                                        <option value="Female">Perempuan</option>
                                    </select>
                                    @error('gender')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <label>Tanggal Lahir *</label>
                                    <input wire:model="birth_date" type="date" class="field-input">
                                    @error('birth_date')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <label>Tempat Lahir</label>
                                    <input wire:model="birth_place" type="text" class="field-input" placeholder="Kota lahir">
                                </div>
                                <div class="field">
                                    <label>Golongan Darah</label>
                                    <select wire:model="blood_type" class="field-input field-select">
                                        <option value="">Pilih...</option>
                                        @foreach(['A','B','AB','O'] as $bt)
                                        <option value="{{ $bt }}">{{ $bt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>No. Telepon</label>
                                    <input wire:model="phone" type="text" class="field-input" placeholder="08xx...">
                                </div>
                                <div class="field full">
                                    <label>Alamat</label>
                                    <textarea wire:model="address" class="field-input" rows="2" placeholder="Alamat domisili..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TEKNIS --}}
                    <div class="e-card">
                        <div class="e-card-head">
                            <div class="e-icon" style="background:rgba(212,168,67,.12);color:#b8860b;"><i class="fa-solid fa-dumbbell"></i></div>
                            <h3>Data Teknis & Pertandingan</h3>
                        </div>
                        <div class="e-card-body">
                            <div style="background:var(--paper);border-radius:10px;padding:14px;margin-bottom:14px;border:1px solid var(--paper2);">
                                <div class="field">
                                    <label>Kontingen *</label>
                                    <select wire:model="contingent_id" class="field-input field-select">
                                        <option value="">Pilih Kontingen...</option>
                                        @foreach($contingents as $con)
                                        <option value="{{ $con->id }}">{{ $con->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('contingent_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="form-grid-3">
                                <div class="field">
                                    <label>Kyu / Dan *</label>
                                    <select wire:model="kyu" class="field-input field-select">
                                        <option value="">Pilih...</option>
                                        @foreach($kyuLevels as $kl)
                                        <option value="{{ $kl->name }}">{{ $kl->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kyu')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <label>Berat Badan (kg) *</label>
                                    <input wire:model="weight" type="number" step="0.1" class="field-input" placeholder="00.0">
                                    @error('weight')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <label>Usia</label>
                                    <input wire:model="age" type="number" class="field-input" placeholder="0">
                                </div>
                                <div class="field">
                                    <label>Dojo / Ranting *</label>
                                    <input wire:model="dojo_origin" type="text" class="field-input" placeholder="Nama dojo">
                                    @error('dojo_origin')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <label>Kota/Wilayah *</label>
                                    <input wire:model="city" type="text" class="field-input" placeholder="Kab/Kota">
                                    @error('city')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="field">
                                    <label>Kelompok Usia</label>
                                    <select wire:model="age_group" class="field-input field-select">
                                        <option value="">Pilih...</option>
                                        @foreach(['Pemula','Remaja','Dewasa','Senior'] as $ag)
                                        <option value="{{ $ag }}">{{ $ag }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Tipe Tanding</label>
                                    <select wire:model="match_type" class="field-input field-select">
                                        <option value="">Pilih...</option>
                                        <option value="embu">Embu</option>
                                        <option value="randori">Randori</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label>Rank</label>
                                    <input wire:model="rank" type="text" class="field-input" placeholder="Rank...">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PRESTASI --}}
                    <div class="e-card">
                        <div class="e-card-head">
                            <div class="e-icon" style="background:rgba(52,152,219,.1);color:#2980b9;"><i class="fa-solid fa-trophy"></i></div>
                            <h3>Catatan Prestasi</h3>
                            <button type="button" wire:click="addAchievement" style="margin-left:auto;padding:5px 12px;background:var(--paper);border:1px solid var(--paper2);border-radius:7px;font-size:11.5px;cursor:pointer;color:var(--ink);font-family:'DM Sans',sans-serif;display:flex;align-items:center;gap:5px;">
                                <i class="fa-solid fa-plus" style="font-size:9px;"></i> Tambah
                            </button>
                        </div>
                        <div class="e-card-body">
                            @foreach($achievement_history as $idx => $ach)
                            <div class="ach-row">
                                <input wire:model="achievement_history.{{ $idx }}" type="text" class="field-input" placeholder="Contoh: Juara 1 Embu Dewasa — Piala Walikota 2025">
                                @if(count($achievement_history) > 1)
                                <button type="button" wire:click="removeAchievement({{ $idx }})" class="ach-del">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- KANAN --}}
                <div class="create-sidebar">

                    {{-- BPJS & DOKUMEN --}}
                    <div class="e-card">
                        <div class="e-card-head">
                            <div class="e-icon" style="background:rgba(39,174,96,.1);color:#27ae60;"><i class="fa-solid fa-shield-heart"></i></div>
                            <h3>BPJS & Dokumen</h3>
                        </div>
                        <div class="e-card-body" style="display:flex;flex-direction:column;gap:12px;">
                            <div class="field">
                                <label>Nomor BPJS</label>
                                <input wire:model="bpjs_number" type="text" class="field-input" placeholder="0000000...">
                            </div>
                            <div class="field">
                                <label>Status BPJS *</label>
                                <select wire:model="bpjs_status" class="field-input field-select">
                                    <option value="AKTIF">✅ Aktif & Valid</option>
                                    <option value="TIDAK_AKTIF">❌ Tidak Aktif</option>
                                    <option value="PROSES">⏳ Dalam Proses</option>
                                </select>
                                @error('bpjs_status')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label>KTP / Akte Kelahiran</label>
                                <input wire:model="identity_card" type="file" class="field-input" style="padding:6px;">
                                @if($existing_identity_card_path)
                                <div style="display:flex;align-items:center;gap:6px;padding:7px 10px;background:var(--paper);border-radius:7px;font-size:11.5px;">
                                    <i class="fa-solid fa-file-image" style="color:var(--red);"></i> File tersedia
                                </div>
                                @endif
                            </div>
                            <div class="field">
                                <label>Kartu BPJS</label>
                                <input wire:model="bpjs_card" type="file" class="field-input" style="padding:6px;">
                                @if($existing_bpjs_card_path)
                                <div style="display:flex;align-items:center;gap:6px;padding:7px 10px;background:var(--paper);border-radius:7px;font-size:11.5px;">
                                    <i class="fa-solid fa-shield-alt" style="color:#27ae60;"></i> BPJS tersedia
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="e-card">
                        <div class="e-card-head">
                            <div class="e-icon" style="background:rgba(212,168,67,.12);color:#b8860b;"><i class="fa-solid fa-medal"></i></div>
                            <h3>Kategori Lomba
                                <span style="font-size:10.5px;font-family:'DM Sans';font-weight:400;color:var(--smoke);margin-left:6px;">({{ count($selectedCategories) }} dipilih)</span>
                            </h3>
                        </div>
                        <div class="e-card-body">
                            <div class="cat-grid">
                                @foreach($categories as $cat)
                                @php $sel = in_array($cat->id, $selectedCategories); @endphp
                                <div class="cat-item {{ $sel ? 'selected' : '' }}"
                                    wire:click="$toggle('selectedCategories', {{ $cat->id }})">
                                    <div class="cat-check">
                                        @if($sel)<i class="fa-solid fa-check" style="font-size:8px;color:#fff;"></i>@endif
                                    </div>
                                    <span class="cat-label">{{ $cat->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit" class="btn-save-c" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="fa-solid fa-user-{{ $isEdit ? 'pen' : 'plus' }}"></i>
                            {{ $isEdit ? 'Simpan Perubahan' : 'Daftarkan Atlet' }}
                        </span>
                        <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>

                </div>
            </div>
            </form>
        </div>
        @endif
    </div>
</div>
