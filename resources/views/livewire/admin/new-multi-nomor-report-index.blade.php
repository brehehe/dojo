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
                <!-- Daftar Semua Atlet -->
                @if(count($allAthletes) > 0)
                    <div class="table-section-prem" style="padding:24px;border-color:rgba(41, 128, 185, 0.2);background:rgba(41, 128, 185, 0.02);">
                        <h3 style="font-family:'Cinzel',serif;font-size:13.5px;font-weight:700;color:#2980b9;margin:0 0 4px;"><i class="fas fa-users" style="margin-right:6px;"></i> Daftar Semua Atlet ({{ count($allAthletes) }} Atlet)</h3>
                        <p style="font-size:11.5px;color:var(--smoke);margin:0 0 16px;">Daftar seluruh atlet beserta nomor pertandingan yang mereka ikuti.</p>

                        <div class="custom-scrollbar" style="overflow-x:auto;max-height:450px;border:1px solid var(--paper2);border-radius:12px;background:#fff;">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th style="font-size:10px;padding:10px 12px;text-align:center;width:40px;">No</th>
                                        <th style="font-size:10px;padding:10px 12px;">Nama Atlet</th>
                                        <th style="font-size:10px;padding:10px 12px;text-align:center;">Jumlah</th>
                                        <th style="font-size:10px;padding:10px 12px;">Daftar Nomor Pertandingan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allAthletes as $index => $a)
                                        @php
                                            $nomorWithType = [];
                                            foreach ($a['nomorList'] as $idx => $nomor) {
                                                $nomorName = $a['nomorNameList'][$idx] ?? $nomor;
                                                $nomorWithType[] = $nomorName . ' (' . $a['typeList'][$idx] . ')';
                                            }
                                        @endphp
                                        <tr wire:key="atlet-{{ $index }}">
                                            <td style="text-align:center;padding:10px 12px;">
                                                <span style="font-size:11px;font-weight:700;color:var(--smoke);">{{ $index + 1 }}</span>
                                            </td>
                                            <td style="padding:10px 12px;">
                                                <span style="font-size:12.5px;font-weight:700;color:var(--ink);text-transform:uppercase;">{{ $a['nama'] }}</span>
                                            </td>
                                            <td style="text-align:center;padding:10px 12px;">
                                                @if($a['jumlahNomor'] > 1)
                                                    <span class="badge" style="background:rgba(192,57,43,0.1);color:var(--red);font-size:10px;padding:2px 6px;">{{ $a['jumlahNomor'] }}</span>
                                                @elseif($a['jumlahNomor'] == 1)
                                                    <span class="badge" style="background:rgba(39,174,96,0.1);color:#27ae60;font-size:10px;padding:2px 6px;">{{ $a['jumlahNomor'] }}</span>
                                                @else
                                                    <span class="badge" style="background:rgba(149,165,166,0.1);color:#7f8c8d;font-size:10px;padding:2px 6px;">0</span>
                                                @endif
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
                        <h3 style="font-family:'Cinzel',serif;font-size:13.5px;font-weight:700;color:#27ae60;margin:0 0 4px;">Tidak Ada Atlet</h3>
                        <p style="font-size:12px;color:var(--smoke);margin:0;">Belum ada atlet yang terdaftar.</p>
                    </div>
                @endif
            </div>
        </div>


    </div>
</div>
