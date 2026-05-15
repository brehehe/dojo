<div>
    @push('styles')
        <style>
            .tm-page {
                padding: 24px;
                padding-bottom: 100px;
                background: var(--paper, #F7F4EF);
                min-height: 100vh;
                font-family: 'Inter', sans-serif;
            }

            /* HEADER */
            .tm-hdr {
                margin-bottom: 24px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            @media(min-width: 768px) {
                .tm-hdr { flex-direction: row; justify-content: space-between; align-items: flex-end; }
            }
            .tm-hdr h2 {
                font-family: 'Cinzel', serif;
                font-size: 24px;
                font-weight: 700;
                margin: 0 0 4px;
                color: var(--ink, #2c3e50);
            }
            .tm-hdr p {
                font-size: 13px;
                color: var(--smoke, #7f8c8d);
                margin: 0;
            }

            /* FILTER CARD */
            .tm-filter-card {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                padding: 20px;
                margin-bottom: 20px;
            }
            .tm-filter-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 12px;
                margin-bottom: 12px;
            }
            .tm-filter-label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke);
                margin-bottom: 6px;
                display: block;
            }
            .tm-filter-sel {
                width: 100%;
                padding: 9px 12px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12.5px;
                color: var(--ink);
                background: #fdfbf7;
                outline: none;
                cursor: pointer;
            }
            .tm-filter-sel:focus { border-color: var(--ink); }
            .tm-filter-input {
                width: 100%;
                padding: 9px 12px;
                border: 1px solid var(--paper2);
                border-radius: 10px;
                font-size: 12.5px;
                color: var(--ink);
                background: #fdfbf7;
                outline: none;
            }
            .tm-filter-input:focus { border-color: var(--ink); }

            /* BUTTONS */
            .btn-gen {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 10px 18px;
                border-radius: 10px;
                font-size: 12px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                cursor: pointer;
                transition: all .2s;
                border: none;
                text-decoration: none;
                white-space: nowrap;
            }
            .btn-gen.primary { background: var(--ink); color: #fff; }
            .btn-gen.primary:hover { background: #1a252f; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,62,80,.2); }
            .btn-gen.success { background: #27ae60; color: #fff; }
            .btn-gen.success:hover { background: #219a52; transform: translateY(-1px); }
            .btn-gen.ghost { background: #fff; color: var(--smoke); border: 1px solid var(--paper2); }
            .btn-gen.ghost:hover { border-color: var(--ink); color: var(--ink); }
            .btn-gen.warning { background: #f39c12; color: #fff; }
            .btn-gen.warning:hover { background: #d68910; }

            /* GRID CARDS */
            .tm-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
                gap: 16px;
            }

            /* MATCH CARD */
            .match-card {
                background: #fff;
                border: 1px solid var(--paper2, #e0dcd3);
                border-radius: 16px;
                overflow: hidden;
                transition: box-shadow .2s;
            }
            .match-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.08); }
            .match-card.saved { border-color: #27ae60; }

            .match-card-head {
                background: var(--ink, #2c3e50);
                padding: 14px 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            }
            .match-card-title {
                font-family: 'Cinzel', serif;
                font-size: 13px;
                font-weight: 700;
                color: #fff;
                margin: 0;
                text-transform: uppercase;
                flex: 1;
                min-width: 0;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .match-card-badges {
                display: flex;
                align-items: center;
                gap: 6px;
                flex-shrink: 0;
            }

            .type-badge {
                padding: 2px 8px;
                border-radius: 20px;
                font-size: 9px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .05em;
            }
            .type-badge.embu { background: rgba(243,156,18,.2); color: #f39c12; border: 1px solid rgba(243,156,18,.3); }
            .type-badge.randori { background: rgba(192,57,43,.2); color: #e74c3c; border: 1px solid rgba(192,57,43,.3); }
            .type-badge.saved { background: rgba(39,174,96,.2); color: #27ae60; border: 1px solid rgba(39,174,96,.3); }

            .rank-row {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 18px;
                border-bottom: 1px solid var(--paper2);
                transition: background .15s;
            }
            .rank-row:last-child { border-bottom: none; }
            .rank-row:hover { background: #fdfbf7; }
            .rank-row.empty { padding: 30px 18px; justify-content: center; flex-direction: column; text-align: center; }

            .rank-icon { font-size: 22px; flex-shrink: 0; width: 32px; text-align: center; }

            .rank-info { flex: 1; min-width: 0; }
            .rank-label {
                font-size: 9px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--smoke);
                margin-bottom: 2px;
            }
            .rank-name {
                font-size: 13px;
                font-weight: 800;
                color: var(--ink);
                text-transform: uppercase;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .rank-contingent {
                font-size: 11px;
                color: var(--smoke);
                font-weight: 600;
            }

            .rank-score {
                text-align: right;
                flex-shrink: 0;
            }
            .rank-score-val {
                font-family: 'Outfit', sans-serif;
                font-size: 18px;
                font-weight: 800;
                color: var(--ink);
            }
            .rank-score-lbl {
                font-size: 9px;
                font-weight: 700;
                color: var(--smoke);
                text-transform: uppercase;
            }

            .saved-footer {
                padding: 10px 18px;
                background: rgba(39,174,96,.05);
                border-top: 1px solid rgba(39,174,96,.15);
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 11px;
                color: #27ae60;
                font-weight: 600;
            }

            /* MODAL */
            .modal-overlay {
                position: fixed; top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
                z-index: 100; display: flex; align-items: center; justify-content: center; padding: 16px;
            }
            .modal-content {
                background: #fff; border-radius: 20px; width: 100%; max-width: 480px;
                overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            }
            .modal-hdr {
                background: #fdfbf7; padding: 20px; border-bottom: 1px solid var(--paper2);
                display: flex; justify-content: space-between; align-items: center;
            }
            .modal-hdr h3 { font-family: 'Cinzel', serif; font-size: 17px; font-weight: 700; color: var(--ink); margin: 0; }
            .modal-body { padding: 24px; }
            .modal-footer { padding: 20px; border-top: 1px solid var(--paper2); display: flex; gap: 12px; justify-content: flex-end; }

            /* EMPTY STATE */
            .empty-state { padding: 60px 20px; text-align: center; color: var(--smoke); }
            .empty-state i { font-size: 36px; opacity: .2; margin-bottom: 12px; display: block; }
            .empty-state p { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; margin: 0; }
        </style>
    @endpush

    <div class="tm-page">

        {{-- HEADER --}}
        <div class="tm-hdr">
            <div>
                <h2><i class="fas fa-medal" style="color:#f39c12; margin-right:8px;"></i>Laporan Hasil Juara</h2>
                <p>Rekapitulasi Hasil Pertandingan · Embu & Randori · Generate & Simpan</p>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button wire:click="exportExcel" class="btn-gen success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button onclick="window.print()" class="btn-gen ghost">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button wire:click="generateAllResults"
                    wire:confirm="Generate semua hasil juara? Data lama akan ditimpa."
                    wire:loading.attr="disabled"
                    class="btn-gen primary">
                    <span wire:loading.remove wire:target="generateAllResults">
                        <i class="fas fa-database"></i> Generate Semua
                    </span>
                    <span wire:loading wire:target="generateAllResults">
                        <i class="fas fa-spinner fa-spin"></i> Memproses...
                    </span>
                </button>
            </div>
        </div>

        {{-- FILTERS --}}
        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <span class="tm-filter-label"><i class="fas fa-search" style="margin-right:4px;"></i>Cari</span>
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input" placeholder="Nama nomor pertandingan...">
                </div>
                <div>
                    <span class="tm-filter-label">Kategori</span>
                    <select wire:model.live="draftTypeFilter" class="tm-filter-sel">
                        <option value="">Semua Kategori</option>
                        <option value="embu">Embu</option>
                        <option value="randori">Randori</option>
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Kelompok Umur</span>
                    <select wire:model.live="ageGroupFilter" class="tm-filter-sel">
                        <option value="">Semua Kelompok</option>
                        @foreach($ageGroups as $ag)
                            <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Gender</span>
                    <select wire:model.live="genderFilter" class="tm-filter-sel">
                        <option value="">Semua Gender</option>
                        <option value="Putra">Putra</option>
                        <option value="Putri">Putri</option>
                        <option value="Campuran">Campuran</option>
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Status Juara</span>
                    <select wire:model.live="hasWinnersFilter" class="tm-filter-sel">
                        <option value="">Semua Status</option>
                        <option value="yes">Sudah Ada Juara</option>
                        <option value="no">Belum Ada Juara</option>
                    </select>
                </div>
                <div>
                    <span class="tm-filter-label">Nomor Pertandingan</span>
                    <select wire:model.live="matchNumberFilter" class="tm-filter-sel">
                        <option value="">Semua Nomor</option>
                        @foreach($allMatchNumbersDropdown as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->display_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- MATCH CARDS GRID --}}
        <div class="tm-grid">
            @forelse($matchNumbers as $match)
                @php
                    $savedResults = $match->saved_results ?? collect();
                    $computedJuara = $match->computed_juara ?? [];
                    $isSaved = $savedResults->isNotEmpty();
                    $hasResult = !empty($computedJuara);
                    $isEmbu = strtolower($match->draft_type) === 'embu';
                @endphp

                <div class="match-card {{ $isSaved ? 'saved' : '' }}">
                    {{-- Card Head --}}
                    <div class="match-card-head">
                        <div style="flex:1; min-width:0;">
                            <div class="match-card-badges" style="margin-bottom:4px; justify-content:flex-start;">
                                <span class="type-badge {{ $isEmbu ? 'embu' : 'randori' }}">{{ $match->draft_type }}</span>
                                @if($match->ageGroup)
                                    <span style="font-size:9px; font-weight:700; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:.06em;">{{ $match->ageGroup->name }}</span>
                                @endif
                            </div>
                            <div class="match-card-title">{{ $match->display_name ?? $match->name }}</div>
                        </div>
                        <div class="match-card-badges">
                            @if($isSaved)
                                <span class="type-badge saved"><i class="fas fa-check-circle"></i> Tersimpan</span>
                            @endif
                            @if($hasResult)
                                <button wire:click="openGenerateModal({{ $match->id }}, '{{ addslashes($match->display_name ?? $match->name) }}')"
                                    class="btn-gen warning" style="padding: 6px 12px; font-size: 10px;">
                                    <i class="fas fa-save"></i> {{ $isSaved ? 'Update' : 'Simpan' }}
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Juara Rows --}}
                    @if($hasResult)
                        @foreach([1=>'🥇', 2=>'🥈', 3=>'🥉', 4=>'🥉'] as $rank => $icon)
                            @php $data = $computedJuara[$rank] ?? null; @endphp
                            <div class="rank-row">
                                <div class="rank-icon">{{ $icon }}</div>
                                <div class="rank-info">
                                    <div class="rank-label">Juara {{ $rank === 4 ? '3 Bersama' : $rank }}</div>
                                    @if($data)
                                        <div class="rank-name">{{ $data['athlete_names'] }}</div>
                                        <div class="rank-contingent">{{ $data['contingent_name'] }}</div>
                                    @else
                                        <div style="font-size:12px; color:var(--smoke); font-style:italic;">-</div>
                                    @endif
                                </div>
                                @if($data && ($data['accumulated_score'] ?? 0) > 0)
                                    <div class="rank-score">
                                        <div class="rank-score-val">{{ number_format($data['accumulated_score'], 1) }}</div>
                                        @if($isEmbu)
                                            <div class="rank-score-lbl">P+F</div>
                                        @else
                                            <div class="rank-score-lbl">Total</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        @if($isSaved)
                            @php $firstResult = $savedResults->first(); @endphp
                            <div class="saved-footer">
                                <i class="fas fa-database"></i>
                                <span>Tersimpan {{ $firstResult->confirmed_at?->diffForHumans() }}
                                    @if($firstResult->generated_by) · {{ $firstResult->generated_by }}@endif
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="rank-row empty">
                            <i class="fas fa-trophy" style="font-size:28px; opacity:.15; margin-bottom:8px; display:block;"></i>
                            <div style="font-size:12px; font-weight:700; text-transform:uppercase; color:var(--smoke);">Belum Ada Hasil</div>
                            <div style="font-size:11px; color:var(--smoke); margin-top:4px; opacity:.7;">Penilaian belum diinput</div>
                        </div>
                    @endif
                </div>
            @empty
                <div style="grid-column: 1/-1;">
                    <div class="empty-state" style="background:#fff; border:1px solid var(--paper2); border-radius:16px;">
                        <i class="fas fa-folder-open"></i>
                        <p>Tidak Ada Data</p>
                        <div style="font-size:12px; color:var(--smoke); margin-top:4px;">Coba sesuaikan filter pencarian</div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top: 24px;">
            {{ $matchNumbers->links('livewire.admin.pagination') }}
        </div>

        {{-- CONFIRM GENERATE MODAL --}}
        @if($showConfirmModal)
            <div class="modal-overlay" wire:click.self="$set('showConfirmModal', false)">
                <div class="modal-content animate-in zoom-in-95 duration-200">
                    <div class="modal-hdr">
                        <h3><i class="fas fa-database" style="color:#27ae60; margin-right:8px;"></i>Generate Hasil</h3>
                        <button wire:click="$set('showConfirmModal', false)"
                            style="background:none; border:none; cursor:pointer; font-size:18px; color:var(--smoke);">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div style="font-size:14px; font-weight:700; color:var(--ink); margin-bottom:16px;">
                            {{ $generateMatchName }}
                        </div>
                        <div style="padding:12px 16px; background:rgba(243,156,18,.08); border:1px solid rgba(243,156,18,.25); border-radius:12px; font-size:12px; font-weight:600; color:#d68910;">
                            <i class="fas fa-info-circle" style="margin-right:6px;"></i>
                            Data juara yang tersimpan sebelumnya akan ditimpa dengan hasil terbaru.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="$set('showConfirmModal', false)" class="btn-gen ghost">Batal</button>
                        <button wire:click="generateSingleResult" wire:loading.attr="disabled" class="btn-gen success">
                            <span wire:loading.remove wire:target="generateSingleResult">
                                <i class="fas fa-save"></i> Simpan Hasil
                            </span>
                            <span wire:loading wire:target="generateSingleResult">
                                <i class="fas fa-spinner fa-spin"></i> Menyimpan...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
