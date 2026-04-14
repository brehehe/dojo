<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Drawing Embu</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Technical Meeting / Drawing</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button onclick="window.print()" class="w-full md:w-auto group bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-slate-900/10 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-print text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em]">Cetak Drawing</span>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100 min-h-[600px]">
        @forelse($matchSummary as $gender => $ageGroups)
            <div class="gender-block mb-12" wire:key="gender-{{ $gender }}">
                <!-- GENDER HEADER -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-[2px] flex-1 bg-slate-100"></div>
                    <div class="flex items-center gap-2 bg-slate-900 text-white px-8 py-3 rounded-full shadow-xl">
                        <i class="fas @if($gender == 'Male') fa-mars @elseif($gender == 'Female') fa-venus @else fa-venus-mars @endif text-xs text-orange-400"></i>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em]">{{ $gender == 'Male' ? 'LAKI-LAKI' : ($gender == 'Female' ? 'PEREMPUAN' : 'CAMPURAN / MIX') }}</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100"></div>
                </div>

                <div class="space-y-12 pl-4 md:pl-10 border-l-4 border-slate-50">
                    @foreach($ageGroups as $ageGroupName => $matches)
                        <div class="age-group-block" wire:key="age-{{ $gender }}-{{ $ageGroupName }}">
                            <!-- AGE GROUP HEADER -->
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-[20px] flex items-center justify-center font-black shadow-sm border border-indigo-100/50">
                                    {{ substr($ageGroupName, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-slate-800 uppercase tracking-tight leading-none">{{ $ageGroupName }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">KATEGORI KELOMPOK USIA</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-10">
                                @foreach($matches as $mId => $data)
                                    <div class="match-table-container animate-in slide-in-from-bottom-4 duration-500" wire:key="match-table-{{ $mId }}">
                                        <div class="flex items-center justify-between mb-4 px-2">
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-orange-500 shadow-sm shadow-orange-500/20"></div>
                                                <h4 class="text-[12px] font-black text-slate-700 uppercase tracking-wide">{{ $data['name'] }}</h4>
                                            </div>
                                            <span class="text-[10px] font-black text-slate-300 uppercase italic tracking-widest">Entry ID: #{{ $mId }}</span>
                                        </div>

                                        <div class="bg-white rounded-[28px] border-2 border-slate-50 shadow-sm overflow-hidden">
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-left border-collapse">
                                                    <thead>
                                                        <tr class="bg-slate-50/50 border-b border-slate-100">
                                                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-wider text-center w-20">No</th>
                                                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-wider">Nama Peserta (Atlet)</th>
                                                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-wider">Kontingen</th>
                                                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-wider text-center w-40">Tingkat (Rank)</th>
                                                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-wider min-w-[300px]">Urutan Jurus / Teknik yang Dimainkan :</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-50">
                                                        @php $lastRegId = null; @endphp
                                                        @foreach($data['athletes'] as $aIdx => $ath)
                                                            @php 
                                                                $isNewTeam = ($lastRegId !== $ath->registration_id);
                                                                $lastRegId = $ath->registration_id;
                                                            @endphp
                                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                                <td class="px-8 py-5 text-center">
                                                                    <span class="text-[11px] font-black text-slate-300">{{ $aIdx + 1 }}</span>
                                                                </td>
                                                                <td class="px-8 py-5">
                                                                    <div class="flex flex-col">
                                                                        <span class="text-[12px] font-black text-slate-700 uppercase tracking-tight">{{ $ath->name }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="px-8 py-5">
                                                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-tight">{{ $ath->contingent }}</span>
                                                                </td>
                                                                <td class="px-8 py-5 text-center">
                                                                    <span class="inline-block px-3 py-1 bg-orange-50 text-orange-600 rounded-lg text-[10px] font-black uppercase tracking-tight border border-orange-100/50">
                                                                        {{ $ath->rank }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-8 py-5">
                                                                    @if($isNewTeam)
                                                                        <div class="flex flex-wrap gap-2 text-wrap">
                                                                            @forelse($ath->readable_techniques as $tIdx => $tName)
                                                                                <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-xl px-3 py-1.5 mb-1">
                                                                                    <span class="text-[10px] font-black text-indigo-400">{{ $tIdx + 1 }}</span>
                                                                                    <span class="text-[10px] font-black text-indigo-700 uppercase tracking-tight whitespace-nowrap">{{ $tName }}</span>
                                                                                </div>
                                                                            @empty
                                                                                <span class="text-[10px] text-slate-300 font-bold uppercase italic tracking-widest">Belum ada teknik dipilih</span>
                                                                            @endforelse
                                                                        </div>
                                                                    @else
                                                                        <div class="flex items-center gap-2">
                                                                            <i class="fas fa-level-up-alt rotate-90 text-slate-200 text-xs"></i>
                                                                            <span class="text-[10px] text-slate-300 font-bold uppercase tracking-widest italic tracking-tighter">Sama seperti di atas</span>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-32 text-center">
                <div class="w-24 h-24 bg-slate-50 text-slate-200 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-trophy text-4xl"></i>
                </div>
                <h3 class="text-base font-black text-slate-400 uppercase tracking-[0.3em]">Belum Ada Data Drawing</h3>
                <p class="text-[11px] text-slate-300 font-medium mt-3">Silakan pastikan nomor pertandingan bertipe 'Embu' telah memiliki pendaftar.</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($paginatedMatches->hasPages())
            <div class="mt-12 px-8 py-6 bg-slate-50 border-t border-slate-100 rounded-[28px]">
                {{ $paginatedMatches->links() }}
            </div>
        @endif
    </div>
</div>
