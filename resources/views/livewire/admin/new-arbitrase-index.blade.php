<div>
    @push('styles')
        <style>
            /* ══════════════════════════════════════════════════════
               PAGE STYLES — Master Arbitrase (Premium Layout)
            ══════════════════════════════════════════════════════ */
            .prem-page-a {
                background: var(--paper);
                color: var(--ink);
                padding: 28px;
            }

            /* ── PAGE HEADER ── */
            .page-hdr-a {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 14px;
                margin-bottom: 24px;
            }

            .page-hdr-a h2 {
                font-family: 'Cinzel', serif;
                font-size: 20px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 4px;
            }

            .page-hdr-a p {
                font-size: 12px;
                color: var(--smoke);
                margin: 0;
            }

            .btn-prem-add {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: var(--red);
                color: #fff;
                border: none;
                border-radius: 12px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                font-family: 'DM Sans', sans-serif;
                transition: all .2s;
                white-space: nowrap;
                box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2);
            }

            .btn-prem-add:hover {
                background: var(--red-deep);
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(192, 57, 43, 0.3);
                color: #fff;
            }

            /* ── STAT CARDS ── */
            .stats-grid-a {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 14px;
                margin-bottom: 22px;
            }

            .stat-card-a {
                background: #fff;
                border-radius: 16px;
                padding: 18px 20px;
                border: 1px solid var(--paper2);
                position: relative;
                overflow: hidden;
                transition: transform .2s, box-shadow .2s;
            }

            .stat-card-a:hover {
                transform: translateY(-3px);
                box-shadow: 0 10px 32px rgba(0, 0, 0, .08);
            }

            .stat-card-a::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: 0;
                width: 70px;
                height: 70px;
                border-radius: 50%;
                opacity: .08;
                transform: translate(20px, 20px);
            }

            .stat-card-a.gold::after {
                background: var(--gold);
            }

            .st-icon {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                margin-bottom: 12px;
            }

            .st-icon.gold {
                background: rgba(212, 168, 67, 0.15);
                color: #b8860b;
            }

            .st-val {
                font-family: 'Cinzel', serif;
                font-size: 26px;
                font-weight: 700;
                color: var(--ink);
                line-height: 1;
                margin-bottom: 4px;
            }

            .st-lbl {
                font-size: 11px;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .05em;
                font-weight: 600;
            }

            /* ── TOOLBAR ── */
            .tool-bar-a {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-bottom: 16px;
            }

            .search-box-a {
                position: relative;
                flex: 1;
                min-width: 240px;
            }

            .search-box-a i {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--smoke);
                font-size: 13px;
            }

            .search-box-a input {
                width: 100%;
                padding: 10px 14px 10px 38px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12.5px;
                font-family: 'DM Sans', sans-serif;
                outline: none;
                transition: border .15s;
                background: #fff;
                box-sizing: border-box;
            }

            .search-box-a input:focus {
                border-color: var(--red);
            }

            .filter-sel-a {
                padding: 10px 34px 10px 14px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12.5px;
                font-family: 'DM Sans', sans-serif;
                color: var(--ink);
                background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23b5afa6'/%3E%3C/svg%3E") no-repeat right 12px center;
                appearance: none;
                outline: none;
                min-width: 130px;
            }

            .filter-sel-a:focus {
                border-color: var(--red);
            }

            /* ── TABLE ── */
            .table-wrap-a {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 16px;
                overflow: hidden;
            }

            .premium-table {
                width: 100%;
                border-collapse: collapse;
            }

            .premium-table th {
                background: var(--paper);
                padding: 14px 16px;
                text-align: left;
                font-size: 10.5px;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke);
                font-weight: 700;
                border-bottom: 1px solid var(--paper2);
            }

            .premium-table td {
                padding: 16px;
                border-bottom: 1px solid var(--paper2);
                vertical-align: middle;
            }

            .premium-table tr:last-child td {
                border-bottom: none;
            }

            .premium-table tr:hover {
                background: rgba(248, 245, 240, 0.5);
            }

            .group-name {
                font-family: 'Cinzel', serif;
                font-size: 13.5px;
                font-weight: 700;
                color: var(--ink);
            }

            .badge {
                display: inline-block;
                padding: 4px 8px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
            }

            .badge-wasit {
                background: rgba(41, 128, 185, 0.1);
                color: #2980b9;
            }

            /* ── ACTION BUTTONS ── */
            .act-btn-a {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                border: 1px solid var(--paper2);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #888;
                font-size: 12px;
                text-decoration: none;
                background: #fff;
                transition: all .15s;
                cursor: pointer;
            }

            .act-btn-a:hover {
                background: var(--paper2);
            }

            .act-btn-a.view {
                color: #2980b9;
                border-color: rgba(41, 128, 185, .2);
                background: rgba(41, 128, 185, .06);
            }

            .act-btn-a.view:hover {
                background: rgba(41, 128, 185, .15);
                border-color: #2980b9;
            }

            .act-btn-a.edit {
                color: #27ae60;
                border-color: rgba(39, 174, 96, .2);
                background: rgba(39, 174, 96, .06);
            }

            .act-btn-a.edit:hover {
                background: rgba(39, 174, 96, .15);
                border-color: #27ae60;
            }

            .act-btn-a.danger {
                color: var(--red);
                border-color: rgba(192, 57, 43, .2);
                background: rgba(192, 57, 43, .05);
            }

            .act-btn-a.danger:hover {
                border-color: var(--red);
                background: rgba(192, 57, 43, 0.12);
            }

            /* ── MODAL ── */
            .modal-hdr {
                border-bottom: 1px solid var(--paper2);
                padding-bottom: 16px;
                margin-bottom: 20px;
            }

            .modal-title {
                font-family: 'Cinzel', serif;
                font-size: 16px;
                font-weight: 700;
                color: var(--ink);
            }

            .modal-field {
                margin-bottom: 16px;
            }

            .modal-field label {
                display: block;
                font-size: 11px;
                font-weight: 600;
                color: var(--smoke);
                text-transform: uppercase;
                margin-bottom: 6px;
                letter-spacing: .05em;
            }

            .modal-input,
            .modal-select,
            .modal-textarea {
                width: 100%;
                padding: 10px 14px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-family: 'DM Sans', sans-serif;
                font-size: 13px;
                outline: none;
                transition: all .2s;
                box-sizing: border-box;
                background: #fff;
            }

            .modal-input:focus,
            .modal-select:focus,
            .modal-textarea:focus {
                border-color: var(--red);
            }

            .modal-textarea {
                resize: vertical;
                min-height: 80px;
            }

            .modal-err {
                color: var(--red);
                font-size: 11px;
                font-style: italic;
                margin-top: 4px;
                display: block;
            }

            .modal-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 0;
                padding: 16px 24px 24px;
                border-top: 1px solid var(--paper2);
                position: sticky;
                bottom: 0;
                background: #fff;
                z-index: 10;
                border-bottom-left-radius: 20px;
                border-bottom-right-radius: 20px;
            }

            .btn-cancel {
                padding: 10px 20px;
                background: #fff;
                border: 1px solid var(--paper2);
                color: var(--smoke);
                border-radius: 10px;
                font-size: 12.5px;
                font-weight: 600;
                cursor: pointer;
                transition: all .2s;
            }

            .btn-cancel:hover {
                background: var(--paper);
                color: var(--ink);
            }

            .btn-save {
                padding: 10px 20px;
                background: var(--red);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 12.5px;
                font-weight: 600;
                cursor: pointer;
                transition: all .2s;
                box-shadow: 0 4px 12px rgba(192, 57, 43, 0.2);
            }

            .btn-save:hover {
                background: var(--ink);
                transform: translateY(-1px);
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            }

            .grid-2 {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            .grid-3 {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 16px;
            }

            .section-title {
                font-family: 'Cinzel', serif;
                font-size: 13px;
                font-weight: 700;
                color: var(--red);
                border-bottom: 1px solid var(--paper2);
                padding-bottom: 8px;
                margin-bottom: 16px;
                margin-top: 24px;
            }

            @media (max-width: 1024px) {
                .grid-3 {
                    grid-template-columns: 1fr 1fr;
                }
            }

            @media (max-width: 768px) {
                .stats-grid-a {
                    grid-template-columns: 1fr;
                }

                .prem-page-a {
                    padding: 16px;
                }

                .grid-2,
                .grid-3 {
                    grid-template-columns: 1fr;
                }
            }

            .modal-scroll-area {
                max-height: calc(100vh - 300px);
                overflow-y: auto;
                padding-right: 8px;
            }

            /* Custom Scrollbar for Modal Area */
            .modal-scroll-area::-webkit-scrollbar {
                width: 6px;
            }

            .modal-scroll-area::-webkit-scrollbar-track {
                background: var(--paper);
                border-radius: 10px;
            }

            .modal-scroll-area::-webkit-scrollbar-thumb {
                background: var(--paper2);
                border-radius: 10px;
            }

            .modal-scroll-area::-webkit-scrollbar-thumb:hover {
                background: var(--smoke);
            }
        </style>
    @endpush

    <div class="prem-page-a">

        {{-- HEADER --}}
        <div class="page-hdr-a">
            <div>
                <h2>Master Dewan Arbitrase (Arbitrators)</h2>
                <p>Kelola data dewan arbitrase dan akun akses pengawasan</p>
            </div>
            <button wire:click="showCreateModal" class="btn-prem-add">
                <i class="fa-solid fa-shield-halved"></i> Tambah Dewan Arbitrase
            </button>
        </div>

        {{-- STATS --}}
        <div class="stats-grid-a">
            <div class="stat-card-a gold">
                <div class="st-icon gold"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="st-val">{{ $referees->total() }}</div>
                <div class="st-lbl">Total Dewan Arbitrase</div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="table-section-prem">
            <div class="table-toolbar-prem">
                <h3>Daftar Dewan Arbitrase</h3>
                <div class="search-field-prem">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Cari nama, email, atau no lisensi...">
                </div>
                <select wire:model.live="perPage" class="perpage-select-prem">
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                    <option value="all">Semua</option>
                </select>
            </div>
            <div style="overflow-x:auto;">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Profil Dewan</th>
                            <th>Lisensi / Sertifikasi</th>
                            <th>Kontak</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($referees as $referee)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:12px;">
                                        <div
                                            style="width:36px;height:36px;border-radius:10px;background:var(--paper);display:flex;align-items:center;justify-content:center;color:var(--smoke);overflow:hidden;">
                                            @if($referee->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($referee->photo))
                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($referee->photo) }}" style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <i class="fa-solid fa-shield-halved"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="group-name">{{ $referee->user->name ?? '-' }}</div>
                                            <div style="font-size:12px;color:var(--smoke);">
                                                {{ $referee->user->email ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:13px;font-weight:700;color:var(--ink);">
                                        {{ $referee->license_number ?: '-' }}
                                    </div>
                                    <div style="margin-top:4px;">
                                        <span
                                            class="badge badge-wasit">{{ $referee->certification_level ?: 'Belum Ada' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size:12px;font-weight:600;color:var(--ink);"><i
                                            class="fa-brands fa-whatsapp" style="color:#2ecc71;"></i>
                                        {{ $referee->phone ?: '-' }}</div>
                                    <div style="font-size:11px;color:var(--smoke);margin-top:2px;">
                                        <i class="fa-solid fa-map-marker-alt"></i> {{ $referee->city ?: '-' }},
                                        {{ $referee->province ?: '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div style="display:flex;gap:5px;justify-content:flex-end;">
                                        <button wire:click="showEditModal({{ $referee->id }})" class="act-btn-a edit"
                                            title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button {{ $referee->user_id === auth()->id() ? 'disabled' : '' }}
                                            onclick="confirmDelete({{ $referee->id }}, '{{ addslashes($referee->user->name ?? '') }}')"
                                            class="act-btn-a danger" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;padding:40px 20px;">
                                    <i class="fa-solid fa-user-shield"
                                        style="font-size:32px;color:var(--paper2);margin-bottom:12px;"></i>
                                    <div style="font-family:'Cinzel',serif;font-weight:700;color:var(--ink);">Data Kosong
                                    </div>
                                    <div style="font-size:12px;color:var(--smoke);">Belum ada data dewan arbitrase.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($referees->hasPages())
                <div style="padding:12px 16px;border-top:1px solid var(--paper2);">
                    {{ $referees->links('livewire.admin.pagination') }}
                </div>
            @endif
        </div>

        {{-- MODAL --}}
        @if($showingRefereeModal)
            <x-modal wire:model.live="showingRefereeModal" maxWidth="3xl" :title="$refereeIdBeingEdited ? 'Edit Data Dewan Arbitrase' : 'Tambah Dewan Arbitrase Baru'">
                <form wire:submit="saveReferee" id="refereeForm">
                    <div style="padding: 24px;">
                        <div class="section-title" style="margin-top: 0;">Informasi Akun Login</div>
                        <div class="grid-2">
                            <div class="modal-field">
                                <label>Nama Lengkap *</label>
                                <input wire:model="name" type="text" class="modal-input" placeholder="Nama dewan arbitrase">
                                @error('name') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                            <div class="modal-field">
                                <label>Email *</label>
                                <input wire:model="email" type="email" class="modal-input" placeholder="Email untuk login">
                                @error('email') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="modal-field">
                            <label>Password {{ $refereeIdBeingEdited ? '(Kosongkan jika tdk diubah)' : '*' }}</label>
                            <input wire:model="password" type="password" class="modal-input" placeholder="Min 8 karakter">
                            @error('password') <span class="modal-err">{{ $message }}</span> @enderror
                        </div>

                        <div class="section-title">Informasi Sertifikasi</div>
                        <div class="grid-2">
                            <div class="modal-field">
                                <label>Tingkat Sertifikasi / Kualifikasi</label>
                                <input wire:model="certification_level" type="text" class="modal-input"
                                    placeholder="Contoh: Dewan Arbitrase Nasional">
                                @error('certification_level') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                            <div class="modal-field">
                                <label>Nomor Lisensi</label>
                                <input wire:model="license_number" type="text" class="modal-input"
                                    placeholder="No. Lisensi / Sertifikat">
                                @error('license_number') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="section-title">Biodata Pribadi</div>
                        <div class="grid-2">
                            <div class="modal-field">
                                <label>Nomor Induk Kependudukan (NIK)</label>
                                <input wire:model="nik" type="text" class="modal-input" placeholder="16 digit NIK">
                                @error('nik') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                            <div class="modal-field">
                                <label>No. HP / WhatsApp</label>
                                <input wire:model="phone" type="text" class="modal-input" placeholder="Contoh: 08123456789">
                                @error('phone') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid-3">
                            <div class="modal-field">
                                <label>Jenis Kelamin</label>
                                <select wire:model="gender" class="modal-select">
                                    <option value="">Pilih...</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('gender') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                            <div class="modal-field">
                                <label>Tempat Lahir</label>
                                <input wire:model="birth_place" type="text" class="modal-input">
                                @error('birth_place') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                            <div class="modal-field">
                                <label>Tanggal Lahir</label>
                                <input wire:model="birth_date" type="date" class="modal-input">
                                @error('birth_date') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid-2">
                            <div class="modal-field">
                                <label>Provinsi</label>
                                <input wire:model="province" type="text" class="modal-input" placeholder="Asal Provinsi">
                                @error('province') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                            <div class="modal-field">
                                <label>Kota / Kabupaten</label>
                                <input wire:model="city" type="text" class="modal-input" placeholder="Asal Kota/Kab">
                                @error('city') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="modal-field">
                            <label>Alamat Lengkap</label>
                            <textarea wire:model="address" class="modal-textarea"
                                placeholder="Alamat domisili dewan arbitrase..."></textarea>
                            @error('address') <span class="modal-err">{{ $message }}</span> @enderror
                        </div>

                        <div class="section-title">Foto Dewan Arbitrase</div>
                        <div class="modal-field" style="background: var(--paper); padding: 16px; border-radius: 12px; border: 1px dashed var(--paper2); display: flex; align-items: center; gap: 16px;">
                            <div style="position: relative; flex-shrink: 0; width: 80px; height: 80px; border-radius: 12px; background: #fff; border: 1px solid var(--paper2); display: flex; align-items: center; justify-content: center; color: var(--smoke);">
                                @if ($photo)
                                    <img src="{{ $photo->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                                    <button type="button" wire:click="removePhoto" style="position: absolute; top: -6px; right: -6px; background: var(--red); color: #fff; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.15); font-size: 10px; z-index: 20;" title="Hapus Foto">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @elseif ($existingPhoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingPhoto))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($existingPhoto) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                                    <button type="button" wire:click="removePhoto" style="position: absolute; top: -6px; right: -6px; background: var(--red); color: #fff; border: none; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.15); font-size: 10px; z-index: 20;" title="Hapus Foto">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                @else
                                    <i class="fa-solid fa-shield-halved" style="font-size: 28px;"></i>
                                @endif
                                
                                <div wire:loading.flex wire:target="photo" style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); flex-direction: column; align-items: center; justify-content: center; color: #fff; font-size: 10px; font-weight: 700; text-transform: uppercase; border-radius: 12px; display: none;">
                                    <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 14px; margin-bottom: 2px;"></i>
                                    <span>Wait...</span>
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--ink); margin-bottom: 4px; display: block;">Unggah Foto Resmi</label>
                                <input wire:model="photo" type="file" class="modal-input" accept="image/*" style="font-size: 12px; padding: 6px 12px;">
                                <p style="font-size: 11px; color: var(--smoke); margin: 4px 0 0;">Format: JPG, PNG, WEBP (Maks. 2MB). Foto resmi untuk profil penugasan.</p>
                                @error('photo') <span class="modal-err">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </form>

                <x-slot name="footer">
                    <button type="button" wire:click="$set('showingRefereeModal', false)" class="btn-cancel">Batal</button>
                    <button type="submit" form="refereeForm" class="btn-save">
                        <span wire:loading.remove wire:target="saveReferee">Simpan Data</span>
                        <span wire:loading wire:target="saveReferee"><i class="fa-solid fa-circle-notch fa-spin"></i>
                            Menyimpan...</span>
                    </button>
                </x-slot>
            </x-modal>

        @endif

    </div>

    @push('scripts')
        <script>
            function confirmDelete(id, name) {
                Swal.fire({
                    title: 'Hapus Dewan Arbitrase?',
                    html: `Data dewan arbitrase <b>${name}</b> dan akun loginnya akan dihapus permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#c0392b',
                    cancelButtonColor: '#7f8c8d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[1.5rem]',
                        confirmButton: 'rounded-xl font-black uppercase tracking-widest text-[13px] px-6 py-3',
                        cancelButton: 'rounded-xl font-black uppercase tracking-widest text-[13px] px-6 py-3'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.deleteReferee(id);
                    }
                });
            }
        </script>
    @endpush
</div>
