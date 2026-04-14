<div class="min-h-screen bg-slate-50 p-4 md:p-8">
    <div class="max-w-full mx-auto">
        <!-- Status Bar -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                    <i class="fas fa-gavel"></i>
                </div>
                <div>
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-tight">Wasit Juri</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $referee->user->name }}</p>
                </div>
            </div>
            @if($judgeIndex)
                <div class="px-4 py-2 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-slate-900/20">
                    JURI {{ $judgeIndex }}
                </div>
            @endif
        </div>

        @if($activeMatch)
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <!-- Match Header -->
                <div class="p-8 bg-slate-900 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 bg-orange-500 text-white text-[9px] font-black uppercase tracking-widest rounded-md uppercase">LIVE ON COURT</span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ strtoupper($activeMatch->draft_type) }}</span>
                        </div>
                        <h1 class="text-2xl font-black uppercase tracking-tighter mb-1">{{ $activeMatch->name }}</h1>
                        <p class="text-xs text-slate-400 font-medium italic opacity-80">Mohon berikan penilaian terbaik Anda secara objektif.</p>
                    </div>
                    <i class="fas fa-trophy absolute -right-4 -bottom-4 text-8xl text-white/5 -rotate-12"></i>
                </div>

                <div class="p-4 md:p-8">
                    @if($activeMatch->draft_type === 'embu')
                        @include('livewire.referee.partials.embu-form')
                    @else
                        @include('livewire.referee.partials.randori-form')
                    @endif

                    <!-- Common Footer Actions -->
                    <div class="mt-8 space-y-4">
                        <div class="bg-amber-500 rounded-[2rem] p-8 text-white flex items-center justify-between shadow-xl shadow-amber-500/20">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/70">Total Skor Terhitung</p>
                                <p class="text-xs text-white/50 italic leading-tight">Terhitung secara otomatis</p>
                            </div>
                            <div class="text-5xl font-black tracking-tighter">
                                {{ number_format($totalScore, 1) }}
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Catatan Wasit (Opsional)</label>
                            <textarea 
                                wire:model="notes" 
                                class="w-full bg-white border border-slate-200 rounded-2xl p-4 text-sm font-medium focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none"
                                rows="3"
                                placeholder="Ketik catatan di sini..."
                            ></textarea>
                        </div>

                        <div class="flex flex-col md:flex-row gap-4">
                            <button 
                                wire:click="resetForm" 
                                class="w-full md:w-1/3 py-5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-black uppercase tracking-widest rounded-[2rem] transition-all active:scale-95"
                            >
                                <i class="fas fa-undo mr-2"></i> Reset
                            </button>
                            <button 
                                wire:click="submitScore" 
                                class="w-full md:w-2/3 py-5 bg-orange-600 hover:bg-orange-700 text-white font-black uppercase tracking-widest rounded-[2rem] shadow-xl shadow-orange-600/20 transition-all active:scale-95 flex items-center justify-center gap-3"
                            >
                                <i class="fas fa-paper-plane text-xs"></i>
                                <span>Kirim Nilai Final</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Placeholder same as before -->
            <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200 animate-pulse">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="fas fa-broadcast-tower text-3xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Menunggu Pertandingan</h3>
                <p class="text-xs text-slate-500 font-medium mt-2 max-w-[200px] mx-auto leading-relaxed">
                    Belum ada pertandingan yang dipanggil ke lapangan oleh Panitera.
                </p>
            </div>
        @endif

        <div class="mt-12 flex justify-center">
            <button wire:click="loadActiveMatch" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-orange-600 transition-colors">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh Status</span>
            </button>
        </div>
    </div>
</div>