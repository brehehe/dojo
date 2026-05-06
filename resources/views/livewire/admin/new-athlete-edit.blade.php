<div>
    @push('styles')
    <style>
    .edit-page { padding: 28px; background: var(--paper); }

    /* TOPBAR */
    .edit-topbar { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px; }
    .back-btn-e { width:36px;height:36px;border-radius:9px;border:1px solid var(--paper2);background:#fff;display:flex;align-items:center;justify-content:center;color:var(--ink);font-size:13px;text-decoration:none;transition:all .15s; }
    .back-btn-e:hover { background:var(--paper2); }
    .edit-title { font-family:'Cinzel',serif;font-size:17px;font-weight:700; }
    .edit-sub { font-size:11.5px;color:var(--smoke);margin-top:2px; }

    /* GRID */
    .edit-grid { display:grid;grid-template-columns:1fr 340px;gap:20px; }

    /* SECTION CARDS */
    .e-card { background:#fff;border-radius:16px;border:1px solid var(--paper2);margin-bottom:16px;overflow:hidden; }
    .e-card-head { padding:16px 22px;border-bottom:1px solid var(--paper2);display:flex;align-items:center;gap:10px; }
    .e-card-head .e-icon { width:30px;height:30px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0; }
    .e-card-head h3 { font-family:'Cinzel',serif;font-size:12.5px;font-weight:700;margin:0;flex:1; }
    .e-card-body { padding:20px 22px; }

    /* FORM FIELDS */
    .form-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
    .form-grid-3 { display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px; }
    .field { display:flex;flex-direction:column;gap:5px; }
    .field.full { grid-column:span 2; }
    .field label { font-size:10.5px;color:var(--smoke);font-weight:600;text-transform:uppercase;letter-spacing:.06em; }
    .field-input {
      padding:9px 13px;border:1px solid var(--paper2);border-radius:9px;
      font-size:13px;color:var(--ink);background:#fff;
      font-family:'DM Sans',sans-serif;outline:none;transition:border .15s;width:100%;box-sizing:border-box;
    }
    .field-input:focus { border-color:var(--red); }
    .field-input::placeholder { color:var(--smoke); }
    .field-select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23b5afa6'/%3E%3C/svg%3E"); background-repeat:no-repeat;background-position:right 12px center; padding-right:32px; }
    .field-error { font-size:11px;color:var(--red);font-style:italic; }

    /* ACHIEVEMENT */
    .ach-row { display:flex;gap:8px;margin-bottom:8px;align-items:center; }
    .ach-del { width:32px;height:32px;border:1px solid var(--paper2);border-radius:8px;background:none;cursor:pointer;color:var(--smoke);font-size:11px;flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .15s; }
    .ach-del:hover { background:rgba(192,57,43,.08);color:var(--red);border-color:rgba(192,57,43,.2); }
    .add-ach-btn { display:inline-flex;align-items:center;gap:6px;padding:7px 14px;border:1px dashed var(--paper2);border-radius:9px;background:none;font-size:12px;color:var(--smoke);cursor:pointer;transition:all .15s;font-family:'DM Sans',sans-serif; }
    .add-ach-btn:hover { border-color:var(--red);color:var(--red);background:rgba(192,57,43,.05); }

    /* CATEGORIES */
    .cat-grid { display:grid;grid-template-columns:1fr 1fr;gap:8px;max-height:240px;overflow-y:auto; }
    .cat-item { display:flex;align-items:center;gap:8px;padding:9px 12px;border:1px solid var(--paper2);border-radius:9px;cursor:pointer;transition:all .15s;user-select:none; }
    .cat-item:hover { border-color:var(--red);background:rgba(192,57,43,.04); }
    .cat-item.selected { border-color:var(--red);background:rgba(192,57,43,.07); }
    .cat-check { width:16px;height:16px;border-radius:4px;border:2px solid var(--paper2);flex-shrink:0;display:flex;align-items:center;justify-content:center;transition:all .15s; }
    .cat-item.selected .cat-check { border-color:var(--red);background:var(--red); }
    .cat-label { font-size:12px;font-weight:500;color:var(--ink); }

    /* FILE UPLOAD */
    .file-zone { border:2px dashed var(--paper2);border-radius:10px;padding:16px;text-align:center;cursor:pointer;transition:all .15s; }
    .file-zone:hover { border-color:var(--red);background:rgba(192,57,43,.03); }
    .file-zone-label { font-size:11.5px;color:var(--smoke); }
    .file-existing { display:flex;align-items:center;gap:7px;padding:8px 12px;background:var(--paper);border-radius:8px;margin-top:8px;font-size:12px; }
    .file-existing i { color:var(--red); }

    /* SUBMIT BTN */
    .btn-save { width:100%;padding:14px;background:var(--red);color:#fff;border:none;border-radius:12px;font-family:'Cinzel',serif;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:10px;box-shadow: 0 4px 12px rgba(192,57,43,0.15); }
    .btn-save:hover { background:var(--ink); transform:translateY(-1px); box-shadow: 0 6px 18px rgba(0,0,0,0.15); }

    /* RIGHT SIDEBAR */
    .sidebar-right { display:flex;flex-direction:column;gap:16px; }
    .profile-preview { background:var(--ink);border-radius:16px;padding:24px;text-align:center;position:relative;overflow:hidden; }
    .profile-preview::before { content:'';position:absolute;top:-40px;right:-40px;width:120px;height:120px;background:radial-gradient(circle,rgba(192,57,43,.4),transparent 70%); }
    .prev-avatar { width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,var(--red),var(--gold));display:flex;align-items:center;justify-content:center;font-family:'Cinzel',serif;font-size:22px;font-weight:700;color:#fff;margin:0 auto 12px;position:relative;z-index:1; }
    .prev-name { font-family:'Cinzel',serif;font-size:13px;font-weight:700;color:#fff;position:relative;z-index:1;word-break:break-word; }
    .prev-nik  { font-size:10px;color:var(--smoke);font-family:monospace;position:relative;z-index:1;margin-top:4px; }

    /* TIMELINE */
    .tl-wrap { padding:16px 22px; }
    .tl-line { position:relative;padding-left:22px; }
    .tl-line::before { content:'';position:absolute;left:5px;top:6px;bottom:0;width:2px;background:var(--paper2); }
    .tl-item { position:relative;padding-bottom:18px; }
    .tl-item::before { content:'';position:absolute;left:-19px;top:4px;width:10px;height:10px;border-radius:50%;background:#fff;border:2px solid var(--red); }
    .tl-item:not(:first-child)::before { border-color:var(--paper2); }
    .tl-name { font-size:13px;font-weight:600; }
    .tl-date { font-size:11px;color:var(--smoke); }

    @media (max-width:1100px) { .edit-grid { grid-template-columns:1fr; } .sidebar-right { order:-1; } }
    @media (max-width:640px)  { .edit-page { padding:14px; } .form-grid-2,.form-grid-3 { grid-template-columns:1fr; } .field.full { grid-column:span 1; } .cat-grid { grid-template-columns:1fr; } }
    </style>
    @endpush

    <div class="edit-page">

        {{-- TOPBAR --}}
        <div class="edit-topbar">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="{{ route('admin.new-athletes.show', $athlete->id) }}" class="back-btn-e">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <div class="edit-title">Edit Profil Atlet</div>
                    <div class="edit-sub">{{ $athlete->name }} &nbsp;·&nbsp; NIK: <span style="font-family:monospace;">{{ $athlete->nik }}</span></div>
                </div>
            </div>
            <button form="editForm" type="submit" class="btn-save" style="width:auto;padding:9px 22px;">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
        </div>

        <form id="editForm" wire:submit="save">
        <div class="edit-grid">

            {{-- ─── KIRI ─── --}}
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
                                <input wire:model="name" type="text" class="field-input" placeholder="Sesuai dokumen identitas...">
                                @error('name')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="field">
                                <label>NIK (16 digit) *</label>
                                <input wire:model="nik" type="text" class="field-input" maxlength="16" placeholder="0000000000000000">
                                @error('nik')<span class="field-error">{{ $message }}</span>@enderror
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
                                <input wire:model="birth_place" type="text" class="field-input" placeholder="Kota lahir...">
                            </div>
                            <div class="field">
                                <label>Golongan Darah</label>
                                <select wire:model="blood_type" class="field-input field-select">
                                    <option value="">Pilih...</option>
                                    @foreach(['A','B','AB','O'] as $bt)
                                    <option value="{{ $bt }}" @selected($blood_type == $bt)>{{ $bt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>No. Telepon</label>
                                <input wire:model="phone" type="text" class="field-input" placeholder="08xx...">
                            </div>
                            <div class="field full">
                                <label>Alamat Lengkap</label>
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
                                    <option value="{{ $con->id }}" @selected($contingent_id == $con->id)>{{ $con->name }}</option>
                                    @endforeach
                                </select>
                                @error('contingent_id')<span class="field-error">{{ $message }}</span>@enderror
                                <span style="font-size:11px;color:var(--smoke);font-style:italic;">* Perubahan kontingen akan mencatat riwayat perpindahan otomatis.</span>
                            </div>
                        </div>
                        <div class="form-grid-3">
                            <div class="field">
                                <label>Tingkatan (Kyu/Dan) *</label>
                                <select wire:model="kyu" class="field-input field-select">
                                    <option value="">Pilih Kyu...</option>
                                    @foreach($kyuLevels as $kl)
                                    <option value="{{ $kl->name }}" @selected($kyu == $kl->name)>{{ $kl->name }}</option>
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
                                <label>Dojo / Ranting Asal *</label>
                                <input wire:model="dojo_origin" type="text" class="field-input" placeholder="Nama dojo...">
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
                                    <option value="{{ $ag }}" @selected($age_group == $ag)>{{ $ag }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Tipe Tanding</label>
                                <select wire:model="match_type" class="field-input field-select">
                                    <option value="">Pilih...</option>
                                    <option value="embu" @selected($match_type=='embu')>Embu</option>
                                    <option value="randori" @selected($match_type=='randori')>Randori</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Rank / Peringkat</label>
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

            {{-- ─── KANAN ─── --}}
            <div class="sidebar-right">

                {{-- PREVIEW PROFIL --}}
                <div class="profile-preview">
                    <div class="prev-avatar">{{ substr($athlete->name, 0, 1) }}</div>
                    <div class="prev-name">{{ $athlete->name }}</div>
                    <div class="prev-nik">{{ $athlete->nik }}</div>
                    <div style="display:flex;gap:6px;justify-content:center;margin-top:12px;flex-wrap:wrap;position:relative;z-index:1;">
                        <span style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);padding:3px 9px;border-radius:20px;font-size:10px;">
                            {{ $athlete->gender === 'L' || $athlete->gender === 'Male' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        @if($athlete->contingent)
                        <span style="background:rgba(192,57,43,.3);color:rgba(255,255,255,.9);padding:3px 9px;border-radius:20px;font-size:10px;">
                            {{ $athlete->contingent?->name }}
                        </span>
                        @endif
                    </div>
                </div>

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
                            <div class="file-zone">
                                <input wire:model="identity_card" type="file" style="width:100%;font-size:12px;cursor:pointer;">
                            </div>
                            @if($existing_identity_card_path)
                            <div class="file-existing"><i class="fa-solid fa-file-image"></i> File tersedia</div>
                            @endif
                        </div>
                        <div class="field">
                            <label>Kartu BPJS</label>
                            <div class="file-zone">
                                <input wire:model="bpjs_card" type="file" style="width:100%;font-size:12px;cursor:pointer;">
                            </div>
                            @if($existing_bpjs_card_path)
                            <div class="file-existing"><i class="fa-solid fa-shield-alt"></i> BPJS tersedia</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- KATEGORI --}}
                <div class="e-card">
                    <div class="e-card-head">
                        <div class="e-icon" style="background:rgba(212,168,67,.12);color:#b8860b;"><i class="fa-solid fa-medal"></i></div>
                        <h3>Kategori Lomba
                            <span style="font-size:10.5px;font-family:'DM Sans';font-weight:400;color:var(--smoke);margin-left:6px;">
                                ({{ count($selectedCategories) }} dipilih)
                            </span>
                        </h3>
                    </div>
                    <div class="e-card-body">
                        <div class="cat-grid">
                            @foreach($categories as $cat)
                            @php $isSelected = in_array($cat->id, $selectedCategories); @endphp
                            <div class="cat-item {{ $isSelected ? 'selected' : '' }}"
                                wire:click="$toggle('selectedCategories', {{ $cat->id }})">
                                <div class="cat-check">
                                    @if($isSelected)<i class="fa-solid fa-check" style="font-size:8px;color:#fff;"></i>@endif
                                </div>
                                <span class="cat-label">{{ $cat->name }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- RIWAYAT KONTINGEN --}}
                @php $histories = \App\Models\Athlete::find($athleteId)?->contingentHistories()->with('contingent')->get(); @endphp
                @if($histories && $histories->count())
                <div class="e-card">
                    <div class="e-card-head">
                        <div class="e-icon" style="background:rgba(192,57,43,.08);color:var(--red);"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <h3>Riwayat Kontingen</h3>
                    </div>
                    <div class="tl-wrap">
                        <div class="tl-line">
                            @foreach($histories as $hist)
                            <div class="tl-item">
                                <div class="tl-name">{{ $hist->contingent?->name ?? '-' }}</div>
                                <div class="tl-date">{{ $hist->moved_at?->format('d M Y') }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- SAVE BUTTON --}}
                <button type="submit" class="btn-save" wire:loading.attr="disabled">
                    <span wire:loading.remove><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</span>
                    <span wire:loading><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                </button>

            </div>
        </div>
        </form>
    </div>
</div>
