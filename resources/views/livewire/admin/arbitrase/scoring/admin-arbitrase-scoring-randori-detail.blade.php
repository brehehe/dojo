<div class="p-6">
    <div class="max-w-full mx-auto">
        <!-- Header -->
        <div class="mb-10">
            <nav class="flex mb-4 text-xs font-bold uppercase tracking-widest text-slate-400 gap-2 items-center">
                <a href="{{ route('admin.arbitrase.scoring.index') }}" class="hover:text-amber-500 transition-colors">Scoring</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-slate-800">Randori Bracket</span>
            </nav>
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-2 py-0.5 bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded border border-rose-100">RANDORI</span>
                        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">{{ $matchNumber->name }}</h1>
                    </div>
                    <p class="text-sm text-slate-500 font-medium italic">
                        Klik pada kotak pertandingan untuk memasukkan hasil skor.
                    </p>
                </div>
            </div>
        </div>

        <!-- Scrollable Bracket Container -->
        <div class="w-full overflow-x-auto pb-20 no-scrollbar">
            @if(isset($drawingData['rounds']) && count($drawingData['rounds']) > 0)
                <div class="flex gap-20 items-center min-w-max px-10">
                    @foreach($drawingData['rounds'] as $roundIndex => $matches)
                        @php 
                            $roundNames = ['PENYISIHAN', 'SEPEREMPAT FINAL', 'SEMI FINAL', 'FINAL'];
                            $roundName = $roundNames[$roundIndex] ?? 'RONDE ' . ($roundIndex + 1);
                            if ($roundIndex == count($drawingData['rounds']) - 1) $roundName = 'FINAL';
                            elseif ($roundIndex == count($drawingData['rounds']) - 2) $roundName = 'SEMI FINAL';
                        @endphp

                    <div class="flex flex-col gap-10">
                        <h3 class="text-center text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] mb-4">{{ $roundName }}</h3>
                        <div class="flex flex-col justify-around h-full gap-8">
                            @foreach($matches as $matchIndex => $match)
                                @php 
                                    $nodeKey = $roundIndex . '_' . $matchIndex;
                                    $result = $results[$nodeKey] ?? null;
                                @endphp
                                
                                <div 
                                    wire:click="openMatchModal({{ $roundIndex }}, {{ $matchIndex }})"
                                    class="relative group cursor-pointer"
                                >
                                    <!-- Match Box -->
                                    <div class="w-72 bg-white rounded-2xl border-2 {{ $result ? 'border-amber-400 shadow-lg shadow-amber-500/10' : 'border-slate-100 hover:border-slate-300 shadow-sm' }} overflow-hidden transition-all active:scale-95">
                                        <!-- Red Corner -->
                                        <div class="p-3 border-b border-slate-50 flex items-center justify-between {{ $result && $result->winner_color === 'red' ? 'bg-rose-50/50' : '' }}">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <div class="w-2 h-2 rounded-full bg-rose-500 shadow-sm shadow-rose-500/30"></div>
                                                <span class="text-[11px] font-black uppercase tracking-tight truncate {{ $result && $result->winner_color === 'red' ? 'text-rose-600' : 'text-slate-700' }}">
                                                    {{ $match['athlete1']['name'] ?? 'TBD' }}
                                                </span>
                                            </div>
                                            @if($result && $result->winner_color === 'red')
                                                <span class="text-[8px] font-black bg-rose-500 text-white px-1.5 py-0.5 rounded uppercase">WIN</span>
                                            @endif
                                            @if($result)
                                                <span class="text-xs font-black text-slate-800 ml-2">{{ number_format($result->score_red, 0) }}</span>
                                            @endif
                                        </div>

                                        <!-- VS Separator -->
                                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                                            <div class="w-6 h-6 rounded-full bg-slate-900 border-2 border-white flex items-center justify-center text-[8px] font-black text-white shadow-sm italic">VS</div>
                                        </div>

                                        <!-- Blue Corner -->
                                        <div class="p-3 flex items-center justify-between {{ $result && $result->winner_color === 'blue' ? 'bg-indigo-50/50' : '' }}">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-sm shadow-indigo-500/30"></div>
                                                <span class="text-[11px] font-black uppercase tracking-tight truncate {{ $result && $result->winner_color === 'blue' ? 'text-indigo-600' : 'text-slate-700' }}">
                                                    {{ $match['athlete2']['name'] ?? 'TBD' }}
                                                </span>
                                            </div>
                                            @if($result && $result->winner_color === 'blue')
                                                <span class="text-[8px] font-black bg-indigo-500 text-white px-1.5 py-0.5 rounded uppercase">WIN</span>
                                            @endif
                                            @if($result)
                                                <span class="text-xs font-black text-slate-800 ml-2">{{ number_format($result->score_blue, 0) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Match Number Badge -->
                                    <div class="absolute -top-3 -left-3 px-2 py-0.5 bg-slate-100 text-slate-400 text-[9px] font-bold rounded-md border border-slate-200">
                                        M-{{ $matchIndex + 1 }}
                                    <!-- Action Buttons -->
                                    <div class="flex flex-col gap-1 ml-2">
                                        <button 
                                            wire:click="callMatch({{ $roundIndex }}, {{ $matchIndex }})"
                                            class="w-8 h-8 rounded-full flex items-center justify-center {{ $matchNumber->active_bracket_node === ($roundIndex . '_' . $matchIndex) ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'bg-slate-100 text-slate-400 hover:bg-amber-100 hover:text-amber-600' }} transition-all"
                                            title="Panggil Wasit untuk pertandingan ini"
                                        >
                                            <i class="fas fa-bullhorn text-[10px]"></i>
                                        </button>
                                        <button 
                                            wire:click="openMatchModal({{ $roundIndex }}, {{ $matchIndex }})"
                                            class="w-8 h-8 rounded-full flex items-center justify-center bg-slate-900 text-white hover:bg-orange-600 transition-all shadow-md active:scale-95"
                                            title="Input Nilai Hasil Akhir"
                                        >
                                            <i class="fas fa-edit text-[10px]"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 bg-white border-2 border-dashed border-slate-200 rounded-[2.5rem] mx-10">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-sitemap text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Bagan Belum Siap</h3>
                    <p class="text-xs text-slate-500 mt-2">Data bagan sedang diproses atau belum di-generate dengan benar.</p>
                </div>
            @endif
        </div>

        <!-- Result Modal -->
        @if($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] w-full max-w-xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Hasil Pertandingan</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Pilihlah Atlet yang menang</p>
                            </div>
                            <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="flex gap-6 mb-10">
                            <!-- Red Side -->
                            <div class="flex-1 text-center">
                                <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-3xl flex items-center justify-center mx-auto mb-4 border-4 border-rose-50 shadow-sm">
                                    <i class="fas fa-user text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-black text-slate-800 uppercase mb-4 h-10 flex items-center justify-center line-clamp-2 leading-tight px-2">
                                    {{ $activeMatch['data']['athlete1']['name'] ?? '-' }}
                                </h3>
                                
                                <div class="mb-4">
                                    <input type="number" wire:model="scoreRed" class="w-20 px-3 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-center text-xl font-black focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none transition-all">
                                </div>

                                <button 
                                    wire:click="selectWinner({{ $activeMatch['round'] }}, {{ $activeMatch['match'] }}, 'red')"
                                    class="w-full py-4 bg-rose-500 hover:bg-rose-600 text-white font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-rose-500/20 transition-all active:scale-95"
                                >
                                    Pilih Menang
                                </button>
                            </div>

                            <div class="flex flex-col justify-center text-slate-300">
                                <span class="text-xs font-black italic">VS</span>
                            </div>

                            <!-- Blue Side -->
                            <div class="flex-1 text-center">
                                <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-4 border-4 border-indigo-50 shadow-sm">
                                    <i class="fas fa-user text-2xl"></i>
                                </div>
                                <h3 class="text-sm font-black text-slate-800 uppercase mb-4 h-10 flex items-center justify-center line-clamp-2 leading-tight px-2">
                                    {{ $activeMatch['data']['athlete2']['name'] ?? '-' }}
                                </h3>

                                <div class="mb-4">
                                    <input type="number" wire:model="scoreBlue" class="w-20 px-3 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-center text-xl font-black focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all">
                                </div>

                                <button 
                                    wire:click="selectWinner({{ $activeMatch['round'] }}, {{ $activeMatch['match'] }}, 'blue')"
                                    class="w-full py-4 bg-indigo-500 hover:bg-indigo-600 text-white font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-indigo-500/20 transition-all active:scale-95"
                                >
                                    Pilih Menang
                                </button>
                            </div>
                        </div>

                        <button 
                            wire:click="$set('showModal', false)"
                            class="w-full py-4 bg-slate-50 hover:bg-slate-100 text-slate-400 font-black uppercase tracking-widest rounded-2xl transition-all"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
