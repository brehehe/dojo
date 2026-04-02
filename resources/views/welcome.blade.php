<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Smart-Perkemi | Sistem Informasi Shorinji Kempo</title>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <!-- jsPDF & html2canvas for certificate download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-['Inter'] antialiased bg-slate-50 overflow-x-hidden selection:bg-orange-200">

<!-- LANDING PAGE -->
<main class="landing-page min-h-screen bg-gradient-to-br from-amber-50 to-orange-100/50" id="landingPage">
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-to-br from-[#0f2b3d] to-[#1a3a4f] text-white py-16 md:py-24 px-6 text-center relative overflow-hidden">
        <!-- Decoration bits -->
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-4xl mx-auto relative z-10">
            <div class="flex justify-center mb-8">
                <div class="bg-orange-500 w-20 h-20 rounded-3xl flex items-center justify-center shadow-2xl shadow-orange-500/20 rotate-3 transition-transform hover:rotate-0">
                    <i class="fas fa-fist-raised text-4xl text-white"></i>
                </div>
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold mb-4 tracking-tighter bg-gradient-to-r from-amber-200 via-orange-300 to-amber-200 bg-clip-text text-transparent uppercase">
                SMART-PERKEMI
            </h1>
            <p class="text-slate-300 text-lg md:text-xl font-medium mb-10 max-w-2xl mx-auto leading-relaxed">
                少林寺拳法 · Sistem Informasi Terpadu <br class="hidden md:block"> Prestasi, Kegiatan & Manajemen Kenshi Nusantara
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl shadow-orange-600/20 transition transform hover:-translate-y-1 flex items-center justify-center gap-3 no-underline">
                        <i class="fas fa-tachometer-alt"></i> Panel Admin
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl shadow-orange-600/20 transition transform hover:-translate-y-1 flex items-center justify-center gap-3 no-underline">
                        <i class="fas fa-user-shield"></i> Login Admin
                    </a>
                @endauth
                
                <button class="w-full sm:w-auto bg-transparent border-2 border-orange-500 text-orange-400 hover:bg-orange-500/10 px-8 py-4 rounded-full font-bold text-lg transition transform hover:-translate-y-1 flex items-center justify-center gap-3" onclick="alert('Login Kenshi segera hadir!')">
                    <i class="fas fa-user-graduate"></i> Login Kenshi
                </button>
                <a href="/piala_walikotasby2026" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl shadow-emerald-600/20 transition transform hover:-translate-y-1 flex items-center justify-center gap-3 no-underline">
                    <i class="fas fa-edit"></i> Registrasi Online
                </a>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="max-w-7xl mx-auto px-6 py-16 md:py-24">
        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold border-l-8 border-orange-600 pl-6 mb-12 text-slate-800 tracking-tight">
                Statistik Kegiatan: Kejuaraan, Gashuku & UKT
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition duration-300">
                    <h3 class="text-xl font-bold mb-6 text-slate-700 flex items-center gap-2">
                        <i class="fas fa-trophy text-orange-500"></i> Kejuaraan Wilayah
                    </h3>
                    <canvas id="landingKejuaraanChart" class="w-full"></canvas>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition duration-300">
                    <h3 class="text-xl font-bold mb-6 text-slate-700 flex items-center gap-2">
                        <i class="fas fa-campground text-blue-500"></i> Gashuku
                    </h3>
                    <canvas id="landingGashukuChart" class="w-full"></canvas>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 hover:shadow-2xl transition duration-300">
                    <h3 class="text-xl font-bold mb-6 text-slate-700 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-emerald-500"></i> Ujian Kenaikan (UKT)
                    </h3>
                    <canvas id="landingUKTChart" class="w-full"></canvas>
                </div>
            </div>
        </div>
        
        <div class="text-center py-10">
            <a href="/piala_walikotasby2026" class="inline-flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-5 rounded-2xl font-bold text-xl shadow-2xl shadow-emerald-600/30 transition transform hover:scale-105 no-underline">
                <i class="fas fa-file-signature"></i> Buka Form Pendaftaran Online
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#0f2b3d] text-slate-400 text-center py-12 px-6 border-t border-slate-800">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6 opacity-60">
                <i class="fas fa-fist-raised text-2xl text-orange-500 mb-2"></i>
                <p class="font-bold text-white tracking-widest uppercase">SMART-PERKEMI</p>
            </div>
            <p class="text-sm">© 2026 SMART-PERKEMI · Shorinji Kempo Indonesia</p>
            <p class="mt-2 text-xs opacity-50 font-medium uppercase tracking-[0.2em]">Martial Arts Management System</p>
        </div>
    </footer>
</main>

<script>
    // ==================== DATA ====================
    // Landing Charts
    function initLandingCharts() {
        const config = (label, data, color) => ({
            type: 'bar',
            data: {
                labels: ['Kejurdo', 'Kejurkot', 'Kejurkab', 'Kejurprov', 'Porprov'].slice(0, data.length),
                datasets: [{ label, data, backgroundColor: color, borderRadius: 8 }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, border: { display: false } }, x: { border: { display: false } } }
            }
        });
        new Chart(document.getElementById('landingKejuaraanChart'), config('Peserta', [180, 320, 480, 620, 450], '#e67e22'));
        new Chart(document.getElementById('landingGashukuChart'), config('Peserta', [210, 350, 420, 280], '#3b82f6'));
        new Chart(document.getElementById('landingUKTChart'), config('Peserta', [340, 520, 680, 420], '#10b981'));
    }

    initLandingCharts();
</script>

</body>
</html>
