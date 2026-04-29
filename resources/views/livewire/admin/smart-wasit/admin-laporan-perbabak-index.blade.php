<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Laporan Penilaian Perbabak</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Log Detail Penilaian Wasit Setiap Babak Pertandingan</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportExcel"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button onclick="window.print()"
                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex items-center gap-2">
                <i class="fas fa-print"></i> Cetak Log
            </button>
        </div>
    </div>

    {{-- Summary Perbabak --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden print:shadow-none mb-6">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fas fa-chart-line text-slate-800"></i>
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Statistik Per Babak</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Babak</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Jumlah Penilaian</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Rata-rata Nilai</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Jumlah Wasit</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($roundStats as $stat)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-800 uppercase">{{ $stat['name'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-600">
                                {{ $stat['count'] }}
                            </td>
                            <td class="px-6 py-4 text-center font-black text-indigo-600">
                                {{ number_format($stat['avg'], 2) }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-slate-600">
                                {{ $stat['referee_count'] }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold">
                                <span class="text-sm">{{ $stat['trend'] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Cari Peserta/Nomor</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium"
                    placeholder="Search...">
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kelompok Umur</label>
                <select wire:model.live="ageGroupFilter"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                    <option value="">Semua</option>
                    @foreach($ageGroups as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Nomor Pertandingan</label>
                <select wire:model.live="matchNumberFilter"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                    <option value="">Semua</option>
                    @foreach($matchNumbersForFilter as $mn)
                        <option value="{{ $mn->id }}">{{ $mn->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Wasit / Juri</label>
                <select wire:model.live="refereeFilter"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                    <option value="">Semua</option>
                    @foreach($referees as $rf)
                        <option value="{{ $rf->id }}">{{ $rf->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Gender</label>
                <select wire:model.live="genderFilter"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                    <option value="">Semua</option>
                    <option value="Male">Putra</option>
                    <option value="Female">Putri</option>
                    <option value="Mix">Campuran</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Babak</label>
                <select wire:model.live="roundFilter"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                    <option value="">Semua</option>
                    <option value="Penyisihan">Penyisihan</option>
                    <option value="Semifinal">Semifinal</option>
                    <option value="Final">Final</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Assessment Table --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fas fa-list-ul text-slate-800"></i>
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Detail Log Penilaian Wasit</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Babak</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Info Pertandingan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Peserta & Wasit</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-indigo-50/30">TEKNIK</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-emerald-50/30">EKSPRESI</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($assessments as $item)
                        @php
                            $details = $item->details ?? [];
                            $teknik = 0;
                            $ekspresi = 0;

                            foreach ($details as $key => $val) {
                                if (str_starts_with($key, 'goho_') || str_starts_with($key, 'juho_')) {
                                    $teknik += (float) $val;
                                } elseif (str_starts_with($key, 'ekspresi_')) {
                                    $ekspresi += (float) $val;
                                }
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-600">
                                    {{ $item->round_name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-black text-slate-800">{{ $item->matchNumber->name }}</div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->matchNumber->ageGroup->name ?? '' }}</span>
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $item->matchNumber->gender }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 border-l border-slate-50">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs">
                                        {{ $item->judge_index }}
                                    </div>
                                    <div>
                                        <div class="text-[11px] font-black text-slate-800 uppercase tracking-tight">
                                            {{ $item->matchNumber->athletes->where('pivot.registration_id', $item->scorable_id)->pluck('name')->join(' & ') }}
                                        </div>
                                        <div class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mt-0.5">Juri: {{ $item->referee->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600 bg-indigo-50/10">
                                {{ number_format($teknik, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-orange-600 bg-emerald-50/10">
                                {{ number_format($ekspresi, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center bg-orange-50/10 border-l border-slate-50">
                                <span class="px-3 py-1 bg-slate-900 text-white rounded-lg text-sm font-black tracking-tight shadow-sm shadow-slate-200">
                                    {{ number_format($item->total_calculated_score, 1) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-database text-slate-200 text-2xl"></i>
                                </div>
                                <h4 class="text-slate-800 font-black uppercase tracking-tight">Tidak ada data</h4>
                                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-1">Gunakan filter untuk mencari data</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-8 print:hidden">
        {{ $assessments->links('livewire.admin.pagination') }}
    </div>
</div>
