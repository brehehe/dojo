
<div>
    @push('styles')
        <style>
            /* ══════════════════════════════════════════════════════
               PAGE STYLES — Merge Nomer Pertandingan (Premium)
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

            .stat-card-a.blue::after {
                background: #3498db;
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

            .st-icon.blue {
                background: rgba(52, 152, 219, 0.15);
                color: #3498db;
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

            /* ── TABLE ── */
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

            .premium-table tr:hover {
                background: rgba(248, 245, 240, 0.5);
            }

            .merge-name {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                font-weight: 700;
                color: var(--ink);
                display: block;
            }

            .merge-type {
                font-size: 10px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: .05em;
                color: var(--smoke);
            }

            .badge-mn {
                display: inline-block;
                padding: 3px 8px;
                background: var(--paper);
                color: var(--ink);
                border-radius: 6px;
                font-size: 10px;
                font-weight: 700;
                margin: 2px;
                border: 1px solid var(--paper2);
                text-transform: uppercase;
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
            .modal-select {
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
            .modal-select:focus {
                border-color: var(--red);
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
                margin-top: 24px;
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

            .check-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 10px;
                max-height: 250px;
                overflow-y: auto;
                padding: 12px;
                background: var(--paper);
                border-radius: 12px;
                border: 1px solid var(--paper2);
            }

            .check-item {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 10px;
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                cursor: pointer;
                transition: all .2s;
            }

            .check-item:hover {
                border-color: var(--red);
                background: rgba(192, 57, 43, 0.02);
            }

            .check-item input {
                width: 18px;
                height: 18px;
                cursor: pointer;
                accent-color: var(--red);
            }

            .check-item span {
                font-size: 12px;
                font-weight: 700;
                color: var(--ink);
            }

            @media (max-width: 768px) {
                .stats-grid-a {
                    grid-template-columns: 1fr;
                }

                .prem-page-a {
                    padding: 16px;
                }
            }
        </style>
    @endpush

    <div class="prem-page-a">
        {{-- HEADER --}}
        <div class="page-hdr-a">
            <div>
                <h2>Merge Nomer Pertandingan</h2>
                <p>Gabungkan beberapa nomor pertandingan menjadi satu kategori perlombaan</p>
            </div>
            <button wire:click="showCreateModal" class="btn-prem-add">
                <i class="fa-solid fa-object-group"></i> Buat Merge Baru
            </button>
        </div>

        {{-- STATS --}}
        <div class="stats-grid-a">
            <div class="stat-card-a gold">
                <div class="st-icon gold"><i class="fa-solid fa-object-group"></i></div>
                <div class="st-val">{{ $merges->total() }}</div>
                <div class="st-lbl">Total Merge</div>
            </div>
        </div>

        {{-- TABLE SECTION --}}
        <div class="table-section-prem">
            <div class="table-toolbar-prem">
                <h3>Daftar Penggabungan</h3>
                <div class="search-field-prem">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama merge...">
                </div>
                <select wire:model.live="perPage" class="perpage-select-prem">
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                    <option value="50">50 Baris</option>
                </select>
            </div>

            <div style="overflow-x:auto;">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Nama Merge & Tipe</th>
                            <th>Kelompok Umur</th>
                            <th>Nomor yang Digabung</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($merges as $merge)
                            @php $contingents = $merge->getContingents(); @endphp
                            <tr>
                                <td>
                                    <span class="merge-name">{{ $merge->name }}</span>
                                    <span class="merge-type">{{ $merge->type }}</span>
                                </td>
                                <td>
                                    <div style="font-size:13px; font-weight:600; color:var(--ink);">
                                        {{ $merge->ageGroup->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach ($merge->matchNumbers as $mn)
                                            <span class="badge-mn">{{ $mn->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div style="display:flex; gap:6px; justify-content:flex-end;">
                                        <button wire:click="edit({{ $merge->id }})" class="act-btn-a edit"
                                            title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button
                                            onclick="confirmDeleteMerge({{ $merge->id }}, '{{ addslashes($merge->name) }}')"
                                            class="act-btn-a danger" title="Hapus">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:40px;">
                                    <i class="fa-solid fa-box-open"
                                        style="font-size:32px; color:var(--paper2); margin-bottom:12px;"></i>
                                    <div style="font-family:'Cinzel',serif; font-weight:700; color:var(--ink);">Data
                                        Kosong</div>
                                    <div style="font-size:12px; color:var(--smoke);">Belum ada data penggabungan.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($merges->hasPages())
                <div style="padding:16px; border-top:1px solid var(--paper2);">
                    {{ $merges->links('livewire.admin.pagination') }}
                </div>
            @endif
        </div>

        {{-- MODAL --}}
        @if ($showingModal)
            <x-modal wire:model.live="showingModal" maxWidth="2xl">
                <div style="padding:24px;">
                    <div class="modal-hdr">
                        <div class="modal-title">{{ $isEdit ? 'Edit Penggabungan' : 'Tambah Penggabungan' }}</div>
                    </div>
                    <form wire:submit="save">
                        <div class="modal-field">
                            <label>Nama Penggabungan *</label>
                            <input wire:model="name" type="text" class="modal-input"
                                placeholder="Contoh: Embu Beregu Campuran Eksebisi">
                            @error('name')
                                <span class="modal-err">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="modal-field">
                                <label>Kelompok Umur *</label>
                                <select wire:model.live="ageGroupId" class="modal-select">
                                    <option value="">Pilih Kelompok Umur</option>
                                    @foreach ($ageGroups as $ag)
                                        <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                                    @endforeach
                                </select>
                                @error('ageGroupId')
                                    <span class="modal-err">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="modal-field">
                                <label>Jenis Pertandingan *</label>
                                <select wire:model.live="type" class="modal-select">
                                    <option value="embu">EMBU (Seni)</option>
                                    <option value="randori">RANDORI (Tanding)</option>
                                </select>
                                @error('type')
                                    <span class="modal-err">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-field">
                            <label>Pilih Nomor Pertandingan (Min. 2) *</label>
                            <div class="check-grid">
                                @forelse($availableMatchNumbers as $mn)
                                    <label
                                        class="check-item {{ $mn->contingent_count < 3 ? 'border-red-200 bg-red-50/10' : '' }}">
                                        <input wire:model="selectedMatchNumbers" type="checkbox"
                                            value="{{ $mn->id }}">
                                        <div style="flex:1;">
                                            <div style="font-size:12px; font-weight:700; color:var(--ink);">
                                                {{ $mn->name }}</div>
                                            <div
                                                style="font-size:10px; font-weight:600; color:{{ $mn->contingent_count < 3 ? 'var(--red)' : '#27ae60' }};">
                                                <i class="fa-solid fa-flag" style="font-size:9px;"></i>
                                                {{ $mn->contingent_count }} Kontingen
                                            </div>
                                            @if ($mn->contingent_list->isNotEmpty())
                                                <div
                                                    style="font-size:9px; color:var(--smoke); margin-top:2px; line-height:1.2;">
                                                    <span style="opacity:0.7;">Daftar:</span>
                                                    {{ $mn->contingent_list->implode(', ') }}
                                                </div>
                                            @endif
                                        </div>
                                    </label>
                                @empty
                                    <div
                                        style="grid-column: span 2; text-align:center; padding:20px; color:var(--smoke); font-size:12px; font-style:italic;">
                                        Pilih Kelompok Umur terlebih dahulu.
                                    </div>
                                @endforelse
                            </div>
                            @error('selectedMatchNumbers')
                                <span class="modal-err">{{ $message }}</span>
                            @enderror
                        </div>

                        @if ($selectedContingents->isNotEmpty())
                            <div class="modal-field"
                                style="background:rgba(39, 174, 96, 0.05); padding:16px; border-radius:12px; border:1px solid rgba(39, 174, 96, 0.1);">
                                <label style="color:#27ae60;">Kontingen Terdaftar
                                    ({{ $selectedContingents->count() }})</label>
                                <div style="display:flex; flex-wrap:wrap; gap:6px; margin-top:8px;">
                                    @foreach ($selectedContingents as $c)
                                        <div
                                            style="padding:4px 10px; background:#fff; border:1px solid #27ae60; border-radius:8px; font-size:11px; font-weight:700; color:#27ae60;">
                                            <i class="fa-solid fa-flag" style="margin-right:4px;"></i>
                                            {{ $c->name }} ({{ $c->reg_count }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="modal-actions">
                            <button type="button" wire:click="$set('showingModal', false)"
                                class="btn-cancel">Batal</button>
                            <button type="submit" class="btn-save">
                                <span wire:loading.remove wire:target="save">Simpan Penggabungan</span>
                                <span wire:loading wire:target="save"><i class="fa-solid fa-circle-notch fa-spin"></i>
                                    Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>
        @endif

    </div>

    @push('scripts')
        <script>
            function confirmDeleteMerge(id, name) {
                Swal.fire({
                    title: 'Hapus Merge?',
                    html: `Penggabungan <b>${name}</b> akan dihapus permanen.`,
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
                        @this.delete(id);
                    }
                });
            }
        </script>
    @endpush
</div>
