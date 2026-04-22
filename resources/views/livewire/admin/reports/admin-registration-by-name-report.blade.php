<div class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="mb-6 text-center lg:text-left">
        <div class="flex flex-col lg:flex-row items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-orange-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200 transform transition-transform hover:rotate-12">
                <i class="fas fa-users text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Laporan Per Nama</h1>
                <p class="text-slate-900 font-bold text-[15px] uppercase tracking-widest">Registration by Name (P/O List)</p>
            </div>
        </div>
    </div>

    <!-- Filter & Preview Card -->
    <div class="max-w-full mx-auto">
        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-xl shadow-slate-200/50 p-6 lg:p-10 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-orange-500/5 rounded-full -mr-32 -mt-32 blur-3xl group-hover:bg-orange-500/10 transition-colors duration-700"></div>
            
            <div class="relative">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <!-- Left: Control -->
                    <div class="lg:col-span-4 lg:border-r lg:border-slate-100 lg:pr-10">
                        <div class="mb-10">
                            <h2 class="text-xl font-black text-slate-800 mb-2 uppercase tracking-tight">Kriteria Laporan</h2>
                            <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest leading-relaxed">
                                Silahkan pilih kontingen dan filter peserta untuk memuat laporan per nama.
                            </p>
                        </div>

                        <div class="space-y-8">
                            <!-- Contingent Selection -->
                            <div>
                                <label class="block text-[15px] font-black text-slate-800 uppercase tracking-widest mb-3 ml-1">Kontingen / Dojo</label>
                                <select wire:model.live="contingentId" 
                                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-6 py-4 text-[15px] font-black text-black focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all appearance-none cursor-pointer">
                                    <option value="">-- Pilih Kontingen --</option>
                                    @foreach($contingents as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-[15px] font-black text-slate-800 uppercase tracking-widest mb-3 ml-1">Status Peserta</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <button wire:click="$set('statusFilter', 'all')" 
                                            class="py-3 rounded-xl text-[15px] font-black uppercase tracking-tight transition-all {{ $statusFilter === 'all' ? 'bg-slate-900 text-white shadow-lg' : 'bg-slate-50 text-slate-800 hover:bg-slate-100' }}">SEMUA</button>
                                    <button wire:click="$set('statusFilter', 'P')" 
                                            class="py-3 rounded-xl text-[15px] font-black uppercase tracking-tight transition-all {{ $statusFilter === 'P' ? 'bg-orange-600 text-white shadow-lg' : 'bg-slate-50 text-slate-800 hover:bg-slate-100' }}">P (PESERTA)</button>
                                    <button wire:click="$set('statusFilter', 'O')" 
                                            class="py-3 rounded-xl text-[15px] font-black uppercase tracking-tight transition-all {{ $statusFilter === 'O' ? 'bg-orange-600 text-white shadow-lg' : 'bg-slate-50 text-slate-800 hover:bg-slate-100' }}">O (OFFICIAL)</button>
                                </div>
                            </div>

                            @if($contingentId)
                                <button wire:click="download" 
                                        class="w-full bg-slate-900 hover:bg-orange-600 text-white font-black py-5 rounded-2xl flex items-center justify-center gap-4 transition-all duration-500 shadow-xl shadow-slate-900/20 active:scale-95 group/btn">
                                    <i class="fas fa-file-excel text-orange-400 group-hover/btn:text-white transition-colors"></i>
                                    DOWNLOAD EXCEL (NAME)
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Preview -->
                    <div class="lg:col-span-8">
                        @if($contingentId)
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                <div>
                                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Daftar Nama</h3>
                                    <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest italic">Menampilkan {{ count($participants) }} orang pada kontingen terpilih.</p>
                                </div>
                                <div class="relative w-full md:w-64 group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-800">
                                        <i class="fas fa-search text-[15px]"></i>
                                    </span>
                                    <input type="text" wire:model.live.debounce.300ms="search" 
                                           placeholder="Cari nama..." 
                                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-[15px] font-black focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all outline-none">
                                </div>
                            </div>

                            <div class="bg-slate-50 rounded-3xl border border-slate-100 overflow-hidden">
                                <div class="max-h-[600px] overflow-y-auto scrollbar-thin">
                                    <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                        <thead class="bg-slate-800 text-white">
                                            <tr class="bg-slate-900 text-white font-black uppercase text-[15px] tracking-widest">
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">No</th>
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Nama Lengkap</th>
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">L/P</th>
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Tingkat</th>
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Status</th>
                                                <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Jabatan / Dojo</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200">
                                            @forelse($participants as $index => $item)
                                                <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                    <td class="px-6 py-4 text-center text-[15px] font-bold text-slate-800 font-mono border-r border-slate-200">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-4 border-r border-slate-200">
                                                        <p class="text-[15px] font-black text-black uppercase leading-none mb-1">{{ $item->name }}</p>
                                                        @if($item->birth_date)
                                                            <p class="text-[15px] text-slate-800 font-bold uppercase">{{ date('d M Y', strtotime($item->birth_date)) }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-4 text-center border-r border-slate-200">
                                                        <span class="text-[15px] font-black {{ $item->gender == 'Male' ? 'text-blue-500' : ($item->gender == 'Female' ? 'text-pink-500' : 'text-slate-300') }}">
                                                            {{ $item->gender == 'Male' ? 'L' : ($item->gender == 'Female' ? 'P' : '-') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center border-r border-slate-200">
                                                        <span class="text-[15px] font-black text-slate-900 uppercase">{{ $item->tingkat ?: '-' }}</span>
                                                    </td>
                                                    <td class="px-4 py-4 text-center border-r border-slate-200">
                                                        <span class="px-3 py-1 rounded-full text-[15px] font-black uppercase tracking-widest {{ $item->status_code === 'O' ? 'bg-slate-100 text-slate-900' : 'bg-orange-100 text-orange-600' }}">
                                                            {{ $item->status_code }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-4 border-r border-slate-200">
                                                        <p class="text-[15px] font-bold text-slate-900 uppercase">{{ $item->info }}</p>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="p-24 text-center border-r border-slate-200">
                                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                                            <i class="fas fa-user-friends text-slate-200 text-2xl"></i>
                                                        </div>
                                                        <p class="text-[15px] font-black text-slate-300 uppercase tracking-widest">Tidak ada data peserta</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="h-[500px] flex flex-col items-center justify-center bg-slate-50 rounded-[3rem] border-4 border-dashed border-slate-100 p-12">
                                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-xl shadow-slate-200/50 mb-8 border border-slate-100 transform transition-transform hover:scale-110">
                                    <i class="fas fa-address-book text-4xl text-slate-200"></i>
                                </div>
                                <h3 class="text-base font-black text-slate-300 uppercase tracking-widest mb-2 text-center">Pratinjau Nama Peserta</h3>
                                <p class="text-[15px] text-slate-300 font-bold uppercase tracking-tight text-center max-w-xs leading-relaxed">
                                    Silahkan pilih kontingen di samping untuk melihat daftar lengkap atlet dan ofisial.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
