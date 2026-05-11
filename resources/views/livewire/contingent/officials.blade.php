<div>
    @push('styles')
    <style>
        .ctg-header { background: var(--ink); padding: 20px 16px; position: sticky; top: 0; z-index: 10; }
        .ctg-header-title { font-family: 'Cinzel', serif; font-size: 18px; color: #fff; margin: 0; }
        .ctg-header-sub { font-size: 11px; color: var(--smoke); text-transform: uppercase; letter-spacing: .1em; }
        
        .ctg-search-box { padding: 16px; background: #fff; border-bottom: 1px solid var(--paper2); }
        .ctg-search-input { 
            width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--paper2);
            background: var(--paper); font-size: 14px; outline: none;
        }

        .ctg-list { padding: 16px; display: flex; flex-direction: column; gap: 12px; }
        .ctg-card {
            background: #fff; border-radius: 16px; border: 1px solid var(--paper2);
            padding: 16px; display: flex; align-items: center; gap: 16px;
        }
        .ctg-avatar {
            width: 50px; height: 50px; border-radius: 14px; background: rgba(39,174,96,.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #27ae60; flex-shrink: 0;
        }
        .ctg-info { flex: 1; }
        .ctg-name { font-size: 14px; font-weight: 700; color: var(--ink); margin: 0 0 2px; }
        .ctg-meta { font-size: 11px; color: var(--smoke); display: flex; gap: 8px; align-items: center; }
        
        .ctg-actions { display: flex; gap: 8px; }
        .ctg-btn-icon {
            width: 34px; height: 34px; border-radius: 10px; border: none;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; cursor: pointer;
        }
        .ctg-btn-edit { background: rgba(52,152,219,.1); color: #2980b9; }
        .ctg-btn-delete { background: rgba(192,57,43,.1); color: var(--red); }

        .ctg-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,.5); backdrop-filter: blur(4px);
            z-index: 100; display: flex; align-items: flex-end;
        }
        .ctg-form-sheet {
            width: 100%; background: #fff; border-radius: 24px 24px 0 0;
            padding: 24px 20px calc(80px + env(safe-area-inset-bottom, 20px));
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 -10px 40px rgba(0,0,0,.1);
        }
        .ctg-form-title { font-family: 'Cinzel', serif; font-size: 16px; font-weight: 700; margin-bottom: 20px; }
        .ctg-form-group { margin-bottom: 16px; }
        .ctg-form-label { font-size: 12px; font-weight: 600; color: var(--smoke); margin-bottom: 6px; display: block; }
        .ctg-form-input {
            width: 100%; padding: 12px 14px; border-radius: 10px; border: 1px solid var(--paper2);
            background: var(--paper); font-size: 14px;
        }
        .ctg-form-actions { display: grid; grid-template-columns: 1fr 2fr; gap: 12px; margin-top: 24px; }
        
        .fab-add {
            position: fixed; bottom: 80px; right: 20px;
            width: 56px; height: 56px; border-radius: 18px;
            background: var(--red); color: #fff; display: flex;
            align-items: center; justify-content: center; font-size: 24px;
            box-shadow: 0 8px 24px rgba(192,57,43,.4); z-index: 50;
            border: none; cursor: pointer;
        }
    </style>
    @endpush

    <div class="ctg-header">
        <div class="ctg-header-sub">Master Data</div>
        <h2 class="ctg-header-title">Manajemen Official</h2>
    </div>

    <div class="ctg-search-box">
        <input type="text" wire:model.live="search" placeholder="Cari nama atau jabatan..." class="ctg-search-input">
    </div>

    <div class="ctg-list">
        @forelse($officials as $official)
            <div class="ctg-card">
                <div class="ctg-avatar">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="ctg-info">
                    <p class="ctg-name">{{ $official->name }}</p>
                    <div class="ctg-meta">
                        <span style="font-weight:700; color:#27ae60;">{{ $official->role }}</span>
                        @if($official->phone)
                            <span>•</span>
                            <span>{{ $official->phone }}</span>
                        @endif
                    </div>
                </div>
                <div class="ctg-actions">
                    <button wire:click="openEdit({{ $official->id }})" class="ctg-btn-icon ctg-btn-edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button onclick="confirmDeleteOfficial({{ $official->id }})" class="ctg-btn-icon ctg-btn-delete">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
            <div class="ctg-empty" style="padding: 60px 20px; text-align: center;">
                <i class="fa-solid fa-user-slash" style="font-size: 40px; color: var(--paper2); margin-bottom: 16px; display: block;"></i>
                <h4 style="font-family: 'Cinzel'; font-size: 15px;">Belum Ada Official</h4>
                <p style="font-size: 12px; color: var(--smoke);">Silakan tambah data official kontingen Anda.</p>
            </div>
        @endforelse

        <div style="padding-top: 10px;">
            {{ $officials->links() }}
        </div>
    </div>

    <button wire:click="openCreate" class="fab-add">
        <i class="fa-solid fa-plus"></i>
    </button>

    @if($isEditing)
    <div class="ctg-overlay" wire:click.self="resetForm">
        <div class="ctg-form-sheet">
            <h3 class="ctg-form-title">{{ $officialId ? 'Edit Data Official' : 'Tambah Official Baru' }}</h3>
            
            <div class="ctg-form-group">
                <label class="ctg-form-label">Nama Lengkap</label>
                <input type="text" wire:model="name" class="ctg-form-input" placeholder="Nama lengkap official">
                @error('name') <span style="font-size:10px; color:var(--red);">{{ $message }}</span> @enderror
            </div>

            <div class="ctg-form-group">
                <label class="ctg-form-label">Jabatan / Role</label>
                <select wire:model="role" class="ctg-form-input">
                    <option value="">Pilih Jabatan</option>
                    <option value="Manager">Manager</option>
                    <option value="Pelatih">Pelatih</option>
                    <option value="Official">Official</option>
                    <option value="Medis">Tim Medis</option>
                </select>
                @error('role') <span style="font-size:10px; color:var(--red);">{{ $message }}</span> @enderror
            </div>

            <div class="ctg-form-group">
                <label class="ctg-form-label">Nomor WhatsApp</label>
                <input type="text" wire:model="phone" class="ctg-form-input" placeholder="08xxxxxx">
                @error('phone') <span style="font-size:10px; color:var(--red);">{{ $message }}</span> @enderror
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
        function confirmDeleteOfficial(id) {
            Swal.fire({
                title: 'Hapus Official?',
                text: "Data official ini akan dihapus.",
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
