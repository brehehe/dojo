<div class="min-h-screen pb-10 space-y-6">

    {{-- HERO HEADER --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-800 via-indigo-950 to-slate-900 p-7 shadow-2xl border border-white/10">
        <div class="absolute inset-0 opacity-30" style="background-image: radial-gradient(circle at 15% 50%, #6366f1 0%, transparent 45%), radial-gradient(circle at 85% 20%, #8b5cf6 0%, transparent 35%), radial-gradient(circle at 60% 85%, #06b6d4 0%, transparent 35%);"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center gap-2 bg-emerald-500/20 border border-emerald-500/30 rounded-full px-3 py-1">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-emerald-300 text-[10px] font-black uppercase tracking-widest">Sistem Aktif</span>
                    </div>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-1.5">Selamat Datang 👋</h1>
                <p class="text-indigo-200 text-sm font-medium">{{ auth()->user()->name }} — Smart Perkemi Admin Dashboard</p>
            </div>
            <div class="flex-shrink-0 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-4 text-right">
                <div id="hd-time" class="text-2xl font-black text-white tabular-nums">--:--:--</div>
                <div id="hd-date" class="text-indigo-200 text-xs font-semibold mt-0.5">Loading...</div>
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
        $statsCards = [
            ['label' => 'Total Atlet',   'value' => $stats['total_athletes'],   'color' => 'blue',   'icon' => 'fa-id-card',        'badge' => 'Terdaftar',  'badge_icon' => 'fa-check-circle'],
            ['label' => 'Kontingen',     'value' => $stats['total_contingents'], 'color' => 'emerald','icon' => 'fa-shield-alt',     'badge' => 'Kab/Kota',   'badge_icon' => 'fa-map-marker-alt'],
            ['label' => 'Menunggu',      'value' => $stats['pending_count'],    'color' => 'amber',  'icon' => 'fa-hourglass-half', 'badge' => 'Perlu Aksi', 'badge_icon' => 'fa-clock'],
            ['label' => 'Verified',      'value' => $stats['verified_count'],   'color' => 'violet', 'icon' => 'fa-clipboard-check','badge' => null,         'badge_icon' => null],
        ];
        @endphp

        @foreach($statsCards as $card)
        <div class="group hd-card border rounded-3xl p-5 overflow-hidden hover:-translate-y-1 transition-all duration-300 cursor-default hd-shadow">
            <div class="relative z-10">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4 transition-colors"
                     style="background-color: color-mix(in srgb, var(--{{ $card['color'] === 'blue' ? 'hd-text-2' : 'hd-text-2' }}) 5%, transparent)"
                     class="bg-{{ $card['color'] }}-500/15 border border-{{ $card['color'] }}-500/20 group-hover:bg-{{ $card['color'] }}-500/25">
                    <i class="fas {{ $card['icon'] }} text-{{ $card['color'] }}-400 text-sm"></i>
                </div>
                <div class="text-3xl font-black hd-text-1 mb-1 hd-counter {{ $card['color'] === 'amber' ? '!text-amber-500' : ($card['color'] === 'violet' ? '!text-violet-400' : '') }}"
                     data-target="{{ $card['value'] }}">0</div>
                <p class="text-[10px] font-black uppercase tracking-widest hd-text-3">{{ $card['label'] }}</p>

                @if($card['badge'])
                <div class="mt-3 inline-flex items-center gap-1.5 text-[10px] font-bold rounded-lg px-2 py-1 border
                            text-{{ $card['color'] }}-500 border-{{ $card['color'] }}-500/25"
                     :style="darkMode ? 'background:rgba(var(--tw-color),0.10)' : 'background:#f0fdf4'">
                    <i class="fas {{ $card['badge_icon'] }} text-[9px]"></i> {{ $card['badge'] }}
                </div>
                @else
                <div class="mt-3">
                    <div class="flex justify-between mb-1">
                        <span class="text-[10px] hd-text-3 font-bold">Verifikasi</span>
                        <span class="text-[10px] text-violet-400 font-black">{{ $stats['verification_rate'] }}%</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden" :class="darkMode ? 'bg-white/5' : 'bg-slate-200'">
                        <div class="h-full bg-gradient-to-r from-violet-500 to-purple-600 rounded-full" style="width: {{ $stats['verification_rate'] }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- CHARTS ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Line Chart --}}
        <div class="lg:col-span-2 hd-card border rounded-3xl p-6 hd-shadow">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-black hd-text-1 uppercase tracking-widest">Pertumbuhan Atlet</h2>
                    <p class="text-[11px] hd-text-3 font-medium mt-0.5">6 Bulan Terakhir</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                    <i class="fas fa-chart-area text-indigo-400 text-xs"></i>
                </div>
            </div>
            <div class="relative h-52" wire:ignore>
                <canvas id="hd-athlete-chart"></canvas>
            </div>
        </div>

        {{-- Donut Chart --}}
        <div class="hd-card border rounded-3xl p-6 hd-shadow">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-sm font-black hd-text-1 uppercase tracking-widest">Status</h2>
                    <p class="text-[11px] hd-text-3 font-medium mt-0.5">Pendaftaran</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                    <i class="fas fa-chart-pie text-emerald-400 text-xs"></i>
                </div>
            </div>
            <div class="relative h-36 flex items-center justify-center mb-4" wire:ignore>
                <canvas id="hd-status-chart"></canvas>
            </div>
            <div class="space-y-2.5">
                @foreach([['Verified', 'bg-emerald-500', $statusBreakdown['verified']], ['Pending', 'bg-amber-400', $statusBreakdown['pending']], ['Ditolak', 'bg-rose-500', $statusBreakdown['rejected']]] as [$label, $dotClass, $count])
                @if($count > 0 || $label !== 'Ditolak')
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full {{ $dotClass }} flex-shrink-0"></span>
                        <span class="text-xs font-semibold hd-text-2">{{ $label }}</span>
                    </div>
                    <span class="text-xs font-black hd-text-1">{{ $count }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS --}}
    <div>
        <h2 class="text-[10px] font-black uppercase tracking-widest hd-text-3 mb-3 px-1 flex items-center gap-2">
            <i class="fas fa-bolt text-amber-500"></i> Aksi Cepat
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                ['route' => 'admin.registrations.index', 'label' => 'Verifikasi', 'sub' => 'Pendaftaran', 'icon' => 'fa-file-signature', 'grad' => 'from-blue-600 to-indigo-700', 'shadow' => 'shadow-blue-500/20 hover:shadow-blue-500/35', 'sub_color' => 'text-blue-200', 'border' => 'border-blue-500/30'],
                ['route' => 'admin.master.contingents.index', 'label' => 'Kontingen', 'sub' => 'Master Data', 'icon' => 'fa-users', 'grad' => 'from-emerald-600 to-teal-700', 'shadow' => 'shadow-emerald-500/20 hover:shadow-emerald-500/35', 'sub_color' => 'text-emerald-200', 'border' => 'border-emerald-500/30'],
                ['route' => 'admin.technical-meeting.embu', 'label' => 'T. Meeting', 'sub' => 'Pengundian', 'icon' => 'fa-random', 'grad' => 'from-amber-500 to-orange-600', 'shadow' => 'shadow-amber-500/20 hover:shadow-amber-500/35', 'sub_color' => 'text-amber-100', 'border' => 'border-amber-500/30'],
                ['route' => 'admin.arbitrase.scoring.index', 'label' => 'Arbitrase', 'sub' => 'Scoring Live', 'icon' => 'fa-gavel', 'grad' => 'from-rose-600 to-pink-700', 'shadow' => 'shadow-rose-500/20 hover:shadow-rose-500/35', 'sub_color' => 'text-rose-200', 'border' => 'border-rose-500/30'],
            ] as $action)
            <a href="{{ route($action['route']) }}"
               class="group flex flex-col items-center gap-3 bg-gradient-to-br {{ $action['grad'] }} text-white p-5 rounded-2xl shadow-lg {{ $action['shadow'] }} hover:-translate-y-1 transition-all duration-300 border {{ $action['border'] }}">
                <div class="w-11 h-11 bg-white/15 rounded-xl flex items-center justify-center group-hover:scale-110 group-hover:bg-white/25 transition-all duration-300">
                    <i class="fas {{ $action['icon'] }} text-lg"></i>
                </div>
                <div class="text-center">
                    <div class="font-black text-sm uppercase tracking-wide">{{ $action['label'] }}</div>
                    <div class="{{ $action['sub_color'] }} text-[10px] font-semibold mt-0.5">{{ $action['sub'] }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- TABLES ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Latest Contingents --}}
        <div class="hd-card border rounded-3xl p-5 hd-shadow">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-black hd-text-1 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-list-alt text-indigo-400"></i> Kontingen Terbaru
                </h2>
                <a href="{{ route('admin.master.contingents.index') }}"
                   class="text-[10px] font-bold text-indigo-400 hover:text-indigo-300 flex items-center gap-1 transition-colors">
                    Lihat Semua <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="space-y-1.5">
                @foreach($latestContingents as $contingent)
                <div class="flex items-center gap-3 p-2.5 rounded-xl hd-hover transition-colors group">
                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/15 flex items-center justify-center flex-shrink-0">
                        <span class="text-[10px] font-black text-indigo-400">{{ strtoupper(substr($contingent->name, 0, 2)) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black hd-text-1 truncate uppercase">{{ $contingent->name }}</p>
                        <p class="text-[10px] hd-text-3 font-medium truncate">{{ $contingent->kab_kota }}</p>
                    </div>
                    <a href="{{ route('admin.master.contingents.detail', $contingent) }}"
                       class="opacity-0 group-hover:opacity-100 w-7 h-7 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 hover:bg-indigo-500/20 transition-all">
                        <i class="fas fa-eye text-[10px]"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Latest Registrations --}}
        <div class="hd-card border rounded-3xl p-5 hd-shadow">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xs font-black hd-text-1 uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-file-alt text-rose-400"></i> Registrasi Terbaru
                </h2>
                <a href="{{ route('admin.registrations.index') }}"
                   class="text-[10px] font-bold text-rose-400 hover:text-rose-300 flex items-center gap-1 transition-colors">
                    Lihat Semua <i class="fas fa-arrow-right text-[9px]"></i>
                </a>
            </div>
            <div class="space-y-1.5">
                @foreach($latestRegistrations as $reg)
                @php
                    $sc = match($reg->status) {
                        'verified' => ['emerald','fa-check'],
                        'pending'  => ['amber','fa-clock'],
                        default    => ['rose','fa-times'],
                    };
                @endphp
                <div class="flex items-center gap-3 p-2.5 rounded-xl hd-hover transition-colors">
                    <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center
                                bg-{{ $sc[0] }}-500/10 border border-{{ $sc[0] }}-500/20 text-{{ $sc[0] }}-400">
                        <i class="fas {{ $sc[1] }} text-[10px]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black hd-text-1 truncate uppercase">{{ $reg->contingent->name ?? '—' }}</p>
                        <p class="text-[10px] hd-text-3 font-medium">{{ $reg->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-[10px] font-black px-2 py-1 rounded-lg border
                                 bg-{{ $sc[0] }}-500/10 text-{{ $sc[0] }}-400 border-{{ $sc[0] }}-500/20">
                        {{ ucfirst($reg->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
    // ── Clock ──────────────────────────────────────────────────────
    function updateClock() {
        var now = new Date();
        var t = document.getElementById('hd-time');
        var d = document.getElementById('hd-date');
        if (t) t.textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        if (d) d.textContent = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Animated Counters ─────────────────────────────────────────
    document.querySelectorAll('.hd-counter').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-target'), 10) || 0;
        var current = 0;
        var step = Math.max(1, Math.ceil(target / 60));
        var timer = setInterval(function() {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString('id-ID');
            if (current >= target) clearInterval(timer);
        }, 20);
    });

    // ── Theme Helper ─────────────────────────────────────────────
    function isDarkMode() {
        return localStorage.getItem('hd-theme') !== 'light';
    }
    function getChartColors(dark) {
        return {
            grid:    dark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.05)',
            tick:    dark ? '#475569' : '#94a3b8',
            tooltip: dark ? { bg:'#1a1d2e', border:'rgba(99,102,241,0.3)', title:'#a5b4fc', body:'#e2e8f0' }
                          : { bg:'#ffffff', border:'#e2e8f0',              title:'#4338ca', body:'#475569' },
            line:    { border:'rgba(99,102,241,1)', fill: dark ? 'rgba(99,102,241,0.15)' : 'rgba(99,102,241,0.08)' },
            donut:   dark ? ['rgba(16,185,129,0.85)','rgba(245,158,11,0.85)','rgba(244,63,94,0.85)']
                          : ['#10b981','#f59e0b','#f43f5e'],
        };
    }

    // ── Chart Instances ──────────────────────────────────────────
    var athleteChart = null;
    var statusChart  = null;

    var monthlyLabels = @json($monthlyAthletes['labels']);
    var monthlyData   = @json($monthlyAthletes['data']);
    var verified = {{ $statusBreakdown['verified'] }};
    var pending  = {{ $statusBreakdown['pending'] }};
    var rejected = {{ $statusBreakdown['rejected'] }};

    function buildAthleteChart(dark) {
        var colors = getChartColors(dark);
        var ctx = document.getElementById('hd-athlete-chart');
        if (!ctx) return;
        if (athleteChart) { athleteChart.destroy(); }
        athleteChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyLabels.length ? monthlyLabels : ['Jan','Feb','Mar','Apr','Mei','Jun'],
                datasets: [{
                    label: 'Atlet Baru',
                    data: monthlyData.length ? monthlyData : [0,0,0,0,0,0],
                    fill: true,
                    backgroundColor: colors.line.fill,
                    borderColor: colors.line.border,
                    borderWidth: 2.5,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: dark ? '#1a1d2e' : '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.tooltip.bg,
                        borderColor: colors.tooltip.border,
                        borderWidth: 1,
                        titleColor: colors.tooltip.title,
                        bodyColor: colors.tooltip.body,
                        padding: 10, cornerRadius: 10,
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: colors.grid, drawBorder: false }, ticks: { font: { size: 11, weight: 'bold' }, color: colors.tick } },
                    x: { grid: { display: false }, ticks: { font: { size: 11, weight: 'bold' }, color: colors.tick } }
                }
            }
        });
    }

    function buildStatusChart(dark) {
        var colors = getChartColors(dark);
        var ctx = document.getElementById('hd-status-chart');
        if (!ctx) return;
        if (statusChart) { statusChart.destroy(); }
        statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Verified', 'Pending', 'Ditolak'],
                datasets: [{
                    data: [verified, pending, rejected || 0],
                    backgroundColor: colors.donut,
                    borderColor: dark ? '#1a1d2e' : '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.tooltip.bg,
                        borderColor: colors.tooltip.border,
                        borderWidth: 1,
                        titleColor: colors.tooltip.title,
                        bodyColor: colors.tooltip.body,
                        padding: 10, cornerRadius: 10,
                    }
                }
            }
        });
    }

    // Init charts
    setTimeout(function() {
        var dark = isDarkMode();
        buildAthleteChart(dark);
        buildStatusChart(dark);
    }, 250);

    // ── Listen for theme toggle ───────────────────────────────────
    document.addEventListener('hd-theme-changed', function(e) {
        var dark = e.detail.dark;
        buildAthleteChart(dark);
        buildStatusChart(dark);
    });
})();
</script>
