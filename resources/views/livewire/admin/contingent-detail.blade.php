<div class="space-y-10 pb-24">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <a href="{{ route('admin.contingents.index') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-orange-600 mb-4 transition-colors font-bold text-xs uppercase tracking-widest">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Detail Kontingen</h1>
            <p class="text-slate-500 font-medium">Informasi lengkap pendaftaran #{{ $contingent->id }} ({{ $contingent->referral_code }})</p>
        </div>
        
        <div @class([
            'px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-sm border',
            'bg-amber-50 text-amber-600 border-amber-100' => $contingent->status === 'pending',
            'bg-emerald-50 text-emerald-600 border-emerald-100' => $contingent->status === 'confirmed',
            'bg-red-50 text-red-600 border-red-100' => $contingent->status === 'rejected',
        ])>
            Status: {{ $contingent->status }}
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-6 py-4 rounded-2xl font-bold flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-slate-100">
                <h3 class="text-xl font-extrabold mb-8 text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="fas fa-info-circle text-orange-500"></i> Informasi Umum
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Kontingen</p>
                        <p class="text-slate-800 font-bold border-b border-slate-50 pb-2">{{ $contingent->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Kabupaten / Kota</p>
                        <p class="text-slate-800 font-bold border-b border-slate-50 pb-2">{{ $contingent->kab_kota }}</p>
                    </div>
                    <div>
                        <p class="text-[10px) font-black text-slate-400 uppercase tracking-widest mb-1">Penanggung Jawab</p>
                        <p class="text-slate-800 font-bold border-b border-slate-50 pb-2">{{ $contingent->leader_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Telepon / HP</p>
                        <p class="text-slate-800 font-bold border-b border-slate-50 pb-2">{{ $contingent->leader_phone }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat Email</p>
                        <p class="text-slate-800 font-bold border-b border-slate-50 pb-2">{{ $contingent->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">SIM Perkemi Valid?</p>
                        <p class="text-slate-800 font-bold border-b border-slate-50 pb-2">{{ $contingent->sim_perkemi_confirm ?? 'Ya' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                        <p class="text-slate-800 font-bold border-b border-slate-50 pb-2">{{ $contingent->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Officials List Card -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <h3 class="text-xl font-extrabold mb-8 text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="fas fa-user-tie text-emerald-500"></i> Daftar Official ({{ $contingent->officials->count() }})
                </h3>
                
                <div class="overflow-x-auto -mx-10 px-10">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-50">
                                <th class="py-4">Nama Official</th>
                                <th class="py-4">Jabatan</th>
                                <th class="py-4">No. HP</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-700 font-medium">
                            @forelse($contingent->officials as $official)
                            <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition-colors">
                                <td class="py-5 font-bold text-slate-800">{{ $official->name }}</td>
                                <td class="py-5">
                                    <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tight">
                                        {{ $official->role }}
                                    </span>
                                </td>
                                <td class="py-5 text-slate-500">{{ $official->phone ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-10 text-center text-slate-400 italic font-medium">Tidak ada data official</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Athletes List -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                    <h3 class="text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-3">
                        <i class="fas fa-user-ninja text-orange-500"></i> Daftar Atlet ({{ $contingent->athletes->count() }})
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                    @foreach($contingent->athletes as $athlete)
                    <div class="bg-slate-50 rounded-[2rem] p-6 border border-slate-100 hover:shadow-md transition-all group">
                        <!-- Header: Name & Basic -->
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h4 class="text-lg font-black text-slate-800 leading-tight group-hover:text-orange-600 transition-colors">{{ $athlete->name }}</h4>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                    {{ $athlete->gender }} · {{ $athlete->age_group }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                @if($athlete->identity_document_path)
                                <a href="{{ Storage::url($athlete->identity_document_path) }}" target="_blank" class="w-10 h-10 flex items-center justify-center bg-white text-blue-600 rounded-xl shadow-sm border border-slate-100 hover:bg-blue-50 transition-all" title="Akte/KTP">
                                    <i class="fas fa-id-card"></i>
                                </a>
                                @endif
                                @if($athlete->bpjs_card_path)
                                <a href="{{ Storage::url($athlete->bpjs_card_path) }}" target="_blank" class="w-10 h-10 flex items-center justify-center bg-white text-emerald-600 rounded-xl shadow-sm border border-slate-100 hover:bg-emerald-50 transition-all" title="BPJS">
                                    <i class="fas fa-shield-alt"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-2 gap-4 mb-6 pt-6 border-t border-slate-200/50">
                            <!-- Left: Technical -->
                            <div class="space-y-4">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1">Tingkatan</p>
                                    <p class="text-xs font-bold text-slate-700 leading-tight">{{ $athlete->rank }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1">Dojo</p>
                                    <p class="text-xs font-black text-orange-600 leading-tight uppercase">{{ $athlete->dojo_origin }}</p>
                                </div>
                            </div>
                            <!-- Right: Identity -->
                            <div class="space-y-4 text-right">
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1 text-right">NIK / Identitas</p>
                                    <p class="text-xs font-bold text-slate-700">{{ $athlete->nik ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1 text-right">Tgl Lahir</p>
                                    <p class="text-xs font-bold text-slate-700">{{ $athlete->birth_date ? ($athlete->birth_date instanceof \Carbon\Carbon ? $athlete->birth_date->format('d/m/Y') : $athlete->birth_date) : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Categories -->
                        <div class="mb-6">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mb-3">Kategori Pertandingan</p>
                            <div class="flex flex-wrap gap-1.5">
                                @forelse($athlete->categories as $category)
                                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-tight">{{ $category->name }}</span>
                                @empty
                                <span class="text-xs text-slate-400 italic">Belum pilih kategori</span>
                                @endforelse
                            </div>
                        </div>

                        <!-- BPJS Status -->
                        <div class="pt-6 border-t border-slate-200/50 flex justify-between items-center">
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-1">No. BPJS</p>
                                <p class="text-xs font-bold text-slate-700 tracking-tight">{{ $athlete->bpjs_number ?? '-' }}</p>
                            </div>
                            <div @class([
                                'inline-flex items-center gap-2 px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-tight shadow-sm',
                                'bg-emerald-100 text-emerald-700 border border-emerald-200' => $athlete->bpjs_status === 'Aktif',
                                'bg-red-100 text-red-700 border border-red-200' => $athlete->bpjs_status !== 'Aktif',
                            ])>
                                <span class="w-2 h-2 rounded-full {{ $athlete->bpjs_status === 'Aktif' ? 'bg-emerald-500' : 'bg-red-500 animate-pulse' }}"></span>
                                {{ $athlete->bpjs_status }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar / Payment Info -->
        <div class="space-y-8">
            <!-- Payment Card -->
            <div class="bg-[#0f2b3d] text-white rounded-[2.5rem] p-10 shadow-xl relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-orange-600/20 rounded-full blur-2xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col items-center text-center">
                    <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mb-4">Total Biaya Pendaftaran</p>
                    <div class="text-4xl font-black text-orange-400 mb-8 tracking-tighter">
                        Rp {{ number_format($contingent->final_amount, 0, ',', '.') }}
                    </div>
                    
                    <div class="w-full space-y-4 mb-10 pt-8 border-t border-white/10 text-left">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-white/40 font-bold uppercase tracking-widest leading-none">Metode</span>
                            <span class="font-bold text-white uppercase">{{ $contingent->payment_method ?? 'Bank Transfer' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-white/40 font-bold uppercase tracking-widest leading-none">Status Bayar</span>
                            <span @class([
                                'px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tight',
                                'bg-amber-500/20 text-amber-400' => $contingent->status === 'pending',
                                'bg-emerald-500/20 text-emerald-400' => $contingent->status === 'confirmed',
                            ])>{{ $contingent->status === 'confirmed' ? 'Diterima' : 'Menunggu' }}</span>
                        </div>
                    </div>
                    
                    <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-4">Bukti Pembayaran</p>
                    <div class="w-full aspect-[4/3] bg-white/5 rounded-2xl border-2 border-dashed border-white/10 overflow-hidden group relative">
                        @if($contingent->transfer_proof_path)
                            <img src="{{ Storage::url($contingent->transfer_proof_path) }}" class="w-full h-full object-cover transition-all group-hover:scale-110">
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <a href="{{ Storage::url($contingent->transfer_proof_path) }}" target="_blank" class="bg-white/20 backdrop-blur-md text-white p-3 rounded-full hover:bg-white/40 transition-colors">
                                    <i class="fas fa-expand"></i>
                                </a>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-white/20 italic text-sm">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                Tidak ada file
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Additional Stats -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100">
                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Informasi Tambahan</h4>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-users text-blue-500"></i>
                            <span class="text-xs font-bold text-slate-600">Total Atlet</span>
                        </div>
                        <span class="font-bold text-slate-800">{{ $contingent->athletes->count() }} Orang</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-user-tie text-emerald-500"></i>
                            <span class="text-xs font-bold text-slate-600">Total Official</span>
                        </div>
                        <span class="font-bold text-slate-800">{{ $contingent->officials->count() }} Orang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Action Footer -->
    @if($contingent->status === 'pending')
    <div class="fixed bottom-10 left-1/2 -translate-x-1/2 w-full max-w-4xl px-6 z-50">
        <div class="bg-white/80 backdrop-blur-lg rounded-[2rem] p-4 shadow-2xl border border-white/50 flex flex-col sm:flex-row gap-4 items-center justify-between">
            <div class="px-4 hidden sm:block">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block leading-none mb-1">Aksi Verifikasi</span>
                <span class="text-sm font-bold text-slate-800 truncate">{{ $contingent->name }}</span>
            </div>
            
            <div class="flex gap-3 w-full sm:w-auto">
                <button wire:click="reject" 
                        wire:confirm="Apakah Anda yakin ingin menolak pendaftaran ini?"
                        class="flex-1 sm:flex-none px-8 py-4 bg-white border-2 border-red-100 hover:bg-red-50 text-red-600 rounded-2xl font-bold text-sm tracking-widest transition-all">
                    TOLAK
                </button>
                <button wire:click="confirm" 
                        wire:confirm="Apakah Anda yakin ingin mengonfirmasi pembayaran ini?"
                        class="flex-1 sm:flex-none px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white shadow-xl shadow-emerald-600/20 rounded-2xl font-bold text-sm tracking-widest transition-all active:scale-95">
                    KONFIRMASI BAYAR
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
