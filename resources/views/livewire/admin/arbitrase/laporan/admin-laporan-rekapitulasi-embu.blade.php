<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Rekapitulasi Laporan Embu</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Hasil Perolehan Skor &
                Ranking</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportExcel"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button onclick="window.print()"
                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div class="lg:col-span-1">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Cari Atlet</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium"
                    placeholder="Search name...">
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kelompok
                    Umur</label>
                <x-select wire:model.live="ageGroupFilter" placeholder="Pilih Kelompok Umur">
                    <option value="">Semua</option>
                    @foreach($ageGroups as $ag)
                        <option wire:key="ag-{{ $ag->id }}" value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Nomor
                    Pertandingan</label>
                <x-select wire:model.live="matchNumberFilter" placeholder="Pilih Nomor Pertandingan" wire:key="mn-filter-{{ count($matchNumbersForFilter) }}">
                    <option value="">Semua</option>
                    @foreach($matchNumbersForFilter as $mn)
                        <option wire:key="mn-{{ $mn->id }}" value="{{ $mn->id }}">{{ $mn->display_name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Babak</label>
                <x-select wire:model.live="roundFilter" placeholder="Pilih Babak">
                    <option value="">Semua</option>
                    <option value="Penyisihan">Penyisihan</option>
                    <option value="Final">Final</option>
                </x-select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Gender</label>
                <x-select wire:model.live="genderFilter" placeholder="Pilih Gender">
                    <option value="">Semua</option>
                    <option value="Male">Putra</option>
                    <option value="Female">Putri</option>
                    <option value="Mix">Campuran</option>
                </x-select>
            </div>
        </div>
    </div>

    {{-- Rekap Table --}}
    <x-table>
        <x-slot name="header">
            <x-table.th class="text-center">No</x-table.th>
            <x-table.th>Info Pertandingan</x-table.th>
            <x-table.th>Peserta / Kontingen</x-table.th>
            <x-table.th class="text-center">J1</x-table.th>
            <x-table.th class="text-center">J2</x-table.th>
            <x-table.th class="text-center">J3</x-table.th>
            <x-table.th class="text-center">J4</x-table.th>
            <x-table.th class="text-center">J5</x-table.th>
            <x-table.th class="text-center">Denda</x-table.th>
            <x-table.th class="text-center">Nilai Akhir</x-table.th>
            <x-table.th class="text-center">Rank</x-table.th>
        </x-slot>

        @forelse($scores as $index => $score)
            <x-table.tr>
                <x-table.td class="text-center text-xs font-bold text-slate-400">
                    {{ ($scores->currentPage() - 1) * $scores->perPage() + $index + 1 }}
                </x-table.td>
                <x-table.td>
                    <div class="text-xs font-black text-slate-800 uppercase">{{ $score->matchNumber->name }}</div>
                    <div class="flex items-center gap-2 mt-1">
                        <span
                            class="text-[9px] font-bold text-white bg-slate-800 px-1.5 py-0.5 rounded uppercase">{{ $score->round_label }}</span>
                        <span
                            class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $score->matchNumber->ageGroup->name ?? '' }}</span>
                    </div>
                </x-table.td>
                <x-table.td>
                    <div class="text-xs font-black text-slate-800 uppercase">
                        {{ $score->matchNumber->athletes->where('pivot.registration_id', $score->registration_id)->pluck('name')->join(' & ') }}
                    </div>
                    <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mt-0.5">
                        {{ $score->registration->contingent?->name ?? '-' }}
                    </div>
                </x-table.td>
                <x-table.td class="text-center text-xs font-bold text-slate-600">
                    {{ number_format($score->judge_1, 1) }}
                </x-table.td>
                <x-table.td class="text-center text-xs font-bold text-slate-600">
                    {{ number_format($score->judge_2, 1) }}
                </x-table.td>
                <x-table.td class="text-center text-xs font-bold text-slate-600">
                    {{ number_format($score->judge_3, 1) }}
                </x-table.td>
                <x-table.td class="text-center text-xs font-bold text-slate-600">
                    {{ number_format($score->judge_4, 1) }}
                </x-table.td>
                <x-table.td class="text-center text-xs font-bold text-slate-600">
                    {{ number_format($score->judge_5, 1) }}
                </x-table.td>
                <x-table.td class="text-center text-xs font-bold text-rose-500">
                    {{ $score->denda > 0 ? '-' . number_format($score->denda, 1) : '0' }}
                </x-table.td>
                <x-table.td class="text-center bg-indigo-50/20">
                    <span class="text-lg font-black text-indigo-600 tracking-tight">
                        {{ number_format($score->nilai_akhir, 1) }}
                    </span>
                </x-table.td>
                <x-table.td class="text-center">
                    <div class="flex flex-col items-center gap-2">
                        @if($score->rank <= 3 && $score->rank > 0)
                            <span
                                class="w-8 h-8 rounded-full inline-flex items-center justify-center font-black text-sm
                                        {{ $score->rank == 1 ? 'bg-yellow-100 text-yellow-700' : ($score->rank == 2 ? 'bg-slate-200 text-slate-700' : 'bg-orange-100 text-orange-700') }}">
                                {{ $score->rank }}
                            </span>
                        @else
                            <span class="text-xs font-bold text-slate-400">{{ $score->rank ?: '-' }}</span>
                        @endif
                    </div>
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <x-table.td colspan="11" class="px-6 py-20 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-database text-slate-200 text-2xl"></i>
                    </div>
                    <h4 class="text-slate-800 font-black uppercase tracking-tight">Tidak ada data</h4>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Data rekapitulasi belum
                        tersedia</p>
                </x-table.td>
            </x-table.tr>
        @endforelse

        <x-slot name="pagination">
            {{ $scores->links('livewire.admin.pagination') }}
        </x-slot>
    </x-table>

    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }

            .rounded-3xl {
                border-radius: 0 !important;
            }

            .shadow-sm {
                box-shadow: none !important;
            }

            table {
                font-size: 10px !important;
            }
        }
    </style>
</div>