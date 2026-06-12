<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Master Contingent Form (Premium Layout)
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
    
    .section-title { font-family:'Cinzel',serif; font-size:14px; font-weight:700; color:var(--red); border-bottom:1px solid var(--paper2); padding-bottom:8px; margin-bottom:20px; text-transform:uppercase; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 11px; font-weight: 600; color: var(--smoke); text-transform: uppercase; margin-bottom: 6px; letter-spacing: .05em; }
    .form-input, .form-textarea {
        width: 100%; padding: 12px 16px; border: 1px solid var(--paper2); border-radius: 10px;
        font-family: 'DM Sans', sans-serif; font-size: 13px; outline: none; transition: all .2s; box-sizing: border-box; background: #faf9f7;
    }
    .form-input:focus, .form-textarea:focus { border-color: var(--red); background: #fff; }
    .form-textarea { resize: vertical; min-height:100px; }
    .form-err { color: var(--red); font-size: 12px; font-style: italic; margin-top: 6px; display: block; }

    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; }

    /* ── ACTIONS ── */
    .form-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 20px; border-top: 1px solid var(--paper2); }
    .btn-save {
      padding: 12px 24px; background: var(--red); color: #fff; border: none; border-radius: 10px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all .2s; box-shadow: 0 4px 12px rgba(192,57,43,0.2);
    }
    .btn-save:hover { background: var(--ink); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }

    @media (max-width: 768px) {
      .prem-page-a { padding: 16px; }
      .grid-2 { grid-template-columns: 1fr; }
    }
    </style>
    @endpush

    <div class="prem-page-a">
        <div class="page-hdr-a">
            <div>
                <h2>{{ $isEdit ? 'Edit Kontingen' : 'Tambah Kontingen' }}</h2>
                <p>Silakan lengkapi formulir pendaftaran kontingen</p>
            </div>
            <a href="{{ route('admin.new-contingents') }}" class="btn-back" >
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>

        <form wire:submit="save">
            <div class="form-card">
                <div class="section-title">Identitas Kontingen</div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Nama Kontingen / Dojo *</label>
                        <input wire:model="name" type="text" class="form-input" placeholder="Contoh: Perkemi Kota Bandung">
                        @error('name') <span class="form-err">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Asal Kabupaten / Kota *</label>
                        <input wire:model="kab_kota" type="text" class="form-input" placeholder="Contoh: Kota Bandung">
                        @error('kab_kota') <span class="form-err">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="section-title" style="margin-top:20px;">Informasi Manajer Tim / Penanggung Jawab</div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Nama Lengkap Manajer *</label>
                        <input wire:model="leader_name" type="text" class="form-input" placeholder="Nama lengkap penanggung jawab">
                        @error('leader_name') <span class="form-err">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>No. HP / WhatsApp (Aktif) *</label>
                        <input wire:model="leader_phone" type="text" class="form-input" placeholder="Contoh: 08123456789">
                        @error('leader_phone') <span class="form-err">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Email Kontingen / Manajer *</label>
                    <input wire:model="email" type="email" class="form-input" placeholder="email@contoh.com">
                    @error('email') <span class="form-err">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Alamat Sekretariat / Domisili</label>
                    <textarea wire:model="address" class="form-textarea" placeholder="Alamat lengkap..."></textarea>
                    @error('address') <span class="form-err">{{ $message }}</span> @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.new-contingents') }}" class="btn-back" >Batal</a>
                    <button type="submit" class="btn-save">
                        <span wire:loading.remove wire:target="save"><i class="fa-solid fa-save"></i> Simpan Data Kontingen</span>
                        <span wire:loading wire:target="save"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
