<div>
    <!-- Page Title Start -->
    <div class="flex items-center md:justify-between flex-wrap gap-2 mb-4 print:hidden px-6 pt-6">
        <h4 class="text-default-900 text-lg font-semibold">Admin Dashboard</h4>

        <div class="md:flex hidden items-center gap-2 text-sm font-semibold">
            <a href="#" class="text-sm font-medium text-default-700">Smart Perkemi</a>

            <i class="iconify tabler--chevron-right text-sm flex-shrink-0 text-default-500 rtl:rotate-180"></i>

            <a href="#" class="text-sm font-medium text-default-700" aria-current="page">Admin Dashboard</a>
        </div>
    </div>
    <!-- Page Title End -->

    <div class="px-6 pb-6">
        <!-- Widgets Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">

            <!-- Total Atlet -->
            <div class="card bg-blue-500 border-none rounded-xl p-5 shadow-lg relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all">
                </div>
                <div class="absolute right-10 bottom-0 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
                <div class="flex justify-between items-center z-10 relative">
                    <div>
                        <p class="text-sm text-white/80 font-medium mb-1">Total Atlet</p>
                        <h3 class="text-3xl font-bold text-white tracking-tight">
                            {{ number_format($stats['total_athletes']) }}
                        </h3>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/20 text-white rounded-xl flex items-center justify-center text-3xl ring-1 ring-white/30 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 z-10 relative border-t border-white/20 pt-3 text-white">
                    <span class="inline-flex items-center gap-1 text-xs font-bold bg-white/20 px-2 py-1 rounded-md">
                        <i class="fas fa-chart-line"></i> +12%
                    </span>
                    <span class="text-xs text-white/80">vs bulan lalu</span>
                </div>
            </div>

            <!-- Total Kontingen -->
            <div class="card bg-emerald-500 border-none rounded-xl p-5 shadow-lg relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all">
                </div>
                <div class="absolute right-10 bottom-0 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
                <div class="flex justify-between items-center z-10 relative">
                    <div>
                        <p class="text-sm text-white/80 font-medium mb-1">Total Kontingen</p>
                        <h3 class="text-3xl font-bold text-white tracking-tight">
                            {{ number_format($stats['total_contingents']) }}
                        </h3>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/20 text-white rounded-xl flex items-center justify-center text-3xl ring-1 ring-white/30 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 z-10 relative border-t border-white/20 pt-3">
                    <span
                        class="inline-flex items-center gap-1 text-xs font-bold text-white bg-white/20 px-2 py-1 rounded-md">
                        <i class="fas fa-database"></i> Master Data
                    </span>
                </div>
            </div>

            <!-- Pending Verifikasi -->
            <div class="card bg-amber-500 border-none rounded-xl p-5 shadow-lg relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all">
                </div>
                <div class="absolute right-10 bottom-0 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
                <div class="flex justify-between items-center z-10 relative">
                    <div>
                        <p class="text-sm text-white/80 font-medium mb-1">Pending Verifikasi</p>
                        <h3 class="text-3xl font-bold text-white tracking-tight">
                            {{ number_format($stats['pending_count']) }}
                        </h3>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/20 text-white rounded-xl flex items-center justify-center text-3xl ring-1 ring-white/30 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="mt-5 flex items-center gap-2 z-10 relative border-t border-white/20 pt-3">
                    <a href="{{ route('admin.registrations.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-bold text-white hover:text-white/80 transition-colors">
                        Tinjau Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Verification Rate -->
            <div class="card bg-purple-500 border-none rounded-xl p-5 shadow-lg relative overflow-hidden group">
                <div
                    class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all">
                </div>
                <div class="absolute right-10 bottom-0 w-24 h-24 bg-white/5 rounded-full blur-xl"></div>
                <div class="flex justify-between items-center z-10 relative">
                    <div>
                        <p class="text-sm text-white/80 font-medium mb-1">Verifikasi Registrasi</p>
                        <h3 class="text-3xl font-bold text-white tracking-tight">{{ $stats['verification_rate'] }}%</h3>
                    </div>
                    <div
                        class="w-14 h-14 bg-white/20 text-white rounded-xl flex items-center justify-center text-3xl ring-1 ring-white/30 group-hover:scale-110 transition-transform shadow-inner">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
                <div class="mt-5 z-10 relative border-t border-white/20 pt-3">
                    <div class="flex justify-between text-xs text-white/80 mb-1.5 font-medium">
                        <span>Progress</span>
                        <span>{{ number_format($stats['verified_count']) }} /
                            {{ number_format($stats['total_registrations']) }}</span>
                    </div>
                    <div class="w-full bg-black/20 h-1.5 rounded-full overflow-hidden shadow-inner">
                        <div class="bg-white h-full rounded-full shadow-[0_0_10px_rgba(255,255,255,0.5)]"
                            style="width: {{ $stats['verification_rate'] }}%"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

            <!-- Growth Chart -->
            <div
                class="lg:col-span-2 card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-slate-100">Pertumbuhan Atlet</h4>
                        <p class="text-xs text-slate-500 mt-1">Tren pendaftaran atlet baru dalam 6 bulan terakhir.</p>
                    </div>
                    <div class="flex gap-2">
                        <button
                            class="px-3 py-1.5 text-xs font-semibold bg-custom-500/10 text-custom-500 rounded-md transition-all">6
                            Bulan</button>
                        <button
                            class="px-3 py-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 dark:hover:text-slate-100 transition-all">1
                            Tahun</button>
                    </div>
                </div>
                <div class="h-72" id="athleteGrowthChart">
                    <!-- Chart will be rendered here -->
                </div>
            </div>

            <!-- Status Breakdown -->
            <div
                class="card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-base font-bold text-slate-800 dark:text-slate-100">Status Registrasi</h4>
                    <i data-lucide="pie-chart" class="size-4 text-slate-400"></i>
                </div>
                <div class="h-64 flex flex-col items-center justify-center relative" id="registrationStatusChart">
                    <!-- Donut Chart will be rendered here -->
                </div>
                <div class="mt-2 space-y-2">
                    <div
                        class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span
                                class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            <span class="text-slate-600 dark:text-slate-300 font-medium">Terverifikasi</span>
                        </div>
                        <span
                            class="font-bold text-slate-800 dark:text-slate-100">{{ $statusBreakdown['verified'] }}</span>
                    </div>
                    <div
                        class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span
                                class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
                            <span class="text-slate-600 dark:text-slate-300 font-medium">Pending</span>
                        </div>
                        <span
                            class="font-bold text-slate-800 dark:text-slate-100">{{ $statusBreakdown['pending'] }}</span>
                    </div>
                    <div
                        class="flex items-center justify-between text-sm p-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span
                                class="w-2.5 h-2.5 rounded-full bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.5)]"></span>
                            <span class="text-slate-600 dark:text-slate-300 font-medium">Ditolak</span>
                        </div>
                        <span
                            class="font-bold text-slate-800 dark:text-slate-100">{{ $statusBreakdown['rejected'] }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Tables Grid -->
        <div class="grid grid-cols-1 gap-5 mb-5">
            <div
                class="card bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-md overflow-hidden shadow-sm">
                <div class="p-5 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <h6 class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Data
                        Registrasi</h6>

                    <div class="flex gap-3 items-center">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-input text-sm ps-9 py-1.5 border border-slate-200 dark:border-slate-700 rounded-md dark:bg-slate-800 dark:text-slate-200"
                                placeholder="Search for....">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3">
                                <i data-lucide="search" class="size-3.5 text-slate-400"></i>
                            </div>
                        </div>

                        <a href="{{ route('admin.registrations.index') }}"
                            class="btn px-3 py-1.5 text-sm bg-custom-500 text-white rounded-md hover:bg-custom-600 transition-colors flex items-center">
                            <i data-lucide="eye" class="size-3.5 me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="overflow-x-auto">
                        <div class="min-w-full inline-block align-middle">
                            <div class="overflow-hidden">
                                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                                        <tr
                                            class="text-sm font-semibold text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                            <th scope="col" class="px-5 py-3 text-start">#</th>
                                            <th scope="col" class="px-5 py-3 text-start">Kontingen</th>
                                            <th scope="col" class="px-5 py-3 text-start">Tipe</th>
                                            <th scope="col" class="px-5 py-3 text-start">Tanggal</th>
                                            <th scope="col" class="px-5 py-3 text-start">Total Tagihan</th>
                                            <th scope="col" class="px-5 py-3 text-start">Status</th>
                                            <th scope="col" class="px-5 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                        @forelse($latestRegistrations as $reg)
                                            <tr
                                                class="text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                                <td class="px-5 py-3 whitespace-nowrap text-sm">
                                                    {{ $loop->iteration + $latestRegistrations->firstItem() - 1 }}
                                                </td>
                                                <td class="px-5 py-3 whitespace-nowrap text-sm">
                                                    {{ $reg->contingent->name ?? '—' }}
                                                </td>
                                                <td class="px-5 py-3 whitespace-nowrap text-sm">
                                                    <span
                                                        class="px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 dark:bg-slate-800 text-slate-500 uppercase">{{ $reg->contingent->kab_kota ?? '-' }}</span>
                                                </td>
                                                <td class="px-5 py-3 whitespace-nowrap text-sm text-slate-500">
                                                    {{ $reg->created_at->format('d M, Y') }}
                                                </td>
                                                <td class="px-5 py-3 whitespace-nowrap text-sm font-medium">
                                                    Rp {{ number_format($reg->final_amount, 0, ',', '.') }}
                                                </td>
                                                <td class="px-5 py-3 whitespace-nowrap">
                                                    @php
                                                        $sc = match ($reg->status) {
                                                            'verified' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/30',
                                                            'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/30',
                                                            default => 'bg-rose-500/10 text-rose-500 border-rose-500/30',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="inline-flex items-center gap-x-1.5 py-0.5 px-2.5 rounded text-xs font-bold border {{ $sc }} uppercase">
                                                        {{ $reg->status }}
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                                    <a href="{{ route('admin.registrations.show', $reg) }}"
                                                        class="btn btn-sm text-blue-500 hover:text-blue-600 p-1">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8"
                                                    class="px-5 py-8 text-center text-slate-500 dark:text-slate-400">
                                                    Tidak ada data registrasi ditemukan.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5 border-t border-slate-200 dark:border-slate-800">
                    {{ $latestRegistrations->links('vendor.livewire.tailwick') }}
                </div>
            </div>
        </div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                document.addEventListener('livewire:initialized', () => {

                    // Athlete Growth Chart
                    const athleteOptions = {
                        series: [{
                            name: 'Atlet Baru',
                            data: @json($monthlyAthletes['data'])
                        }],
                        chart: {
                            type: 'area',
                            height: 280,
                            toolbar: { show: false },
                            sparkline: { enabled: false },
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 3 },
                        colors: ['#0ea5e9'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.45,
                                opacityTo: 0.05,
                                stops: [20, 100, 100, 100]
                            }
                        },
                        xaxis: {
                            categories: @json($monthlyAthletes['labels']),
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                        },
                        grid: { borderColor: 'rgba(0,0,0,0.05)', strokeDashArray: 4 },
                        tooltip: { theme: 'dark' }
                    };

                    const athleteChart = new ApexCharts(document.querySelector("#athleteGrowthChart"), athleteOptions);
                    athleteChart.render();

                    // Registration Status Chart
                    const statusOptions = {
                        series: [{{ $statusBreakdown['verified'] }}, {{ $statusBreakdown['pending'] }}, {{ $statusBreakdown['rejected'] }}],
                        chart: {
                            type: 'donut',
                            height: 240,
                        },
                        labels: ['Verified', 'Pending', 'Ditolak'],
                        colors: ['#10b981', '#f59e0b', '#ef4444'],
                        legend: { show: false },
                        dataLabels: { enabled: false },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: true,
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            formatter: () => '{{ $stats['total_registrations'] }}'
                                        }
                                    }
                                }
                            }
                        },
                        tooltip: { theme: 'dark' }
                    };

                    const statusChart = new ApexCharts(document.querySelector("#registrationStatusChart"), statusOptions);
                    statusChart.render();
                });
            </script>
        @endpush
    </div>
</div>