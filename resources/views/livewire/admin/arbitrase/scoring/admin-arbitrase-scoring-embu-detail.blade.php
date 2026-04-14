<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumbs & Header -->
        <div class="mb-8">
            <nav class="flex mb-4 text-xs font-bold uppercase tracking-widest text-slate-400 gap-2 items-center">
                <a href="{{ route('admin.arbitrase.scoring.index') }}" class="hover:text-amber-500 transition-colors">Scoring</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
                <span class="text-slate-800">Embu Detail</span>
            </nav>
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest rounded border border-indigo-100">EMBU</span>
                        <h1 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">{{ $matchNumber->name }}</h1>
                    </div>
                    <p class="text-sm text-slate-500 font-medium italic">
                        {{ $matchNumber->ageGroup->name ?? 'Semua Usia' }} • {{ $matchNumber->gender ?? 'Mix' }}
                    </p>
                </div>
                
                <div class="px-6 py-4 bg-slate-900 rounded-3xl shadow-xl shadow-slate-900/20 text-white min-w-[200px]">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Status Penilaian</p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-black">{{ $registrations->whereNotNull('score')->count() }} / {{ $registrations->count() }}</span>
                        <span class="text-[10px] font-bold px-2 py-1 bg-white/10 rounded-lg">PESERTA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Participants Table -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest w-20">Rank</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Peserta / Kontingen</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Nilai Juri (1-5)</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Total</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($registrations as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                @if($item['score']?->rank == 1)
                                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 shadow-sm border border-amber-200 ring-4 ring-amber-50">
                                        <i class="fas fa-trophy text-sm"></i>
                                    </div>
                                @elseif($item['score']?->rank == 2)
                                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 shadow-sm border border-slate-200 ring-4 ring-slate-50">
                                        <i class="fas fa-medal text-sm"></i>
                                    </div>
                                @elseif($item['score']?->rank == 3)
                                    <div class="w-10 h-10 bg-orange-50 rounded-full flex items-center justify-center text-orange-600 shadow-sm border border-orange-100 ring-4 ring-orange-50/50">
                                        <i class="fas fa-medal text-sm"></i>
                                    </div>
                                @else
                                    <span class="text-sm font-black text-slate-300 ml-4">#{{ $item['score']?->rank ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                        @foreach($item['athletes'] as $athlete)
                                            {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                                        @endforeach
                                    </span>
                                    <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1 mt-1 uppercase">
                                        <i class="fas fa-map-marker-alt text-[8px]"></i>
                                        {{ $item['contingent']->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @for($i=1; $i<=5; $i++)
                                        @php $val = $item['score']?->{"judge_$i"}; @endphp
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black {{ $val > 0 ? 'bg-slate-100 text-slate-700' : 'bg-slate-50 text-slate-200 border border-dashed border-slate-200' }}">
                                            {{ $val > 0 ? number_format($val, 0) : '-' }}
                                        </div>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-lg font-black {{ $item['score']?->total_score > 0 ? 'text-amber-500' : 'text-slate-200' }}">
                                    {{ number_format($item['score']?->total_score ?? 0, 1) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        wire:click="callParticipant({{ $item['id'] }})"
                                        class="inline-flex items-center gap-2 px-3 py-2 {{ $matchNumber->active_registration_id == $item['id'] ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }} rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                                    >
                                        <i class="fas fa-bullhorn {{ $matchNumber->active_registration_id == $item['id'] ? 'animate-bounce' : '' }}"></i>
                                        <span>{{ $matchNumber->active_registration_id == $item['id'] ? 'Tampil' : 'Panggil' }}</span>
                                    </button>

                                    <button 
                                        wire:click="openScoringModal({{ $item['id'] }})"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-900 text-slate-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all active:scale-95"
                                    >
                                        <i class="fas fa-edit"></i>
                                        <span>Input (Admin)</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Scoring Modal -->
        @if($showModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm">
                <div class="bg-white rounded-[2.5rem] w-full max-w-2xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">Input Nilai Juri</h2>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Metode: Sum of Middle 3</p>
                            </div>
                            <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-rose-500 transition-colors">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-5 gap-4 mb-10">
                            @for($i=1; $i<=5; $i++)
                                <div class="flex flex-col">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Wasit {{ $i }}</label>
                                    <input 
                                        type="number" 
                                        step="0.1"
                                        wire:model.live="scores.judge_{{ $i }}"
                                        class="w-full px-4 py-5 bg-slate-50 border border-slate-100 rounded-3xl text-center text-2xl font-black text-slate-800 focus:bg-white focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 outline-none transition-all"
                                    >
                                </div>
                            @endfor
                        </div>

                        <div class="bg-amber-500 rounded-3xl p-6 text-white flex items-center justify-between shadow-lg shadow-amber-500/20">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/70">Total Skor Akhir</p>
                                <p class="text-xs text-white/50 italic leading-tight">*Dihitung dari 3 nilai tengah</p>
                            </div>
                            <div class="text-5xl font-black tracking-tighter">
                                @php
                                    $raw = array_values($scores);
                                    sort($raw);
                                    $calculated = count($raw) === 5 ? ($raw[1] + $raw[2] + $raw[3]) : 0;
                                @endphp
                                {{ number_format($calculated, 1) }}
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button 
                                wire:click="$set('showModal', false)"
                                class="flex-1 py-4 bg-slate-50 hover:bg-slate-100 text-slate-400 font-black uppercase tracking-widest rounded-2xl transition-all"
                            >
                                Batal
                            </button>
                            <button 
                                wire:click="saveScore"
                                class="flex-[2] py-4 bg-slate-900 hover:bg-amber-500 text-white font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-slate-900/10 transition-all active:scale-95"
                            >
                                Simpan Nilai
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
