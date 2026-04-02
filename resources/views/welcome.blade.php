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
                <button class="w-full sm:w-auto bg-orange-600 hover:bg-orange-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl shadow-orange-600/20 transition transform hover:-translate-y-1 flex items-center justify-center gap-3" id="openAdminLoginBtn">
                    <i class="fas fa-user-shield"></i> Login Admin
                </button>
                <button class="w-full sm:w-auto bg-transparent border-2 border-orange-500 text-orange-400 hover:bg-orange-500/10 px-8 py-4 rounded-full font-bold text-lg transition transform hover:-translate-y-1 flex items-center justify-center gap-3" id="openKenshiLoginBtn">
                    <i class="fas fa-user-graduate"></i> Login Kenshi
                </button>
                <a href="/register" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl shadow-emerald-600/20 transition transform hover:-translate-y-1 flex items-center justify-center gap-3 no-underline">
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

        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold border-l-8 border-orange-600 pl-6 mb-12 text-slate-800 tracking-tight">
                Perolehan Medali per Kegiatan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="medalsLandingGrid"></div>
        </div>

        <div class="mb-16">
            <h2 class="text-2xl md:text-3xl font-bold border-l-8 border-orange-600 pl-6 mb-12 text-slate-800 tracking-tight">
                Prestasi Kenshi & Mitra UMKM
            </h2>
            <div class="flex flex-wrap gap-6 mb-12" id="landingAthletes"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="landingUMKM"></div>
        </div>

        <div class="text-center py-10">
            <a href="/register" class="inline-flex items-center gap-3 bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-5 rounded-2xl font-bold text-xl shadow-2xl shadow-emerald-600/30 transition transform hover:scale-105 no-underline">
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
            <p class="text-sm">© 2025 SMART-PERKEMI · Shorinji Kempo Indonesia</p>
            <p class="mt-2 text-xs opacity-50 font-medium uppercase tracking-[0.2em]">Martial Arts Management System</p>
        </div>
    </footer>
</main>

<!-- LOGIN MODAL -->
<div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[2000] flex justify-center items-center opacity-0 invisible transition-all duration-300" id="loginModal">
    <div class="bg-white rounded-[3rem] p-10 w-full max-w-md mx-6 text-center relative shadow-2xl">
        <button class="absolute top-8 right-8 text-slate-300 hover:text-slate-600 text-2xl transition-colors" id="closeModalBtn">&times;</button>
        <div id="loginFormContainer" class="flex flex-col items-center">
            <div class="w-20 h-20 bg-orange-100 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
                <i class="fas fa-lock text-4xl text-orange-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-800 mb-2" id="modalTitle">Login</h3>
            <p class="text-slate-500 text-sm mb-8">Silakan masukkan akun Anda untuk melanjutkan</p>
            
            <div class="w-full space-y-4">
                <input type="text" id="loginUsername" placeholder="Username / ID Kenshi" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-full focus:border-orange-500 outline-none transition-colors">
                <input type="password" id="loginPassword" placeholder="Password" class="w-full px-6 py-4 bg-slate-50 border-2 border-slate-100 rounded-full focus:border-orange-500 outline-none transition-colors">
                <button id="confirmLoginBtn" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-4 rounded-full font-bold text-lg shadow-xl shadow-orange-600/20 transition-all active:scale-95">Masuk</button>
            </div>
            <div class="mt-6 text-red-500 text-sm font-medium" id="loginErrorMsg"></div>
        </div>
    </div>
</div>

<!-- DASHBOARD ADMIN -->
<div class="dashboard-container min-h-screen hidden flex-col lg:flex-row bg-slate-50" id="dashboardApp">
    <!-- Sidebar -->
    <aside class="w-full lg:w-72 bg-gradient-to-b from-[#0f2b3d] to-[#0a1e2c] text-white flex-shrink-0 lg:fixed lg:inset-y-0 lg:flex flex-col shadow-2xl z-[100]">
        <div class="p-8 border-b border-white/10 flex items-center gap-4">
            <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shield-alt text-xl"></i>
            </div>
            <h2 class="text-xl font-bold tracking-tighter uppercase">Admin Panel</h2>
        </div>
        
        <nav class="flex-1 overflow-y-auto p-6 space-y-2">
            <!-- Nav Item -->
            <div class="nav-item">
                <div class="flex items-center justify-between p-4 cursor-pointer rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition-all nav-main" data-menu="kejuaraan">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-trophy w-6 text-center"></i>
                        <span class="font-semibold">Kejuaraan</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs opacity-50"></i>
                </div>
                <div class="hidden flex-col gap-2 pl-12 py-3 sub-menu" id="sub-kejuaraan">
                    <a href="/register" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">Registrasi Event</a>
                    <a href="#" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">Kejurdo (antar Dojo)</a>
                    <a href="#" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">Kejurkot</a>
                    <a href="#" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">Kejurkab</a>
                    <a href="#" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">Kejurprov</a>
                </div>
            </div>

            <!-- Gashuku -->
            <div class="nav-item">
                <div class="flex items-center justify-between p-4 cursor-pointer rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition-all nav-main" data-menu="gashuku">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-campground w-6 text-center"></i>
                        <span class="font-semibold">Gashuku</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs opacity-50"></i>
                </div>
                <div class="hidden flex-col gap-2 pl-12 py-3 sub-menu" id="sub-gashuku">
                    <a href="#" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">Gaskot</a>
                    <a href="#" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">Gaskab</a>
                </div>
            </div>

            <!-- UKT -->
            <div class="nav-item">
                <div class="flex items-center justify-between p-4 cursor-pointer rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition-all nav-main" data-menu="ukt">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-graduation-cap w-6 text-center"></i>
                        <span class="font-semibold">Ujian Kenaikan</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs opacity-50"></i>
                </div>
                <div class="hidden flex-col gap-2 pl-12 py-3 sub-menu" id="sub-ukt">
                    <a href="#" class="text-sm text-slate-400 hover:text-orange-400 transition-colors py-2 no-underline">UKT Kota</a>
                </div>
            </div>
            
            <div class="nav-item">
                <div class="flex items-center justify-between p-4 cursor-pointer rounded-2xl text-slate-300 hover:bg-white/10 hover:text-white transition-all nav-main" data-menu="iuran">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-wallet w-6 text-center"></i>
                        <span class="font-semibold">Iuran</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs opacity-50"></i>
                </div>
                <div class="hidden flex-col gap-2 pl-12 py-3 sub-menu" id="sub-iuran"></div>
            </div>
        </nav>
        
        <div class="p-6 border-t border-white/10">
            <button class="w-full bg-red-600/20 hover:bg-red-600/30 text-red-400 py-3 rounded-xl border border-red-600/30 transition-colors font-bold" onclick="location.reload()">Logout Admin</button>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 lg:pl-72 p-6 md:p-10">
        <div class="max-w-6xl mx-auto space-y-10 py-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Ringkasan Admin</h1>
                    <p class="text-slate-500">Monitor perkembangan kenshi dan kegiatan Smart-Perkemi</p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-white rounded-2xl px-6 py-3 shadow-sm border border-slate-100 flex items-center gap-3">
                        <span class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></span>
                        <span class="font-bold text-slate-700">Sistem Online</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-3xl p-6 shadow-md border border-slate-100">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Total Atlet</p>
                    <div class="text-4xl font-extrabold text-orange-600">1,432</div>
                    <div class="mt-2 text-xs text-emerald-600 font-bold flex items-center gap-1">
                        <i class="fas fa-caret-up"></i> +12% Bulan ini
                    </div>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-md border border-slate-100">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Medali Emas</p>
                    <div class="text-4xl font-extrabold text-amber-500">53</div>
                    <div class="mt-2 text-xs text-slate-400 font-medium uppercase tracking-tighter">Event Wilayah & Prov</div>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-md border border-slate-100">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Data UKT</p>
                    <div class="text-4xl font-extrabold text-blue-600">824</div>
                    <div class="mt-2 text-xs text-blue-600 font-medium">Pengiriman Sertifikat</div>
                </div>
                <div class="bg-white rounded-3xl p-6 shadow-md border border-slate-100">
                    <p class="text-slate-500 text-sm font-bold uppercase tracking-widest mb-1">Pendaftaran</p>
                    <div class="text-4xl font-extrabold text-emerald-600">158</div>
                    <div class="mt-2 text-xs text-emerald-600 font-medium tracking-tight">Menunggu Verifikasi</div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100">
                    <h4 class="font-bold mb-6 text-slate-700">Tren Kejuaraan</h4>
                    <canvas id="adminKejuaraanChart"></canvas>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100">
                    <h4 class="font-bold mb-6 text-slate-700">Partisipasi Gashuku</h4>
                    <canvas id="adminGashukuChart"></canvas>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100">
                    <h4 class="font-bold mb-6 text-slate-700">Data Kelulusan UKT</h4>
                    <canvas id="adminUKTChart"></canvas>
                </div>
            </div>

            <!-- Medals Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="adminMedalsGrid"></div>

            <!-- UMKM Admin -->
            <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-orange-500/20 rounded-full blur-2xl"></div>
                
                <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-10 relative z-10">
                    <div>
                        <h3 class="text-2xl font-bold mb-2 tracking-tight">Kemitraan UMKM & Logistik</h3>
                        <p class="text-slate-400">Kelola promosi unit usaha Kenshi di Landing Page</p>
                    </div>
                    <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-full font-bold shadow-lg transition-transform active:scale-95" id="addPromoDashboardBtn">
                        <i class="fas fa-plus"></i> Tambah Mitra Baru
                    </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="dashboardUMKM"></div>
            </div>
        </div>
    </main>
</div>

<!-- DASHBOARD KENSHI (MEMBER) -->
<div class="kenshi-dashboard min-h-screen hidden bg-slate-50 flex-col" id="kenshiDashboard">
    <!-- Header -->
    <header class="bg-[#0f2b3d] text-white p-6 shadow-xl relative z-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/20">
                    <i class="fas fa-user-ninja text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Portal Anggota Kenshi</h2>
                    <p class="text-slate-400 text-sm font-medium uppercase tracking-widest hidden md:block">Selamat Datang di Panel Prestasi Anda</p>
                </div>
            </div>
            <button id="logoutKenshiBtn" class="bg-red-500/20 hover:bg-red-500/30 text-red-400 border border-red-500/30 px-8 py-3 rounded-full font-bold transition-all">
                Keluar Sesi
            </button>
        </div>
    </header>

    <!-- Kenshi Section -->
    <div class="bg-gradient-to-r from-orange-600 to-amber-500 py-16 px-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-black/5"></div>
        <div class="max-w-7xl mx-auto relative z-10 text-white text-center md:text-left">
            <h2 class="text-4xl md:text-5xl font-black mb-2 tracking-tight flex flex-col md:flex-row items-center gap-4" id="kenshiNameDisplay">
                <i class="fas fa-id-card opacity-50"></i> Nama Kenshi
            </h2>
            <p class="text-white/80 text-lg font-medium">Digital Passport: Riwayat Partisipasi & Validasi E-Sertifikat</p>
        </div>
    </div>

    <!-- Main Section -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-12 space-y-10 relative z-10">
        <!-- History -->
        <div class="bg-white rounded-[2.5rem] p-8 md:p-12 shadow-xl shadow-slate-200/50 border border-slate-100">
            <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                <i class="fas fa-history text-2xl text-orange-500"></i>
                <h3 class="text-2xl font-bold text-slate-800 tracking-tight">Log Aktivitas & Kompetisi</h3>
            </div>
            
            <div class="overflow-x-auto -mx-8 md:mx-0">
                <table class="w-full text-left" id="historyTable">
                    <thead>
                        <tr class="text-slate-400 text-xs font-bold uppercase tracking-widest">
                            <th class="px-8 py-4 border-b border-slate-50">Kegiatan</th>
                            <th class="px-8 py-4 border-b border-slate-50">Tanggal</th>
                            <th class="px-8 py-4 border-b border-slate-50">Tingkat/Kyu</th>
                            <th class="px-8 py-4 border-b border-slate-50 text-right">E-Sertifikat</th>
                        </tr>
                    </thead>
                    <tbody id="historyTableBody" class="text-slate-700 font-medium">
                        <!-- Iterated by JS -->
                    </tbody>
                </table>
            </div>
            <div id="noDataMsg" class="hidden py-20 text-center text-slate-400 italic">Belum ada riwayat kegiatan tercatat.</div>
        </div>

        <!-- Download All -->
        <div class="bg-emerald-50 rounded-[2.5rem] p-10 border-2 border-dashed border-emerald-200 text-center max-w-3xl mx-auto">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-download text-3xl text-emerald-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-emerald-900 mb-4">Arsip Kolektif Sertifikat</h3>
            <p class="text-emerald-700/70 mb-8 font-medium">Klik untuk mendownload seluruh e-sertifikat yang Anda miliki dalam satu paket (Simulasi ZIP).</p>
            <button id="downloadAllCertsBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-5 rounded-2xl font-bold text-lg shadow-xl shadow-emerald-600/30 transition-all active:scale-95 flex items-center justify-center gap-3 mx-auto">
                <i class="fas fa-file-pdf"></i> Unduh Semua Dokumen (.pdf)
            </button>
            <p class="mt-6 text-xs text-emerald-600/40 uppercase tracking-widest font-bold">Dokumen berlaku resmi dalam lingkungan PERKEMI</p>
        </div>
    </main>
    
    <footer class="bg-slate-50 border-t border-slate-100 py-10 text-center">
        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Powered by Smart-Perkemi Framework</p>
    </footer>
</div>

<!-- Certificate Preview Modal -->
<div id="certPreview" class="fixed inset-0 bg-slate-950/95 z-[3000] flex justify-center items-center p-6 opacity-0 invisible transition-all duration-300">
    <div class="bg-white rounded-[3rem] p-10 w-full max-w-2xl text-center relative shadow-2xl flex flex-col items-center">
        <div id="certContent" class="w-full">
            <h3 class="text-2xl font-extrabold text-slate-800 mb-8 border-b-2 border-orange-100 pb-4">Digital Certificate Preview</h3>
            <div id="certDetail" class="mb-10 text-slate-600 overflow-hidden rounded-2xl border-4 border-slate-50"></div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 w-full">
            <button class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-full font-bold shadow-lg transition-transform active:scale-95" id="downloadCertBtn">Simpan Sebagai PDF</button>
            <button class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 py-4 rounded-full font-bold transition-all" id="closeCertBtn">Tutup Preview</button>
        </div>
    </div>
</div>

<script>
    // ==================== DATA ====================
    const medalsData = {
        "Kejurdo (antar Dojo)": { emas: 12, perak: 8, perunggu: 6 },
        "Kejurkot": { emas: 18, perak: 14, perunggu: 10 },
        "Kejurkab": { emas: 24, perak: 19, perunggu: 15 },
        "Kejurprov": { emas: 32, perak: 26, perunggu: 20 },
        "Porprov Jatim": { emas: 28, perak: 22, perunggu: 18 }
    };

    // Data kenshi (username, password, nama, riwayat kegiatan)
    const kenshiUsers = [
        { username: "KSH001", password: "kempo123", name: "Budi Santoso", history: [
            { activity: "Kejurdo (antar Dojo)", date: "2024-02-10", level: "Kabupaten", certId: "CERT-KEJURDO-001" },
            { activity: "Gashuku Provinsi", date: "2024-05-15", level: "Provinsi", certId: "CERT-GASPROV-045" },
            { activity: "UKT Kota Surabaya", date: "2024-08-20", level: "Kyu 5", certId: "CERT-UKT-089" }
        ] },
        { username: "KSH002", password: "kempo456", name: "Siti Aminah", history: [
            { activity: "Kejurkot Surabaya", date: "2024-03-12", level: "Kota", certId: "CERT-KOT-023" },
            { activity: "Gashuku Kabupaten", date: "2024-06-18", level: "Kabupaten", certId: "CERT-GASKAB-012" },
            { activity: "Porprov Jatim", date: "2024-09-05", level: "Provinsi", certId: "CERT-PORPROV-056" },
            { activity: "UKT Provinsi", date: "2024-11-10", level: "Kyu 3", certId: "CERT-UKTPROV-034" }
        ] }
    ];

    let currentKenshi = null;

    // Helper render medals
    function renderMedalsGrid(containerId, data) {
        const container = document.getElementById(containerId);
        if(!container) return;
        container.innerHTML = '';
        for(let [event, medals] of Object.entries(data)) {
            container.innerHTML += `
                <div class="bg-white rounded-3xl p-8 shadow-md border border-slate-100 flex flex-col items-center text-center transition-transform hover:-translate-y-1">
                    <h4 class="font-bold text-slate-700 mb-6 flex items-center gap-2">
                        <span class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-lg">🏆</span>
                        ${event}
                    </h4>
                    <div class="flex justify-between w-full gap-4">
                        <div class="flex-1">
                            <span class="text-3xl font-black text-amber-500 block">${medals.emas}</span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-tighter">🥇 Emas</span>
                        </div>
                        <div class="flex-1 border-x border-slate-50 px-4">
                            <span class="text-3xl font-black text-slate-400 block">${medals.perak}</span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-tighter">🥈 Perak</span>
                        </div>
                        <div class="flex-1">
                            <span class="text-3xl font-black text-orange-700 block">${medals.perunggu}</span>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-tighter">🥉 Perunggu</span>
                        </div>
                    </div>
                </div>`;
        }
    }

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

    const landingAthletes = [{ name:"Rama Wijaya", region:"Jawa Timur", prestasi:"Emas Porprov Jatim VIII", avatar:"RW" },{ name:"Ayu Lestari", region:"Nasional", prestasi:"Juara 1 Kejurnas", avatar:"AL" }];
    function renderLandingAthletes() { 
        document.getElementById('landingAthletes').innerHTML = landingAthletes.map(a => `
            <div class="bg-white rounded-3xl p-4 flex items-center gap-5 shadow-xl shadow-slate-200/50 border border-slate-100 flex-1 min-w-[280px]">
                <div class="w-16 h-16 bg-orange-600 rounded-2xl flex items-center justify-center font-black text-white text-xl shadow-lg ring-4 ring-orange-50 shrink-0 uppercase">${a.avatar}</div>
                <div class="overflow-hidden">
                    <b class="text-slate-800 block text-lg truncate">${a.name}</b>
                    <small class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">${a.region}</small>
                    <div class="mt-1">
                        <span class="inline-block bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs font-bold leading-none">🏆 ${a.prestasi}</span>
                    </div>
                </div>
            </div>`).join(''); 
    }

    const landingUMKM = [{ name:"Kempo Pro Gear", owner:"Dojo Tegalsari", desc:"Seragam & perlengkapan bertanding standar PERKEMI" },{ name:"Energi Herbal", owner:"Kenshi Cab. Malang", desc:"Minuman suplemen sehat kenshi" }];
    function renderLandingUMKM() { 
        document.getElementById('landingUMKM').innerHTML = landingUMKM.map(u => `
            <div class="bg-white rounded-3xl p-8 shadow-xl shadow-slate-200/50 border border-slate-100 group transition-all hover:-translate-y-2">
                <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-6 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i class="fas fa-handshake text-xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800 mb-1 tracking-tight">${u.name}</h4>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mb-4">by ${u.owner}</p>
                <p class="text-slate-500 text-sm leading-relaxed mb-8">${u.desc}</p>
                <button class="w-full bg-slate-50 hover:bg-orange-600 hover:text-white py-3 rounded-2xl font-bold text-slate-600 transition-all active:scale-95" onclick="alert('Menghubungi Mitra...')">Lihat Produk</button>
            </div>`).join(''); 
    }

    // Admin Charts
    function initAdminCharts() {
        const adminConfig = (id, label, data, color) => new Chart(document.getElementById(id), {
            type: 'bar',
            data: { labels: ['Q1', 'Q2', 'Q3', 'Q4'], datasets: [{ label, data, backgroundColor: color, borderRadius: 10 }] },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, display: false }, x: { grid: { display: false } } } }
        });
        adminConfig('adminKejuaraanChart', 'Kejuaraan', [40, 70, 100, 80], '#e67e22');
        adminConfig('adminGashukuChart', 'Gashuku', [30, 50, 60, 45], '#3b82f6');
        adminConfig('adminUKTChart', 'UKT', [80, 120, 150, 110], '#10b981');
    }

    let dashboardUmkm = [...landingUMKM];
    function renderDashboardUMKM() { 
        document.getElementById('dashboardUMKM').innerHTML = dashboardUmkm.map(u => `
            <div class="bg-white/5 border border-white/10 rounded-[2rem] p-6 hover:bg-white/10 transition-colors">
                <div class="flex items-start justify-between mb-4">
                    <i class="fas fa-store text-2xl text-orange-400"></i>
                    <span class="bg-emerald-500/20 text-emerald-400 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Aktif</span>
                </div>
                <h4 class="text-lg font-bold mb-1">${u.name}</h4>
                <p class="text-slate-500 text-sm mb-6">${u.owner}</p>
                <div class="flex gap-2">
                    <button class="flex-1 bg-white/10 hover:bg-white/20 py-2 rounded-xl text-xs font-bold" onclick="alert('Edit Mitra')">Edit</button>
                    <button class="px-3 bg-red-500/10 text-red-400 py-2 rounded-xl text-xs" onclick="alert('Nonaktifkan')"><i class="fas fa-trash"></i></button>
                </div>
            </div>`).join(''); 
    }

    function buildIuranMenu() { 
        const c = document.getElementById('sub-iuran'); 
        c.innerHTML = '<a href="#" class="text-sm text-slate-400 hover:text-orange-400 py-2 no-underline">Kota Surabaya</a><a href="#" class="text-sm text-slate-400 hover:text-orange-400 py-2 no-underline">Kab Sidoarjo</a>'; 
    }
    function initSidebarEvents() { 
        document.querySelectorAll('.nav-main').forEach(main=>{ 
            main.addEventListener('click',()=>{ 
                let sub=main.closest('.nav-item').querySelector('.sub-menu'); 
                if(sub) sub.classList.toggle('hidden'); 
            }); 
        }); 
    }

    // Certificate Generation
    async function downloadCertificate(activity, name, date, level) {
        const certDetail = document.getElementById('certDetail');
        certDetail.innerHTML = `
            <div id="captureArea" style="text-align:center; font-family:'Inter'; padding:60px; border:30px solid #e67e22; border-radius:10px; background:white; color:#0f2b3d; width:800px; margin:0 auto; position:relative;">
                <div style="font-weight:900; font-size:40px; color:#e67e22; letter-spacing:-2px; margin-bottom:10px;">SMART-PERKEMI</div>
                <div style="letter-spacing:10px; font-size:12px; font-weight:800; color:#cbd5e1; margin-bottom:40px;">NATIONAL ARCHIVE</div>
                <h2 style="margin:20px 0; font-style:italic; font-size:32px; font-weight:800; text-transform:uppercase;">E-Sertifikat Keikutsertaan</h2>
                <p style="font-size:18px; color:#64748b;">Dengan bangga diberikan kepada:</p>
                <h3 style="font-size:42px; margin:20px 0; font-weight:900; color:#0f2b3d; border-bottom:4px solid #f1f5f9; display:inline-block; padding-bottom:10px;">${name}</h3>
                <p style="font-size:18px; color:#64748b; margin-top:20px;">Atas partisipasi dan pencapaian dalam kegiatan:</p>
                <h2 style="font-size:28px; font-weight:800; color:#e67e22; margin:10px 0;">${activity}</h2>
                <div style="margin-top:30px; display:flex; justify-content:center; gap:40px;">
                    <div><span style="display:block; font-size:10px; font-weight:900; color:#cbd5e1; text-transform:uppercase;">Tingkat</span><b>${level}</b></div>
                    <div><span style="display:block; font-size:10px; font-weight:900; color:#cbd5e1; text-transform:uppercase;">Tanggal</span><b>${date}</b></div>
                </div>
                <div style="margin-top:50px; font-size:10px; color:#cbd5e1; border-top:1px solid #f1f5f9; padding-top:20px;">
                    VERIFIED ID: CERT-${Date.now()}
                </div>
            </div>`;
        
        const previewModal = document.getElementById('certPreview');
        previewModal.classList.remove('invisible', 'opacity-0');
        
        document.getElementById('downloadCertBtn').onclick = async () => {
            const area = document.getElementById('captureArea');
            const canvas = await html2canvas(area, { scale: 2 });
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('landscape', 'mm', 'a4');
            pdf.addImage(imgData, 'PNG', 10, 10, 277, 190);
            pdf.save(`Sertifikat_${name.replace(/\s/g,'_')}_${activity.replace(/\s/g,'_')}.pdf`);
        };
        document.getElementById('closeCertBtn').onclick = () => previewModal.classList.add('invisible', 'opacity-0');
    }

    // Render Kenshi History
    function renderKenshiHistory(kenshi) {
        const tbody = document.getElementById('historyTableBody');
        tbody.innerHTML = '';
        kenshi.history.forEach(item => {
            const row = tbody.insertRow();
            row.className = "hover:bg-slate-50 transition-colors group";
            
            const cellAct = row.insertCell(0);
            cellAct.className = "px-8 py-6 font-bold text-slate-800";
            cellAct.innerText = item.activity;
            
            const cellDate = row.insertCell(1);
            cellDate.className = "px-8 py-6 text-slate-500 font-medium";
            cellDate.innerText = item.date;
            
            const cellLevel = row.insertCell(2);
            cellLevel.className = "px-8 py-6";
            cellLevel.innerHTML = `<span class="bg-slate-100 text-slate-600 px-4 py-1 rounded-full text-xs font-black uppercase tracking-tight">${item.level}</span>`;
            
            const cellCert = row.insertCell(3);
            cellCert.className = "px-8 py-6 text-right";
            const btn = document.createElement('button');
            btn.innerHTML = '<i class="fas fa-file-invoice"></i> Buka Sertifikat';
            btn.className = "bg-orange-100 text-orange-600 hover:bg-orange-600 hover:text-white px-5 py-2 rounded-xl text-xs font-extrabold transition-all active:scale-95";
            btn.onclick = () => downloadCertificate(item.activity, kenshi.name, item.date, item.level);
            cellCert.appendChild(btn);
        });
        document.getElementById('kenshiNameDisplay').innerHTML = `
            <div class="flex items-center gap-4">
                <i class="fas fa-id-card opacity-30"></i>
                <span class="tracking-tighter">${kenshi.name}</span>
                <span class="text-sm bg-black/10 px-4 py-1 rounded-full font-bold ml-2">ID: ${kenshi.username}</span>
            </div>`;
    }

    // ==================== UI LOGIC ====================
    const modal = document.getElementById('loginModal');
    const modalTitle = document.getElementById('modalTitle');
    const errorMsgSpan = document.getElementById('loginErrorMsg');
    const landingPageDiv = document.getElementById('landingPage');
    const dashboardAdmin = document.getElementById('dashboardApp');
    const kenshiDashboard = document.getElementById('kenshiDashboard');

    function openLogin(type) {
        modalTitle.innerText = type === 'admin' ? 'Administrasi Smart-Perkemi' : 'Portal Identitas Kenshi';
        errorMsgSpan.innerText = '';
        modal.classList.remove('invisible', 'opacity-0');
        modal.dataset.type = type;
    }

    document.getElementById('confirmLoginBtn').onclick = () => {
        const user = document.getElementById('loginUsername').value.trim();
        const pass = document.getElementById('loginPassword').value.trim();
        const type = modal.dataset.type;

        if(type === 'admin') {
            if(user === 'admin' && pass === 'admin123') {
                modal.classList.add('invisible', 'opacity-0');
                landingPageDiv.classList.add('hidden');
                dashboardAdmin.classList.remove('hidden');
                initAdminCharts(); renderMedalsGrid('adminMedalsGrid', medalsData); renderDashboardUMKM(); buildIuranMenu(); initSidebarEvents();
                document.getElementById('addPromoDashboardBtn').onclick = () => { 
                    let n=prompt("Nama Usaha:"); 
                    if(n) { dashboardUmkm.push({name:n,owner:"Anggota Kempo",desc:"Mitra Baru"}); renderDashboardUMKM(); }
                };
            } else errorMsgSpan.innerText = 'Gagal: Gunakan admin / admin123';
        } else {
            const found = kenshiUsers.find(k => k.username === user && k.password === pass);
            if(found) {
                modal.classList.add('invisible', 'opacity-0');
                landingPageDiv.classList.add('hidden');
                kenshiDashboard.classList.remove('hidden');
                renderKenshiHistory(found);
            } else errorMsgSpan.innerText = 'Gagal: KSH001 / kempo123';
        }
    };

    document.getElementById('openAdminLoginBtn').onclick = () => openLogin('admin');
    document.getElementById('openKenshiLoginBtn').onclick = () => openLogin('kenshi');
    document.getElementById('closeModalBtn').onclick = () => modal.classList.add('invisible', 'opacity-0');
    document.getElementById('logoutKenshiBtn').onclick = () => location.reload();

    initLandingCharts(); renderLandingAthletes(); renderLandingUMKM(); renderMedalsGrid('medalsLandingGrid', medalsData);
</script>

</body>
</html>
