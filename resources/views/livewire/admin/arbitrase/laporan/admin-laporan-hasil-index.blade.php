<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 text-[13px] font-black uppercase tracking-widest text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">
                    <i class="fas fa-medal text-amber-500"></i> Laporan Hasil Juara
                </span>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Rekapitulasi Hasil Pertandingan</h1>
            <p class="text-sm text-slate-500 font-medium mt-0.5">Embu & Randori · Generate & simpan juara ke database</p>
        </div>

        <button wire:click="generateAllResults" wire:confirm="Generate semua hasil juara ke database? Data lama akan ditimpa."
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-black uppercase tracking-widest text-sm transition-all shadow-lg shadow-emerald-600/20 disabled:opacity-60 shrink-0">
            <span wire:loading.remove wire:target="generateAllResults">
                <i class="fas fa-database mr-1"></i> Generate Semua
            </span>
            <span wire:loading wire:target="generateAllResults">
                <i class="fas fa-spinner fa-spin mr-1"></i> Memproses...
            </span>
        </button>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Cari</label>
            <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm font-medium transition-all"
                    placeholder="Nama nomor pertandingan...">
            </div>
        </div>
        <div class="w-full md:w-1/5">
            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kategori</label>
            <select wire:model.live="draftTypeFilter"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                <option value="">Semua</option>
                <option value="Embu">Embu</option>
                <option value="Randori">Randori</option>
            </select>
        </div>
        <div class="w-full md:w-1/5">
            <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kelompok Umur</label>
            <select wire:model.live="ageGroupFilter"
                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                <option value="">Semua</option>
                @foreach($ageGroups as $ag)
                    <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-auto">
            <button onclick="window.print()"
                class="w-full md:w-auto px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center justify-center gap-2">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 gap-6">
        @forelse($matchNumbers as $match)
            @php
                $savedResults = $match->saved_results ?? collect();
                $computedJuara = $match->computed_juara ?? [];
                $isSaved = $savedResults->isNotEmpty();
                $hasResult = !empty($computedJuara);
            @endphp

            <div class="bg-white rounded-2xl border {{ $isSaved ? 'border-emerald-200' : 'border-slate-200' }} shadow-sm overflow-hidden">
                {{-- Card Header --}}
                <div class="px-5 py-4 bg-gradient-to-r from-slate-800 to-slate-700 flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <span class="text-[10px] font-black px-2 py-0.5 rounded uppercase tracking-widest {{ $match->draft_type === 'Embu' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}">
                                {{ $match->draft_type }}
                            </span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $match->ageGroup?->name }}</span>
                        </div>
                        <h3 class="text-sm font-black text-white uppercase tracking-tight truncate">{{ $match->name }}</h3>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if($isSaved)
                            <span class="inline-flex items-center gap-1 text-[10px] font-black text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-2 py-1 rounded-lg uppercase tracking-widest">
                                <i class="fas fa-check-circle"></i> Tersimpan
                            </span>
                        @endif
                        @if($hasResult)
                            <button wire:click="openGenerateModal({{ $match->id }}, '{{ addslashes($match->name) }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white text-[11px] font-black uppercase tracking-widest rounded-lg transition-all border border-white/10">
                                <i class="fas fa-save"></i> {{ $isSaved ? 'Update' : 'Generate' }}
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Juara Grid --}}
                @if($hasResult)
                    <div class="p-4 grid grid-cols-1 gap-3">
                        @foreach([1=>'🥇 Juara 1',2=>'🥈 Juara 2',3=>'🥉 Juara 3',4=>'🥉 Juara 3B'] as $rank => $label)
                            @php $data = $computedJuara[$rank] ?? null; @endphp
                            <div class="rounded-xl border {{ $data ? 'bg-amber-50/40 border-amber-200' : 'bg-slate-50 border-slate-100' }} p-3">
                                <p class="text-[10px] font-black uppercase tracking-widest {{ $data ? 'text-amber-600' : 'text-slate-400' }} mb-1.5">{{ $label }}</p>
                                @if($data)
                                    <p class="text-[12px] font-black text-slate-800 uppercase leading-snug">{{ $data['athlete_names'] }}</p>
                                    <p class="text-[11px] font-bold text-indigo-600 uppercase mt-0.5 truncate">{{ $data['contingent_name'] }}</p>
                                    @if($data['accumulated_score'] > 0)
                                        <p class="text-[11px] font-black text-slate-500 mt-1">
                                            <span class="text-amber-600">{{ number_format($data['accumulated_score'], 1) }}</span>
                                            @if($match->draft_type === 'Embu')
                                                <span class="text-slate-400 ml-1">P:{{ number_format($data['penyisihan_score'],1) }} + F:{{ number_format($data['final_score'],1) }}</span>
                                            @endif
                                        </p>
                                    @endif
                                @else
                                    <p class="text-[11px] font-bold text-slate-400 italic">-</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- If saved, show confirmed info --}}
                    @if($isSaved)
                        @php $firstResult = $savedResults->first(); @endphp
                        <div class="px-4 pb-3">
                            <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-xl px-3 py-2">
                                <i class="fas fa-database text-emerald-500 text-xs"></i>
                                <p class="text-[11px] font-bold text-emerald-700">
                                    Tersimpan {{ $firstResult->confirmed_at?->diffForHumans() }}
                                    @if($firstResult->generated_by) · oleh {{ $firstResult->generated_by }}@endif
                                </p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="py-10 text-center">
                        <i class="fas fa-trophy text-slate-200 text-3xl mb-3 block"></i>
                        <p class="text-sm font-black text-slate-300 uppercase tracking-widest">Belum Ada Hasil</p>
                        <p class="text-xs text-slate-400 mt-1">Penilaian belum diinput</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-2 bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
                <i class="fas fa-folder-open text-slate-300 text-5xl mb-4"></i>
                <h3 class="text-lg font-black text-slate-700">Tidak Ada Data</h3>
                <p class="text-slate-500 font-medium text-sm">Coba sesuaikan filter pencarian.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $matchNumbers->links('livewire.admin.pagination') }}</div>

    {{-- Confirm Generate Single Modal --}}
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            wire:click.self="$set('showConfirmModal', false)">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-6 animate-in zoom-in-95 duration-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-database text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest">Generate Hasil</p>
                        <p class="text-base font-black text-slate-800">{{ $generateMatchName }}</p>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-5">
                    <p class="text-sm font-bold text-amber-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        Data juara yang tersimpan sebelumnya akan ditimpa dengan hasil terbaru.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button wire:click="$set('showConfirmModal', false)"
                        class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-900 font-black text-sm uppercase tracking-widest rounded-2xl transition-all">
                        Batal
                    </button>
                    <button wire:click="generateSingleResult" wire:loading.attr="disabled"
                        class="flex-1 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm uppercase tracking-widest rounded-2xl transition-all shadow-lg disabled:opacity-60">
                        <span wire:loading.remove wire:target="generateSingleResult">
                            <i class="fas fa-save mr-1"></i> Simpan
                        </span>
                        <span wire:loading wire:target="generateSingleResult">
                            <i class="fas fa-spinner fa-spin"></i>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
