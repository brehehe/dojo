<div class="space-y-8 animate-in fade-in duration-700 h-full" x-data="{ 
    showModal: @entangle('assigningBlock').live,
    searchWasit: @entangle('searchReferee').live
}">
    
    <!-- Title & Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Panel Wasit</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-8 h-1 bg-amber-500 rounded-full"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Arbitrase / Shift Lapangan</span>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <button wire:click="autoGenerateAllReferees" 
                onclick="confirm('Sistem akan secara acak merekayasa Panel Wasit dan Dewan Arbitrase untuk seluruh Lapangan & Shift yang aktif. Lanjutkan?') || event.stopImmediatePropagation()"
                class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-600/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-magic"></i> Generate Wasit Otomatis
            </button>
        </div>
    </div>

    @if(session('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span class="text-xs font-bold">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Main Grid -->
    <div class="space-y-6 w-full pb-12">
        @forelse($paginatedShifts as $shift)
            <div class="bg-white border border-slate-100 rounded-3xl p-0 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
                
                <!-- Shift Header -->
                <div class="bg-slate-50 border-b border-slate-100 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center shadow-sm text-slate-800">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">
                                {{ \Carbon\Carbon::parse($shift->rundown->date)->isoFormat('dddd, D MMMM Y') }} | {{ $shift->sessionTime->name }}
                            </h3>
                            <p class="text-[10px] text-slate-500 font-bold tracking-widest mt-0.5">
                                <i class="fas fa-clock mr-1 text-slate-300"></i> 
                                {{ $shift->sessionTime->start_time ? $shift->sessionTime->start_time->format('H:i') : '--:--' }} - 
                                {{ $shift->sessionTime->end_time ? $shift->sessionTime->end_time->format('H:i') : '--:--' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Referee Table per Court -->
                <div class="p-6 overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                        <thead class="bg-slate-800 text-white">
                            <tr>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Court</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"><i class="fas fa-star text-amber-400 mr-1"></i> Wasit Nasional (Ketua)</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"><i class="fas fa-user-tie text-blue-300 mr-1"></i> Wasit Daerah 1</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"><i class="fas fa-user-tie text-blue-300 mr-1"></i> Wasit Daerah 2</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"><i class="fas fa-id-badge text-emerald-300 mr-1"></i> Wasit Pembantu 1</th>
                                <th class="px-4 py-3 text-[10px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap"><i class="fas fa-id-badge text-emerald-300 mr-1"></i> Wasit Pembantu 2</th>
                                <th class="px-3 py-3 text-[10px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center" width="50">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($shift->active_courts as $courtItem)
                                @php
                                    $panel = $shift->assigned_referees->where('court_id', $courtItem->court_id)->keyBy('judge_index');
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4 text-xs font-black text-slate-800 border-r border-slate-200 text-center uppercase">
                                        {{ $courtItem->court->name ?? 'Unknown' }}
                                    </td>
                                    
                                    @for($i = 1; $i <= 5; $i++)
                                        <td class="px-4 py-3 border-r border-slate-200 align-top">
                                            @if(isset($panel[$i]))
                                                <div class="text-[11px] font-black text-slate-800 uppercase leading-snug">{{ $panel[$i]->referee->user->name }}</div>
                                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                                    {{ $panel[$i]->referee->city ?? $panel[$i]->referee->province ?? '-' }}
                                                </div>
                                            @else
                                                <div class="text-[10px] font-bold text-slate-300 italic">Belum Diatur</div>
                                            @endif
                                        </td>
                                    @endfor
                                    
                                    <td class="px-3 py-3 text-center align-middle">
                                        <button wire:click="openAssignModal({{ $shift->rundown_id }}, {{ $shift->session_time_id }}, {{ $courtItem->court_id }})" 
                                            class="w-8 h-8 rounded-lg flex items-center justify-center transition-all bg-slate-100 text-slate-500 hover:bg-amber-100 hover:text-amber-600 active:scale-95 duration-200" title="Atur Panel">
                                            <i class="fas fa-users-cog"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @php
                        $dewan = $shift->assigned_referees->where('court_id', null)->where('judge_index', 0)->first();
                    @endphp
                    <div class="mt-4 bg-slate-100 border border-slate-200 rounded-xl px-5 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-university text-slate-400"></i>
                            <span class="text-[11px] font-black text-slate-600 uppercase tracking-widest">Dewan Arbitrase:</span>
                            @if($dewan)
                                <span class="text-[11px] font-black text-indigo-700 capitalize">{{ $dewan->referee->user->name }} ({{ $dewan->referee->city ?? '-' }})</span>
                            @else
                                <span class="text-[11px] font-bold text-rose-500 italic">Belum Diatur</span>
                            @endif
                        </div>
                        <button wire:click="openAssignModal({{ $shift->rundown_id }}, {{ $shift->session_time_id }}, null)" 
                            class="px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 text-[9px] font-black transition-all active:scale-95 uppercase tracking-widest">
                            <i class="fas fa-edit mr-1"></i> Atur
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-calendar-times text-3xl"></i>
                </div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Belum Ada Jadwal</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-2 max-w-sm mx-auto">Sistem belum menemukan data pertandingan. Silakan lakukan Generate Drawing di menu Technical Meeting.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($paginatedShifts->hasPages())
        <div class="mt-4 px-8 py-6 bg-white shadow-sm border border-slate-100 rounded-[28px]">
            {{ $paginatedShifts->links() }}
        </div>
    @endif

    <!-- Assignment Drawer / Modal -->
    <div x-show="showModal" style="display: none;" class="relative z-[200]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
      
        <div class="fixed inset-0 z-[210] overflow-hidden">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Modal Panel -->
                <div x-show="showModal" 
                     @click.away="$wire.closeAssignModal()"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-[32px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-100 flex flex-col max-h-[85vh]">
                    
                    <!-- Header -->
                    <div class="bg-white px-6 py-5 border-b border-slate-100 flex items-center justify-between shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                <i class="fas fa-users-cog text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight" id="modal-title">Penugasan {{ $isDewanArbitraseMode ? 'Dewan Arbitrase' : 'Panel Wasit' }}</h3>
                                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-0.5">{{ $isDewanArbitraseMode ? 'Pilih 1 Wasit' : 'Pilih Minimal 5 Wasit' }}</p>
                            </div>
                        </div>
                        <button wire:click="closeAssignModal" class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Search Box -->
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 shrink-0">
                        <div class="relative w-full">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" wire:model.live="searchReferee" placeholder="Cari berdasarkan nama atau no lisensi..." 
                                class="w-full bg-white border border-slate-200 text-xs font-semibold text-slate-700 pl-10 pr-4 py-3 rounded-xl shadow-sm focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 transition-all placeholder:text-slate-300 outline-none">
                        </div>

                        <!-- Selected Counter & Error -->
                        <div class="flex items-center justify-between mt-3 px-1">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" :class="[ @js(count($selectedReferees)) >= (@js($isDewanArbitraseMode) ? 1 : 5) ? 'bg-emerald-500' : 'bg-rose-500' ]"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">
                                    {{ count($selectedReferees) }} Terpilih
                                </span>
                            </div>
                            @error('referees') <span class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</span> @enderror
                        </div>
                    </div>
      
                    <!-- Checklist Body -->
                    <div class="bg-white px-6 py-4 overflow-y-auto flex-1 custom-scrollbar">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pb-8">
                            @forelse($allReferees as $ref)
                                <label class="relative flex items-start gap-3 p-3 border rounded-2xl cursor-pointer hover:bg-slate-50 transition-all group select-none
                                    {{ in_array($ref->id, $selectedReferees) ? 'border-amber-400 bg-amber-50/30' : 'border-slate-100' }}">
                                    
                                    <div class="flex items-center h-5 mt-0.5">
                                        <input type="checkbox" wire:model="selectedReferees" value="{{ $ref->id }}" 
                                            class="w-4 h-4 text-amber-500 border-slate-300 rounded focus:ring-amber-500 cursor-pointer">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <p class="text-[11px] font-black text-slate-800 truncate">{{ $ref->user->name }}</p>
                                            <span class="text-[8px] font-bold text-white bg-slate-800 px-1.5 py-0.5 rounded uppercase tracking-wider">{{ $ref->gender == 'L' ? 'L' : 'P' }}</span>
                                        </div>
                                        <p class="text-[9px] text-slate-500 font-semibold uppercase tracking-wider truncate"><i class="fas fa-id-badge mr-1 text-slate-300"></i>{{ $ref->city ?? $ref->province ?? '-' }}</p>
                                        <div class="mt-1.5">
                                            <span class="inline-block text-[8px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-md px-2 py-0.5 uppercase tracking-widest">{{ $ref->certification_level }}</span>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="col-span-full py-8 text-center text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                    Tidak ada wasit ditemukan
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Footer / Save Button -->
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 shrink-0">
                        <button type="button" wire:click="closeAssignModal" class="px-5 py-2.5 text-[10px] font-black text-slate-500 uppercase tracking-widest rounded-xl hover:bg-slate-200 hover:text-slate-700 transition cursor-pointer">Batal</button>
                        <button type="button" wire:click="saveReferees" 
                            wire:loading.attr="disabled"
                            wire:target="saveReferees"
                            class="px-5 py-2.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-800 active:scale-95 shadow-md transition disabled:opacity-50 flex items-center gap-2">
                            <span wire:loading.remove wire:target="saveReferees"><i class="fas fa-save text-[9px]"></i> Simpan Penugasan</span>
                            <span wire:loading wire:target="saveReferees"><i class="fas fa-spinner fa-spin text-[9px]"></i> Menyimpan...</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
