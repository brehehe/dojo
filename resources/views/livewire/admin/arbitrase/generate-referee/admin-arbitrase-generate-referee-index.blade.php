<div class="space-y-8 animate-in fade-in duration-700 h-full" x-data="{ 
    showModal: @entangle('assigningMatchId').live,
    searchWasit: @entangle('searchReferee').live
}">
    
    <!-- Title & Stats -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Panel Wasit</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="w-8 h-1 bg-amber-500 rounded-full"></span>
                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Arbitrase / Pertandingan</span>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <button wire:click="autoGenerateAllReferees" 
                onclick="confirm('Sistem akan secara acak memilih 5 Wasit untuk SEMUA pertandingan yang belum memiliki panel wasit. Lanjutkan?') || event.stopImmediatePropagation()"
                class="w-full sm:w-auto px-6 py-2.5 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-700 shadow-lg shadow-rose-600/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                <i class="fas fa-magic"></i> Generate Wasit Otomatis
            </button>
            <div class="relative w-full md:w-64">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" wire:model.live="searchMatch" placeholder="Cari Pertandingan..." 
                    class="w-full bg-white border border-slate-200 text-sm font-semibold text-slate-700 pl-10 pr-4 py-2.5 rounded-xl shadow-sm focus:border-amber-400 focus:ring-4 focus:ring-amber-400/10 transition-all placeholder:text-slate-300 placeholder:font-medium outline-none">
            </div>
        </div>
    </div>

    @if(session('message'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-500"></i>
            <span class="text-xs font-bold">{{ session('message') }}</span>
        </div>
    @endif

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full pb-12">
        @forelse($paginatedMatches as $match)
            <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
                
                <!-- Background Decoration -->
                <div class="absolute -right-12 -top-12 w-32 h-32 bg-slate-50 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500 pointer-events-none"></div>

                <div>
                    <!-- Headers -->
                    <div class="flex items-start justify-between gap-4 mb-5 relative z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg
                                    @if(strtolower($match->draft_type) == 'randori') bg-red-50 text-red-600 
                                    @else bg-amber-50 text-amber-600 @endif">
                                    <i class="fas @if(strtolower($match->draft_type) == 'randori') fa-fire-alt @else fa-layer-group @endif mr-1 text-[8px]"></i>
                                    {{ strtoupper($match->draft_type) }}
                                </span>
                                <span class="flex items-center gap-1.5 text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded-lg bg-slate-50 text-slate-500">
                                    <i class="fas @if($match->gender == 'Male') fa-mars text-blue-500 @elseif($match->gender == 'Female') fa-venus text-pink-500 @else fa-venus-mars text-purple-500 @endif text-[8px]"></i>
                                    {{ $match->gender == 'Male' ? 'Putra' : ($match->gender == 'Female' ? 'Putri' : 'Campuran') }}
                                </span>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $match->name }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $match->ageGroup->name ?? 'Unknown Age' }}</p>
                        </div>
                    </div>

                    <!-- Referees Display -->
                    <div class="mt-4 relative z-10 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Panel Wasit Bertugas:</p>
                            
                            @if($match->referees->count() > 0)
                                <div class="flex flex-col gap-2">
                                    @foreach($match->referees as $rIdx => $ref)
                                        <div class="flex items-center gap-3 bg-slate-50 border border-slate-100 rounded-xl pl-3 pr-4 py-2 hover:bg-white transition-colors">
                                            <div class="w-6 h-6 rounded-md bg-white text-slate-400 flex items-center justify-center border border-slate-100 text-[10px] font-black shrink-0 shadow-sm">
                                                {{ $rIdx + 1 }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-[11px] font-black text-slate-700 truncate tracking-tight uppercase">{{ $ref->user->name }}</p>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $ref->certification_level }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center gap-3 bg-rose-50 border border-rose-100/50 rounded-2xl px-4 py-3">
                                    <div class="w-6 h-6 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center shrink-0">
                                        <i class="fas fa-exclamation text-[10px]"></i>
                                    </div>
                                    <p class="text-[10px] font-bold text-rose-600 uppercase tracking-widest">Panel wasit belum dipilih (Min 5)</p>
                                </div>
                            @endif
                        </div>

                        <!-- Participants Info -->
                        <div class="border-l border-slate-100 pl-4">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Daftar Kontestan:</p>
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl overflow-hidden shadow-inner">
                                <table class="w-full text-left">
                                    <thead class="bg-slate-100/50">
                                        <tr>
                                            <th class="px-3 py-1.5 text-[8px] font-black text-slate-500 uppercase tracking-widest">No</th>
                                            <th class="px-3 py-1.5 text-[8px] font-black text-slate-500 uppercase tracking-widest">Nama Atlet</th>
                                            <th class="px-3 py-1.5 text-[8px] font-black text-slate-500 uppercase tracking-widest">Kontingen</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($match->athletes as $aIdx => $ath)
                                            <tr>
                                                <td class="px-3 py-2 text-[9px] font-bold text-slate-400">{{ $aIdx + 1 }}</td>
                                                <td class="px-3 py-2 text-[10px] font-black text-slate-700 uppercase tracking-tight">{{ $ath->name }}</td>
                                                <td class="px-3 py-2 text-[9px] font-bold text-amber-600 uppercase">{{ $ath->registrations->first()->contingent->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-slate-50 flex items-center justify-between relative z-10">
                    <div class="flex items-center gap-2 text-[10px] font-bold text-slate-400">
                        <i class="fas fa-users text-slate-300"></i>
                        <span>{{ $match->referees->count() }} Terpilih</span>
                    </div>
                    
                    <button wire:click="openAssignModal({{ $match->id }})" 
                        class="text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl transition-all active:scale-95
                        @if($match->referees->count() >= 5) bg-slate-900 text-white hover:bg-slate-800 shadow-md shadow-slate-900/10 
                        @else bg-amber-500 text-white hover:bg-amber-600 shadow-md shadow-amber-500/20 @endif">
                        <i class="fas fa-user-plus mr-1 text-[9px]"></i> {{ $match->referees->count() > 0 ? 'Edit Panel Wasit' : 'Pilih Wasit' }}
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 text-center">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-gavel text-3xl"></i>
                </div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Belum Ada Pertandingan</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-2 max-w-sm mx-auto">Pastikan Anda telah melakukan Generate Drawing di menu Technical Meeting sebelum menetapkan panel wasit.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($paginatedMatches->hasPages())
        <div class="mt-4 px-8 py-6 bg-white shadow-sm border border-slate-100 rounded-[28px]">
            {{ $paginatedMatches->links() }}
        </div>
    @endif

    <!-- Assignment Drawer / Modal -->
    <div x-show="showModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div x-show="showModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>
      
        <div class="fixed inset-0 z-10 overflow-hidden">
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
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight" id="modal-title">Penugasan Wasit (Checklist)</h3>
                                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mt-0.5">Pilih Minimal 5 Wasit</p>
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
                                <span class="w-2 h-2 rounded-full" :class="[ @js(count($selectedReferees)) >= 5 ? 'bg-emerald-500' : 'bg-rose-500' ]"></span>
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
                                        <p class="text-[9px] text-slate-500 font-semibold uppercase tracking-wider truncate"><i class="fas fa-id-badge mr-1 text-slate-300"></i>{{ $ref->license_number }}</p>
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
