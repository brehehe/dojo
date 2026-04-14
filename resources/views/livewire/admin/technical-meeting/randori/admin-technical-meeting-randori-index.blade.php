<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Drawing Randori</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-red-600 rounded-full"></span>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Technical Meeting / Drawing</p>
            </div>
        </div>
        <div class="w-full md:w-auto flex flex-col md:flex-row gap-2">
            <!-- Generate All Button -->
            <button wire:click="generateAllDrawings"
                    wire:confirm="Yakin ingin melakukan generate drawing otomatis untuk semua nomor Randori yang belum memiliki drawing?"
                    wire:loading.attr="disabled"
                    wire:target="generateAllDrawings"
                    class="w-full md:w-auto group bg-red-600 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-red-600/20 hover:bg-red-700 transition-all flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50">
                <span wire:loading.remove wire:target="generateAllDrawings"><i class="fas fa-magic text-xs"></i></span>
                <span wire:loading wire:target="generateAllDrawings"><i class="fas fa-spinner fa-spin text-xs"></i></span>
                <span class="uppercase text-[9px] tracking-[0.2em] whitespace-nowrap">Generate Semua</span>
            </button>
            <button onclick="window.print()" class="w-full md:w-auto group bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-slate-900/10 hover:bg-slate-700 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-print text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em] whitespace-nowrap">Cetak Drawing</span>
            </button>
        </div>
    </div>

    <!-- THB Rules Info Box -->
    <div class="bg-red-50 border border-red-200 rounded-2xl px-6 py-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 mt-0.5">
                <i class="fas fa-scroll text-xs"></i>
            </div>
            <div class="flex-1">
                <p class="text-[11px] font-black text-red-800 uppercase tracking-widest mb-3">📜 Peraturan Randori & Bagan (THB)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-lg bg-red-200 text-red-800 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5"><i class="fas fa-sitemap mt-[1px]"></i></span>
                        <p class="text-[10px] text-red-800 font-semibold leading-relaxed"><strong>Sistem Gugur Tunggal:</strong> Menggunakan struktur pohon turnamen (Bagan) $2^n$ (8, 16, 32 dst).</p>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-lg bg-red-200 text-red-800 flex items-center justify-center text-[9px] font-black shrink-0 mt-0.5"><i class="fas fa-user-shield mt-[1px]"></i></span>
                        <p class="text-[10px] text-red-800 font-semibold leading-relaxed"><strong>Aturan Pemisahan Kontingen:</strong> Atlet dari kontingen yang sama disebar saling berjauhan secara otomatis agar tidak bertumbuk di babak awal.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div x-data="{ globalTab: 'sebelum' }" class="bg-white rounded-[32px] p-8 shadow-sm border border-slate-100 min-h-[600px]">
        
        <!-- Global Tab Header -->
        <div class="flex gap-4 mb-8 border-b-2 border-slate-100 px-1">
            <button @click="globalTab = 'sebelum'" 
                class="px-4 py-3 text-[11px] uppercase font-black tracking-widest border-b-4 -mb-[3px] transition-all duration-200"
                :class="globalTab === 'sebelum' ? 'border-red-500 text-red-600' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-200'">
                <i class="fas fa-users mr-2"></i> Daftar Pendaftar Atlet
            </button>
            <button @click="globalTab = 'hasil'" 
                class="px-4 py-3 text-[11px] uppercase font-black tracking-widest border-b-4 -mb-[3px] transition-all duration-200"
                :class="globalTab === 'hasil' ? 'border-amber-500 text-amber-600' : 'border-transparent text-slate-400 hover:text-slate-600 hover:border-slate-200'">
                <i class="fas fa-sitemap mr-2"></i> Bagan Pertandingan
            </button>
        </div>

        @forelse($matchSummary as $gender => $ageGroups)
            <div class="gender-block mb-12" wire:key="gender-{{ $gender }}">
                <!-- GENDER HEADER -->
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-[2px] flex-1 bg-slate-100"></div>
                    <div class="flex items-center gap-2 bg-slate-900 text-white px-8 py-3 rounded-full shadow-xl">
                        <i class="fas @if($gender == 'Male') fa-mars @elseif($gender == 'Female') fa-venus @else fa-venus-mars @endif text-xs text-red-400"></i>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em]">{{ $gender == 'Male' ? 'LAKI-LAKI' : ($gender == 'Female' ? 'PEREMPUAN' : 'CAMPURAN / MIX') }}</span>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-100"></div>
                </div>

                <div class="space-y-12 pl-4 md:pl-10 border-l-4 border-slate-50">
                    @foreach($ageGroups as $ageGroupName => $matches)
                        <div class="age-group-block" wire:key="age-{{ $gender }}-{{ $ageGroupName }}">
                            <!-- AGE GROUP HEADER -->
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 bg-red-50 text-red-600 rounded-[20px] flex items-center justify-center font-black shadow-sm border border-red-100/50">
                                    {{ substr($ageGroupName, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-black text-slate-800 uppercase tracking-tight leading-none">{{ $ageGroupName }}</h3>
                                    <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">KATEGORI KELOMPOK USIA</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-12">
                                @foreach($matches as $mId => $data)
                                    <div class="match-block animate-in slide-in-from-bottom-4 duration-500" wire:key="match-{{ $mId }}">

                                        <!-- Match Title Row -->
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 px-1">
                                            <div class="flex items-center gap-3">
                                                <div class="w-3 h-3 rounded-full bg-red-500 shadow-sm shadow-red-500/20"></div>
                                                <h4 class="text-[13px] font-black text-slate-700 uppercase tracking-wide">{{ $data['name'] }}</h4>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @php
                                                    $totalAthletes = count($data['athletes']);
                                                    $drawing       = $data['drawing_data'];
                                                    $drawingAt     = $data['drawing_at'];
                                                @endphp

                                                <span class="text-[9px] font-black text-slate-400 bg-slate-50 border border-slate-100 rounded-lg px-3 py-1.5 uppercase tracking-widest">
                                                    {{ $totalAthletes }} Atlet Terdaftar
                                                </span>

                                                @if($drawing)
                                                    <button wire:click="resetDrawing({{ $mId }})"
                                                            wire:confirm="Reset drawing bagan untuk nomor ini?"
                                                            class="inline-flex items-center gap-1.5 text-[9px] font-black text-slate-500 border border-slate-200 bg-slate-50 hover:bg-slate-100 px-3 py-1.5 rounded-lg uppercase tracking-widest transition active:scale-95">
                                                        <i class="fas fa-redo text-[8px]"></i> Reset
                                                    </button>
                                                    <button wire:click="generateDrawing({{ $mId }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="generateDrawing({{ $mId }})"
                                                            class="inline-flex items-center gap-1.5 text-[9px] font-black text-red-600 border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg uppercase tracking-widest transition active:scale-95 disabled:opacity-50">
                                                        <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-sync text-[8px]"></i> Regenerate</span>
                                                        <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[8px]"></i> Generating...</span>
                                                    </button>
                                                @else
                                                    <button wire:click="generateDrawing({{ $mId }})"
                                                            wire:loading.attr="disabled"
                                                            wire:target="generateDrawing({{ $mId }})"
                                                            class="inline-flex items-center gap-1.5 text-[9px] font-black text-white bg-slate-900 hover:bg-slate-700 px-4 py-2 rounded-lg uppercase tracking-widest transition active:scale-95 shadow-sm disabled:opacity-50">
                                                        <span wire:loading.remove wire:target="generateDrawing({{ $mId }})"><i class="fas fa-sitemap text-[8px]"></i> Generate Bagan</span>
                                                        <span wire:loading wire:target="generateDrawing({{ $mId }})"><i class="fas fa-spinner fa-spin text-[8px]"></i> Generating...</span>
                                                    </button>
                                                @endif

                                                <span class="text-[10px] font-black text-slate-300 uppercase italic tracking-widest">Entry #{{ $mId }}</span>
                                            </div>
                                        </div>

                                        <!-- Tabs Wrapper -->
                                        <div class="mt-4">
                                            <!-- Tab Content: Hasil Generate Bagan -->
                                            <div x-show="globalTab === 'hasil'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                                        @if($drawing)
                                            <!-- Format Banner -->
                                            <div class="mb-5 flex flex-col sm:flex-row sm:items-center gap-3 bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl px-5 py-4">
                                                <div class="flex items-center gap-3 flex-1">
                                                    <div class="w-8 h-8 rounded-xl bg-red-500 flex items-center justify-center shrink-0">
                                                        <i class="fas fa-sitemap text-white text-xs"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Format Randori (Single Elimination)</p>
                                                        <p class="text-[11px] font-black text-white mt-0.5">Bagan Peserta {{ $drawing['bracket_size'] }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-4 shrink-0">
                                                    <div class="text-center">
                                                        <p class="text-[10px] font-black text-emerald-400">{{ $drawing['total_entries'] }}</p>
                                                        <p class="text-[8px] text-slate-400 font-bold uppercase">Peserta Aktif</p>
                                                    </div>
                                                    <div class="w-px h-6 bg-slate-700"></div>
                                                    <div class="text-center">
                                                        @php $byes = $drawing['bracket_size'] - $drawing['total_entries']; @endphp
                                                        <p class="text-[10px] font-black text-blue-400">{{ $byes }}</p>
                                                        <p class="text-[8px] text-slate-400 font-bold uppercase">Byes</p>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($drawingAt)
                                                <p class="text-[9px] text-slate-300 font-bold uppercase tracking-widest mb-4 px-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Drawing dibuat: {{ \Carbon\Carbon::parse($drawingAt)->locale('id')->isoFormat('D MMMM YYYY, HH:mm') }} WIB
                                                </p>
                                            @endif

                                            <!-- Bagan Tree Visualization -->
                                            <div class="bg-slate-50 border border-slate-100 rounded-3xl p-8 overflow-x-auto custom-scrollbar relative">
                                                
                                                @php
                                                    $bracket = $drawing['bracket'];
                                                    $bracketSize = $drawing['bracket_size'];
                                                    $rounds = $bracketSize > 1 ? log($bracketSize, 2) : 0;
                                                    $currentMatchOrder = 1;
                                                @endphp
                                                
                                                <div class="flex gap-16 @if($rounds <= 1) justify-center @endif min-w-max pb-4 items-center">
                                                    
                                                    @if($rounds == 0)
                                                        <!-- Juara Langsung (1 Person) -->
                                                        <div class="flex flex-col items-center justify-center py-12 px-20">
                                                            <div class="text-center mb-6">
                                                                <span class="text-[10px] uppercase font-black tracking-widest text-amber-500">Juara Langsung (1 Atlet)</span>
                                                            </div>
                                                            <div class="bg-white border-2 border-amber-300 rounded-3xl p-8 shadow-xl flex flex-col items-center gap-4 relative overflow-hidden group">
                                                                <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-amber-400 to-yellow-500"></div>
                                                                <i class="fas fa-trophy text-amber-400 text-5xl drop-shadow-lg group-hover:scale-110 transition-transform duration-500"></i>
                                                                <div class="text-center">
                                                                    <p class="text-lg font-black text-slate-800 uppercase tracking-tight">{{ $bracket[0]['name'] ?? 'Pemenang' }}</p>
                                                                    <p class="text-[11px] font-bold text-amber-600 uppercase tracking-widest">{{ $bracket[0]['contingent'] ?? '-' }}</p>
                                                                </div>
                                                                <div class="mt-2 px-4 py-1.5 bg-amber-50 rounded-full border border-amber-100">
                                                                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">CHAMPION</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <!-- Round 1 to Final -->
                                                        @for ($r = 1; $r <= $rounds; $r++)
                                                            @php
                                                                $nodesInRound = pow(2, $rounds - $r + 1);
                                                                $matchesInRound = $nodesInRound / 2;
                                                            @endphp
                                                            <div class="flex flex-col justify-around w-[220px] relative">
                                                                
                                                                <!-- Label column -->
                                                                <div class="text-center absolute -top-4 w-full">
                                                                    <span class="text-[9px] uppercase font-black tracking-widest text-[#cbd5e1] whitespace-nowrap">
                                                                        @if($r == $rounds) FINAL
                                                                        @elseif($r == $rounds - 1) SEMI-FINAL
                                                                        @elseif($r == $rounds - 2) QUARTER-FINAL
                                                                        @else ROUND {{ $r }}
                                                                        @endif
                                                                    </span>
                                                                </div>

                                                                <!-- Matches Wrapper -->
                                                                @for ($m = 0; $m < $matchesInRound; $m++)
                                                                    <div class="relative py-3">
                                                                        
                                                                        <!-- Render Match Box -->
                                                                        <div class="bg-white border-2 border-slate-200 rounded-xl overflow-hidden shadow-sm flex flex-col z-10 relative">
                                                                            <div class="absolute inset-y-0 left-0 w-1 bg-red-400"></div>

                                                                            @if($r === 1)
                                                                                @php 
                                                                                    $p1 = $bracket[$m * 2]; 
                                                                                    $p2 = $bracket[$m * 2 + 1]; 
                                                                                @endphp

                                                                                <!-- Player 1 -->
                                                                                <div class="flex items-center justify-between p-3 border-b border-slate-100">
                                                                                    <div class="flex items-center gap-2 overflow-hidden">
                                                                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></div>
                                                                                        <div class="truncate">
                                                                                            <p class="text-[11px] font-black {{ $p1 ? 'text-slate-700' : 'text-slate-400' }} truncate capitalize" title="{{ $p1 ? $p1['name'] : 'BYE' }}">
                                                                                                {{ $p1 ? $p1['name'] : '(BYE)' }}
                                                                                            </p>
                                                                                            @if($p1)
                                                                                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest truncate">{{ $p1['contingent'] }}</p>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Player 2 -->
                                                                                <div class="flex items-center justify-between p-3">
                                                                                    <div class="flex items-center gap-2 overflow-hidden">
                                                                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></div>
                                                                                        <div class="truncate">
                                                                                            <p class="text-[11px] font-black {{ $p2 ? 'text-slate-700' : 'text-slate-400' }} truncate capitalize" title="{{ $p2 ? $p2['name'] : 'BYE' }}">
                                                                                                {{ $p2 ? $p2['name'] : '(BYE)' }}
                                                                                            </p>
                                                                                            @if($p2)
                                                                                                <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest truncate">{{ $p2['contingent'] }}</p>
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="absolute top-1/2 -right-3 -translate-y-1/2 bg-white border-2 border-slate-200 rounded-full w-6 h-6 flex items-center justify-center text-[8px] font-black text-slate-400 z-20">
                                                                                    {{ $currentMatchOrder++ }}
                                                                                </div>

                                                                            @else
                                                                                <!-- TBD MATCHES -->
                                                                                <div class="flex items-center justify-between p-3 border-b border-slate-100/50 bg-slate-50/50">
                                                                                    <div class="flex items-center gap-2 opacity-50">
                                                                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                                                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">TBD</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex items-center justify-between p-3 bg-slate-50/50">
                                                                                    <div class="flex items-center gap-2 opacity-50">
                                                                                        <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                                                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">TBD</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="absolute top-1/2 -right-3 -translate-y-1/2 bg-white border-2 border-slate-200 rounded-full w-6 h-6 flex items-center justify-center text-[8px] font-black text-slate-400 z-20 shadow-sm">
                                                                                    {{ $currentMatchOrder++ }}
                                                                                </div>
                                                                            @endif

                                                                        </div>
                                                                        
                                                                        <!-- Connector -->
                                                                        @if($r < $rounds)
                                                                            <div class="absolute top-1/2 -right-[64px] w-[64px] h-px bg-slate-300 z-0"></div>
                                                                        @endif

                                                                    </div>
                                                                @endfor
                                                            </div>
                                                        @endfor
                                                        
                                                        <!-- Champion -->
                                                        <div class="flex flex-col justify-around w-[180px] relative">
                                                            <div class="text-center absolute -top-4 w-full">
                                                                <span class="text-[9px] uppercase font-black tracking-widest text-amber-500 whitespace-nowrap">JUARA</span>
                                                            </div>
                                                            <div class="py-3 items-center flex">
                                                                <div class="bg-amber-50 border-2 border-amber-200 rounded-xl overflow-hidden shadow-md flex items-center justify-center p-5 w-full">
                                                                    <i class="fas fa-trophy text-amber-400 text-2xl mb-1 mt-1 block w-full text-center"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-8 mb-6 h-px bg-slate-100"></div>
                                        @else
                                            <div class="bg-slate-50 rounded-2xl py-8 px-6 text-center border border-dashed border-slate-200 mb-6">
                                                <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100 shadow-sm">
                                                    <i class="fas fa-sitemap text-slate-300 text-lg"></i>
                                                </div>
                                                <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Bagan belum dibuat</p>
                                                <p class="text-[10px] text-slate-300 font-medium mt-1">Klik <strong>Generate Bagan</strong> untuk mengundi peserta ke dalam Tree.</p>
                                            </div>
                                        @endif
                                            </div>

                                            <!-- Tab Content: Sebelum Generate (Detail Atlet) -->
                                            <div x-show="globalTab === 'sebelum'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
                                                <div class="mt-2">
                                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3 px-1">
                                                        <i class="fas fa-users mr-1"></i> Data Peserta Randori
                                                    </p>

                                                    <div class="bg-white overflow-hidden rounded-[20px] border border-slate-100 shadow-sm">
                                                        <table class="w-full text-left border-collapse">
                                                            <thead>
                                                                <tr class="border-b border-slate-100 bg-slate-50/50">
                                                                    <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider text-center w-12">No</th>
                                                                    <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider">Nama Peserta (Atlet)</th>
                                                                    <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider">Kontingen</th>
                                                                    <th class="px-5 py-3.5 text-[9px] font-black text-slate-400 uppercase tracking-wider text-center w-32">Rank</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-slate-50">
                                                                @forelse($data['athletes'] as $idx => $ath)
                                                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                                                        <td class="px-5 py-4 text-center">
                                                                            <span class="text-[11px] font-black text-slate-300">{{ $idx + 1 }}</span>
                                                                        </td>
                                                                        <td class="px-5 py-4">
                                                                            <span class="text-[12px] font-black text-slate-700 uppercase tracking-tight">{{ $ath->name }}</span>
                                                                        </td>
                                                                        <td class="px-5 py-4">
                                                                            <div class="flex items-center gap-2">
                                                                                <i class="fas fa-shield-alt text-red-500 text-[10px]"></i>
                                                                                <span class="text-[11px] font-bold text-slate-600 uppercase tracking-wide">{{ $ath->contingent }}</span>
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-5 py-4 text-center">
                                                                            <span class="inline-block px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase border border-red-100/50">
                                                                                {{ $ath->rank ?? '-' }}
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="4" class="px-5 py-8 text-center text-slate-400 text-xs italic">Belum ada peserta</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
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
                <div class="w-24 h-24 bg-red-50 text-red-300 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-sitemap text-4xl"></i>
                </div>
                <h3 class="text-base font-black text-slate-400 uppercase tracking-[0.3em]">Belum Ada Data Drawing Randori</h3>
                <p class="text-[11px] text-slate-300 font-medium mt-3">Silakan pastikan nomor pertandingan bertipe 'Randori' telah memiliki pendaftar.</p>
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
