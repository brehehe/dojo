<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Data Registrasi</h1>
            <p class="text-sm text-slate-500 font-medium">Kelola dan verifikasi pendaftaran atlet Kempo.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-1">
                <button wire:click="$set('status', '')" 
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $status === '' ? 'bg-orange-600 text-white shadow-lg shadow-orange-200' : 'text-slate-500 hover:bg-slate-50' }}">
                    SEMUA
                </button>
                <button wire:click="$set('status', 'pending')" 
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $status === 'pending' ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-100' : 'text-slate-500 hover:bg-slate-50' }}">
                    PENDING
                </button>
                <button wire:click="$set('status', 'verified')" 
                    class="px-4 py-2 rounded-xl text-xs font-black transition-all {{ $status === 'verified' ? 'bg-green-600 text-white shadow-lg shadow-green-100' : 'text-slate-500 hover:bg-slate-50' }}">
                    VERIFIED
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Pendaftaran</p>
                <h3 class="text-2xl font-black text-slate-800">{{ \App\Models\Registration::count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-file-invoice text-slate-200 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-yellow-600 uppercase tracking-widest mb-1">Menunggu Verifikasi</p>
                <h3 class="text-2xl font-black text-slate-800">{{ \App\Models\Registration::where('status', 'pending')->count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-yellow-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-clock text-yellow-100 text-3xl"></i>
            </div>
        </div>
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Terverifikasi</p>
                <h3 class="text-2xl font-black text-slate-800">{{ \App\Models\Registration::where('status', 'verified')->count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-green-50 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-check-circle text-green-100 text-3xl"></i>
            </div>
        </div>
        <div class="bg-orange-600 p-6 rounded-3xl shadow-xl shadow-orange-100 relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-orange-200 uppercase tracking-widest mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-white">Rp {{ number_format(\App\Models\Registration::where('status', 'verified')->sum('final_amount'), 0, ',', '.') }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fas fa-wallet text-white/20 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Cari Kontingen atau Kode Referral..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-medium focus:bg-white focus:ring-4 focus:ring-orange-500/5 transition-all outline-none">
            </div>
            <div class="flex items-center gap-2">
                <select wire:model.live="perPage" class="bg-slate-50 border border-slate-100 rounded-xl px-3 py-2 text-xs font-bold text-slate-600 outline-none focus:ring-2 focus:ring-orange-500/20">
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                    <option value="50">50 Baris</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontingen / Kode</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Peserta</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Daftar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($registrations as $reg)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-700 leading-none mb-1 uppercase tracking-tight">{{ $reg->contingent?->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] font-bold text-orange-500 font-mono tracking-wider">{{ $reg->referral_code }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex flex-col items-center">
                                    <span class="text-xs font-black text-slate-700">{{ $reg->athletes_count ?? $reg->athletes()->count() }} Atlet</span>
                                    <span class="text-[9px] font-bold text-slate-400">{{ $reg->officials_count ?? $reg->officials()->count() }} Official</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-black text-slate-700 font-mono italic">Rp {{ number_format($reg->final_amount, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($reg->status === 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-yellow-100 text-yellow-700 uppercase tracking-widest">
                                        <div class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></div>
                                        Pending
                                    </span>
                                @elseif($reg->status === 'verified')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-green-100 text-green-700 uppercase tracking-widest">
                                        <div class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></div>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-black bg-red-100 text-red-700 uppercase tracking-widest">
                                        <div class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></div>
                                        Rejected
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold text-slate-500">{{ $reg->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.registrations.show', $reg->id) }}" 
                                        class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center hover:bg-orange-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <button onclick="confirmDelete('{{ $reg->id }}')" 
                                        class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                    <i class="fas fa-inbox text-slate-200 text-2xl"></i>
                                </div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest italic">Tidak ada data pendaftaran ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-50 bg-slate-50/30">
            {{ $registrations->links() }}
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: "Seluruh data atlet dan official di pendaftaran ini akan terhapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-6 py-3 font-black text-xs uppercase tracking-widest',
                    cancelButton: 'rounded-xl px-6 py-3 font-black text-xs uppercase tracking-widest'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteRegistration', id);
                }
            })
        }
    </script>
</div>
