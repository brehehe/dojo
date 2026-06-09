<div>
    @push('styles')
    <style>
        .prem-page-a { background: var(--paper); color: var(--ink); padding: 28px; }
        .page-hdr-a { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
        .page-hdr-a h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
        .page-hdr-a p { font-size: 12px; color: var(--smoke); margin: 0; }
        .btn-prem-add { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--red); color: #fff; border: none; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; font-family: 'DM Sans', sans-serif; transition: all .2s; white-space: nowrap; box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2); }
        .btn-prem-add:hover { background: var(--red-deep); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(192, 57, 43, 0.3); color: #fff; }
        
        /* ── STAT CARDS ── */
        .stats-grid-a { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 22px; max-width: 900px; }
        .stats-grid-a-sub { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; max-width: 1200px; }
        .stats-grid-a-six { display: grid; grid-template-columns: repeat(6, 1fr); gap: 14px; margin-bottom: 22px; }
        .stats-grid-a-two { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-bottom: 22px; max-width: 600px; }
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
        .stat-card-a.gold::after   { background: var(--gold); }
        .stat-card-a.blue::after   { background: #2980b9; }
        .stat-card-a.green::after  { background: #27ae60; }
        .stat-card-a.orange::after { background: #e67e22; }
        .stat-card-a.red::after    { background: var(--red); }

        .st-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 12px; }
        .st-icon.gold   { background: rgba(212, 168, 67, 0.15); color: #b8860b; }
        .st-icon.blue   { background: rgba(41, 128, 185, 0.15); color: #2980b9; }
        .st-icon.green  { background: rgba(39, 174, 96, 0.15); color: #27ae60; }
        .st-icon.orange { background: rgba(230, 126, 34, 0.15); color: #e67e22; }
        .st-icon.red    { background: rgba(192, 57, 43, 0.15); color: var(--red); }
        
        .st-val { font-family: 'Cinzel', serif; font-size: 26px; font-weight: 700; color: var(--ink); line-height: 1; margin-bottom: 4px; }
        .st-lbl { font-size: 11px; color: var(--smoke); text-transform: uppercase; letter-spacing: .05em; font-weight: 600; }

        .table-section-prem { background: #fff; border-radius: 16px; border: 1px solid var(--paper2); margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { background: var(--paper); padding: 14px 16px; text-align: left; font-size: 10.5px; text-transform: uppercase; letter-spacing: .06em; color: var(--smoke); font-weight: 700; border-bottom: 1px solid var(--paper2); }
        .premium-table td { padding: 16px; border-bottom: 1px solid var(--paper2); vertical-align: top; }
        .premium-table tr:last-child td { border-bottom: none; }
        .premium-table tr:hover { background: rgba(248, 245, 240, 0.5); }
        .row-has-duplicate { background-color: #fef9c3 !important; }
        .row-has-duplicate:hover { background-color: #fef08a !important; }
        .row-no-duplicate { background-color: #dcfce7 !important; }
        .row-no-duplicate:hover { background-color: #bbf7d0 !important; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
        .contingent-item { margin-bottom: 16px; }
        .contingent-item:last-child { margin-bottom: 0; }
        .contingent-title { font-weight: 700; color: var(--ink); font-size: 13px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
        .athlete-list { margin: 0; padding-left: 24px; list-style-type: disc; color: var(--smoke); font-size: 12.5px; line-height: 1.5; }

        @media (max-width: 1200px) {
            .stats-grid-a-six { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 992px) {
            .stats-grid-a-sub { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .stats-grid-a { grid-template-columns: repeat(2, 1fr); }
            .stats-grid-a-six { grid-template-columns: repeat(2, 1fr); }
            .prem-page-a { padding: 16px; }
        }
        @media (max-width: 480px) {
            .stats-grid-a { grid-template-columns: 1fr; }
            .stats-grid-a-sub { grid-template-columns: 1fr; }
            .stats-grid-a-six { grid-template-columns: 1fr; }
            .stats-grid-a-two { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    <div class="prem-page-a">
        <div class="page-hdr-a">
            <div>
                <h2>Laporan Kontingen &amp; Atlet Kosong</h2>
                <p>Distribusi Atlet per Kontingen pada setiap Nomor Pertandingan & Daftar Atlet tanpa jadwal</p>
            </div>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button wire:click="loadData" class="btn-prem-add" style="background:var(--paper);border:1px solid var(--paper2);color:var(--ink);box-shadow:none;">
                    <i class="fas fa-sync text-indigo-500" style="margin-right:6px;"></i> Refresh dari Database
                </button>
                <button wire:click="downloadExcel" class="btn-prem-add" style="background:#27ae60;box-shadow: 0 4px 15px rgba(39, 174, 96, 0.2);">
                    <i class="fas fa-file-excel" style="margin-right:6px;"></i> Export Ke Excel
                </button>
            </div>
        </div>

        {{-- FILTER BAR --}}
        <div style="background:#fff; border:1px solid var(--paper2); border-radius:16px; padding:16px 20px; margin-bottom:20px; display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
            <div style="flex: 1; min-width: 200px; position: relative;">
                <label style="display:block; font-size:9px; font-weight:700; color:var(--smoke); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Cari Nama Atlet / Kontingen / Nomor</label>
                <div style="position: relative;">
                    <input type="text" wire:model.live="searchQuery" placeholder="Ketik pencarian..." style="width:100%; padding:10px 12px 10px 36px; border:1px solid var(--paper2); border-radius:10px; font-size:13px; color:var(--ink); outline:none; background:var(--paper);">
                    <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--smoke); font-size:13px;"></i>
                </div>
            </div>
            <div style="width: 200px; min-width: 150px;">
                <label style="display:block; font-size:9px; font-weight:700; color:var(--smoke); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px;">Jenis Kelamin</label>
                <select wire:model.live="genderFilter" style="width:100%; padding:10px 12px; border:1px solid var(--paper2); border-radius:10px; font-size:13px; color:var(--ink); outline:none; background:var(--paper); cursor:pointer;">
                    <option value="">Semua Jenis Kelamin</option>
                    <option value="Male">Laki-laki (Male)</option>
                    <option value="Female">Perempuan (Female)</option>
                </select>
            </div>
        </div>

        {{-- STATS GRID --}}
        <div class="stats-grid-a">
            <div class="stat-card-a gold">
                <div class="st-icon gold"><i class="fas fa-users"></i></div>
                <div class="st-val">{{ $totalAthletes }}</div>
                <div class="st-lbl">Total Atlet Sesuai Filter</div>
            </div>
            <div class="stat-card-a blue">
                <div class="st-icon blue"><i class="fas fa-running"></i></div>
                <div class="st-val">{{ $totalRegisteredAthletes }}</div>
                <div class="st-lbl">Atlet Terdaftar di Nomor</div>
            </div>
            <div class="stat-card-a red">
                <div class="st-icon red"><i class="fas fa-user-times"></i></div>
                <div class="st-val">{{ $totalUnregisteredAthletes }}</div>
                <div class="st-lbl">Atlet Tidak Terdaftar</div>
            </div>
        </div>

        {{-- STATUS NOMOR PERTANDINGAN GRID --}}
        <div style="margin-bottom: 24px;">
            <div style="font-size: 11px; color: var(--smoke); text-transform: uppercase; letter-spacing: .08em; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-list-check" style="color:var(--red);"></i> Status Nomor Pertandingan Sesuai Filter
            </div>
            <div class="stats-grid-a-two">
                <div class="stat-card-a green">
                    <div class="st-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="st-val">{{ $totalMatchesWithAthletes }}</div>
                    <div class="st-lbl">Ada Peserta</div>
                </div>
                <div class="stat-card-a red">
                    <div class="st-icon red"><i class="fas fa-times-circle"></i></div>
                    <div class="st-val">{{ $totalMatchesWithoutAthletes }}</div>
                    <div class="st-lbl">Tidak Ada Peserta</div>
                </div>
            </div>
        </div>

        {{-- EKSEBISI & NON EKSEBISI GRID --}}
        {{--<div style="margin-bottom: 24px;">
            <div style="font-size: 11px; color: var(--smoke); text-transform: uppercase; letter-spacing: .08em; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-chart-pie" style="color:var(--red);"></i> Kategori Eksebisi &amp; Non Eksebisi (Atlet Terdaftar)
            </div>
            <div class="stats-grid-a-six">
                <div class="stat-card-a gold">
                    <div class="st-icon gold"><i class="fas fa-award"></i></div>
                    <div class="st-val">{{ $totalEksebisi }}</div>
                    <div class="st-lbl">Eksebisi</div>
                </div>
                <div class="stat-card-a blue">
                    <div class="st-icon blue"><i class="fas fa-medal"></i></div>
                    <div class="st-val">{{ $totalNonEksebisi }}</div>
                    <div class="st-lbl">Non Eksebisi</div>
                </div>
                <div class="stat-card-a green">
                    <div class="st-icon green"><i class="fas fa-shield-alt"></i></div>
                    <div class="st-val">{{ $totalEmbuEksebisi }}</div>
                    <div class="st-lbl">Embu Eksebisi</div>
                </div>
                <div class="stat-card-a orange">
                    <div class="st-icon orange"><i class="fas fa-scroll"></i></div>
                    <div class="st-val">{{ $totalEmbuNonEksebisi }}</div>
                    <div class="st-lbl">Embu Non Eksebisi</div>
                </div>
                <div class="stat-card-a red">
                    <div class="st-icon red"><i class="fas fa-fist-raised"></i></div>
                    <div class="st-val">{{ $totalRandoriEksebisi }}</div>
                    <div class="st-lbl">Randori Eksebisi</div>
                </div>
                <div class="stat-card-a orange">
                    <div class="st-icon orange"><i class="fas fa-user-shield"></i></div>
                    <div class="st-val">{{ $totalRandoriNonEksebisi }}</div>
                    <div class="st-lbl">Randori Non Eksebisi</div>
                </div>
            </div>
        </div>--}}

        {{-- KELOMPOK UMUR GRID --}}
        <div style="margin-bottom: 24px;">
            <div style="font-size: 11px; color: var(--smoke); text-transform: uppercase; letter-spacing: .08em; font-weight: 700; margin-bottom: 12px; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-filter" style="color:var(--red);"></i> Distribusi Per Kelompok Umur
            </div>
            <div class="stats-grid-a-sub">
                <div class="stat-card-a blue">
                    <div class="st-icon blue"><i class="fas fa-child"></i></div>
                    <div class="st-val">{{ $ageGroupStats['Pemula'] }}</div>
                    <div class="st-lbl">Pemula</div>
                </div>
                <div class="stat-card-a green">
                    <div class="st-icon green"><i class="fas fa-user-friends"></i></div>
                    <div class="st-val">{{ $ageGroupStats['Remaja A'] }}</div>
                    <div class="st-lbl">Remaja A</div>
                </div>
                <div class="stat-card-a orange">
                    <div class="st-icon orange"><i class="fas fa-user-friends"></i></div>
                    <div class="st-val">{{ $ageGroupStats['Remaja B'] }}</div>
                    <div class="st-lbl">Remaja B</div>
                </div>
                <div class="stat-card-a red">
                    <div class="st-icon red"><i class="fas fa-user-tie"></i></div>
                    <div class="st-val">{{ $ageGroupStats['Dewasa'] }}</div>
                    <div class="st-lbl">Dewasa</div>
                </div>
            </div>
        </div>

        <!-- Tabel 1: Nomor Pertandingan dan Kontingen -->
        <div class="table-section-prem" style="padding:24px;">
            <h3 style="font-family:'Cinzel',serif;font-size:14px;font-weight:700;color:var(--ink);margin:0 0 16px;"><i class="fas fa-list-alt" style="color:#2980b9;margin-right:8px;"></i> Distribusi Atlet & Kontingen per Nomor Pertandingan</h3>
            
            <div style="overflow-x:auto;border:1px solid var(--paper2);border-radius:12px;">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">ID</th>
                            <th style="width: 300px;">Nomor Pertandingan</th>
                            <th>Daftar Kontingen & Atlet</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matchData as $match)
                            <tr class="{{ $match['has_duplicate_contingent'] ? 'row-has-duplicate' : 'row-no-duplicate' }}">
                                <td style="text-align: center; font-weight: 700; color: var(--smoke);">{{ $match['id'] }}</td>
                                <td>
                                    <div style="font-weight: 700; color: var(--ink); font-size: 13.5px; margin-bottom: 6px;">{{ $match['name'] }}</div>
                                    <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                                        <span class="badge" style="background:rgba(41,128,185,0.1);color:#2980b9;">{{ $match['age_group'] }}</span>
                                        <span class="badge" style="background:rgba(39,174,96,0.1);color:#27ae60;">
                                            <i class="fas fa-users" style="margin-right:4px;"></i> {{ $match['total_athletes'] }} Atlet
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if(empty($match['contingents']))
                                        <span style="color: #95a5a6; font-style: italic; font-size: 12px;">Belum ada peserta terdaftar</span>
                                    @else
                                        @foreach($match['contingents'] as $c)
                                            <div class="contingent-item">
                                                <div class="contingent-title">
                                                    <i class="fas fa-flag" style="color:var(--red);font-size:11px;"></i> {{ $c['name'] }}
                                                </div>
                                                <ul class="athlete-list">
                                                    @foreach($c['athletes'] as $athleteName)
                                                        <li>{{ $athleteName }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 32px; color: var(--smoke); font-style: italic;">Tidak ada data nomor pertandingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabel 2: Atlet yang tidak ada jadwal -->
        <div class="table-section-prem" style="padding:24px;border-color:rgba(192, 57, 43, 0.2);background:rgba(192, 57, 43, 0.02);">
            <h3 style="font-family:'Cinzel',serif;font-size:14px;font-weight:700;color:var(--red);margin:0 0 16px;"><i class="fas fa-user-times" style="margin-right:8px;"></i> Daftar Atlet Belum Terdaftar di Nomor Pertandingan ({{ count($unregisteredAthletes) }} Atlet)</h3>
            
            <div style="overflow-x:auto;border:1px solid var(--paper2);border-radius:12px;background:#fff;">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Atlet</th>
                            <th>Jenis Kelamin</th>
                            <th>Kontingen</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unregisteredAthletes as $index => $athlete)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: var(--smoke);">{{ $index + 1 }}</td>
                                <td style="font-weight: 700; color: var(--ink); text-transform: uppercase;">{{ $athlete['name'] }}</td>
                                <td>
                                    @if($athlete['gender'] === 'Laki-laki')
                                        <span class="badge" style="background:rgba(41,128,185,0.1);color:#2980b9;">
                                            <i class="fas fa-mars" style="margin-right:4px;"></i> Laki-laki
                                        </span>
                                    @elseif($athlete['gender'] === 'Perempuan')
                                        <span class="badge" style="background:rgba(233,30,99,0.1);color:#e91e63;">
                                            <i class="fas fa-venus" style="margin-right:4px;"></i> Perempuan
                                        </span>
                                    @else
                                        <span class="badge" style="background:rgba(149,165,166,0.1);color:#7f8c8d;">
                                            {{ $athlete['gender'] }}
                                        </span>
                                    @endif
                                </td>
                                <td style="color: var(--smoke); font-size: 12.5px;">
                                    <i class="fas fa-flag" style="color:var(--red);font-size:10px;margin-right:4px;"></i> {{ $athlete['contingent'] }}
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge" style="background:rgba(149,165,166,0.1);color:#7f8c8d;">0 Pertandingan</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 32px; color: var(--smoke); font-style: italic;">
                                    <i class="fas fa-check-circle" style="color:#27ae60;font-size:24px;margin-bottom:10px;display:block;"></i>
                                    Semua atlet telah terdaftar di minimal 1 nomor pertandingan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
