<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Master Nomor Pertandingan (Premium Layout)
    ══════════════════════════════════════════════════════ */
    .prem-page-a { background: var(--paper); color: var(--ink); padding: 28px; }

    /* ── PAGE HEADER ── */
    .page-hdr-a { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
    .page-hdr-a h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
    .page-hdr-a p  { font-size: 12px; color: var(--smoke); margin: 0; }
    .btn-prem-add {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 10px 20px; background: var(--red); color: #fff;
      border: none; border-radius: 12px; font-size: 13px; font-weight: 700;
      cursor: pointer; text-decoration: none; font-family: 'DM Sans', sans-serif;
      transition: all .2s; white-space: nowrap;
      box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2);
    }
    .btn-prem-add:hover { background: var(--red-deep); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(192, 57, 43, 0.3); color: #fff; }

    /* ── STAT CARDS ── */
    .stats-grid-a { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; }
    .stat-card-a {
      background: #fff; border-radius: 16px; padding: 18px 20px;
      border: 1px solid var(--paper2); position: relative; overflow: hidden;
      transition: transform .2s, box-shadow .2s;
    }
    .stat-card-a:hover { transform: translateY(-3px); box-shadow: 0 10px 32px rgba(0,0,0,.08); }
    .stat-card-a::after {
      content: ''; position: absolute; bottom: 0; right: 0;
      width: 70px; height: 70px; border-radius: 50%; opacity: .08;
      transform: translate(20px, 20px);
    }
    .stat-card-a.gold::after  { background: var(--gold); }
    .stat-card-a.blue::after  { background: #3498db; }
    .stat-card-a.green::after { background: #2ecc71; }

    .st-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px; }
    .st-icon.gold { background: rgba(212, 168, 67, 0.15); color: #b8860b; }
    .st-icon.blue { background: rgba(52, 152, 219, 0.15); color: #3498db; }
    .st-icon.green { background: rgba(46, 204, 113, 0.15); color: #2ecc71; }
    
    .st-val { font-family: 'Cinzel', serif; font-size: 26px; font-weight: 700; color: var(--ink); line-height: 1; margin-bottom: 4px; }
    .st-lbl { font-size: 11px; color: var(--smoke); text-transform: uppercase; letter-spacing: .05em; font-weight: 600; }

    /* ── TOOLBAR ── */
    .tool-bar-a { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
    .search-box-a { position: relative; flex: 1; min-width: 200px; }
    .search-box-a i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--smoke); font-size: 13px; }
    .search-box-a input {
      width: 100%; padding: 10px 14px 10px 38px; border: 1px solid var(--paper2);
      border-radius: 10px; font-size: 12.5px; font-family: 'DM Sans', sans-serif;
      outline: none; transition: border .15s; background: #fff; box-sizing: border-box;
    }
    .search-box-a input:focus { border-color: var(--red); }
    .filter-sel-a {
      padding: 10px 34px 10px 14px; border: 1px solid var(--paper2); border-radius: 10px;
      font-size: 12.5px; font-family: 'DM Sans', sans-serif; color: var(--ink);
      background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%23b5afa6'/%3E%3C/svg%3E") no-repeat right 12px center;
      appearance: none; outline: none; min-width: 130px;
    }
    .filter-sel-a:focus { border-color: var(--red); }

    /* ── TABLE ── */
    .table-wrap-a { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; overflow: hidden; }
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th {
      background: var(--paper); padding: 14px 16px; text-align: left;
      font-size: 10.5px; text-transform: uppercase; letter-spacing: .06em;
      color: var(--smoke); font-weight: 700; border-bottom: 1px solid var(--paper2);
    }
    .premium-table td { padding: 16px; border-bottom: 1px solid var(--paper2); vertical-align: middle; }
    .premium-table tr:last-child td { border-bottom: none; }
    .premium-table tr:hover { background: rgba(248, 245, 240, 0.5); }

    .group-name { font-family: 'Cinzel', serif; font-size: 13.5px; font-weight: 700; color: var(--ink); margin-bottom:4px;}
    
    .badge { display:inline-block; padding:4px 8px; border-radius:6px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; }
    .badge-randori { background:rgba(231,76,60,0.1); color:#e74c3c; }
    .badge-embu { background:rgba(52,152,219,0.1); color:#3498db; }
    
    .badge-gender { background:var(--paper); border:1px solid var(--paper2); color:var(--ink); display:inline-flex; align-items:center; gap:4px;}

    /* ── ACTION BUTTONS ── */
    .act-btn-a {
      width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--paper2);
      display: inline-flex; align-items: center; justify-content: center;
      color: #888; font-size: 12px; text-decoration: none; background: #fff;
      transition: all .15s; cursor: pointer;
    }
    .act-btn-a:hover { background: var(--paper2); }
    .act-btn-a.view   { color: #2980b9; border-color: rgba(41,128,185,.2); background: rgba(41,128,185,.06); }
    .act-btn-a.view:hover { background: rgba(41,128,185,.15); border-color: #2980b9; }
    .act-btn-a.edit   { color: #27ae60; border-color: rgba(39,174,96,.2); background: rgba(39,174,96,.06); }
    .act-btn-a.edit:hover { background: rgba(39,174,96,.15); border-color: #27ae60; }
    .act-btn-a.danger { color: var(--red); border-color: rgba(192,57,43,.2); background: rgba(192,57,43,.05); }
    .act-btn-a.danger:hover { border-color: var(--red); background: rgba(192, 57, 43, 0.12); }

    /* ── MODAL ── */
    .modal-hdr { border-bottom: 1px solid var(--paper2); padding-bottom: 16px; margin-bottom: 20px; }
    .modal-title { font-family: 'Cinzel', serif; font-size: 16px; font-weight: 700; color: var(--ink); }
    .modal-field { margin-bottom: 16px; }
    .modal-field label { display: block; font-size: 11px; font-weight: 600; color: var(--smoke); text-transform: uppercase; margin-bottom: 6px; letter-spacing: .05em; }
    .modal-input, .modal-select {
        width: 100%; padding: 10px 14px; border: 1px solid var(--paper2); border-radius: 10px;
        font-family: 'DM Sans', sans-serif; font-size: 13px; outline: none; transition: all .2s; box-sizing: border-box; background:#fff;
    }
    .modal-input:focus, .modal-select:focus { border-color: var(--red); }
    .modal-err { color: var(--red); font-size: 11px; font-style: italic; margin-top: 4px; display: block; }
    .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
    .btn-cancel { padding: 10px 20px; background: #fff; border: 1px solid var(--paper2); color: var(--smoke); border-radius: 10px; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all .2s; }
    .btn-cancel:hover { background: var(--paper); color: var(--ink); }
    .btn-save { padding: 10px 20px; background: var(--red); color: #fff; border: none; border-radius: 10px; font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all .2s; box-shadow: 0 4px 12px rgba(192,57,43,0.2); }
    .btn-save:hover { background: var(--ink); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }

    .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }

    /* Athlete List Modal */
    .ath-list { list-style:none; padding:0; margin:0; max-height:400px; overflow-y:auto; border:1px solid var(--paper2); border-radius:10px; }
    .ath-item { padding:12px 16px; border-bottom:1px solid var(--paper2); display:flex; flex-direction:column; gap:4px; }
    .ath-item:last-child { border-bottom:none; }
    .ath-item-name { font-weight:700; font-size:13px; color:var(--ink); }
    .ath-item-meta { font-size:11px; color:var(--smoke); display:flex; gap:8px; }

    @media (max-width: 768px) {
      .stats-grid-a { grid-template-columns: 1fr; }
      .prem-page-a { padding: 16px; }
      .grid-2 { grid-template-columns:1fr; }
    }
    </style>
    @endpush

    <div class="prem-page-a">
        
        {{-- HEADER --}}
        <div class="page-hdr-a">
            <div>
                <h2>Master Nomor Pertandingan</h2>
                <p>Kelola kelas randori dan embu untuk turnamen</p>
            </div>
            <button wire:click="showCreateModal" class="btn-prem-add">
                <i class="fa-solid fa-list-check"></i> Tambah Nomor
            </button>
        </div>

        {{-- STATS --}}
        <div class="stats-grid-a">
            <div class="stat-card-a gold">
                <div class="st-icon gold"><i class="fa-solid fa-list-ol"></i></div>
                <div class="st-val">{{ $matchNumbers->total() }}</div>
                <div class="st-lbl">Total Nomor</div>
            </div>
            <div class="stat-card-a blue">
                <div class="st-icon blue"><i class="fa-solid fa-people-arrows"></i></div>
                <div class="st-val">{{ \App\Models\MatchNumber\MatchNumber::where('draft_type', 'embu')->count() }}</div>
                <div class="st-lbl">Embu</div>
            </div>
            <div class="stat-card-a green">
                <div class="st-icon green"><i class="fa-solid fa-hand-fist"></i></div>
                <div class="st-val">{{ \App\Models\MatchNumber\MatchNumber::where('draft_type', 'randori')->count() }}</div>
                <div class="st-lbl">Randori</div>
            </div>
        </div>
        {{-- TABLE --}}
        <div class="table-section-prem">
            <div class="table-toolbar-prem">
                <h3>Daftar Nomor Pertandingan</h3>
            <div class="search-field-prem">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nomor pertandingan...">
            </div>
            <select wire:model.live="filterAgeGroup" class="perpage-select-prem">
                <option value="">Semua Umur</option>
                @foreach($ageGroups as $ag)
                    <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterDraftType" class="perpage-select-prem">
                <option value="">Semua Tipe</option>
                <option value="randori">Randori</option>
                <option value="embu">Embu</option>
            </select>
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
                            <th>Nomor Pertandingan</th>
                            <th>Kategori</th>
                            <th>Kapasitas</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matchNumbers as $mn)
                        <tr>
                            <td>
                                <div class="group-name">{{ $mn->name }}</div>
                                <div style="display:flex; gap:6px; margin-top:4px;">
                                    <span class="badge {{ $mn->draft_type == 'randori' ? 'badge-randori' : 'badge-embu' }}">
                                        {{ $mn->draft_type }}
                                    </span>
                                    <span class="badge badge-gender">
                                        @if($mn->gender == 'male') <i class="fa-solid fa-mars" style="color:#3498db;"></i> Putra
                                        @elseif($mn->gender == 'female') <i class="fa-solid fa-venus" style="color:#e74c3c;"></i> Putri
                                        @else <i class="fa-solid fa-venus-mars" style="color:#9b59b6;"></i> Campuran @endif
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:12px; font-weight:700; color:var(--ink);">{{ $mn->ageGroup->name ?? '-' }}</div>
                            </td>
                            <td>
                                <div style="font-size:12px; font-weight:600; color:var(--ink);">{{ $mn->max_athletes }} Atlet/Regu</div>
                            </td>
                            <td>
                                <div style="display:flex;gap:5px;justify-content:flex-end;">
                                    <button wire:click="showAthletes({{ $mn->id }})" class="act-btn-a view" title="Lihat Atlet">
                                        <i class="fa-solid fa-users"></i>
                                    </button>
                                    <button wire:click="showEditModal({{ $mn->id }})" class="act-btn-a edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $mn->id }}, '{{ addslashes($mn->name) }}')" class="act-btn-a danger" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:40px 20px;">
                                <i class="fa-solid fa-box-open" style="font-size:32px;color:var(--paper2);margin-bottom:12px;"></i>
                                <div style="font-family:'Cinzel',serif;font-weight:700;color:var(--ink);">Data Kosong</div>
                                <div style="font-size:12px;color:var(--smoke);">Belum ada nomor pertandingan.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($matchNumbers->hasPages())
            <div style="padding:12px 16px;border-top:1px solid var(--paper2);">
                {{ $matchNumbers->links('livewire.admin.pagination') }}
            </div>
            @endif
        </div>

        {{-- FORM MODAL --}}
        @if($showingMatchNumberModal)
        <x-modal wire:model.live="showingMatchNumberModal" maxWidth="lg">
            <div style="padding:24px;">
                <div class="modal-hdr">
                    <div class="modal-title">{{ $matchNumberIdBeingEdited ? 'Edit Nomor Pertandingan' : 'Tambah Nomor Pertandingan' }}</div>
                </div>
                <form wire:submit="saveMatchNumber">
                    <div class="modal-field">
                        <label>Nama Nomor Pertandingan *</label>
                        <input wire:model="name" type="text" class="modal-input" placeholder="Contoh: Randori Dewasa Putra Kelas 60 Kg">
                        @error('name') <span class="modal-err">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid-2">
                        <div class="modal-field">
                            <label>Kelompok Umur *</label>
                            <select wire:model="age_group_id" class="modal-select">
                                <option value="">Pilih Kelompok Umur</option>
                                @foreach($ageGroups as $ag)
                                    <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                                @endforeach
                            </select>
                            @error('age_group_id') <span class="modal-err">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-field">
                            <label>Jenis Kelamin *</label>
                            <select wire:model="gender" class="modal-select">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male">Putra</option>
                                <option value="female">Putri</option>
                                <option value="mixed">Campuran</option>
                            </select>
                            @error('gender') <span class="modal-err">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="modal-field">
                            <label>Tipe Pertandingan *</label>
                            <select wire:model="draft_type" class="modal-select">
                                <option value="">Pilih Tipe</option>
                                <option value="randori">Randori</option>
                                <option value="embu">Embu</option>
                            </select>
                            @error('draft_type') <span class="modal-err">{{ $message }}</span> @enderror
                        </div>
                        <div class="modal-field">
                            <label>Maksimal Atlet (Per Regu) *</label>
                            <input wire:model="max_athletes" type="number" class="modal-input" placeholder="Contoh: 1, 2, atau 3">
                            @error('max_athletes') <span class="modal-err">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" wire:click="$set('showingMatchNumberModal', false)" class="btn-cancel">Batal</button>
                        <button type="submit" class="btn-save">
                            <span wire:loading.remove wire:target="saveMatchNumber">Simpan Data</span>
                            <span wire:loading wire:target="saveMatchNumber"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
        @endif

        {{-- ATHLETES MODAL --}}
        @if($showingAthletesModal && $selectedMatchNumber)
        <x-modal wire:model.live="showingAthletesModal" maxWidth="md">
            <div style="padding:24px;">
                <div class="modal-hdr">
                    <div class="modal-title">Daftar Atlet: {{ $selectedMatchNumber->name }}</div>
                </div>
                
                @if(count($registeredAthletes) > 0)
                <ul class="ath-list">
                    @foreach($registeredAthletes as $ath)
                    <li class="ath-item">
                        <div class="ath-item-name">{{ $ath->athlete_name }}</div>
                        <div class="ath-item-meta">
                            <span><i class="fa-solid fa-flag text-red-500"></i> {{ $ath->contingent_name }}</span>
                            <span>•</span>
                            <span>{{ $ath->kyu }}</span>
                            @if($ath->weight)
                            <span>•</span>
                            <span>{{ $ath->weight }} Kg</span>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <div style="text-align:center; padding:20px; color:var(--smoke); font-size:12px;">
                    Belum ada atlet yang terdaftar di nomor ini.
                </div>
                @endif
                
                <div class="modal-actions">
                    <button type="button" wire:click="$set('showingAthletesModal', false)" class="btn-cancel">Tutup</button>
                </div>
            </div>
        </x-modal>
        @endif

    </div>

    @push('scripts')
    <script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Nomor Pertandingan?',
            html: `Data <b>${name}</b> akan dihapus permanen.`,
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
                @this.deleteMatchNumber(id);
            }
        });
    }
    </script>
    @endpush
</div>
