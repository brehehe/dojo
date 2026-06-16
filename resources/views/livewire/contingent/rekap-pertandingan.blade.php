<div>
    @push('styles')
        <style>
            .tm-page { padding: 24px; padding-bottom: 100px; background: var(--paper, #F7F4EF); min-height: 100vh; font-family: 'DM Sans', sans-serif; }
            .tm-hdr { margin-bottom: 24px; }
            .tm-hdr h2 { font-family: 'Cinzel', serif; font-size: 24px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
            .tm-hdr p { font-size: 13px; color: var(--smoke); margin: 0; }

            .tm-tabs { display: flex; gap: 8px; margin-bottom: 24px; border-bottom: 1px solid var(--paper2); padding-bottom: 8px; }
            .tm-tab-btn { padding: 10px 20px; border-radius: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; transition: all .2s; border: 1px solid var(--paper2); background: #fff; color: var(--smoke); }
            .tm-tab-btn.active { background: var(--ink); color: #fff; border-color: var(--ink); }

            .tm-filter-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; padding: 20px; margin-bottom: 24px; }
            .tm-filter-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
            .tm-filter-label { font-size: 10px; font-weight: 700; text-transform: uppercase; color: var(--smoke); margin-bottom: 6px; display: block; }
            .tm-filter-input, .tm-filter-sel { width: 100%; padding: 10px 14px; border: 1px solid var(--paper2); border-radius: 10px; font-size: 13px; background: #fdfbf7; }

            .tm-table-card { background: #fff; border: 1px solid var(--paper2); border-radius: 16px; overflow: hidden; }
            .tm-table { width: 100%; border-collapse: collapse; }
            .tm-table th { background: #fdfbf7; padding: 14px 18px; text-align: left; font-size: 10px; font-weight: 800; text-transform: uppercase; color: var(--smoke); border-bottom: 1px solid var(--paper2); }
            .tm-table td { padding: 14px 18px; font-size: 13px; color: var(--ink); border-bottom: 1px solid var(--paper2); }
            
            .badge-aka { background: #c0392b; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; }
            .badge-shiro { background: #fff; color: #2c3e50; border: 1px solid var(--paper2); padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; }
            .winner-mark { color: #27ae60; font-weight: 800; margin-left: 4px; }
        </style>
    @endpush

    <div class="tm-page">
        <div class="tm-hdr">
            <h2>Rekap Pertandingan</h2>
            <p>Hasil Pertandingan Kontingen: <strong>{{ $contingent->name }}</strong></p>
        </div>

        <div class="tm-tabs">
            <button wire:click="$set('tab', 'embu')" class="tm-tab-btn {{ $tab === 'embu' ? 'active' : '' }}">Rekap Embu</button>
            <button wire:click="$set('tab', 'randori')" class="tm-tab-btn {{ $tab === 'randori' ? 'active' : '' }}">Rekap Randori</button>
        </div>

        <div class="tm-filter-card">
            <div class="tm-filter-grid">
                <div>
                    <label class="tm-filter-label">Cari Atlet</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="tm-filter-input" placeholder="Nama atlet...">
                </div>
                <div>
                    <label class="tm-filter-label">Nomor Pertandingan</label>
                    <select wire:model.live="matchNumberFilter" class="tm-filter-sel">
                        <option value="">Semua</option>
                        @foreach($matchNumbers as $mn)
                            <option value="{{ $mn->id }}">{{ $mn->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if($tab === 'embu')
            @php
                $scoresByMatch = $scores->getCollection()->groupBy('match_number_id');
            @endphp
            @forelse($scoresByMatch as $matchNumberId => $matchScores)
                @php
                    $firstScore = $matchScores->first();
                    $matchNumber = $firstScore->matchNumber;
                    // Group scores per team (by athlete_label), preserving order
                    $teamGroups = $matchScores->groupBy('athlete_label');
                    $hasMultipleTeams = $teamGroups->count() > 1;
                @endphp
                <div class="tm-table-card" style="margin-bottom: 24px; overflow: hidden;">
                    <div style="padding: 16px 20px; background: #fdfbf7; border-bottom: 1px solid var(--paper2, #e8e3da);">
                        <h3 style="font-family: 'Cinzel', serif; font-size: 15px; font-weight: 700; color: #c0392b; margin: 0 0 6px;">
                            {{ $matchNumber->name }}
                        </h3>
                        <div style="font-size: 11px; color: var(--smoke, #7f8c8d); display: flex; flex-wrap: wrap; gap: 16px;">
                            <span><strong>Kelompok Umur:</strong> {{ $matchNumber->ageGroup->name ?? '-' }}</span>
                            <span><strong>Gender:</strong> {{ $matchNumber->gender_indo }}</span>
                        </div>
                    </div>
                    @foreach($teamGroups as $athleteLabel => $teamScores)
                        @php
                            $teamIndex = $loop->iteration;
                        @endphp
                        @if($hasMultipleTeams)
                            <div style="padding: 8px 20px; background: #f0eee9; border-bottom: 1px solid var(--paper2,#e8e3da);">
                                <span style="font-size: 10px; font-weight: 800; text-transform: uppercase; color: #c0392b; letter-spacing: 0.05em;">
                                    Tim {{ $teamIndex }}
                                </span>
                                <span style="font-size: 12px; font-weight: 600; color: var(--ink); margin-left: 8px;">{{ $athleteLabel }}</span>
                            </div>
                        @endif
                        <table class="tm-table">
                            <thead>
                                <tr>
                                    <th style="width: 15%;">Babak</th>
                                    <th style="width: 35%;">Atlet</th>
                                    <th style="width: 15%; text-align:center;">Total</th>
                                    <th style="width: 35%;">Catatan Wasit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teamScores as $s)
                                    <tr>
                                        <td>
                                            <span style="font-size: 10px; font-weight: 800; text-transform: uppercase;">
                                                {{ $s->round_label }}
                                            </span>
                                        </td>
                                        <td style="font-weight: 600;">
                                            {{ $s->athlete_label ?: '-' }}
                                        </td>
                                        <td align="center" style="font-weight: 900; font-size: 14px; color: #27ae60;">
                                            {{ number_format($s->nilai_akhir, 2) }}
                                        </td>
                                        <td style="font-size: 11px; color: #555; white-space: pre-line; line-height: 1.4;">{!! nl2br(e($s->referee_notes)) !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            @empty
                <div class="tm-table-card" style="padding:40px; text-align:center; color:var(--smoke)">
                    Belum ada data nilai Embu.
                </div>
            @endforelse
            <div style="padding:16px 0;">{{ $scores->links() }}</div>
        @else
            <div class="tm-table-card">
                <table class="tm-table">
                    <thead>
                        <tr>
                            <th>Nomor Pertandingan</th>
                            <th>Babak / Pool</th>
                            <th>AKA (Merah)</th>
                            <th>SHIRO (Putih)</th>
                            <th style="text-align:center">Skor</th>
                            <th>Hasil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $r)
                            <tr>
                                <td>
                                    <div style="font-weight:700">{{ $r->matchNumber->name }}</div>
                                    <div style="font-size:10px; color:var(--smoke)">{{ $r->matchNumber->ageGroup->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <div style="font-weight:700; text-transform:uppercase">{{ $r->round_label }}</div>
                                    <div style="font-size:10px; color:var(--smoke)">Pool {{ $r->pool_name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span class="badge-aka">AKA</span>
                                    <span style="font-weight:600; margin-left:4px">{{ $r->processed_aka['name'] }}</span>
                                    @if($r->processed_aka['is_winner']) <i class="fas fa-check-circle winner-mark"></i> @endif
                                    <div style="font-size:10px; color:var(--smoke); margin-left:36px">{{ $r->processed_aka['contingent'] }}</div>
                                </td>
                                <td>
                                    <span class="badge-shiro">SHIRO</span>
                                    <span style="font-weight:600; margin-left:4px">{{ $r->processed_shiro['name'] }}</span>
                                    @if($r->processed_shiro['is_winner']) <i class="fas fa-check-circle winner-mark"></i> @endif
                                    <div style="font-size:10px; color:var(--smoke); margin-left:48px">{{ $r->processed_shiro['contingent'] }}</div>
                                </td>
                                <td align="center" style="font-weight:800; font-size:14px">
                                    {{ $r->score_red }} - {{ $r->score_blue }}
                                </td>
                                <td>
                                    @if($r->processed_aka['is_mine'] || $r->processed_shiro['is_mine'])
                                        @php 
                                            $iWon = ($r->processed_aka['is_mine'] && $r->processed_aka['is_winner']) || 
                                                    ($r->processed_shiro['is_mine'] && $r->processed_shiro['is_winner']);
                                        @endphp
                                        @if($iWon)
                                            <span style="color:#27ae60; font-weight:800; font-size:10px; text-transform:uppercase"><i class="fas fa-trophy"></i> Menang</span>
                                        @else
                                            <span style="color:#c0392b; font-weight:800; font-size:10px; text-transform:uppercase">Kalah</span>
                                        @endif
                                    @else
                                        <span style="font-size:10px; color:var(--smoke)">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" align="center" style="padding:40px; color:var(--smoke)">Belum ada data hasil Randori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div style="padding:16px;">{{ $results->links() }}</div>
            </div>
        @endif
    </div>
</div>
