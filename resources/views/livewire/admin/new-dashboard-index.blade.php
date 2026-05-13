<div>
    @push('styles')
        <style>
            /* ══════════════════════════════════════════════════════
           OVERRIDE & CONTENT STYLES
        ══════════════════════════════════════════════════════ */
            .premium-dashboard {
                background: var(--paper);
                color: var(--ink);
                padding: 28px;
            }

            .cinzel {
                font-family: 'Cinzel', serif;
            }

            /* ── STAT CARDS ── */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
                margin-bottom: 24px;
            }

            .stat-card {
                background: #fff;
                border-radius: 16px;
                padding: 20px 22px;
                border: 1px solid var(--paper2);
                position: relative;
                overflow: hidden;
                transition: transform .2s, box-shadow .2s;
            }

            .stat-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 12px 40px rgba(0, 0, 0, .09);
            }

            .stat-card::after {
                content: '';
                position: absolute;
                bottom: 0;
                right: 0;
                width: 80px;
                height: 80px;
                border-radius: 50%;
                opacity: .07;
                transform: translate(25px, 25px);
            }

            .stat-card.red::after {
                background: var(--red);
            }

            .stat-card.gold::after {
                background: var(--gold);
            }

            .stat-card.blue::after {
                background: #3498db;
            }

            .stat-card.green::after {
                background: #27ae60;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                border-radius: 11px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                margin-bottom: 12px;
            }

            .stat-card.red .stat-icon {
                background: rgba(192, 57, 43, .1);
                color: var(--red);
            }

            .stat-card.gold .stat-icon {
                background: rgba(212, 168, 67, .12);
                color: #b8860b;
            }

            .stat-card.blue .stat-icon {
                background: rgba(52, 152, 219, .1);
                color: #2980b9;
            }

            .stat-card.green .stat-icon {
                background: rgba(39, 174, 96, .1);
                color: #27ae60;
            }

            .stat-label {
                font-size: 11px;
                color: var(--smoke);
                font-weight: 500;
                letter-spacing: .05em;
                text-transform: uppercase;
                margin-bottom: 5px;
            }

            .stat-value {
                font-family: 'Cinzel', serif;
                font-size: 28px;
                font-weight: 700;
                line-height: 1;
                margin-bottom: 7px;
            }

            .stat-delta {
                font-size: 11.5px;
                display: flex;
                align-items: center;
                gap: 5px;
                color: #27ae60;
                font-weight: 500;
            }

            .stat-delta.down {
                color: var(--red);
            }

            .stat-delta i {
                font-size: 9px;
            }

            /* ── 2-COL ── */
            .two-col {
                display: grid;
                grid-template-columns: 1fr 360px;
                gap: 20px;
                margin-bottom: 22px;
            }

            /* ── CARD ── */
            .card-premium {
                background: #fff;
                border-radius: 16px;
                border: 1px solid var(--paper2);
                overflow: hidden;
            }

            .card-head-premium {
                padding: 20px 22px 0;
                display: flex;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
                gap: 8px;
            }

            .card-head-premium h3 {
                font-family: 'Cinzel', serif;
                font-size: 13px;
                font-weight: 700;
                flex: 1;
                min-width: 120px;
            }

            .card-sub-premium {
                font-size: 11px;
                color: var(--smoke);
            }

            .tab-group-premium {
                display: flex;
                gap: 3px;
            }

            .tab-btn-premium {
                padding: 5px 12px;
                border-radius: 7px;
                border: none;
                background: none;
                font-size: 12px;
                font-family: 'DM Sans', sans-serif;
                cursor: pointer;
                color: #888;
                font-weight: 500;
                transition: all .15s;
            }

            .tab-btn-premium.active {
                background: var(--ink);
                color: #fff;
            }

            .chart-wrap-premium {
                padding: 14px 22px 18px;
            }

            /* ── LEADERBOARD ── */
            .leader-list {
                padding: 6px 0 14px;
            }

            .leader-item {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 11px 22px;
                transition: background .15s;
            }

            .leader-item:hover {
                background: var(--paper);
            }

            .rank-premium {
                font-family: 'Cinzel', serif;
                font-size: 13px;
                font-weight: 700;
                width: 24px;
                text-align: center;
                flex-shrink: 0;
            }

            .rank-premium.gold {
                color: #d4a843;
            }

            .rank-premium.silver {
                color: #95a5a6;
            }

            .rank-premium.bronze {
                color: #a0522d;
            }

            .leader-avatar {
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 700;
                color: #fff;
                flex-shrink: 0;
            }

            .leader-info {
                flex: 1;
                min-width: 0;
            }

            .leader-info h4 {
                font-size: 13px;
                font-weight: 600;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .leader-info p {
                font-size: 11px;
                color: var(--smoke);
                margin-top: 1px;
            }

            .leader-score {
                font-family: 'Cinzel', serif;
                font-size: 14px;
                font-weight: 700;
                min-width: 26px;
                text-align: right;
            }

            /* ── TABLE SECTION ── */
            .table-section-premium {
                background: #fff;
                border-radius: 16px;
                border: 1px solid var(--paper2);
                margin-bottom: 24px;
                overflow: hidden;
            }

            .table-head-premium {
                padding: 18px 22px;
                display: flex;
                align-items: center;
                gap: 10px;
                border-bottom: 1px solid var(--paper2);
                flex-wrap: wrap;
            }

            .table-head-premium h3 {
                font-family: 'Cinzel', serif;
                font-size: 13.5px;
                font-weight: 700;
                flex: 1;
                min-width: 120px;
            }

            .filter-group-premium {
                display: flex;
                gap: 5px;
                flex-wrap: wrap;
            }

            .filter-btn-premium {
                padding: 5px 12px;
                border-radius: 7px;
                border: 1px solid var(--paper2);
                background: none;
                font-size: 11.5px;
                color: #666;
                cursor: pointer;
                font-family: 'DM Sans', sans-serif;
                transition: all .15s;
            }

            .filter-btn-premium.active {
                background: var(--ink);
                color: #fff;
                border-color: var(--ink);
            }

            .export-btn-premium {
                padding: 7px 13px;
                background: var(--red);
                color: #fff;
                border: none;
                border-radius: 9px;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 6px;
                font-family: 'DM Sans', sans-serif;
                transition: background .15s;
                white-space: nowrap;
            }

            .export-btn-premium:hover {
                background: var(--kempo-red-deep);
            }

            .table-scroll-premium {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .premium-table {
                width: 100%;
                border-collapse: collapse;
                min-width: 700px;
            }

            .premium-table thead th {
                padding: 10px 14px;
                font-size: 10px;
                color: var(--smoke);
                font-weight: 600;
                letter-spacing: .08em;
                text-transform: uppercase;
                text-align: left;
                background: var(--paper);
                border-bottom: 1px solid var(--paper2);
                white-space: nowrap;
            }

            .premium-table thead th:first-child {
                padding-left: 22px;
            }

            .premium-table thead th:last-child {
                padding-right: 22px;
                text-align: center;
            }

            .premium-table tbody tr {
                transition: background .12s;
            }

            .premium-table tbody tr:hover {
                background: rgba(247, 244, 239, .6);
            }

            .premium-table tbody td {
                padding: 12px 14px;
                font-size: 12.5px;
                border-bottom: 1px solid var(--paper2);
                color: var(--ink);
            }

            .premium-table tbody tr:last-child td {
                border-bottom: none;
            }

            .premium-table td:first-child {
                padding-left: 22px;
            }

            .premium-table td:last-child {
                padding-right: 22px;
                text-align: center;
            }

            .badge-premium {
                display: inline-flex;
                align-items: center;
                padding: 3px 9px;
                border-radius: 20px;
                font-size: 10.5px;
                font-weight: 600;
                white-space: nowrap;
            }

            .badge-premium.gold {
                background: rgba(212, 168, 67, .15);
                color: #9a6e00;
            }

            .badge-premium.silver {
                background: rgba(149, 165, 166, .15);
                color: #607d8b;
            }

            .badge-premium.green {
                background: rgba(39, 174, 96, .12);
                color: #1e8449;
            }

            .badge-premium.blue {
                background: rgba(52, 152, 219, .12);
                color: #1a6ea3;
            }

            .badge-premium.red {
                background: rgba(192, 57, 43, .12);
                color: var(--kempo-red-deep);
            }

            .action-btn-premium {
                width: 28px;
                height: 28px;
                border-radius: 7px;
                border: 1px solid var(--paper2);
                background: none;
                cursor: pointer;
                font-size: 11px;
                color: #888;
                transition: all .15s;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .action-btn-premium:hover {
                background: var(--paper2);
                color: var(--ink);
            }

            /* ── SCHEDULE ── */
            .sched-item {
                display: flex;
                gap: 12px;
                padding: 12px 22px;
                border-bottom: 1px solid var(--paper2);
                align-items: flex-start;
            }

            .sched-item:last-child {
                border-bottom: none;
            }

            .sched-time {
                font-family: 'Cinzel', serif;
                font-size: 11.5px;
                color: var(--smoke);
                min-width: 52px;
                padding-top: 2px;
            }

            .sched-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                margin-top: 5px;
                flex-shrink: 0;
            }

            .sched-info h4 {
                font-size: 13px;
                font-weight: 600;
            }

            .sched-info p {
                font-size: 11px;
                color: var(--kempo-smoke);
                margin-top: 2px;
            }

            /* ── ACTIVITY ── */
            .activity-item {
                display: flex;
                gap: 11px;
                padding: 11px 22px;
                align-items: flex-start;
            }

            .act-icon {
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                flex-shrink: 0;
                margin-top: 2px;
            }

            .activity-item h4 {
                font-size: 12.5px;
                font-weight: 500;
                line-height: 1.4;
            }

            .activity-item p {
                font-size: 11px;
                color: var(--smoke);
                margin-top: 2px;
            }

            /* ── PROGRESS ── */
            .prog-item {
                padding: 10px 22px;
            }

            .prog-header {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-bottom: 7px;
            }

            .prog-label {
                font-size: 12.5px;
                font-weight: 500;
                flex: 1;
            }

            .prog-val {
                font-size: 13px;
                font-weight: 700;
                font-family: 'Cinzel', serif;
            }

            .prog-track {
                height: 5px;
                background: var(--paper2);
                border-radius: 10px;
                overflow: hidden;
            }

            .prog-bar {
                height: 100%;
                border-radius: 10px;
            }

            /* PAGINATION */
            .pagination-premium {
                display: flex;
                align-items: center;
                gap: 5px;
                padding: 14px 22px;
                justify-content: flex-end;
                border-top: 1px solid var(--paper2);
                flex-wrap: wrap;
            }

            .page-info-premium {
                font-size: 12px;
                color: var(--smoke);
                margin-right: auto;
            }

            .page-btn-premium {
                width: 32px;
                height: 32px;
                border-radius: 7px;
                border: 1px solid var(--paper2);
                background: none;
                cursor: pointer;
                font-size: 12px;
                color: #666;
                font-family: 'DM Sans', sans-serif;
                font-weight: 500;
                transition: all .15s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .page-btn-premium:hover {
                background: var(--paper2);
            }

            .page-btn-premium.active {
                background: #0f0d0b;
                color: #fff;
                border-color: #0f0d0b;
                font-weight: 600;
                pointer-events: none;
                cursor: default;
            }

            .page-btn-premium:disabled,
            .page-btn-premium[aria-disabled="true"] {
                opacity: .25;
                cursor: default;
                pointer-events: none;
            }

            .page-btn-premium.ellipsis {
                pointer-events: none;
                border: none;
                opacity: .4;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 1024px) {
                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }

                .two-col {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 640px) {
                .stats-grid {
                    grid-template-columns: 1fr 1fr;
                    gap: 10px;
                }

                .stat-value {
                    font-size: 22px;
                }

                .premium-dashboard {
                    padding: 14px;
                }
            }

            @media (max-width: 400px) {
                .stats-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    @endpush

    <div class="premium-dashboard">
        <!-- ── STAT CARDS ── -->
        <div class="stats-grid">
            <div class="stat-card red">
                <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                <div class="stat-label">Total Atlet</div>
                <div class="stat-value" style="color:var(--red)">{{ number_format($stats['total_athletes']) }}</div>
                <div class="stat-delta"><i class="fa-solid fa-arrow-trend-up"></i> +12% dari bulan lalu</div>
            </div>
            <div class="stat-card gold">
                <div class="stat-icon"><i class="fa-solid fa-flag"></i></div>
                <div class="stat-label">Kontingen</div>
                <div class="stat-value" style="color:#b8860b">{{ number_format($stats['total_contingents']) }}</div>
                <div class="stat-delta"><i class="fa-solid fa-arrow-trend-up"></i> Terdaftar aktif</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
                <div class="stat-label">Pending Verifikasi</div>
                <div class="stat-value" style="color:#2980b9">{{ number_format($stats['pending_count']) }}</div>
                <div class="stat-delta"><i class="fa-solid fa-check"></i> Menunggu review</div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon"><i class="fa-solid fa-medal"></i></div>
                <div class="stat-label">Medali Diberikan</div>
                <div class="stat-value" style="color:#27ae60">
                    {{ $medalStats['gold'] + $medalStats['silver'] + $medalStats['bronze'] }}</div>
                <div class="stat-delta"><i class="fa-solid fa-arrow-trend-up"></i> Update real-time</div>
            </div>
        </div>

        <!-- ── CHARTS ROW ── -->
        <div class="two-col">
            <div class="card-premium">
                <div class="card-head-premium" style="padding-bottom:14px;">
                    <h3>Distribusi Medali per Kontingen</h3>
                    <div class="tab-group-premium">
                        <button class="tab-btn-premium active">Bar Chart</button>
                    </div>
                </div>
                <div class="chart-wrap-premium" style="height:250px;">
                    <canvas id="medalChart"></canvas>
                </div>
            </div>

            <div class="card-premium">
                <div class="card-head-premium">
                    <div>
                        <h3>Status Registrasi</h3>
                        <div class="card-sub-premium">Berdasarkan verifikasi</div>
                    </div>
                </div>
                <div class="chart-wrap-premium" style="height:150px;display:flex;justify-content:center;">
                    <canvas id="statusDonutChart"></canvas>
                </div>
                <div style="padding:0 22px 16px;display:grid;grid-template-columns:1fr 1fr;gap:7px;">
                    <div style="display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--smoke);">
                        <span
                            style="width:9px;height:9px;border-radius:3px;background:#27ae60;flex-shrink:0;"></span>Verified
                        ({{ $stats['verification_rate'] }}%)
                    </div>
                    <div style="display:flex;align-items:center;gap:7px;font-size:11.5px;color:var(--smoke);">
                        <span
                            style="width:9px;height:9px;border-radius:3px;background:#f59e0b;flex-shrink:0;"></span>Pending
                    </div>
                </div>
            </div>
        </div>

        <!-- ── TABLE ── -->
        <div class="table-section-premium">
            <div class="table-head-premium">
                <h3>Registrasi Terbaru</h3>
                <div class="filter-group-premium">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="px-3 py-1.5 border border-slate-200 rounded-md text-sm outline-none focus:border-red"
                        placeholder="Cari kontingen...">
                </div>
                <a href="{{ route('admin.registrations.index') }}" class="export-btn-premium">
                    <i class="fa-solid fa-eye"></i> <span>Lihat Semua</span>
                </a>
            </div>

            <div class="table-scroll-premium">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kontingen</th>
                            <th>Wilayah</th>
                            <th>Tanggal</th>
                            <th>Total Tagihan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($latestRegistrations as $reg)
                            <tr>
                                <td style="color:var(--smoke);font-size:11px;">
                                    {{ $loop->iteration + $latestRegistrations->firstItem() - 1 }}</td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:9px;">
                                        <div class="leader-avatar"
                                            style="width:28px;height:28px;font-size:10px;border-radius:7px;background:linear-gradient(135deg,var(--red),#e67e22);">
                                            {{ substr($reg->contingent->name ?? 'K', 0, 2) }}</div>
                                        <div class="font-semibold">{{ $reg->contingent->name ?? '-' }}</div>
                                    </div>
                                </td>
                                <td>{{ $reg->contingent->kab_kota ?? '-' }}</td>
                                <td>{{ $reg->created_at->format('d M Y') }}</td>
                                <td class="font-bold">Rp {{ number_format($reg->final_amount, 0, ',', '.') }}</td>
                                <td>
                                    @php
                                        $badgeClass = match ($reg->status) {
                                            'verified' => 'green',
                                            'pending' => 'gold',
                                            default => 'red',
                                        };
                                    @endphp
                                    <span
                                        class="badge-premium {{ $badgeClass }}">{{ strtoupper($reg->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.new-registrations.show', $reg) }}"
                                        class="action-btn-premium"><i class="fa-solid fa-eye"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-premium">
                <span class="page-info-premium">
                    Menampilkan {{ $latestRegistrations->firstItem() }}–{{ $latestRegistrations->lastItem() }} dari
                    {{ number_format($latestRegistrations->total()) }} registrasi
                </span>

                {{-- Prev: hidden on first page --}}
                @unless ($latestRegistrations->onFirstPage())
                    <button class="page-btn-premium" wire:click="previousPage" wire:loading.attr="disabled">
                        <i class="fa-solid fa-chevron-left" style="font-size:10px;"></i>
                    </button>
                @endunless

                @php
                    $start = max(1, $latestRegistrations->currentPage() - 1);
                    $end = min($latestRegistrations->lastPage(), $latestRegistrations->currentPage() + 1);
                @endphp

                @if ($start > 1)
                    <button class="page-btn-premium" wire:click="gotoPage(1)" wire:loading.attr="disabled">1</button>
                    @if ($start > 2)
                        <button class="page-btn-premium ellipsis" disabled>…</button>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    <button class="page-btn-premium {{ $i == $latestRegistrations->currentPage() ? 'active' : '' }}"
                        wire:click="gotoPage({{ $i }})" wire:loading.attr="disabled">
                        {{ $i }}
                    </button>
                @endfor

                @if ($end < $latestRegistrations->lastPage())
                    @if ($end < $latestRegistrations->lastPage() - 1)
                        <button class="page-btn-premium ellipsis" disabled>…</button>
                    @endif
                    <button class="page-btn-premium" wire:click="gotoPage({{ $latestRegistrations->lastPage() }})"
                        wire:loading.attr="disabled">
                        {{ $latestRegistrations->lastPage() }}
                    </button>
                @endif

                {{-- Next: hidden on last page --}}
                @if ($latestRegistrations->hasMorePages())
                    <button class="page-btn-premium" wire:click="nextPage" wire:loading.attr="disabled">
                        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
                    </button>
                @endif
            </div>
        </div>

        <!-- ── LEADERBOARD + JADWAL ── -->
        <div class="two-col">
            <div class="card-premium">
                <div class="card-head-premium">
                    <h3>Top Kontingen</h3>
                    <div class="card-sub-premium">Berdasarkan Medali</div>
                </div>
                <div style="padding:12px 22px 14px;display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">
                    <div
                        style="text-align:center;padding:10px 6px;background:rgba(212,168,67,.08);border-radius:10px;">
                        <div style="font-size:18px;">🥇</div>
                        <div style="font-size:10px;color:var(--smoke);margin-top:3px;">Emas</div>
                        <div class="cinzel" style="font-size:20px;font-weight:700;color:#b8860b;">
                            {{ $medalStats['gold'] }}</div>
                    </div>
                    <div
                        style="text-align:center;padding:10px 6px;background:rgba(149,165,166,.08);border-radius:10px;">
                        <div style="font-size:18px;">🥈</div>
                        <div style="font-size:10px;color:var(--smoke);margin-top:3px;">Perak</div>
                        <div class="cinzel" style="font-size:20px;font-weight:700;color:#607d8b;">
                            {{ $medalStats['silver'] }}</div>
                    </div>
                    <div style="text-align:center;padding:10px 6px;background:rgba(160,82,45,.08);border-radius:10px;">
                        <div style="font-size:18px;">🥉</div>
                        <div style="font-size:10px;color:var(--smoke);margin-top:3px;">Perunggu</div>
                        <div class="cinzel" style="font-size:20px;font-weight:700;color:#8b4513;">
                            {{ $medalStats['bronze'] }}</div>
                    </div>
                </div>
                <div class="leader-list">
                    @foreach ($medalDistribution['contingents'] as $con)
                        <div class="leader-item">
                            <div
                                class="rank-premium @if ($loop->iteration == 1) gold @elseif($loop->iteration == 2) silver @elseif($loop->iteration == 3) bronze @endif">
                                @if ($loop->iteration == 1)
                                    🥇
                                @elseif($loop->iteration == 2)
                                    🥈
                                @elseif($loop->iteration == 3)
                                    🥉
                                @else
                                    {{ $loop->iteration }}
                                @endif
                            </div>
                            <div class="leader-avatar" style="background:var(--ink);">{{ substr($con->name, 0, 2) }}
                            </div>
                            <div class="leader-info">
                                <h4>{{ $con->name }}</h4>
                                <p>{{ $con->gold_count }} Emas · {{ $con->silver_count }} Perak ·
                                    {{ $con->bronze_count }} Perunggu</p>
                            </div>
                            <div class="leader-score">{{ $con->gold_count + $con->silver_count + $con->bronze_count }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="card-premium">
                <div class="card-head-premium">
                    <h3>Jadwal Hari Ini</h3>
                    <span
                        style="font-size:11px;color:var(--smoke);background:var(--paper);padding:4px 10px;border-radius:20px;">{{ now()->format('l, d M Y') }}</span>
                </div>
                <div style="margin-top:10px;">
                    @forelse($todaySchedules as $sched)
                        <div class="sched-item">
                            <div class="sched-time">{{ $sched->date->format('H.i') }}</div>
                            <div class="sched-dot" style="background:var(--red);"></div>
                            <div class="sched-info">
                                <h4>{{ $sched->name }}</h4>
                                <p>{{ $sched->type }} — {{ $sched->description }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 italic text-sm">
                            Belum ada jadwal hari ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ── BOTTOM ROW ── -->
        <div class="two-col" style="grid-template-columns: 1fr 1fr;">
            <!-- Progress -->
            <div class="card-premium">
                <div class="card-head-premium">
                    <h3>Progress Kontingen</h3>
                </div>
                <div style="padding:14px 0 6px;">
                    @foreach ($latestContingents as $con)
                        <div class="prog-item">
                            <div class="prog-header">
                                <span class="prog-label">{{ $con->name }}</span>
                                <span class="prog-val">{{ $con->athletes_count ?? 0 }} Atlet</span>
                            </div>
                            <div class="prog-track">
                                <div class="prog-bar"
                                    style="width:{{ min(100, ($con->athletes_count ?? 0) * 2) }}%;background:linear-gradient(90deg,var(--red),var(--red-glow));">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Aktivitas -->
            <div class="card-premium">
                <div class="card-head-premium">
                    <h3>Aktivitas Terbaru</h3>
                </div>
                <div style="padding:10px 0;">
                    @foreach ($latestActivities as $act)
                        <div class="activity-item">
                            <div class="act-icon" style="background:{{ $act['bg'] }};color:{{ $act['color'] }};">
                                <i class="fa-solid {{ $act['icon'] }}"></i>
                            </div>
                            <div>
                                <h4>{{ $act['title'] }}</h4>
                                <p>{{ $act['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
        <script>
            document.addEventListener('livewire:initialized', () => {
                // Medal Chart
                new Chart(document.getElementById('medalChart'), {
                    type: 'bar',
                    data: {
                        labels: @json($medalDistribution['labels']),
                        datasets: [{
                                label: 'Emas',
                                data: @json($medalDistribution['gold']),
                                backgroundColor: 'rgba(212,168,67,.85)',
                                borderRadius: 4
                            },
                            {
                                label: 'Perak',
                                data: @json($medalDistribution['silver']),
                                backgroundColor: 'rgba(149,165,166,.7)',
                                borderRadius: 4
                            },
                            {
                                label: 'Perunggu',
                                data: @json($medalDistribution['bronze']),
                                backgroundColor: 'rgba(160,82,45,.6)',
                                borderRadius: 4
                            },
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: {
                                        family: 'DM Sans',
                                        size: 10
                                    },
                                    boxWidth: 9,
                                    padding: 14
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        family: 'DM Sans',
                                        size: 10
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(0,0,0,.05)'
                                },
                                ticks: {
                                    font: {
                                        family: 'DM Sans',
                                        size: 10
                                    },
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });

                // Donut Chart
                new Chart(document.getElementById('statusDonutChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Verified', 'Pending', 'Rejected'],
                        datasets: [{
                            data: [{{ $statusBreakdown['verified'] }},
                                {{ $statusBreakdown['pending'] }},
                                {{ $statusBreakdown['rejected'] }}
                            ],
                            backgroundColor: ['#27ae60', '#f59e0b', '#e74c3c'],
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        cutout: '68%'
                    }
                });
            });
        </script>
    @endpush
</div>
