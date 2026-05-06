<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Master Role Form (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .prem-page-a { background: var(--paper); color: var(--ink); padding: 28px; }

    /* ── PAGE HEADER ── */
    .page-hdr-a { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .page-hdr-a h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .page-hdr-a p  { font-size: 12px; color: var(--smoke); margin: 0; }
    .btn-back {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; background: #fff; color: var(--smoke);
      border: 1px solid var(--paper2); border-radius: 12px; font-size: 13px; font-weight: 700;
      cursor: pointer; text-decoration: none; font-family: 'DM Sans', sans-serif;
      transition: all .2s;
    }
    .btn-back:hover { background: var(--paper); color: var(--ink); }

    /* ── FORM CONTAINER ── */
    .form-card { background: #fff; border-radius: 16px; border: 1px solid var(--paper2); padding: 24px; margin-bottom: 24px; }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--ink); text-transform: uppercase; margin-bottom: 8px; letter-spacing: .05em; }
    .form-input {
        width: 100%; padding: 12px 16px; border: 1px solid var(--paper2); border-radius: 10px;
        font-family: 'DM Sans', sans-serif; font-size: 14px; outline: none; transition: all .2s; box-sizing: border-box; background: #faf9f7;
    }
    .form-input:focus { border-color: var(--red); background: #fff; }
    .form-err { color: var(--red); font-size: 12px; font-style: italic; margin-top: 6px; display: block; }

    /* ── PERMISSIONS GRID ── */
    .perm-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
    .perm-group { background: #faf9f7; border: 1px solid var(--paper2); border-radius: 12px; padding: 16px; }
    .perm-group-title { font-family: 'Cinzel', serif; font-size: 14px; font-weight: 700; color: var(--red); margin-bottom: 12px; border-bottom: 1px solid var(--paper2); padding-bottom: 8px; text-transform: uppercase; }
    
    .chk-label { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; font-size: 13px; color: var(--ink); }
    .chk-label input[type="checkbox"] { width: 16px; height: 16px; cursor: pointer; accent-color: var(--red); }

    /* ── ACTIONS ── */
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid var(--paper2); }
    .btn-save {
      padding: 12px 24px; background: var(--red); color: #fff; border: none; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all .2s; box-shadow: 0 4px 12px rgba(192,57,43,0.2);
    }
    .btn-save:hover { background: var(--ink); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }

    @media (max-width: 768px) {
      .prem-page-a { padding: 16px; }
      .perm-grid { grid-template-columns: 1fr; }
    }
    </style>
    @endpush

    <div class="prem-page-a">
        <div class="page-hdr-a">
            <div>
                <h2>{{ $isEdit ? 'Edit Role' : 'Tambah Role Baru' }}</h2>
                <p>Konfigurasi nama role dan hak akses (permissions)</p>
            </div>
            <a href="{{ route('admin.new-roles') }}" class="btn-back" wire:navigate>
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form wire:submit="save">
            <div class="form-card">
                <div class="form-group">
                    <label>Nama Role *</label>
                    <input wire:model="name" type="text" class="form-input" placeholder="Contoh: Admin Regional" {{ $name === 'Super Admin' ? 'disabled' : '' }}>
                    @error('name') <span class="form-err">{{ $message }}</span> @enderror
                </div>
            </div>

            <h3 style="font-family:'Cinzel',serif;font-size:16px;font-weight:700;color:var(--ink);margin-bottom:16px;">Hak Akses (Permissions)</h3>
            
            <div class="perm-grid">
                @foreach($permissionGroups as $group => $permissions)
                    <div class="perm-group">
                        <div class="perm-group-title">{{ ucfirst($group) }}</div>
                        @foreach($permissions as $permission)
                            <label class="chk-label">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}">
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.new-roles') }}" class="btn-back" wire:navigate>Batal</a>
                <button type="submit" class="btn-save">
                    <span wire:loading.remove wire:target="save"><i class="fa-solid fa-save"></i> Simpan Role</span>
                    <span wire:loading wire:target="save"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
