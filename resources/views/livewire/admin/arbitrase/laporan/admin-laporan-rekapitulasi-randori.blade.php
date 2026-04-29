<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Rekapitulasi Laporan Randori</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Hasil Pertandingan Per Atlet
            </p>
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block font-black text-slate-700 uppercase tracking-widest mb-2">Cari
                    Pertandingan</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium"
                    placeholder="Search category...">
            </div>
            <div>
                <label class="block font-black text-slate-700 uppercase tracking-widest mb-2">Kelompok
                    Umur</label>
                <x-select wire:model.live="ageGroupFilter" placeholder="Pilih Kelompok Umur">
                    <option value="">Semua</option>
                    @foreach($ageGroups as $ag)
                        <option wire:key="ag-{{ $ag->id }}" value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <label class="block font-black text-slate-700 uppercase tracking-widest mb-2">Nomor
                    Pertandingan</label>
                <x-select wire:model.live="matchNumberFilter" placeholder="Pilih Nomor Pertandingan"
                    wire:key="mn-filter-{{ count($matchNumbersForFilter) }}">
                    <option value="">Semua</option>
                    @foreach($matchNumbersForFilter as $mn)
                        <option wire:key="mn-{{ $mn->id }}" value="{{ $mn->id }}">{{ $mn->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <label class="block font-black text-slate-700 uppercase tracking-widest mb-2">Gender</label>
                <x-select wire:model.live="genderFilter" placeholder="Pilih Gender">
                    <option value="">Semua</option>
                    <option wire:key="g-male" value="Male">Putra</option>
                    <option wire:key="g-female" value="Female">Putri</option>
                    <option wire:key="g-mix" value="Mix">Campuran</option>
                </x-select>
            </div>
        </div>
    </div>

    {{-- Rekap Table --}}
    <x-table>
        <x-slot name="header">
            <x-table.th class="text-center">No</x-table.th>
            <x-table.th>Babak</x-table.th>
            <x-table.th>Kelas (kg)</x-table.th>
            <x-table.th class="text-center">Pool</x-table.th>
            <x-table.th class="text-center">Pita</x-table.th>
            <x-table.th>Nama Atlet</x-table.th>
            <x-table.th>Kontingen</x-table.th>
            <x-table.th class="text-center">Warn</x-table.th>
            <x-table.th class="text-center">Ippon(10)</x-table.th>
            <x-table.th class="text-center">Waza(5)</x-table.th>
            <x-table.th class="text-center">Batsu 5</x-table.th>
            <x-table.th class="text-center">Batsu 10</x-table.th>
            <x-table.th class="text-center">Yusei(5)</x-table.th>
            <x-table.th class="text-center">Mujo(15)</x-table.th>
            <x-table.th class="text-center bg-emerald-900/20 text-emerald-400">Total</x-table.th>
            <x-table.th class="text-center">Status</x-table.th>
        </x-slot>

        @forelse($rekapRows as $index => $row)
            <x-table.tr>
                <x-table.td class="text-center font-bold text-slate-500">{{ $index + 1 }}</x-table.td>
                <x-table.td>
                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-tight">{{ $row->babak }}</span>
                </x-table.td>
                <x-table.td>
                    <div class="text-[11px] font-bold text-slate-500 truncate max-w-[150px]" title="{{ $row->kelas }}">
                        {{ $row->kelas }}
                    </div>
                </x-table.td>
                <x-table.td class="text-center font-black text-black uppercase">{{ $row->pool }}</x-table.td>
                <x-table.td class="text-center">
                    <span
                        class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase {{ $row->pita === 'Merah' ? 'bg-rose-500/10 text-rose-500' : 'bg-blue-500/10 text-blue-500' }}">
                        {{ $row->pita }}
                    </span>
                </x-table.td>
                <x-table.td>
                    <div class="font-black text-black uppercase">{{ $row->nama_atlet }}</div>
                </x-table.td>
                <x-table.td class="font-medium text-black">{{ $row->kontingen }}</x-table.td>
                <x-table.td class="text-center font-bold {{ $row->peringatan > 0 ? 'text-amber-500' : 'text-black' }}">
                    {{ $row->peringatan }}
                </x-table.td>
                <x-table.td class="text-center font-black text-black">{{ $row->ippon }}</x-table.td>
                <x-table.td class="text-center font-black text-black">{{ $row->waza_ari }}</x-table.td>
                <x-table.td class="text-center font-bold text-red-500">{{ $row->batsu_5 }}</x-table.td>
                <x-table.td class="text-center font-bold text-red-500">{{ $row->batsu_10 }}</x-table.td>
                <x-table.td class="text-center font-black text-black">{{ $row->yusei_kachi }}</x-table.td>
                <x-table.td class="text-center font-black text-black">{{ $row->mujoken }}</x-table.td>
                <x-table.td class="text-center bg-emerald-500/5">
                    <div
                        class="w-10 h-10 rounded-full flex items-center justify-center mx-auto text-sm font-black text-emerald-600 border-2 border-emerald-500/20">
                        {{ number_format($row->total_nilai, 0) }}
                    </div>
                </x-table.td>
                <x-table.td class="text-center">
                    <div class="flex flex-col items-center gap-1.5">
                        <span
                            class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $row->status === 'Menang' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/10 text-rose-500' }}">
                            {{ $row->status }} {{ $row->win_method ? '(' . $row->win_method . ')' : '' }}
                        </span>
                    </div>
                </x-table.td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <x-table.td colspan="16" class="px-6 py-20 text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-database text-slate-200 text-2xl"></i>
                    </div>
                    <h4 class="text-slate-800 font-black uppercase tracking-tight">Tidak ada data</h4>
                    <p class="text-slate-400 font-bold uppercase tracking-widest mt-1">Data rekapitulasi belum
                        tersedia</p>
                </x-table.td>
            </x-table.tr>
        @endforelse

        <x-slot name="pagination">
            {{ $results->links('livewire.admin.pagination') }}
        </x-slot>
    </x-table>

    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }

            .print\:hidden {
                display: none !important;
            }

            .bg-slate-900 {
                background: white !important;
                border: 1px solid #e2e8f0 !important;
            }

            .text-white {
                color: black !important;
            }

            .text-slate-400,
            .text-slate-500 {
                color: #64748b !important;
            }

            .bg-emerald-900\/20,
            .bg-emerald-500\/5,
            .bg-rose-500\/10 {
                background: none !important;
                border: 1px solid #ccc !important;
            }

            table {
                border: 1px solid #000 !important;
            }

            th,
            td {
                border: 1px solid #ccc !important;
                color: black !important;
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
</div>