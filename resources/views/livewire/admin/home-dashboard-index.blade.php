<div class="space-y-6 sm:space-y-8 pb-10">
    
    {{-- WELCOME SECTION (Fluid & Soft) --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 text-xs font-semibold mb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Sistem Terhubung & Aktif
            </div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">
                Selamat Datang, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                Berikut adalah ringkasan aktivitas dan data Smart Perkemi saat ini.
            </p>
        </div>
        <div class="flex items-center gap-3 bg-white dark:bg-slate-900 px-5 py-3 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-700 dark:text-slate-200" id="fluid-time">--:--:--</p>
                <p class="text-xs text-slate-500 dark:text-slate-400" id="fluid-date">Memuat tanggal...</p>
            </div>
        </div>
    </div>

    {{-- STATS CARDS (Elegant & Clean) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Atlet -->
        <div class="card bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Atlet</p>
                    <h3 class="text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['total_athletes']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500 text-xl">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-slate-500 dark:text-slate-400">
                <span class="text-blue-500 font-semibold mr-1"><i class="fas fa-check-circle"></i> Terdaftar</span> di sistem
            </div>
        </div>

        <!-- Kontingen -->
        <div class="card bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Kontingen</p>
                    <h3 class="text-3xl font-bold text-slate-800 dark:text-white">{{ number_format($stats['total_contingents']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-xl">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-slate-500 dark:text-slate-400">
                <span class="text-emerald-500 font-semibold mr-1"><i class="fas fa-map-marker-alt"></i> Kab/Kota</span> aktif
            </div>
        </div>

        <!-- Pending -->
        <div class="card bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Menunggu Verifikasi</p>
                    <h3 class="text-3xl font-bold text-amber-500">{{ number_format($stats['pending_count']) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center text-amber-500 text-xl">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs text-slate-500 dark:text-slate-400">
                <a href="{{ route('admin.registrations.index') }}" class="text-amber-500 font-semibold hover:underline flex items-center gap-1">
                    Tinjau Sekarang <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Verified Rate -->
        <div class="card bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Progres Verifikasi</p>
                    <h3 class="text-3xl font-bold text-purple-500">{{ $stats['verification_rate'] }}%</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-500 text-xl">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <div class="mt-4">
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5">
                    <div class="bg-purple-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $stats['verification_rate'] }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK ACTIONS (Soft Style) --}}
    <div>
        <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-amber-500"></i> Akses Cepat
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
            $actions = [
                ['route' => 'admin.registrations.index', 'label' => 'Verifikasi', 'sub' => 'Pendaftaran', 'icon' => 'fa-file-signature', 'color' => 'blue'],
                ['route' => 'admin.master.contingents.index', 'label' => 'Kontingen', 'sub' => 'Master Data', 'icon' => 'fa-users', 'color' => 'emerald'],
                ['route' => 'admin.technical-meeting.embu', 'label' => 'T. Meeting', 'sub' => 'Pengundian', 'icon' => 'fa-random', 'color' => 'amber'],
                ['route' => 'admin.arbitrase.scoring.index', 'label' => 'Arbitrase', 'sub' => 'Scoring Live', 'icon' => 'fa-gavel', 'color' => 'rose'],
            ];
            @endphp
            @foreach($actions as $action)
            <a href="{{ route($action['route']) }}" class="group block bg-white dark:bg-slate-900 rounded-2xl p-4 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-{{ $action['color'] }}-200 dark:hover:border-{{ $action['color'] }}-500/30 transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-{{ $action['color'] }}-50 dark:bg-{{ $action['color'] }}-500/10 flex items-center justify-center text-{{ $action['color'] }}-500 group-hover:scale-110 transition-transform">
                        <i class="fas {{ $action['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-{{ $action['color'] }}-500 transition-colors">{{ $action['label'] }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $action['sub'] }}</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- CHARTS & TABLES --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Left Column: Line Chart & Registrations --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Line Chart --}}
            <div class="card bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">Pertumbuhan Atlet</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Statistik 6 bulan terakhir</p>
                    </div>
                    <div class="p-2 bg-slate-50 dark:bg-slate-800 rounded-lg text-slate-400">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="relative h-64" wire:ignore>
                    <canvas id="fluidAthleteChart"></canvas>
                </div>
            </div>

            {{-- Latest Registrations --}}
            <div class="card bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">Registrasi Terbaru</h2>
                    <a href="{{ route('admin.registrations.index') }}" class="text-sm text-blue-500 hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm whitespace-nowrap">
                        <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50 dark:bg-slate-800/50">
                            <tr>
                                <th class="px-4 py-3 rounded-l-lg font-semibold">Kontingen</th>
                                <th class="px-4 py-3 font-semibold">Waktu</th>
                                <th class="px-4 py-3 rounded-r-lg font-semibold text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($latestRegistrations as $reg)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 font-medium text-slate-800 dark:text-slate-200">
                                    {{ $reg->contingent->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-slate-500 dark:text-slate-400">
                                    {{ $reg->created_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if($reg->status === 'verified')
                                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 rounded-md text-xs font-semibold">Verified</span>
                                    @elseif($reg->status === 'pending')
                                        <span class="px-2 py-1 bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 rounded-md text-xs font-semibold">Pending</span>
                                    @else
                                        <span class="px-2 py-1 bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400 rounded-md text-xs font-semibold">{{ ucfirst($reg->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center text-slate-500">Belum ada data registrasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- Pagination --}}
                {{ $latestRegistrations->links('vendor.pagination.luwes') }}
            </div>
        </div>

        {{-- Right Column: Donut Chart & Contingents --}}
        <div class="space-y-6">
            {{-- Donut Chart --}}
            <div class="card bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">Status Registrasi</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Persentase keseluruhan</p>
                    </div>
                </div>
                <div class="relative h-48 flex items-center justify-center mb-6" wire:ignore>
                    <canvas id="fluidStatusChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl text-center">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Verified</p>
                        <p class="text-lg font-bold text-emerald-500">{{ $statusBreakdown['verified'] }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 p-3 rounded-xl text-center">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Pending</p>
                        <p class="text-lg font-bold text-amber-500">{{ $statusBreakdown['pending'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Latest Contingents --}}
            <div class="card bg-white dark:bg-slate-900 rounded-2xl p-6 shadow-sm border border-slate-100 dark:border-slate-800">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-bold text-slate-800 dark:text-slate-200">Kontingen Terbaru</h2>
                    <a href="{{ route('admin.master.contingents.index') }}" class="text-sm text-blue-500 hover:underline">Semua</a>
                </div>
                <div class="space-y-3">
                    @forelse($latestContingents as $contingent)
                    <div class="flex items-center gap-3 p-2 hover:bg-slate-50 dark:hover:bg-slate-800/50 rounded-xl transition-colors">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-500 font-bold text-sm shrink-0">
                            {{ strtoupper(substr($contingent->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $contingent->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $contingent->kab_kota }}</p>
                        </div>
                        <a href="{{ route('admin.master.contingents.detail', $contingent) }}" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-blue-500 transition-colors">
                            <i class="fas fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center text-sm text-slate-500 py-4">Belum ada kontingen.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('livewire:initialized', () => {
    // Clock
    function updateFluidClock() {
        const now = new Date();
        const timeEl = document.getElementById('fluid-time');
        const dateEl = document.getElementById('fluid-date');
        if (timeEl) timeEl.textContent = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
        if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' });
    }
    updateFluidClock();
    setInterval(updateFluidClock, 1000);

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const textColor = isDark ? '#94a3b8' : '#64748b'; // slate-400 : slate-500
    const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

    // Data
    const monthlyLabels = @json($monthlyAthletes['labels']);
    const monthlyData   = @json($monthlyAthletes['data']);
    
    // Line Chart
    const ctxAthlete = document.getElementById('fluidAthleteChart');
    if (ctxAthlete) {
        new Chart(ctxAthlete, {
            type: 'line',
            data: {
                labels: monthlyLabels.length ? monthlyLabels : ['Jan','Feb','Mar','Apr','Mei','Jun'],
                datasets: [{
                    label: 'Atlet Terdaftar',
                    data: monthlyData.length ? monthlyData : [0,0,0,0,0,0],
                    borderColor: '#3b82f6', // blue-500
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4, // Fluid curve
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#ffffff',
                        titleColor: isDark ? '#f8fafc' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 4,
                        usePointStyle: true,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: textColor, font: { family: 'inherit' } }
                    },
                    y: {
                        grid: { color: gridColor, drawBorder: false },
                        ticks: { color: textColor, font: { family: 'inherit' }, precision: 0 },
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Donut Chart
    const ctxStatus = document.getElementById('fluidStatusChart');
    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Verified', 'Pending', 'Ditolak'],
                datasets: [{
                    data: [{{ $statusBreakdown['verified'] }}, {{ $statusBreakdown['pending'] }}, {{ $statusBreakdown['rejected'] ?? 0 }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#f43f5e'], // emerald, amber, rose
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%', // Thinner ring for elegance
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? '#1e293b' : '#ffffff',
                        titleColor: isDark ? '#f8fafc' : '#0f172a',
                        bodyColor: isDark ? '#cbd5e1' : '#475569',
                        borderColor: isDark ? '#334155' : '#e2e8f0',
                        borderWidth: 1,
                        padding: 12
                    }
                }
            }
        });
    }
});
</script>
