<div>
    @push('styles')
        <style>
            /* ══════════════════════════════════════════════════════
                   PAGE STYLES — Referee Assignment (Premium Layout)
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

            .judge-box-a {
                margin-bottom: 4px;
            }

            .judge-name-a {
                font-size: 12px;
                font-weight: 700;
                color: var(--ink);
                line-height: 1.2;
            }

            .judge-city-a {
                font-size: 10px;
                color: var(--smoke);
                text-transform: uppercase;
                letter-spacing: .02em;
                font-weight: 600;
            }

            .judge-empty-a {
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

            /* ── DEWAN ARBITRASE STRIP ── */
            .dewan-strip-a {
                background: var(--paper);
                padding: 12px 24px;
                border-top: 1px solid var(--paper2);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .dewan-label-a {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 11px;
                font-weight: 700;
                color: var(--ink);
                text-transform: uppercase;
                letter-spacing: .06em;
            }

            .dewan-val-a {
                font-family: 'Cinzel', serif;
                font-size: 12.5px;
                font-weight: 700;
                color: #2c3e50;
            }

            /* ── MODAL ── */
            .modal-hdr-a {
                border-bottom: 1px solid var(--paper2);
                padding: 20px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .modal-title-a {
                font-family: 'Cinzel', serif;
                font-size: 16px;
                font-weight: 700;
                color: var(--ink);
            }

            .search-wrap-a {
                padding: 16px 24px;
                background: var(--paper);
                border-bottom: 1px solid var(--paper2);
            }

            .referee-grid-a {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                padding: 20px 24px;
                max-height: calc(100vh - 280px);
                overflow-y: auto;
            }

            .referee-item-a {
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

            .referee-item-a:hover {
                background: var(--paper);
                border-color: var(--smoke);
            }

            .referee-item-a.selected {
                border-color: var(--red);
                background: rgba(192, 57, 43, 0.03);
            }

            .ref-name-a {
                font-size: 13px;
                font-weight: 700;
                color: var(--ink);
                margin-bottom: 2px;
            }

            .ref-cert-a {
                font-size: 10px;
                font-weight: 800;
                color: var(--red);
                text-transform: uppercase;
                letter-spacing: .04em;
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
                .referee-grid-a {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <div class="prem-page-a" x-data="{ showModal: @entangle('assigningBlock').live }">

        {{-- HEADER --}}
        <div class="page-hdr-a">
            <div>
                <h2>Penugasan Wasit (Assignment)</h2>
                <p>Atur pembagian wasit per lapangan dan dewan arbitrase</p>
            </div>
            <button wire:click="autoGenerateAllReferees"
                onclick="confirm('Sistem akan men-generate otomatis panel wasit. Lanjutkan?') || event.stopImmediatePropagation()"
                class="btn-prem-gen">
                <i class="fa-solid fa-wand-magic-sparkles"></i> Auto Generate Panel
            </button>
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
                                    <th width="120">Court</th>
                                    <th>Wasit Utama</th>
                                    <th>Wasit 2</th>
                                    <th>Wasit 3</th>
                                    <th>Wasit 4</th>
                                    <th>Wasit 5</th>
                                    <th width="60"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shift->active_courts as $courtItem)
                                    @php
                                        $panel = $shift->assigned_referees
                                            ->where('court_id', $courtItem->court_id)
                                            ->keyBy('judge_index');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="court-label-a">{{ $courtItem->court->name ?? 'Court' }}</div>
                                        </td>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <td>
                                                @if (isset($panel[$i]))
                                                    <div class="judge-box-a">
                                                        <div class="judge-name-a">{{ $panel[$i]->referee->user->name }}
                                                        </div>
                                                        <div class="judge-city-a">{{ $panel[$i]->referee->city ?? '-' }}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="judge-empty-a">Empty</div>
                                                @endif
                                            </td>
                                        @endfor
                                        <td style="text-align:right;">
                                            <button
                                                wire:click="openAssignModal({{ $shift->rundown_id }}, {{ $shift->session_time_id }}, {{ $courtItem->court_id }})"
                                                class="btn-act-a">
                                                <i class="fa-solid fa-users-gear"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @php
                        $dewan = $shift->assigned_referees->where('court_id', null)->where('judge_index', 0)->first();
                    @endphp
                    <div class="dewan-strip-a">
                        <div class="dewan-label-a">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Dewan Arbitrase:</span>
                            @if ($dewan)
                                <span class="dewan-val-a">{{ $dewan->referee->user->name }}</span>
                            @else
                                <span style="color:var(--red); font-style:italic; font-size:10px;">Belum
                                    ditugaskan</span>
                            @endif
                        </div>
                        <button
                            wire:click="openAssignModal({{ $shift->rundown_id }}, {{ $shift->session_time_id }}, null)"
                            style="padding:6px 14px; background:#fff; border:1px solid var(--paper2); border-radius:8px; font-size:10px; font-weight:700; text-transform:uppercase; cursor:pointer;">
                            Edit Dewan
                        </button>
                    </div>
                </div>
            @empty
                <div
                    style="text-align:center; padding:60px 20px; background:#fff; border-radius:20px; border:1px solid var(--paper2);">
                    <i class="fa-solid fa-calendar-xmark"
                        style="font-size:32px; color:var(--paper2); margin-bottom:12px;"></i>
                    <h3 style="font-family:'Cinzel',serif; font-size:16px; color:var(--ink);">Belum Ada Jadwal</h3>
                    <p style="font-size:12px; color:var(--smoke);">Silakan generate drawing pertandingan terlebih
                        dahulu.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- MODAL --}}
        <x-modal wire:model.live="assigningBlock" maxWidth="3xl" :title="$isDewanArbitraseMode ? 'Assign Dewan Arbitrase' : 'Assign Referee Panel'">
            <div style="background:#fff;">
                <div class="search-wrap-a">
                    <div style="position:relative;">
                        <i class="fa-solid fa-search"
                            style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--smoke); font-size:12px;"></i>
                        <input type="text" wire:model.live="searchReferee"
                            placeholder="Search referee name or certification..."
                            style="width:100%; padding:10px 14px 10px 38px; border:1px solid var(--paper2); border-radius:10px; font-size:12.5px; outline:none;">
                    </div>
                </div>

                @error('referees')
                    <div style="padding: 10px 24px; background-color: #fee2e2; color: #b91c1c; font-size: 12.5px; font-weight: 600; border-bottom: 1px solid #fca5a5;">
                        <i class="fa-solid fa-triangle-exclamation" style="margin-right: 6px;"></i> {{ $message }}
                    </div>
                @enderror

                <div class="referee-grid-a custom-scrollbar">
                    @forelse($allReferees as $ref)
                        <div wire:click="toggleReferee('{{ $ref->id }}')"
                            class="referee-item-a {{ in_array($ref->id, $selectedReferees) ? 'selected' : '' }}">
                            <div
                                style="width:24px; height:24px; border-radius:6px; border:2px solid {{ in_array($ref->id, $selectedReferees) ? 'var(--red)' : 'var(--paper2)' }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                @if (in_array($ref->id, $selectedReferees))
                                    <i class="fa-solid fa-check" style="color:var(--red); font-size:10px;"></i>
                                @endif
                            </div>
                            <div>
                                <div class="ref-name-a">{{ $ref->user->name }}</div>
                                <div class="ref-cert-a">{{ $ref->certification_level ?: 'Uncertified' }} &nbsp;·&nbsp;
                                    {{ $ref->city ?: '-' }}</div>
                            </div>
                        </div>
                    @empty
                        <div
                            style="grid-column: span 2; text-align:center; padding:20px; color:var(--smoke); font-size:12px; font-weight:600;">
                            No referee found</div>
                    @endforelse
                </div>
            </div>

            <x-slot name="footer">
                <div style="flex: 1; font-size:12px; font-weight:700; color:var(--ink);">
                    <span style="color:var(--red);">{{ count($selectedReferees) }}</span> Selected
                </div>
                <div style="display:flex; gap:10px;">
                    <button wire:click="closeAssignModal" class="btn-cancel-a">Cancel</button>
                    <button wire:click="saveReferees" class="btn-save-a">
                        <span wire:loading.remove wire:target="saveReferees">Save Assignment</span>
                        <span wire:loading wire:target="saveReferees"><i class="fa-solid fa-circle-notch fa-spin"></i>
                            Saving...</span>
                    </button>
                </div>
            </x-slot>
        </x-modal>

    </div>
</div>
