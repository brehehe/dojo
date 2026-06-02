<div>
    @push('styles')
    <style>
        /* ══════════════════════════════════════════════════════
           PAGE STYLES — Master Multi Nomor (Premium Layout)
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

        .stat-card-a.red::after {
            background: var(--red);
        }

        .stat-card-a.blue::after {
            background: #3498db;
        }

        .stat-card-a.gold::after {
            background: var(--gold);
        }

        /* st-icon and stat-icon-a */
        .st-icon, .stat-icon-a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 12px;
        }

        .st-icon.red, .stat-card-a.red .stat-icon-a {
            background: rgba(192, 57, 43, 0.1);
            color: var(--red);
        }

        .st-icon.blue, .stat-card-a.blue .stat-icon-a {
            background: rgba(41, 128, 185, 0.1);
            color: #2980b9;
        }

        .st-icon.gold, .stat-card-a.gold .stat-icon-a {
            background: rgba(212, 168, 67, 0.15);
            color: #b8860b;
        }

        .st-val, .stat-value-a {
            font-family: 'Cinzel', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
            margin-bottom: 4px;
        }

        .st-lbl, .stat-label-a {
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

        .premium-table tr:last-child td {
            border-bottom: none;
        }

        .premium-table tr:hover {
            background: rgba(248, 245, 240, 0.5);
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        /* ── LAYOUT GRID ── */
        .main-grid-prem {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
            margin-bottom: 24px;
        }

        @media (max-width: 1024px) {
            .main-grid-prem {
                grid-template-columns: 1fr;
            }
            .stats-grid-a {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .stats-grid-a {
                grid-template-columns: 1fr;
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--paper2);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--smoke);
        }
    </style>
    @endpush

    <div class="prem-page-a">
        <!-- Header -->
        <div class="page-hdr-a">
            <div>
                <h2>Deteksi Atlet Multi-Nomor</h2>
                <p>Anti Bentrok Jadwal &amp; Simulasi Jadwal Aman</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button wire:click="loadDataFromDb" class="btn-prem-add" style="background:var(--paper);border:1px solid var(--paper2);color:var(--ink);box-shadow:none;">
                    <i class="fas fa-sync text-indigo-500" style="margin-right:6px;"></i> Refresh dari Database
                </button>
                <button wire:click="downloadExcel" class="btn-prem-add" style="background:#27ae60;box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);">
                    <i class="fas fa-file-excel" style="margin-right:6px;"></i> Export Ke Excel
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid-a">
            <div class="stat-card-a red">
                <div class="stat-icon-a"><i class="fas fa-layer-group"></i></div>
                <div class="stat-label-a">Total Nomor</div>
                <div class="stat-value-a" style="color:var(--red);">{{ count($matches) }}</div>
            </div>
            <div class="stat-card-a blue">
                <div class="stat-icon-a"><i class="fas fa-users"></i></div>
                <div class="stat-label-a">Total Atlet</div>
                <div class="stat-value-a" style="color:#2980b9;">{{ $totalAtlet }}</div>
            </div>
            <div class="stat-card-a gold">
                <div class="stat-icon-a"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-label-a">Multi Nomor (&gt;1)</div>
                <div class="stat-value-a" style="color:#b8860b;">{{ count($multiAthletes) }}</div>
            </div>
        </div>

        <!-- Configurations / Control Bar -->
        <div class="table-section-prem" style="padding:22px;margin-bottom:24px;position:relative;overflow:hidden;">
            <div style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:16px;">
                <div style="flex: 1; min-width: 150px;">
                    <label style="display:block;font-size:10.5px;color:var(--smoke);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;">Simulasi Court</label>
                    <input type="number" wire:model="courtCount" min="1" max="6" style="width:100%;padding:10px 14px;border:1px solid var(--paper2);border-radius:10px;font-size:13px;font-family:'DM Sans',sans-serif;outline:none;box-sizing:border-box;background:#fff;">
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display:block;font-size:10.5px;color:var(--smoke);font-weight:500;text-transform:uppercase;letter-spacing:.06em;margin-bottom:5px;">Simulasi Hari</label>
                    <input type="number" wire:model="hariCount" min="1" max="3" style="width:100%;padding:10px 14px;border:1px solid var(--paper2);border-radius:10px;font-size:13px;font-family:'DM Sans',sans-serif;outline:none;box-sizing:border-box;background:#fff;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <button wire:click="analyze" class="btn-prem-add" style="width:100%;justify-content:center;padding:12px 20px;">
                        <i class="fas fa-sync-alt" style="margin-right:6px;"></i> Proses &amp; Deteksi Bentrok
                    </button>
                </div>
            </div>
        </div>

        <!-- Side-by-Side Area -->
        <div class="main-grid-prem">
            <!-- Left Column: Daftar Nomor Pertandingan -->
            <div class="table-section-prem" style="padding:24px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;border-bottom:1px solid var(--paper2);padding-bottom:12px;">
                    <h3 style="font-family:'Cinzel',serif;font-size:13px;font-weight:700;color:var(--ink);margin:0;"><i class="fas fa-edit" style="color:var(--red);margin-right:6px;"></i> Daftar Nomor Pertandingan</h3>
                    <button wire:click="addMatch" class="btn-prem-add" style="padding:6px 12px;font-size:11.5px;border-radius:8px;">
                        <i class="fas fa-plus" style="margin-right:4px;"></i> Tambah
                    </button>
                </div>
                
                <div class="custom-scrollbar" style="display:flex;flex-direction:column;gap:14px;max-height:600px;overflow-y:auto;padding-right:8px;">
                    @foreach($matches as $idx => $match)
                        <div style="padding:16px;background:var(--paper);border:1px solid var(--paper2);border-radius:12px;position:relative;display:flex;flex-direction:column;gap:10px;" wire:key="match-item-{{ $idx }}">
                            <button wire:click="removeMatch({{ $idx }})" 
                                    style="position:absolute;top:10px;right:10px;background:none;border:none;color:var(--smoke);cursor:pointer;font-size:14px;"
                                    title="Hapus nomor ini">
                                <i class="fas fa-times"></i>
                            </button>

                            <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <span style="font-size:11px;color:var(--smoke);font-weight:600;text-transform:uppercase;">Kode:</span>
                                    <input type="text" wire:model="matches.{{ $idx }}.id" placeholder="Cth: P1"
                                           style="width:70px;padding:6px 10px;border:1px solid var(--paper2);border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;outline:none;background:#fff;">
                                </div>
                                
                                <div style="display:flex;align-items:center;gap:6px;">
                                    <span style="font-size:11px;color:var(--smoke);font-weight:600;text-transform:uppercase;">Tipe:</span>
                                    <select wire:model="matches.{{ $idx }}.type" 
                                            style="padding:6px 10px;border:1px solid var(--paper2);border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;outline:none;background:#fff;color:var(--ink);">
                                        <option value="Embu">Embu</option>
                                        <option value="Randori">Randori</option>
                                    </select>
                                </div>
                            </div>

                            <input type="text" wire:model="matches.{{ $idx }}.name" placeholder="Nama Pertandingan"
                                   style="width:100%;padding:8px 12px;border:1px solid var(--paper2);border-radius:8px;font-size:12.5px;font-family:'DM Sans',sans-serif;outline:none;background:#fff;box-sizing:border-box;">

                            <div>
                                <label style="display:block;font-size:10px;color:var(--smoke);font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Daftar Peserta (Pisahkan dengan koma):</label>
                                <textarea wire:model="matches.{{ $idx }}.pesertaText" rows="2" placeholder="Andi, Budi, Citra..."
                                          style="width:100%;padding:8px 12px;border:1px solid var(--paper2);border-radius:8px;font-size:12px;font-family:'DM Sans',sans-serif;outline:none;background:#fff;box-sizing:border-box;resize:none;"></textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Column -->
            <div style="display:flex;flex-direction:column;gap:24px;">
                <!-- Multi Athlete Alert -->
                @if(count($multiAthletes) > 0)
                    <div class="table-section-prem" style="padding:24px;border-color:rgba(192, 57, 43, 0.2);background:rgba(192, 57, 43, 0.02);">
                        <h3 style="font-family:'Cinzel',serif;font-size:13.5px;font-weight:700;color:var(--red);margin:0 0 4px;"><i class="fas fa-exclamation-triangle" style="margin-right:6px;"></i> Atlet Multi-Nomor Terdeteksi ({{ count($multiAthletes) }} Atlet)</h3>
                        <p style="font-size:11.5px;color:var(--smoke);margin:0 0 16px;">Mereka terdaftar di lebih dari satu nomor pertandingan dan berisiko bentrok jadwal.</p>

                        <div class="custom-scrollbar" style="overflow-x:auto;max-height:250px;border:1px solid var(--paper2);border-radius:12px;background:#fff;">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th style="font-size:10px;padding:10px 12px;text-align:center;width:40px;">No</th>
                                        <th style="font-size:10px;padding:10px 12px;">Nama Atlet</th>
                                        <th style="font-size:10px;padding:10px 12px;text-align:center;">Jumlah</th>
                                        <th style="font-size:10px;padding:10px 12px;">Daftar Nomor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($multiAthletes as $index => $a)
                                        @php
                                            $nomorWithType = [];
                                            foreach ($a['nomorList'] as $idx => $nomor) {
                                                $nomorWithType[] = $nomor . ' (' . $a['typeList'][$idx] . ')';
                                            }
                                        @endphp
                                        <tr wire:key="multi-atlet-{{ $index }}">
                                            <td style="text-align:center;padding:10px 12px;">
                                                <span style="font-size:11px;font-weight:700;color:var(--smoke);">{{ $index + 1 }}</span>
                                            </td>
                                            <td style="padding:10px 12px;">
                                                <span style="font-size:12.5px;font-weight:700;color:var(--ink);text-transform:uppercase;">{{ $a['nama'] }}</span>
                                            </td>
                                            <td style="text-align:center;padding:10px 12px;">
                                                <span class="badge" style="background:rgba(192,57,43,0.1);color:var(--red);font-size:10px;padding:2px 6px;">{{ $a['jumlahNomor'] }}</span>
                                            </td>
                                            <td style="font-size:11.5px;color:var(--smoke);padding:10px 12px;line-height:1.4;">
                                                {{ implode(', ', $nomorWithType) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="table-section-prem" style="padding:32px;text-align:center;background:rgba(39, 174, 96, 0.02);border-color:rgba(39, 174, 96, 0.2);">
                        <div style="width:48px;height:48px;background:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,0.05);margin-bottom:12px;">
                            <i class="fas fa-check-circle" style="font-size:24px;color:#27ae60;"></i>
                        </div>
                        <h3 style="font-family:'Cinzel',serif;font-size:13.5px;font-weight:700;color:#27ae60;margin:0 0 4px;">Tidak Ada Atlet Multi-Nomor</h3>
                        <p style="font-size:12px;color:var(--smoke);margin:0;">Semua atlet terdaftar di tepat 1 nomor pertandingan.</p>
                    </div>
                @endif

                <!-- Ringkasan Semua Atlet -->
                <div class="table-section-prem" style="padding:24px;">
                    <h3 style="font-family:'Cinzel',serif;font-size:13px;font-weight:700;color:var(--ink);margin:0 0 16px;"><i class="fas fa-users-cog" style="color:var(--red);margin-right:6px;"></i> Ringkasan Semua Atlet</h3>
                    
                    <div class="custom-scrollbar" style="overflow-x:auto;max-height:300px;border:1px solid var(--paper2);border-radius:12px;background:#fff;">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th style="font-size:10px;padding:10px 12px;text-align:center;width:40px;">No</th>
                                    <th style="font-size:10px;padding:10px 12px;">Nama Atlet</th>
                                    <th style="font-size:10px;padding:10px 12px;text-align:center;">Nomor</th>
                                    <th style="font-size:10px;padding:10px 12px;">Status Resiko</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $noAll = 1; @endphp
                                @foreach($multiAthletes as $a)
                                    <tr wire:key="summary-multi-{{ $noAll }}">
                                        <td style="text-align:center;padding:10px 12px;">
                                            <span style="font-size:11px;font-weight:700;color:var(--smoke);">{{ $noAll }}</span>
                                        </td>
                                        <td style="padding:10px 12px;">
                                            <span style="font-size:12.5px;font-weight:700;color:var(--ink);text-transform:uppercase;">{{ $a['nama'] }}</span>
                                        </td>
                                        <td style="text-align:center;padding:10px 12px;">
                                            <span style="font-size:11.5px;font-weight:600;color:var(--ink);">{{ $a['jumlahNomor'] }}</span>
                                        </td>
                                        <td style="padding:10px 12px;">
                                            <span class="badge" style="background:rgba(192,57,43,0.1);color:var(--red);font-size:10px;padding:2px 6px;">
                                                Multi Nomor
                                            </span>
                                        </td>
                                    </tr>
                                    @php $noAll++; @endphp
                                @endforeach
                                @foreach($normalAthletes as $a)
                                    <tr wire:key="summary-normal-{{ $noAll }}">
                                        <td style="text-align:center;padding:10px 12px;">
                                            <span style="font-size:11px;font-weight:700;color:var(--smoke);">{{ $noAll }}</span>
                                        </td>
                                        <td style="padding:10px 12px;">
                                            <span style="font-size:12.5px;color:var(--ink);text-transform:uppercase;">{{ $a['nama'] }}</span>
                                        </td>
                                        <td style="text-align:center;padding:10px 12px;">
                                            <span style="font-size:11.5px;color:var(--smoke);">{{ $a['jumlahNomor'] }}</span>
                                        </td>
                                        <td style="padding:10px 12px;">
                                            <span class="badge" style="background:rgba(39,174,96,0.1);color:#27ae60;font-size:10px;padding:2px 6px;">
                                                Normal
                                            </span>
                                        </td>
                                    </tr>
                                    @php $noAll++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Section -->
        <div class="table-section-prem" style="padding:24px;">
            <div style="border-bottom:1px solid var(--paper2);padding-bottom:12px;margin-bottom:20px;">
                <h3 style="font-family:'Cinzel',serif;font-size:13px;font-weight:700;color:var(--ink);margin:0 0 4px;"><i class="fas fa-calendar-check" style="color:var(--red);margin-right:6px;"></i> Rekomendasi Jadwal Pertandingan (Anti Bentrok)</h3>
                <p style="font-size:11.5px;color:var(--smoke);margin:0;">Sistem otomatis mengatur jadwal dengan jeda minimal 30 menit antar pertandingan untuk atlet yang sama.</p>
            </div>

            @if(count($scheduledMatches) === 0)
                <div style="padding:48px;text-align:center;color:var(--smoke);border:2px dashed var(--paper2);border-radius:12px;">
                    <i class="fas fa-calendar-times" style="font-size:24px;display:block;margin-bottom:10px;opacity:0.4;"></i>
                    <p style="font-size:12.5px;margin:0;font-style:italic;">Belum ada jadwal yang disimulasikan. Pastikan minimal ada 1 nomor pertandingan dengan minimal 2 peserta.</p>
                </div>
            @else
                <!-- Days Tabs -->
                @php
                    $days = array_unique(array_column($scheduledMatches, 'day'));
                    sort($days);
                @endphp
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:20px;background:var(--paper);padding:4px;border-radius:10px;max-width:max-content;">
                    @foreach($days as $day)
                        <button wire:click="setActiveDay({{ $day }})"
                                style="padding:8px 16px;border:none;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:12px;font-weight:700;transition:all 0.15s;
                                {{ $activeDay === $day ? 'background:var(--ink);color:#fff;' : 'background:none;color:var(--smoke);' }}"
                                onmouseover="if(this.style.background!=='var(--ink)')this.style.background='var(--paper2)'"
                                onmouseout="if(this.style.background!=='var(--ink)')this.style.background='none'">
                            📅 Hari {{ $day }}
                        </button>
                    @endforeach
                </div>

                <!-- Court wise schedule columns -->
                @php
                    $dayMatches = array_filter($scheduledMatches, function($m) use ($activeDay) {
                        return $m['day'] === $activeDay;
                    });
                    $courts = array_unique(array_column($dayMatches, 'court'));
                    sort($courts);
                @endphp

                <div style="display:flex;flex-direction:column;gap:20px;">
                    @foreach($courts as $courtName)
                        @php
                            $courtMatches = array_filter($dayMatches, function($m) use ($courtName) {
                                return $m['court'] === $courtName;
                            });
                            usort($courtMatches, function($a, $b) {
                                return strcmp($a['time'], $b['time']);
                            });
                        @endphp

                        <div style="border:1px solid var(--paper2);border-radius:12px;overflow:hidden;background:#fff;" wire:key="court-block-{{ $courtName }}">
                            <div style="background:var(--paper);border-bottom:1px solid var(--paper2);padding:12px 16px;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-family:'Cinzel',serif;font-size:13px;font-weight:700;color:var(--ink);">🏟️ {{ $courtName }}</span>
                                <span style="background:rgba(192,57,43,0.1);color:var(--red);font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">{{ count($courtMatches) }} Match</span>
                            </div>
                            <div style="overflow-x:auto;">
                                <table class="premium-table">
                                    <thead>
                                        <tr>
                                            <th style="font-size:10px;padding:10px 12px;width:80px;">Waktu</th>
                                            <th style="font-size:10px;padding:10px 12px;width:80px;">ID</th>
                                            <th style="font-size:10px;padding:10px 12px;">Nama Pertandingan</th>
                                            <th style="font-size:10px;padding:10px 12px;text-align:center;width:90px;">Tipe</th>
                                            <th style="font-size:10px;padding:10px 12px;width:100px;">Round</th>
                                            <th style="font-size:10px;padding:10px 12px;">Peserta</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($courtMatches as $m)
                                            <tr wire:key="match-row-{{ $m['nomorId'] }}-{{ $m['time'] }}">
                                                <td style="padding:10px 12px;font-weight:700;color:var(--ink);font-size:12.5px;">{{ $m['time'] }}</td>
                                                <td style="padding:10px 12px;font-family:monospace;color:var(--red);font-size:11px;">{{ $m['nomorId'] }}</td>
                                                <td style="padding:10px 12px;font-size:12px;color:var(--ink);font-weight:600;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $m['nomorName'] }}">
                                                    {{ $m['nomorName'] }}
                                                </td>
                                                <td style="padding:10px 12px;text-align:center;">
                                                    @if($m['type'] === 'Embu')
                                                        <span class="badge" style="background:rgba(41,128,185,0.1);color:#2980b9;">🥋 Embu</span>
                                                    @else
                                                        <span class="badge" style="background:rgba(212,168,67,0.15);color:#b8860b;">⚡ Randori</span>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 12px;font-size:12px;color:var(--smoke);">{{ $m['roundName'] }}</td>
                                                <td style="padding:10px 12px;font-size:12px;font-weight:700;color:var(--ink);text-transform:uppercase;">
                                                    {{ implode(' vs ', $m['athletes']) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
