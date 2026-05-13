<div>
    @push('styles')
        <style>
            .ctg-header {
                background: var(--ink);
                padding: 20px 16px;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .ctg-header-title {
                font-family: 'Cinzel', serif;
                font-size: 18px;
                color: #fff;
                margin: 0;
            }

            .ctg-header-sub {
                font-size: 11px;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .1em;
            }

            .ctg-search-box {
                padding: 16px;
                background: #fff;
                border-bottom: 1px solid var(--paper2);
            }

            .ctg-search-input {
                width: 100%;
                padding: 12px 16px;
                border-radius: 12px;
                border: 1px solid var(--paper2);
                background: var(--paper);
                font-size: 14px;
                outline: none;
            }

            .ctg-athlete-list {
                padding: 16px;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .ctg-athlete-card {
                background: #fff;
                border-radius: 16px;
                border: 1px solid var(--paper2);
                padding: 16px;
                display: flex;
                align-items: center;
                gap: 16px;
                transition: all .2s;
            }

            .ctg-athlete-avatar {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: var(--paper2);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                color: var(--smoke);
                flex-shrink: 0;
                overflow: hidden;
            }

            .ctg-athlete-info {
                flex: 1;
            }

            .ctg-athlete-name {
                font-size: 14px;
                font-weight: 700;
                color: var(--ink);
                margin: 0 0 2px;
            }

            .ctg-athlete-meta {
                font-size: 11px;
                color: var(--smoke);
                display: flex;
                gap: 8px;
                align-items: center;
            }

            .ctg-athlete-actions {
                display: flex;
                gap: 8px;
            }

            .ctg-btn-icon {
                width: 34px;
                height: 34px;
                border-radius: 10px;
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                cursor: pointer;
                transition: all .2s;
            }

            .ctg-btn-edit {
                background: rgba(52, 152, 219, .1);
                color: #2980b9;
            }

            .ctg-btn-delete {
                background: rgba(192, 57, 43, .1);
                color: var(--red);
            }

            /* Form Overlay */
            .ctg-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .5);
                backdrop-filter: blur(4px);
                z-index: 100;
                display: flex;
                align-items: flex-end;
            }

            .ctg-form-sheet {
                width: 100%;
                background: #fff;
                border-radius: 24px 24px 0 0;
                padding: 24px 20px calc(80px + env(safe-area-inset-bottom, 20px));
                max-height: 90vh;
                overflow-y: auto;
                box-shadow: 0 -10px 40px rgba(0, 0, 0, .1);
            }

            .ctg-form-title {
                font-family: 'Cinzel', serif;
                font-size: 16px;
                font-weight: 700;
                margin-bottom: 20px;
            }

            .ctg-form-group {
                margin-bottom: 16px;
            }

            .ctg-form-label {
                font-size: 12px;
                font-weight: 600;
                color: var(--smoke);
                margin-bottom: 6px;
                display: block;
            }

            .ctg-form-input {
                width: 100%;
                padding: 12px 14px;
                border-radius: 10px;
                border: 1px solid var(--paper2);
                background: var(--paper);
                font-size: 14px;
            }

            .ctg-form-actions {
                display: grid;
                grid-template-columns: 1fr 2fr;
                gap: 12px;
                margin-top: 24px;
            }

            .ctg-btn {
                padding: 14px;
                border-radius: 12px;
                font-size: 14px;
                font-weight: 700;
                border: none;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }

            .ctg-btn-cancel {
                background: var(--paper2);
                color: var(--smoke);
            }

            .ctg-btn-save {
                background: var(--red);
                color: #fff;
                box-shadow: 0 4px 12px rgba(192, 57, 43, .3);
            }

            .fab-add {
                position: fixed;
                bottom: 80px;
                right: 20px;
                width: 56px;
                height: 56px;
                border-radius: 18px;
                background: var(--red);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                box-shadow: 0 8px 24px rgba(192, 57, 43, .4);
                z-index: 50;
                border: none;
                cursor: pointer;
            }
        </style>
    @endpush

    <div class="ctg-header">
        <div class="ctg-header-sub">Master Data</div>
        <h2 class="ctg-header-title">Manajemen Atlet</h2>
    </div>

    <div class="ctg-search-box">
        <input type="text" wire:model.live="search" placeholder="Cari nama atau NIK atlet..." class="ctg-search-input">
    </div>

    <div class="ctg-athlete-list">
        @forelse($athletes as $athlete)
            <div class="ctg-athlete-card">
                <div class="ctg-athlete-avatar">
                    @if ($athlete->photo_path)
                        <img src="{{ asset('storage/' . $athlete->photo_path) }}"
                            style="width: 100%; height: 100%; object-cover: cover;">
                    @else
                        <i class="fa-solid fa-user"></i>
                    @endif
                </div>
                <div class="ctg-athlete-info">
                    <p class="ctg-athlete-name">{{ $athlete->name }}</p>
                    <div class="ctg-athlete-meta">
                        <span><i class="fa-solid fa-venus-mars"></i> {{ $athlete->gender == 'Male' ? 'L' : 'P' }}</span>
                        @if ($athlete->nik)
                            <span>•</span>
                            <span>{{ $athlete->nik }}</span>
                        @endif
                        @if ($athlete->nik_kenshi)
                            <span>•</span>
                            <span>{{ $athlete->nik_kenshi }}</span>
                        @endif
                        @if ($athlete->dojo_origin)
                            <span>•</span>
                            <span><i class="fa-solid fa-house-chimney"></i> {{ $athlete->dojo_origin }}</span>
                        @endif
                    </div>
                </div>
                <div class="ctg-athlete-actions">
                    <button wire:click="openEdit({{ $athlete->id }})" class="ctg-btn-icon ctg-btn-edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button onclick="confirmDeleteAthlete({{ $athlete->id }})" class="ctg-btn-icon ctg-btn-delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="ctg-empty" style="padding: 60px 20px; text-align: center;">
                <i class="fa-solid fa-users-slash"
                    style="font-size: 40px; color: var(--paper2); margin-bottom: 16px; display: block;"></i>
                <h4 style="font-family: 'Cinzel'; font-size: 15px;">Belum Ada Atlet</h4>
                <p style="font-size: 12px; color: var(--smoke);">Silakan tambah data atlet kontingen Anda.</p>
            </div>
        @endforelse

        <div style="padding-top: 10px;">
            {{ $athletes->links('livewire.admin.pagination') }}
        </div>
    </div>

    <button wire:click="openCreate" class="fab-add">
        <i class="fa-solid fa-plus"></i>
    </button>

    @if ($isEditing)
        <div class="ctg-overlay" wire:click.self="resetForm">
            <div class="ctg-form-sheet" x-data x-transition>
                <h3 class="ctg-form-title">{{ $athleteId ? 'Edit Data Atlet' : 'Tambah Atlet Baru' }}</h3>

                <div class="ctg-form-group" style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
                    <div
                        style="width: 80px; height: 80px; border-radius: 16px; background: var(--paper); border: 2px dashed var(--paper2); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @elseif($photo_path)
                            <img src="{{ asset('storage/' . $photo_path) }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fa-solid fa-camera" style="color: var(--smoke); font-size: 24px;"></i>
                        @endif
                        <input type="file" wire:model="photo"
                            style="position: absolute; inset: 0; opacity: 0; cursor: pointer;">
                    </div>
                    <div style="flex: 1;">
                        <label class="ctg-form-label">Foto Profil (3x4)</label>
                        <p style="font-size: 11px; color: var(--smoke); margin: 0;">Upload pas foto formal. Maks 2MB.
                        </p>
                        @error('photo')
                            <span style="font-size:10px; color:var(--red);">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="ctg-form-group">
                    <label class="ctg-form-label">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="ctg-form-input"
                        placeholder="Masukkan nama sesuai KTP/KK">
                    @error('name')
                        <span style="font-size:10px; color:var(--red);">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">NIK (KTP/KK)</label>
                        <input type="text" wire:model="nik" class="ctg-form-input" placeholder="16 digit NIK">
                        @error('nik')
                            <span style="font-size:10px; color:var(--red);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">NIK Kenshi</label>
                        <input type="text" wire:model="nik_kenshi" class="ctg-form-input" placeholder="No. Induk Kenshi">
                        @error('nik_kenshi')
                            <span style="font-size:10px; color:var(--red);">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">Jenis Kelamin</label>
                        <select wire:model="gender" class="ctg-form-input">
                            <option value="Male">Laki-laki</option>
                            <option value="Female">Perempuan</option>
                        </select>
                    </div>
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">Gol. Darah</label>
                        <select wire:model="blood_type" class="ctg-form-input">
                            <option value="-">-</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                            <option value="O">O</option>
                        </select>
                    </div>
                </div>

                <div class="ctg-form-group">
                    <label class="ctg-form-label">Asal Dojo</label>
                    <input type="text" wire:model="dojo_origin" class="ctg-form-input" placeholder="Nama dojo/klub">
                </div>

                <div class="ctg-form-group">
                    <label class="ctg-form-label">Tempat Lahir</label>
                    <input type="text" wire:model="birth_place" class="ctg-form-input" placeholder="Kota kelahiran">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">Tanggal Lahir</label>
                        <input type="date" wire:model="birth_date" class="ctg-form-input">
                        @error('birth_date')
                            <span style="font-size:10px; color:var(--red);">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">Nomor HP</label>
                        <input type="text" wire:model="phone" class="ctg-form-input" placeholder="0812...">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">Nomor BPJS</label>
                        <input type="text" wire:model="bpjs_number" class="ctg-form-input" placeholder="11 digit">
                    </div>
                    <div class="ctg-form-group">
                        <label class="ctg-form-label">Status BPJS</label>
                        <select wire:model="bpjs_status" class="ctg-form-input">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                            <option value="PPU">PPU</option>
                            <option value="PBPU">PBPU</option>
                        </select>
                    </div>
                </div>

                <div class="ctg-form-group">
                    <label class="ctg-form-label">Alamat Domisili</label>
                    <textarea wire:model="address" class="ctg-form-input" rows="3" placeholder="Alamat lengkap..."></textarea>
                </div>

                <div class="ctg-form-actions" style="margin-bottom: 20px;">
                    <button wire:click="resetForm" class="ctg-btn ctg-btn-cancel">Batal</button>
                    <button wire:click="save" class="ctg-btn ctg-btn-save">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                    </button>
                </div>
                
                {{-- Extra space for bottom navigation bar on mobile --}}
                <div style="height: 60px; display: block;" class="mobile-spacer"></div>
            </div>
        </div>
    @endif

    <script>
        function confirmDeleteAthlete(id) {
            Swal.fire({
                title: 'Hapus Atlet?',
                text: "Data atlet ini akan dihapus dari kontingen Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.delete(id);
                }
            })
        }
    </script>
</div>
