<div>
    @push('styles')
    <style>
        .prem-page-a { background: var(--paper); color: var(--ink); padding: 28px; }
        .page-hdr-a { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 24px; }
        .page-hdr-a h2 { font-family: 'Cinzel', serif; font-size: 20px; font-weight: 700; color: var(--ink); margin: 0 0 4px; }
        .page-hdr-a p { font-size: 12px; color: var(--smoke); margin: 0; }
        .btn-prem-add { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: var(--red); color: #fff; border: none; border-radius: 12px; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; font-family: 'DM Sans', sans-serif; transition: all .2s; white-space: nowrap; box-shadow: 0 4px 15px rgba(192, 57, 43, 0.2); }
        .btn-prem-add:hover { background: var(--red-deep); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(192, 57, 43, 0.3); color: #fff; }
        .table-section-prem { background: #fff; border-radius: 16px; border: 1px solid var(--paper2); margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
        .premium-table { width: 100%; border-collapse: collapse; }
        .premium-table th { background: var(--paper); padding: 14px 16px; text-align: left; font-size: 10.5px; text-transform: uppercase; letter-spacing: .06em; color: var(--smoke); font-weight: 700; border-bottom: 1px solid var(--paper2); }
        .premium-table td { padding: 16px; border-bottom: 1px solid var(--paper2); vertical-align: top; }
        .premium-table tr:last-child td { border-bottom: none; }
        .premium-table tr:hover { background: rgba(248, 245, 240, 0.5); }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
        .contingent-item { margin-bottom: 16px; }
        .contingent-item:last-child { margin-bottom: 0; }
        .contingent-title { font-weight: 700; color: var(--ink); font-size: 13px; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
        .athlete-list { margin: 0; padding-left: 24px; list-style-type: disc; color: var(--smoke); font-size: 12.5px; line-height: 1.5; }
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
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: var(--smoke);">{{ $match['id'] }}</td>
                                <td>
                                    <div style="font-weight: 700; color: var(--ink); font-size: 13.5px; margin-bottom: 6px;">{{ $match['name'] }}</div>
                                    <span class="badge" style="background:rgba(41,128,185,0.1);color:#2980b9;">{{ $match['age_group'] }}</span>
                                </td>
                                <td>
                                    @if(empty($match['contingents']))
                                        <span style="color: #95a5a6; font-style: italic; font-size: 12px;">Belum ada peserta terdaftar</span>
                                    @else
                                        @foreach($match['contingents'] as $contingentName => $athletes)
                                            <div class="contingent-item">
                                                <div class="contingent-title">
                                                    <i class="fas fa-flag" style="color:var(--red);font-size:11px;"></i> {{ $contingentName }}
                                                </div>
                                                <ul class="athlete-list">
                                                    @foreach($athletes as $athleteName)
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
                            <th>Kontingen</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unregisteredAthletes as $index => $athlete)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: var(--smoke);">{{ $index + 1 }}</td>
                                <td style="font-weight: 700; color: var(--ink); text-transform: uppercase;">{{ $athlete['name'] }}</td>
                                <td style="color: var(--smoke); font-size: 12.5px;">
                                    <i class="fas fa-flag" style="color:var(--red);font-size:10px;margin-right:4px;"></i> {{ $athlete['contingent'] }}
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge" style="background:rgba(149,165,166,0.1);color:#7f8c8d;">0 Pertandingan</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 32px; color: var(--smoke); font-style: italic;">
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
