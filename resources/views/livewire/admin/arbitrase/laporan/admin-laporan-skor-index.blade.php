<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Rekapitulasi Skor Menyeluruh</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Laporan Semua Penilaian Peserta & Pertandingan</p>
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
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Cari</label>
                <input type="text" wire:model.live.debounce.300ms="search"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm font-medium"
                    placeholder="Nama nomor...">
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kategori</label>
                <x-select wire:model.live="draftTypeFilter" variant="filter">
                    <option value="">Semua</option>
                    <option value="embu">Embu</option>
                    <option value="randori">Randori</option>
                </x-select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Kelompok Umur</label>
                <x-select wire:model.live="ageGroupFilter" variant="filter">
                    <option value="">Semua</option>
                    @foreach($ageGroups as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </x-select>
            </div>
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">Gender</label>
                <x-select wire:model.live="genderFilter" variant="filter">
                    <option value="">Semua</option>
                    <option value="Male">Putra</option>
                    <option value="Female">Putri</option>
                    <option value="Mix">Campuran</option>
                </x-select>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="space-y-8">
        @forelse($matchNumbers as $mn)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden print:shadow-none print:border-slate-300 page-break-after-always">
                {{-- Match Header --}}
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $mn->name }}</h2>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 rounded text-[10px] font-black uppercase tracking-widest">
                                {{ $mn->draft_type }}
                            </span>
                            <span class="text-[12px] text-slate-400 font-bold uppercase tracking-widest">
                                {{ $mn->ageGroup->name ?? '' }} · {{ $mn->gender }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Score Table --}}
                <div class="overflow-x-auto">
                    @if(strtolower($mn->draft_type) === 'embu')
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Peserta / Kontingen</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Penyisihan</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Final</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Akumulasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($mn->all_scores as $score)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-black text-slate-800">{{ $score->athlete_names }}</div>
                                            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $score->contingent_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-600">
                                            {{ $score->penyisihan_score > 0 ? number_format($score->penyisihan_score, 2) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-600">
                                            {{ $score->final_score > 0 ? number_format($score->final_score, 2) : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-sm font-black">
                                                {{ number_format($score->accumulated_score, 2) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-slate-400 font-medium italic">
                                            Belum ada data penilaian untuk nomor ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @else
                        {{-- Randori Table --}}
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Bracket Node</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-right">Merah (Red)</th>
                                    <th class="px-6 py-4 text-center text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">VS</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-left">Biru (Blue)</th>
                                    <th class="px-6 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Pemenang</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($mn->all_scores as $res)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="px-6 py-4 font-black text-slate-400 text-[10px] uppercase tracking-widest">
                                            {{ strtoupper($res->bracket_node) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="text-sm font-black {{ $res->winner_color === 'red' ? 'text-rose-600' : 'text-slate-700' }}">
                                                {{ $res->athlete1['name'] ?? '—' }}
                                            </div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                {{ $res->athlete1['contingent'] ?? '—' }}
                                            </div>
                                            <div class="text-lg font-black mt-1 {{ $res->winner_color === 'red' ? 'text-rose-600' : 'text-slate-300' }}">
                                                {{ number_format($res->score_red ?? 0, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-200 font-black italic">VS</td>
                                        <td class="px-6 py-4 text-left">
                                            <div class="text-sm font-black {{ $res->winner_color === 'blue' ? 'text-blue-600' : 'text-slate-700' }}">
                                                {{ $res->athlete2['name'] ?? '—' }}
                                            </div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                {{ $res->athlete2['contingent'] ?? '—' }}
                                            </div>
                                            <div class="text-lg font-black mt-1 {{ $res->winner_color === 'blue' ? 'text-blue-600' : 'text-slate-300' }}">
                                                {{ number_format($res->score_blue ?? 0, 2) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $winnerName = '-';
                                                $winnerSide = '';
                                                if ($res->winner_color === 'athlete1' || $res->winner_color === 'red') {
                                                    $winnerName = $res->athlete1['name'] ?? '-';
                                                    $winnerSide = 'AKA (MERAH)';
                                                } elseif ($res->winner_color === 'athlete2' || $res->winner_color === 'blue') {
                                                    $winnerName = $res->athlete2['name'] ?? '-';
                                                    $winnerSide = 'SHIRO (BIRU)';
                                                } elseif ($res->winner) {
                                                    $winnerName = $res->winner->name;
                                                    $winnerSide = strtoupper($res->winner_color ?? '');
                                                }
                                            @endphp

                                            @if($winnerName !== '-')
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-crown text-amber-400 text-xs"></i>
                                                    <div>
                                                        <div class="text-[11px] font-black text-slate-800 uppercase tracking-tight">{{ $winnerName }}</div>
                                                        <div class="text-[9px] font-bold text-emerald-600 uppercase tracking-widest">MENANG {{ $winnerSide }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-slate-400 font-medium italic">
                                            Belum ada data pertandingan randori untuk nomor ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-3xl border border-slate-200 p-20 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-file-invoice text-slate-200 text-4xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Tidak ada data ditemukan</h3>
                <p class="text-slate-400 font-medium mt-2">Coba sesuaikan filter atau pencarian Anda.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8 print:hidden">
        {{ $matchNumbers->links('livewire.admin.pagination') }}
    </div>

    <style>
        @media print {
            body { background: white !important; }
            .page-break-after-always { page-break-after: always; }
            .print\:hidden { display: none !important; }
            .print\:shadow-none { shadow: none !important; }
            .print\:border-slate-300 { border-color: #cbd5e1 !important; }
        }
    </style>
</div>
