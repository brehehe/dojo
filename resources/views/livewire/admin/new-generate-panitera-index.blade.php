<div>
    @push('styles')
        <style>
            /* ══════════════════════════════════════════════════════
                   PAGE STYLES — Panitera Assignment (Premium Layout)
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

            .btn-prem-gen {
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

            .btn-prem-gen:hover {
                background: var(--red-deep);
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(192, 57, 43, 0.3);
                color: #fff;
            }

            .btn-prem-outline {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 20px;
                background: #fff;
                color: var(--smoke);
                border: 1px solid var(--paper2);
                border-radius: 12px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                font-family: 'DM Sans', sans-serif;
                transition: all .2s;
                white-space: nowrap;
            }

            .btn-prem-outline:hover {
                background: var(--paper);
                color: var(--red);
                border-color: var(--red);
            }

            /* ── SHIFT CARDS ── */
            .shift-card-a {
                background: #fff;
                border: 1px solid var(--paper2);
                border-radius: 20px;
                overflow: hidden;
                margin-bottom: 24px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            }

            .shift-hdr-a {
                background: rgba(248, 245, 240, 0.5);
                padding: 16px 24px;
                border-bottom: 1px solid var(--paper2);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .shift-title-a {
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .shift-icon-a {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                background: #fff;
                border: 1px solid var(--paper2);
                display: flex;
                align-items: center;
                justify-content: center;
                color: var(--ink);
                font-size: 16px;
            }

            .shift-info-a h3 {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                font-weight: 800;
                color: var(--ink);
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .shift-info-a p {
                font-size: 11px;
                font-weight: 700;
                color: var(--smoke);
                margin: 2px 0 0;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            /* ── COURT TABLE ── */
            .court-table-a {
                width: 100%;
                border-collapse: collapse;
            }

            .court-table-a th {
                background: #fff;
                padding: 12px 16px;
                text-align: left;
                font-size: 10px;
                text-transform: uppercase;
                letter-spacing: .08em;
                color: var(--smoke);
                font-weight: 800;
                border-bottom: 1px solid var(--paper2);
            }

            .court-table-a td {
                padding: 14px 16px;
                border-bottom: 1px solid var(--paper2);
                vertical-align: top;
            }

            .court-table-a tr:last-child td {
                border-bottom: none;
            }

            .court-label-a {
                font-family: 'Cinzel', serif;
                font-size: 13px;
                font-weight: 800;
                color: var(--ink);
            }

            .officer-badge-list {
                display: flex;
                flex-wrap: wrap;
                gap: 6px;
            }

            .officer-badge {
                display: inline-flex;
                align-items: center;
                background: var(--paper);
                border: 1px solid var(--paper2);
                color: var(--ink);
                padding: 4px 10px;
                border-radius: 8px;
                font-size: 11px;
                font-weight: 700;
            }

            .officer-empty-a {
                font-size: 11px;
                color: var(--paper2);
                font-style: italic;
                font-weight: 600;
            }

            .btn-act-a {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                border: 1px solid var(--paper2);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: var(--smoke);
                background: #fff;
                transition: all .2s;
                cursor: pointer;
            }

            .btn-act-a:hover {
                background: var(--paper);
                color: var(--red);
                border-color: var(--red);
            }

            /* ── MODAL ── */
            .search-wrap-a {
                padding: 16px 24px;
                background: var(--paper);
                border-bottom: 1px solid var(--paper2);
            }

            .officer-grid-a {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                padding: 20px 24px;
                max-height: calc(100vh - 280px);
                overflow-y: auto;
            }

            .officer-item-a {
                padding: 12px;
                border: 1px solid var(--paper2);
                border-radius: 12px;
                display: flex;
                align-items: flex-start;
                gap: 10px;
                cursor: pointer;
                transition: all .2s;
                background: #fff;
            }

            .officer-item-a:hover {
                background: var(--paper);
                border-color: var(--smoke);
            }

            .officer-item-a.selected {
                border-color: var(--red);
                background: rgba(192, 57, 43, 0.03);
            }

            .ref-name-a {
                font-size: 13px;
                font-weight: 700;
                color: var(--ink);
                margin-bottom: 2px;
            }

            .ref-email-a {
                font-size: 10px;
                font-weight: 600;
                color: var(--smoke);
            }

            .btn-cancel-a {
                padding: 10px 20px;
                background: #fff;
                border: 1px solid var(--paper2);
                color: var(--smoke);
                border-radius: 10px;
                font-size: 12.5px;
                font-weight: 600;
                cursor: pointer;
            }

            .btn-save-a {
                padding: 10px 20px;
                background: var(--red);
                color: #fff;
                border: none;
                border-radius: 10px;
                font-size: 12.5px;
                font-weight: 600;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(192, 57, 43, 0.2);
            }

            @media (max-width: 1024px) {
                .officer-grid-a {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <div class="prem-page-a" x-data="{ showModal: @entangle('assigningBlock').live }">

        {{-- HEADER --}}
        <div class="page-hdr-a">
            <div>
                <h2>Penugasan Panitera & Koordinator</h2>
                <p>Atur pembagian koordinator lapangan dan panitera per lapangan</p>
            </div>
            <div style="display:flex; gap:10px;">
                <button wire:click="clearAllAssignments"
                    onclick="confirm('Sistem akan menghapus SEMUA penugasan petugas pada sesi-sesi ini. Lanjutkan?') || event.stopImmediatePropagation()"
                    class="btn-prem-outline">
                    <i class="fa-solid fa-trash-can"></i> Reset Penugasan
                </button>
                <button wire:click="resetAndGenerateAllOfficers"
                    onclick="confirm('Sistem akan menghapus semua penugasan petugas saat ini dan men-generate ulang secara otomatis. Lanjutkan?') || event.stopImmediatePropagation()"
                    class="btn-prem-gen">
                    <i class="fa-solid fa-rotate"></i> Reset & Generate Ulang
                </button>
            </div>
        </div>

        {{-- SHIFT LIST --}}
        <div class="shift-list-a">
            @forelse($paginatedShifts as $shift)
                <div class="shift-card-a">
                    <div class="shift-hdr-a">
                        <div class="shift-title-a">
                            <div class="shift-icon-a"><i class="fa-solid fa-clock"></i></div>
                            <div class="shift-info-a">
                                <h3>{{ \Carbon\Carbon::parse($shift->rundown->date)->isoFormat('dddd, D MMMM Y') }}</h3>
                                <p>{{ $shift->sessionTime->name }} &nbsp;·&nbsp;
                                    {{ $shift->sessionTime->start_time->format('H:i') }} -
                                    {{ $shift->sessionTime->end_time->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div style="overflow-x:auto;">
                        <table class="court-table-a">
                            <thead>
                                <tr>
                                    <th width="150">Court</th>
                                    <th>Koordinator Lapangan</th>
                                    <th>Panitera</th>
                                    <th width="100" style="text-align:right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shift->active_courts as $courtItem)
                                    @php
                                        $courtOfficers = $shift->assigned_officers->where('court_id', $courtItem->court_id);
                                        $koors = $courtOfficers->where('role_type', 'koordinator')->sortBy('slot_index');
                                        $paniteras = $courtOfficers->where('role_type', 'panitera')->sortBy('slot_index');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="court-label-a">{{ $courtItem->court->name ?? 'Court' }}</div>
                                        </td>
                                        <td>
                                            @if ($koors->isNotEmpty())
                                                <div class="officer-badge-list">
                                                    @foreach($koors as $koor)
                                                        <span class="officer-badge"><i class="fa-solid fa-user-tie" style="margin-right:4px;"></i> {{ $koor->user->name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="officer-empty-a">Empty</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($paniteras->isNotEmpty())
                                                <div class="officer-badge-list">
                                                    @foreach($paniteras as $panitera)
                                                        <span class="officer-badge"><i class="fa-solid fa-file-pen" style="margin-right:4px;"></i> {{ $panitera->user->name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="officer-empty-a">Empty</div>
                                            @endif
                                        </td>
                                        <td style="text-align:right; white-space:nowrap; gap:6px;">
                                            <button title="Edit Koordinator Lapangan"
                                                wire:click="openAssignModal({{ $shift->rundown_id }}, {{ $shift->session_time_id }}, {{ $courtItem->court_id }}, 'koordinator')"
                                                class="btn-act-a" style="margin-right: 4px;">
                                                <i class="fa-solid fa-user-tie"></i>
                                            </button>
                                            <button title="Edit Panitera"
                                                wire:click="openAssignModal({{ $shift->rundown_id }}, {{ $shift->session_time_id }}, {{ $courtItem->court_id }}, 'panitera')"
                                                class="btn-act-a">
                                                <i class="fa-solid fa-users-rectangle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div
                    style="text-align:center; padding:60px 20px; background:#fff; border-radius:20px; border:1px solid var(--paper2);">
                    <i class="fa-solid fa-calendar-xmark"
                        style="font-size:32px; color:var(--paper2); margin-bottom:12px;"></i>
                    <h3 style="font-family:'Cinzel',serif; font-size:16px; color:var(--ink);">Belum Ada Jadwal</h3>
                    <p style="font-size:12px; color:var(--smoke);">Silakan generate drawing pertandingan terlebih dahulu.</p>
                </div>
            @endforelse
        </div>

        {{-- MODAL --}}
        <x-modal wire:model.live="assigningBlock" maxWidth="3xl" :title="$isKoordinatorMode ? 'Pilih Koordinator Lapangan' : 'Pilih Panitera'">
            <div style="background:#fff;">
                <div class="search-wrap-a">
                    <div style="position:relative;">
                        <i class="fa-solid fa-search"
                            style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--smoke); font-size:12px;"></i>
                        <input type="text" wire:model.live="searchOfficer"
                            placeholder="Cari petugas..."
                            style="width:100%; padding:10px 14px 10px 38px; border:1px solid var(--paper2); border-radius:10px; font-size:12.5px; outline:none;">
                    </div>
                </div>

                @error('officers')
                    <div style="padding: 10px 24px; background-color: #fee2e2; color: #b91c1c; font-size: 12.5px; font-weight: 600; border-bottom: 1px solid #fca5a5;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> {{ $message }}
                    </div>
                @enderror

                <div class="officer-grid-a custom-scrollbar">
                    @forelse($allOfficers as $officer)
                        <div wire:click="toggleOfficer('{{ $officer->id }}')"
                            class="officer-item-a {{ in_array($officer->id, $selectedOfficers) ? 'selected' : '' }}">
                            <div
                                style="width:24px; height:24px; border-radius:6px; border:2px solid {{ in_array($officer->id, $selectedOfficers) ? 'var(--red)' : 'var(--paper2)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                @if (in_array($officer->id, $selectedOfficers))
                                    <i class="fa-solid fa-check" style="color:var(--red); font-size:10px;"></i>
                                @endif
                            </div>
                            <div>
                                <div class="ref-name-a">{{ $officer->name }}</div>
                                <div class="ref-email-a">{{ $officer->email }}</div>
                            </div>
                        </div>
                    @empty
                        <div
                            style="grid-column: span 2; text-align:center; padding:20px; color:var(--smoke); font-size:12px; font-weight:600;">
                            Tidak ada petugas ditemukan
                        </div>
                    @endforelse
                </div>
            </div>

            <x-slot name="footer">
                <div style="flex: 1; font-size:12px; font-weight:700; color:var(--ink);">
                    <span style="color:var(--red);">{{ count($selectedOfficers) }}</span> Terpilih
                </div>
                <div style="display:flex; gap:10px;">
                    <button wire:click="closeAssignModal" class="btn-cancel-a">Batal</button>
                    <button wire:click="saveAssignment" class="btn-save-a">
                        <span wire:loading.remove wire:target="saveAssignment">Simpan Penugasan</span>
                        <span wire:loading wire:target="saveAssignment"><i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...</span>
                    </button>
                </div>
            </x-slot>
        </x-modal>

    </div>
</div>
