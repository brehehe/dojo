<div class="space-y-6 animate-in fade-in zoom-in-95 duration-700">
    <!-- Header with Breadcrumbs -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-[15px] font-black uppercase tracking-widest text-slate-800">
                <a href="{{ route('admin.master.athletes.index') }}"  class="hover:text-orange-600 transition-colors">Master Atlet</a>
                <i class="fas fa-chevron-right text-[15px]"></i>
                <span class="text-slate-900">Detail Profil</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $athlete->name }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.master.athletes.edit', $athlete->id) }}" 
                class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-4 py-2 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all">
                Edit Profil
            </a>
            <button onclick="window.print()" 
                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl text-[15px] font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 transition-all">
                Cetak Profil
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar: Profile Snapshot -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="aspect-square bg-slate-50 flex items-center justify-center relative group">
                    <i class="fas fa-user text-6xl text-slate-200 group-hover:scale-110 transition-transform duration-500"></i>
                    <div class="absolute top-4 right-4 bg-orange-600 text-white text-[15px] font-black px-2 py-1 rounded-full uppercase tracking-widest shadow-lg">
                        {{ $athlete->match_type ?? 'PREMIUM' }}
                    </div>
                </div>
                <div class="p-6 text-center space-y-1">
                    <p class="text-[15px] font-black text-slate-800 tracking-tight">{{ $athlete->name }}</p>
                    <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest italic">{{ $athlete->nik }}</p>
                    <div class="pt-4 flex justify-center gap-2">
                        <span class="px-2 py-1 rounded-md bg-slate-50 text-slate-900 text-[15px] font-black border border-slate-100 uppercase tracking-widest">
                            {{ $athlete->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        <span class="px-2 py-1 rounded-md bg-orange-50 text-orange-600 text-[15px] font-black border border-orange-100 uppercase tracking-widest">
                            {{ $athlete->weight }} KG
                        </span>
                    </div>
                </div>
                <div class="px-6 pb-6 pt-2">
                    <div class="bg-slate-50 rounded-2xl p-4 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Kontingen</span>
                            <span class="text-[15px] font-bold text-slate-800 uppercase">{{ $athlete->contingent?->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Tingkat</span>
                            <span class="text-[15px] font-bold text-slate-800 uppercase">{{ $athlete->kyu ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Medical Stats -->
            <div class="bg-emerald-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-700/20 relative overflow-hidden group">
                <i class="fas fa-heartbeat absolute -right-2 -bottom-2 text-6xl text-white/10 group-hover:scale-125 transition-transform duration-700"></i>
                <p class="text-[15px] font-black uppercase tracking-widest text-white/60 mb-1 relative z-10">BPJS Status</p>
                <div class="flex items-center gap-3 relative z-10">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="text-[15px] font-black tracking-tight uppercase">{{ $athlete->bpjs_status ?? 'AKTIF' }}</span>
                </div>
            </div>
        </div>

        <!-- Main Content: Detailed Information -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Tabs Navigation -->
            <div class="bg-white rounded-2xl p-1.5 shadow-sm border border-slate-100 flex items-center gap-1">
                <button wire:click="switchTab('identity')" 
                    class="flex-1 py-3 px-4 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'identity' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Informasi Identitas
                </button>
                <button wire:click="switchTab('medical')" 
                    class="flex-1 py-3 px-4 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'medical' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Kesehatan & Berkas
                </button>
                <button wire:click="switchTab('categories')" 
                    class="flex-1 py-3 px-4 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'categories' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Kategori Kejuaraan
                </button>
                <button wire:click="switchTab('history')" 
                    class="flex-1 py-3 px-4 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all {{ $activeTab === 'history' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Riwayat Kontingen
                </button>
            </div>

            <!-- Tab Content -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 min-h-[400px]">
                @if($activeTab === 'identity')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Nama Lengkap</label>
                                <p class="text-[15px] font-bold text-slate-800">{{ $athlete->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">NIK</label>
                                <p class="text-[15px] font-bold text-slate-800">{{ $athlete->nik }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Tempat Lahir</label>
                                <p class="text-[15px] font-bold text-slate-800">{{ $athlete->city }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Tanggal Lahir</label>
                                <p class="text-[15px] font-bold text-slate-800">{{ $athlete->birth_date?->format('d F Y') }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Dojo / Ranting Asal</label>
                                <p class="text-[15px] font-bold text-slate-800">{{ $athlete->dojo_origin }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Kelompok Usia</label>
                                <p class="text-[15px] font-bold text-slate-800">{{ str_replace('_', ' ', $athlete->age_group ?? '-') }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Tipe Tanding</label>
                                <p class="text-[15px] font-bold text-slate-800">{{ $athlete->match_type ?? '-' }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Peringkat (Rank)</label>
                                <p class="text-[15px] font-bold text-slate-800 text-orange-600">{{ $athlete->rank ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-50 space-y-4">
                            <h4 class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800">Riwayat Prestasi</h4>
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 min-h-[100px] space-y-3">
                                @forelse($athlete->achievement_history ?? [] as $achievement)
                                    @if($achievement)
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 w-1.5 h-1.5 rounded-full bg-orange-500 shrink-0"></div>
                                            <p class="text-[15px] text-slate-900 font-bold leading-relaxed italic">{{ $achievement }}</p>
                                        </div>
                                    @endif
                                @empty
                                    <p class="text-[15px] text-slate-800 font-medium leading-relaxed italic">
                                        Belum ada riwayat prestasi yang dicatat.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif

                @if($activeTab === 'medical')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1">
                                        <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Nomor BPJS</label>
                                        <p class="text-[15px] font-bold text-slate-800">{{ $athlete->bpjs_number ?? '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Status Kepesertaan</label>
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            <p class="text-[15px] font-bold text-slate-800 capitalize">{{ strtolower($athlete->bpjs_status ?? 'aktif') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6 border-t border-slate-50 space-y-4">
                                    <h4 class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800">Pratinjau Berkas</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="aspect-video bg-slate-50 rounded-2xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center gap-2 group cursor-pointer hover:border-orange-200 transition-all">
                                            <i class="fas fa-id-card text-2xl text-slate-200 group-hover:text-orange-300"></i>
                                            <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Kartu Identitas</span>
                                        </div>
                                        <div class="aspect-video bg-slate-50 rounded-2xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center gap-2 group cursor-pointer hover:border-orange-200 transition-all">
                                            <i class="fas fa-hand-holding-medical text-2xl text-slate-200 group-hover:text-orange-300"></i>
                                            <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Kartu BPJS</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-orange-50/50 rounded-2xl p-6 border border-orange-100 space-y-4">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-info-circle text-orange-600 text-[15px]"></i>
                                    <h5 class="text-[15px] font-black uppercase tracking-widest text-orange-800">Status Verifikasi</h5>
                                </div>
                                <p class="text-[15px] text-slate-900 font-medium leading-relaxed italic">
                                    Dokumen medis telah diunggah dan sedang dalam proses verifikasi oleh panitia kesehatan kejuaraan.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($activeTab === 'categories')
                    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                            <h4 class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800">Partisipasi Kategori Lomba</h4>
                            <span class="px-3 py-1 bg-orange-600 text-white text-[15px] font-black rounded-full uppercase tracking-widest shadow-lg shadow-orange-600/20">
                                {{ $athlete->categories->count() }} Kategori
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($athlete->categories as $category)
                                <div class="group bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 p-5 rounded-2xl border border-slate-100 hover:border-orange-100 transition-all duration-500 flex items-center justify-between relative overflow-hidden">
                                    <div class="space-y-1 relative z-10">
                                        <p class="text-[15px] font-black text-slate-800 uppercase tracking-tight group-hover:text-orange-600 transition-colors">{{ $category->name }}</p>
                                        <div class="flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 rounded text-[15px] font-black bg-orange-100 text-orange-600 uppercase">{{ $category->type ?? 'PEMULA' }}</span>
                                            <span class="text-[15px] text-slate-800 font-bold uppercase tracking-widest italic">{{ $category->age_group ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-medal text-slate-100 group-hover:text-orange-100 text-4xl group-hover:scale-125 transition-all duration-700"></i>
                                </div>
                            @empty
                                <div class="col-span-2 py-12 text-center space-y-3">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl mx-auto flex items-center justify-center text-slate-200">
                                        <i class="fas fa-layer-group text-2xl"></i>
                                    </div>
                                    <p class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Belum terdaftar di kategori manapun</p>
                                </div>
                            @endforelse
                        </div>

                        @if($athlete->categories->count() > 0)
                            <div class="pt-6 bg-orange-50 rounded-2xl p-6 border border-orange-100 flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-orange-600 shrink-0">
                                    <i class="fas fa-trophy text-xl"></i>
                                </div>
                                <div class="space-y-0.5">
                                    <p class="text-[15px] font-black uppercase tracking-widest text-orange-800">Target Kejuaraan</p>
                                    <p class="text-[15px] text-orange-600/70 font-bold italic tracking-tight">Atlet ini terdaftar dalam kategori unggulan. Persiapkan data tim jika kategori beregu dipilih.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if($activeTab === 'history')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                            <h4 class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800">Log Perpindahan Kontingen</h4>
                            <span class="text-[15px] font-bold text-slate-800 italic">Riwayat Perubahan Data</span>
                        </div>

                        <div class="relative pl-8 space-y-8 before:content-[''] before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                            @forelse($athlete->contingentHistories as $history)
                                <div class="relative group">
                                    <div class="absolute -left-[27px] top-1 w-4 h-4 rounded-full bg-white border-4 border-orange-500 group-hover:scale-125 transition-transform duration-300 z-10 shadow-sm"></div>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-3">
                                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-tight">{{ $history->contingent->name }}</p>
                                            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-900 text-[15px] font-black uppercase tracking-widest">
                                                {{ $history->moved_at->format('d M Y') }}
                                            </span>
                                        </div>
                                        <p class="text-[15px] text-slate-800 font-bold italic">{{ $history->notes ?? 'Tidak ada catatan tambahan.' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center space-y-3">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl mx-auto flex items-center justify-center text-slate-200">
                                        <i class="fas fa-history text-2xl"></i>
                                    </div>
                                    <p class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Belum ada riwayat perpindahan kontingen.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
