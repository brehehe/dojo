<div class="space-y-6 animate-in fade-in duration-500 pb-20">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Laporan Komprehensif Smart Wasit</h1>
            <p class="text-[15px] font-bold uppercase tracking-widest text-slate-400 mt-1">Analisis Performa, Akurasi, Konsistensi, dan Validitas Juri</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportExcel"
                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg flex items-center gap-2 hover:scale-[1.02]">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button onclick="window.print()"
                class="px-6 py-3 bg-slate-900 hover:bg-black text-white rounded-2xl font-black text-sm transition-all shadow-lg flex items-center gap-2 hover:scale-[1.02]">
                <i class="fas fa-print"></i> Cetak Laporan Full
            </button>
        </div>
    </div>
    {{-- Filters --}}
    <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-end">
            <div class="lg:col-span-1">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Pencarian Atlet</label>
                <div class="relative group">
                    <input type="text" wire:model.live.debounce.500ms="search"
                        placeholder="Ketik nama atlet..."
                        class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm font-bold transition-all outline-none">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-500 transition-colors"></i>
                </div>
            </div>
            
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Kelompok Umur</label>
                <x-select wire:model.live="ageGroupFilter" placeholder="Semua Umur" variant="filter">
                    <option value="">Semua Umur</option>
                    @foreach($ageGroups as $ag)
                        <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Nomor Pertandingan</label>
                <x-select wire:model.live="matchNumberFilter" placeholder="Semua Nomor" variant="filter">
                    <option value="">Semua Nomor</option>
                    @foreach($matchNumbers as $mn)
                        <option value="{{ $mn->id }}">{{ $mn->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Wasit / Juri</label>
                <x-select wire:model.live="refereeFilter" placeholder="Semua Wasit" variant="filter">
                    <option value="">Semua Wasit</option>
                    @foreach($referees as $rf)
                        <option value="{{ $rf->id }}">{{ $rf->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Gender</label>
                <x-select wire:model.live="genderFilter" placeholder="Semua Gender" variant="filter">
                    <option value="">Semua Gender</option>
                    <option value="Male">Putra</option>
                    <option value="Female">Putri</option>
                    <option value="Mix">Campuran</option>
                </x-select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Babak</label>
                <x-select wire:model.live="roundFilter" placeholder="Semua Babak" variant="filter">
                    <option value="">Semua Babak</option>
                    <option value="Penyisihan">Penyisihan</option>
                    <option value="Semifinal">Semifinal</option>
                    <option value="Final">Final</option>
                </x-select>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1">Court / Lapangan</label>
                <x-select wire:model.live="courtFilter" placeholder="Semua Lapangan" variant="filter">
                    <option value="">Semua Lapangan</option>
                    @foreach($courts as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </x-select>
            </div>

            <div class="flex items-center gap-2">
                <button wire:click="$refresh" class="flex-1 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </button>
            </div>
        </div>
    </div>
    {{-- Tabs Navigation --}}
    <div class="flex items-center gap-2 bg-white p-2 rounded-3xl border border-slate-200 shadow-sm overflow-x-auto no-scrollbar print:hidden">
        <button wire:click="$set('tab', 'skw')"
            class="px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'skw' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-400 hover:bg-slate-50' }}">
            Ranking Kompetensi
        </button>
        <button wire:click="$set('tab', 'iaw')"
            class="px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'iaw' ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-slate-400 hover:bg-slate-50' }}">
            Analisis Akurasi
        </button>
        <button wire:click="$set('tab', 'ik')"
            class="px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'ik' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100' : 'text-slate-400 hover:bg-slate-50' }}">
            Analisis Konsistensi
        </button>
        <button wire:click="$set('tab', 'iv')"
            class="px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'iv' ? 'bg-orange-600 text-white shadow-lg shadow-orange-100' : 'text-slate-400 hover:bg-slate-50' }}">
            Analisis Validitas
        </button>
        <button wire:click="$set('tab', 'detail')"
            class="px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-widest transition-all {{ $tab === 'detail' ? 'bg-slate-800 text-white shadow-lg shadow-slate-100' : 'text-slate-400 hover:bg-slate-50' }}">
            Detail Penilaian
        </button>
    </div>

    {{-- SECTION 1: RANKING KOMPETENSI --}}
    @if($tab === 'skw' || $tab === 'full')
    <section class="space-y-4 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-trophy"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">1. Ranking Kompetensi Wasit (SKW)</h2>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 w-16">Rank</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Nama Wasit</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Court</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Jumlah</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-indigo-50/30">IAW (%)</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-emerald-50/30">IK</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center bg-orange-50/30">IV</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-900 uppercase tracking-widest border-b border-slate-100 text-center bg-slate-100 font-black">SKW</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php $rank = 1; @endphp
                        @foreach($refereeAnalysis->sortByDesc('skw') as $rf)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-black text-sm text-slate-600">
                                        {{ $rank++ }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-slate-800 uppercase">{{ $rf['name'] }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-slate-50 text-slate-500 rounded-lg text-[10px] font-bold uppercase tracking-widest border border-slate-100">
                                        {{ $rf['primary_court'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-500">
                                    {{ $rf['count'] }}
                                </td>
                                <td class="px-6 py-4 text-center bg-indigo-50/10 font-black text-indigo-600">
                                    {{ number_format($rf['iaw'], 2) }}%
                                </td>
                                <td class="px-6 py-4 text-center bg-emerald-50/10 font-black text-emerald-600">
                                    {{ number_format($rf['ik'], 3) }}
                                </td>
                                <td class="px-6 py-4 text-center bg-orange-50/10 font-black text-orange-600">
                                    {{ number_format($rf['iv'], 3) }}
                                </td>
                                <td class="px-6 py-4 text-center bg-slate-50 font-black text-slate-900 text-lg">
                                    {{ number_format($rf['skw'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-slate-900 text-white">
                                        {{ $rf['grade'] }} ({{ $rf['grade_label'] }})
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 2: ANALISIS AKURASI --}}
    @if($tab === 'iaw' || $tab === 'full')
    <section class="space-y-4 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-bullseye"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">2. Analisis Akurasi (IAW)</h2>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden h-full">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Wasit</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Jumlah</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Rata-rata</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Referensi</th>
                            <th class="px-6 py-5 text-[10px] font-black text-blue-600 uppercase tracking-widest border-b border-slate-100 text-center bg-blue-50/30">IAW (%)</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($refereeAnalysis->sortByDesc('iaw') as $rf)
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-6 py-4 font-black text-slate-800 uppercase">{{ $rf['name'] }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-500">{{ $rf['count'] }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">{{ number_format($rf['avg_total'], 2) }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-500">{{ number_format($rf['avg_ref'], 2) }}</td>
                                <td class="px-6 py-4 text-center bg-blue-50/10 font-black text-blue-600">{{ number_format($rf['iaw'], 2) }}%</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-[10px] font-black uppercase text-blue-700">{{ $rf['iaw_category'] }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 3: ANALISIS KONSISTENSI --}}
    @if($tab === 'ik' || $tab === 'full')
    <section class="space-y-4 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-chart-area"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">3. Analisis Konsistensi (IK)</h2>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden h-full">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Wasit</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Jumlah</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Max</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Min</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Std Dev</th>
                            <th class="px-6 py-5 text-[10px] font-black text-emerald-600 uppercase tracking-widest border-b border-slate-100 text-center bg-emerald-50/30">IK</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($refereeAnalysis->sortByDesc('ik') as $rf)
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-6 py-4 font-black text-slate-800 uppercase">{{ $rf['name'] }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-500">{{ $rf['count'] }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">{{ number_format($rf['max'], 1) }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">{{ number_format($rf['min'], 1) }}</td>
                                <td class="px-6 py-4 text-center font-bold text-slate-400 italic">{{ number_format($rf['std_dev'], 3) }}</td>
                                <td class="px-6 py-4 text-center bg-emerald-50/10 font-black text-emerald-600">{{ number_format($rf['ik'], 3) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 4: ANALISIS VALIDITAS --}}
    @if($tab === 'iv' || $tab === 'full')
    <section class="space-y-4 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">4. Analisis Validitas (IV)</h2>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Wasit</th>
                            <th class="px-6 py-5 text-[10px] font-black text-orange-600 uppercase tracking-widest border-b border-slate-100 text-center bg-orange-50/30">Koefisien Korelasi (r)</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Interpretasi</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($refereeAnalysis->sortByDesc('iv') as $rf)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-black text-slate-800 uppercase">{{ $rf['name'] }}</div>
                                </td>
                                <td class="px-6 py-4 text-center bg-orange-50/10 font-black text-orange-600 text-lg">
                                    {{ number_format($rf['iv'], 3) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-600 italic">{{ $rf['iv_interpretation'] }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-orange-100 text-orange-700">
                                        {{ $rf['iv_category'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 5: DETAIL PENILAIAN PER WASIT --}}
    @if($tab === 'detail' || $tab === 'full')
    <section class="space-y-4 animate-in slide-in-from-bottom-4 duration-500">
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-white shadow-lg">
                <i class="fas fa-list-ul"></i>
            </div>
            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">5. Detail Penilaian Per Wasit</h2>
        </div>
        
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Tanggal</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Court</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Wasit</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Kontingen</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Teknik</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 text-center">Ekspresi</th>
                            <th class="px-6 py-5 text-[10px] font-black text-slate-900 uppercase tracking-widest border-b border-slate-100 text-center font-black">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($assessments as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors text-sm">
                                <td class="px-6 py-4 text-slate-500 font-bold">{{ $item->date }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-slate-100 rounded text-[9px] font-black text-slate-600 uppercase">{{ $item->court }}</span>
                                </td>
                                <td class="px-6 py-4 font-black text-slate-800 uppercase">{{ $item->referee }}</td>
                                <td class="px-6 py-4 font-bold text-slate-600 uppercase">{{ $item->contingent }}</td>
                                <td class="px-6 py-4 text-center font-bold text-blue-600">{{ number_format($item->teknik, 2) }}</td>
                                <td class="px-6 py-4 text-center font-bold text-orange-600">{{ number_format($item->ekspresi, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-slate-900 text-white rounded-lg font-black tracking-tight">
                                        {{ number_format($item->total, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $assessments->links('livewire.admin.pagination') }}
            </div>
        </div>
    </section>
    @endif
</div>
