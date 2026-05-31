<div>
    @push('styles')
    <style>
    /* ══════════════════════════════════════════════════════
       PAGE STYLES — Data Atlet (Premium Layout)
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
    .stats-grid-a { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
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
    .stat-card-a.red::after   { background: var(--red); }
    .stat-card-a.gold::after  { background: var(--gold); }
    .stat-card-a.blue::after  { background: #3498db; }
    .stat-card-a.green::after { background: #27ae60; }
    .stat-icon-a {
      width: 38px; height: 38px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px; margin-bottom: 10px;
    }
    .stat-card-a.red   .stat-icon-a { background: rgba(192,57,43,.1); color: var(--red); }
    .stat-card-a.gold  .stat-icon-a { background: rgba(212,168,67,.12); color: #b8860b; }
    .stat-card-a.blue  .stat-icon-a { background: rgba(52,152,219,.1); color: #2980b9; }
    .stat-card-a.green .stat-icon-a { background: rgba(39,174,96,.1); color: #27ae60; }
    .stat-label-a { font-size: 10px; color: var(--smoke); font-weight: 500; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 4px; }
    .stat-value-a { font-family: 'Cinzel', serif; font-size: 24px; font-weight: 700; line-height: 1; }


    /* ── TABLE ── */
    .prem-table-a-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .prem-table-a { width: 100%; border-collapse: collapse; min-width: 720px; }
    .prem-table-a thead th {
      padding: 10px 16px; font-size: 10px; color: var(--smoke); font-weight: 600;
      letter-spacing: .08em; text-transform: uppercase;
      text-align: left; background: var(--paper);
      border-bottom: 1px solid var(--paper2); white-space: nowrap;
    }
    .prem-table-a thead th:first-child { padding-left: 22px; }
    .prem-table-a thead th:last-child  { padding-right: 22px; text-align: center; }
    .prem-table-a tbody tr { transition: background .12s; }
    .prem-table-a tbody tr:hover { background: rgba(247,244,239,.7); }
    .prem-table-a tbody td { padding: 13px 16px; font-size: 13px; border-bottom: 1px solid var(--paper2); color: var(--ink); }
    .prem-table-a tbody tr:last-child td { border-bottom: none; }
    .prem-table-a td:first-child { padding-left: 22px; }
    .prem-table-a td:last-child  { padding-right: 22px; text-align: center; }

    /* ── ATHLETE AVATAR ── */
    .athlete-avatar-a {
      width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 12px; font-weight: 700; color: #fff;
      background: var(--ink);
    }

    /* ── GENDER BADGE ── */
    .gender-badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 20px; font-size: 10.5px; font-weight: 600; }
    .gender-badge.male   { background: rgba(52,152,219,.12); color: #2980b9; }
    .gender-badge.female { background: rgba(192,57,43,.1); color: var(--red); }

    /* ── CATEGORY TAGS ── */
    .cat-tag { display: inline-flex; padding: 2px 7px; border-radius: 5px; font-size: 10.5px; font-weight: 600; background: rgba(212,168,67,.12); color: #9a6e00; margin: 1px; white-space: nowrap; }

    /* ── ACTION BTNS ── */
    .act-btn-a {
      width: 32px; height: 32px; border-radius: 8px;
      border: 1px solid var(--paper2); background: #fff;
      cursor: pointer; font-size: 12px; color: #888;
      transition: all .15s; display: inline-flex; align-items: center; justify-content: center;
      text-decoration: none;
    }
    .act-btn-a:hover { background: var(--paper2); }
    .act-btn-a.view   { color: #2980b9; border-color: rgba(41,128,185,.2); background: rgba(41,128,185,.06); }
    .act-btn-a.view:hover { background: rgba(41,128,185,.15); border-color: #2980b9; }
    .act-btn-a.edit   { color: #27ae60; border-color: rgba(39,174,96,.2); background: rgba(39,174,96,.06); }
    .act-btn-a.edit:hover { background: rgba(39,174,96,.15); border-color: #27ae60; }
    .act-btn-a.danger { color: var(--red); border-color: rgba(192,57,43,.2); background: rgba(192,57,43,.05); }
    .act-btn-a.danger:hover { background: rgba(192,57,43,.12); border-color: var(--red); }


    /* ── EMPTY ── */
    .empty-state-a { padding: 64px 22px; text-align: center; }
    .empty-icon-a { width: 56px; height: 56px; background: var(--paper); border-radius: 14px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; color: var(--smoke); font-size: 20px; }
    .empty-state-a p { font-size: 13px; color: var(--smoke); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) { .stats-grid-a { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px)  { .stats-grid-a { grid-template-columns: 1fr 1fr; gap: 10px; } .prem-page-a { padding: 14px; } }
    @media (max-width: 400px)  { .stats-grid-a { grid-template-columns: 1fr; } }
    </style>
    @endpush

    <div class="prem-page-a">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-hdr-a">
            <div>
                <h2>Data Atlet</h2>
                <p>Database seluruh atlet yang terdaftar dalam turnamen</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button wire:click="export" class="btn-prem-add" style="background: #27ae60; box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);">
                    <i class="fa-solid fa-file-excel"></i>
                    Export
                </button>
                <a href="{{ route('admin.new-athletes.create') }}" class="btn-prem-add">
                    <i class="fa-solid fa-user-plus"></i>
                    Tambah Atlet Baru
                </a>
            </div>
        </div>

        {{-- ── STAT CARDS ── --}}
        <div class="stats-grid-a">
            <div class="stat-card-a red">
                <div class="stat-icon-a"><i class="fa-solid fa-users"></i></div>
                <div class="stat-label-a">Total Atlet</div>
                <div class="stat-value-a" style="color:var(--red)">{{ number_format($stats['total']) }}</div>
            </div>
            <div class="stat-card-a blue">
                <div class="stat-icon-a"><i class="fa-solid fa-mars"></i></div>
                <div class="stat-label-a">Laki-laki</div>
                <div class="stat-value-a" style="color:#2980b9">{{ number_format($stats['male']) }}</div>
            </div>
            <div class="stat-card-a gold">
                <div class="stat-icon-a"><i class="fa-solid fa-venus"></i></div>
                <div class="stat-label-a">Perempuan</div>
                <div class="stat-value-a" style="color:#b8860b">{{ number_format($stats['female']) }}</div>
            </div>
            <div class="stat-card-a green">
                <div class="stat-icon-a"><i class="fa-solid fa-flag"></i></div>
                <div class="stat-label-a">Kontingen</div>
                <div class="stat-value-a" style="color:#27ae60">{{ number_format($stats['contingents']) }}</div>
            </div>
        </div>

        {{-- ── TABLE ── --}}
        <div class="table-section-prem">
            <div class="table-toolbar-prem">
                <h3>Daftar Atlet</h3>

                {{-- Search --}}
                <div class="search-field-prem">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau NIK...">
                </div>

                {{-- Filter Kontingen --}}
                <select wire:model.live="filterContingent" class="perpage-select-prem">
                    <option value="">Semua Kontingen</option>
                    @foreach($contingents as $con)
                        <option value="{{ $con->id }}">{{ $con->name }}</option>
                    @endforeach
                </select>

                {{-- Filter Gender --}}
                <select wire:model.live="filterGender" class="perpage-select-prem" style="max-width:130px;">
                    <option value="">Semua Gender</option>
                    <option value="Male">Laki-laki</option>
                    <option value="Female">Perempuan</option>
                </select>

                {{-- Per Page --}}
                <select wire:model.live="perPage" class="perpage-select-prem" style="max-width:110px;">
                    <option value="10">10 baris</option>
                    <option value="25">25 baris</option>
                    <option value="50">50 baris</option>
                </select>
            </div>

            <div class="prem-table-a-wrap">
                <table class="prem-table-a">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Profil Atlet</th>
                            <th>Kontingen</th>
                            <th style="text-align:center">Data Fisik</th>
                            <th>Kategori Lomba</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($athletes as $athlete)
                        @php
                            $contingent = $athlete->contingent;
                            $latestReg  = $athlete->latestRegistration();
                            $categories = $athlete->categories()
                                ->wherePivot('registration_id', $latestReg?->id)
                                ->get();
                        @endphp
                        <tr>
                            <td style="color:var(--smoke);font-size:11px;">{{ $loop->iteration + $athletes->firstItem() - 1 }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div class="athlete-avatar-a">{{ substr($athlete->name, 0, 1) }}</div>
                                    <div>
                                        <div style="font-weight:600;font-size:13px;">{{ $athlete->name }}</div>
                                        <div style="font-size:11px;color:var(--smoke);">NIK: {{ $athlete->nik ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($contingent)
                                    <div style="font-weight:600;font-size:13px;">{{ $contingent->name }}</div>
                                    <div style="font-size:11px;color:var(--smoke);">{{ $contingent->kab_kota ?? '-' }}</div>
                                @else
                                    <span style="color:var(--smoke);font-size:12px;">Tanpa Kontingen</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <div style="margin-bottom:4px;">
                                    <span class="gender-badge {{ $athlete->gender === 'Male' ? 'male' : 'female' }}">
                                        <i class="fa-solid {{ $athlete->gender === 'Male' ? 'fa-mars' : 'fa-venus' }}" style="font-size:9px;"></i>
                                        {{ $athlete->gender === 'Male' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </div>
                                <div style="font-size:12px;">
                                    <span style="font-weight:600;">{{ $athlete->weight ?? '-' }}</span>
                                    <span style="color:var(--smoke);font-size:11px;"> kg</span>
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;flex-wrap:wrap;gap:2px;">
                                    @forelse($categories as $cat)
                                        <span class="cat-tag">{{ $cat->name }}</span>
                                    @empty
                                        <span style="font-size:11px;color:var(--smoke);font-style:italic;">Belum terdaftar</span>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div style="display:flex;gap:5px;justify-content:center;">
                                    <a href="{{ route('admin.new-athletes.show', $athlete->id) }}" class="act-btn-a view" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.new-athletes.edit', $athlete->id) }}" class="act-btn-a edit" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button class="act-btn-a danger" title="Hapus"
                                        onclick="confirmDeleteAthlete({{ $athlete->id }}, '{{ addslashes($athlete->name) }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state-a">
                                    <div class="empty-icon-a"><i class="fa-solid fa-user-slash"></i></div>
                                    <p>Tidak ada data atlet ditemukan</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($athletes->hasPages())
            <div style="padding:12px 16px;border-top:1px solid var(--paper2);">
                {{ $athletes->links('livewire.admin.pagination') }}
            </div>
            @endif
        </div>

    </div>

</div>

@push('scripts')
<script>
function confirmDeleteAthlete(id, name) {
    Swal.fire({
        title: 'Hapus Atlet?',
        html: `Data atlet <b>${name}</b> akan dihapus secara permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c0392b',
        cancelButtonColor: '#7f8c8d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            @this.deleteAthleteById(id);
        }
    });
}
</script>
@endpush
