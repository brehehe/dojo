<div class="space-y-6">
    <!-- Header/Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.registrations.index') }}"
                class="w-10 h-10 bg-white rounded-xl border border-slate-100 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:border-orange-100 transition-all shadow-sm">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">Detail Pendaftaran</h1>
                    @if($registration->status === 'pending')
                        <span
                            class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[9px] font-black rounded-full uppercase tracking-widest border border-yellow-200">Pending</span>
                    @elseif($registration->status === 'verified')
                        <span
                            class="px-3 py-1 bg-green-100 text-green-700 text-[9px] font-black rounded-full uppercase tracking-widest border border-green-200">Verified</span>
                    @else
                        <span
                            class="px-3 py-1 bg-red-100 text-red-700 text-[9px] font-black rounded-full uppercase tracking-widest border border-red-200">Rejected</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-medium">Ref: <span
                        class="font-mono font-bold text-orange-600">{{ $registration->referral_code }}</span> •
                    Terdaftar pada {{ $registration->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        @if($registration->status === 'pending')
            <div class="flex items-center gap-2">
                <button wire:click="reject"
                    class="px-6 py-2.5 bg-white border-2 border-slate-100 text-slate-500 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-50 hover:text-red-600 transition-all shadow-sm">
                    Tolak Pendaftaran
                </button>
                <button wire:click="verify"
                    class="px-6 py-2.5 bg-orange-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-orange-700 transition-all shadow-lg shadow-orange-200">
                    Verifikasi Sekarang
                </button>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Contingent & Payment -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Contingent Info -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative z-10">
                    <h2 class="text-[10px] font-black text-orange-600 uppercase tracking-[0.2em] mb-6">Profil Kontingen
                    </h2>
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-xl">
                            {{ substr($registration->contingent?->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase leading-none mb-2">
                                {{ $registration->contingent?->name }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider italic">
                                {{ $registration->contingent?->kab_kota }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Manager Tim
                            </p>
                            <p class="text-xs font-black text-slate-700 leading-none">
                                {{ $registration->contingent?->leader_name }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Kontak &
                                Email</p>
                            <p class="text-xs font-black text-slate-700 leading-none mb-1">
                                {{ $registration->contingent?->leader_phone }}</p>
                            <p class="text-[10px] text-slate-400 font-medium lowercase">
                                {{ $registration->contingent?->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fee Details -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative z-10">
                    <h2 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-6">Rincian Biaya</h2>
                    
                    <div class="space-y-4">
                        <!-- Contingent Fee -->
                        <div class="flex justify-between items-start pb-4 border-b border-slate-50">
                            <div>
                                <p class="text-[10px] font-black text-slate-800 uppercase leading-none mb-1">Iuran Kontingen</p>
                                <p class="text-[8px] text-slate-400 font-bold uppercase tracking-wider">Pendaftaran Institusi</p>
                            </div>
                            <p class="text-xs font-black text-slate-700">Rp {{ number_format($this->feeDetails['contingent_fee'], 0, ',', '.') }}</p>
                        </div>

                        <!-- Athlete Fees -->
                        @foreach($this->feeDetails['athlete_fees'] as $groupName => $fee)
                            <div class="flex justify-between items-start pb-4 border-b border-slate-50">
                                <div>
                                    <p class="text-[10px] font-black text-slate-800 uppercase leading-none mb-1">
                                        {{ $fee['count'] }}x Atlet {{ $groupName }}
                                    </p>
                                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-wider">
                                        @ Rp {{ number_format($fee['price'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="text-xs font-black text-slate-700">Rp {{ number_format($fee['total'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach

                        <!-- Unique Code -->
                        @if($this->feeDetails['unique_code'] > 0)
                            <div class="flex justify-between items-start pb-4 border-b border-slate-50">
                                <div>
                                    <p class="text-[10px] font-black text-orange-600 uppercase leading-none mb-1">Kode Unik</p>
                                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-wider">Identifikasi Transfer</p>
                                </div>
                                <p class="text-xs font-black text-orange-600">+{{ number_format($this->feeDetails['unique_code'], 0, ',', '.') }}</p>
                            </div>
                        @endif

                        <!-- Final Amount -->
                        <div class="pt-2">
                            <div class="flex justify-between items-center bg-slate-900 rounded-2xl p-4 text-white">
                                <span class="text-[9px] font-black uppercase tracking-widest text-white/50">Total Akhir</span>
                                <span class="text-sm font-black italic text-emerald-400">Rp {{ number_format($this->feeDetails['final_amount'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Proof -->
            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-[10px] font-black text-orange-400 uppercase tracking-[0.2em] mb-6">Bukti Pembayaran
                    </h2>
                    <div class="bg-white/5 rounded-3xl p-4 border border-white/10 mb-6 group cursor-pointer"
                        onclick="window.open('{{ asset('storage/' . $registration->transfer_proof_path) }}', '_blank')">
                        @if($registration->transfer_proof_path)
                            <img src="{{ asset('storage/' . $registration->transfer_proof_path) }}"
                                class="w-full h-48 object-cover rounded-2xl shadow-inner group-hover:scale-[1.02] transition-transform duration-500"
                                alt="Bukti Transfer">
                        @else
                            <div
                                class="h-48 flex items-center justify-center bg-white/5 rounded-2xl border-2 border-dashed border-white/10">
                                <p class="text-[10px] font-black text-white/20 uppercase tracking-widest italic">Belum ada
                                    lampiran</p>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-white/40 font-bold uppercase">Metode</span>
                            <span class="font-black text-orange-400">{{ $registration->payment_method }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-white/40 font-bold uppercase tracking-widest">Total Bayar</span>
                            <span class="text-xl font-black italic">Rp
                                {{ number_format($registration->final_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl"></div>
            </div>
        </div>

        <!-- Right Column: Officials & Athletes -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Officials Table -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <h2
                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                    Daftar Official Pendamping ({{ $registration->officials->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($registration->officials as $off)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ $off->name }}</h4>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.1em]">
                                    {{ $off->pivot?->role ?? 'OFFICIAL' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] font-mono text-slate-500 leading-none">{{ $off->phone }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Match Numbers & Athletes Grouping -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                <h2
                    class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-orange-600"></div>
                    Ringkasan Nomor Pertandingan ({{ count($groupedMatches) }})
                </h2>
                <div class="space-y-8">
                    @forelse($groupedMatches as $mId => $data)
                        <div
                            class="p-6 bg-slate-50 rounded-[2rem] border border-slate-200 relative group transition-all hover:bg-white hover:shadow-xl hover:shadow-slate-100">
                            <!-- Match Header -->
                            <div
                                class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-200/50">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-slate-900 text-white rounded-2xl flex items-center justify-center shadow-lg transform group-hover:rotate-6 transition-transform">
                                        <i class="fas fa-medal text-lg"></i>
                                    </div>
                                    <div>
                                        <h3
                                            class="text-sm font-black text-slate-800 uppercase tracking-tight leading-none mb-1">
                                            {{ $data['details']->name }}</h3>
                                        <p class="text-[10px] text-orange-600 font-bold uppercase tracking-wider">
                                            {{ $data['details']->ageGroup?->name }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Athletes in this match -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($data['athletes'] as $athlete)
                                    <div
                                        class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm group/athlete">
                                        <div
                                            class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 font-black text-xs uppercase group-hover/athlete:bg-orange-600 group-hover/athlete:text-white transition-all">
                                            {{ substr($athlete->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-xs font-black text-slate-700 uppercase truncate leading-none mb-1">
                                                {{ $athlete->name }}</h4>
                                            <p class="text-[9px] font-bold text-slate-400 font-mono tracking-wider">
                                                {{ $athlete->nik }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[8px] font-black text-slate-300 uppercase leading-none mb-1">Rank</p>
                                            <p class="text-[10px] font-black text-slate-600 uppercase">
                                                {{ $athlete->pivot?->rank ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <br>

                            <!-- Techniques for this match -->
                            @forelse($data['techniques'] as $tid)
                                <span
                                    class="px-2 py-1 bg-white text-orange-600 text-[8px] font-black rounded-full uppercase border border-orange-100 shadow-sm">
                                    {{ $allTechniques[$tid] ?? 'Unknown Tech' }}
                                </span>
                            @empty
                                <span class="text-[9px] text-slate-400 italic font-bold">Tanpa Teknik Khusus</span>
                            @endforelse
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <i class="fas fa-layer-group text-slate-200 text-4xl mb-4"></i>
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Belum ada nomor
                                pertandingan terpilih</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>