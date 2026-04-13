<div class="space-y-6 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Kontingen</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider italic">Pengelolaan Data Dojo, Ranting, dan Unit Kontingen Daerah</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.master.contingents.create') }}" wire:navigate
                class="flex-1 md:flex-none bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95 text-[10px] uppercase tracking-widest">
                <i class="fas fa-plus-circle text-xs"></i>
                <span>Register Kontingen</span>
            </a>
        </div>
    </div>

    <!-- Stats Snapshot -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 group hover:border-orange-200 transition-all cursor-default">
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 text-lg">
                <i class="fas fa-building-user"></i>
            </div>
            <div>
                <p class="text-xl font-black text-slate-800 leading-none tracking-tighter">{{ $contingents->total() }}</p>
                <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest mt-1">Total Unit</p>
            </div>
        </div>
        <!-- Add more stats if needed -->
    </div>

    <!-- Toolbar -->
    <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                <i class="fas fa-search text-xs"></i>
            </span>
            <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari kontingen, kab/kota, atau ketua..."
                class="pl-10 !border-none shadow-none text-sm font-bold" />
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto pr-2">
            <select wire:model.live="statusFilter" class="bg-slate-50 border-none text-[10px] font-black uppercase tracking-widest text-slate-600 rounded-xl px-4 py-2 focus:ring-orange-500/20 transition-all cursor-pointer">
                <option value="">Semua Status</option>
                <option value="PENDING">Pending</option>
                <option value="VERIFIED">Terverifikasi</option>
                <option value="REJECTED">Ditolak</option>
            </select>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-50">Kontingen / Region</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-50">Penanggung Jawab</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-50">Kekuatan Tim</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-50">Status</th>
                        <th class="py-4 px-6 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-50 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($contingents as $contingent)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center text-slate-600 font-black shadow-sm group-hover:scale-110 transition-transform duration-500 relative">
                                        {{ substr($contingent->name, 0, 1) }}
                                        <div class="absolute -right-1 -top-1 px-1 min-w-[16px] h-4 bg-orange-600 rounded-lg text-[7px] flex items-center justify-center text-white border-2 border-white font-black uppercase">
                                            {{ $contingent->registrations->first()->referral_code ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-black text-slate-800 group-hover:text-orange-600 transition-colors text-[13px] tracking-tight uppercase">{{ $contingent->name }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest italic leading-none mt-1">{{ $contingent->kab_kota }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-700 tracking-tight">{{ $contingent->leader_name }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold italic">{{ $contingent->leader_phone }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-800 leading-none">{{ $contingent->athletes_count }}</span>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Atlet</span>
                                    </div>
                                    <div class="w-px h-6 bg-slate-100"></div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-800 leading-none">{{ $contingent->officials_count }}</span>
                                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Official</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $latestReg = $contingent->registrations->first();
                                    $statusStr = $latestReg->status ?? 'PENDING';
                                    $statusClasses = match(strtoupper($statusStr)) {
                                        'VERIFIED' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'REJECTED' => 'bg-red-50 text-red-600 border-red-100',
                                        default => 'bg-orange-50 text-orange-600 border-orange-100',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full border {{ $statusClasses }} text-[9px] font-black uppercase tracking-widest shadow-sm">
                                    {{ $statusStr }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-2 group-hover:translate-x-0 transition-transform duration-300">
                                    <a href="{{ route('admin.master.contingents.detail', $contingent->id) }}" wire:navigate
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.master.contingents.edit', $contingent->id) }}" wire:navigate
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button" 
                                        onclick="confirmDelete({{ $contingent->id }}, '{{ $contingent->name }}')"
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-32 text-center">
                                <div class="flex flex-col items-center gap-6">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                                        <i class="fas fa-building-circle-exclamation text-3xl"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="font-black text-slate-400 uppercase tracking-widest text-xs">Data Kontingen Kosong</p>
                                        <p class="text-[10px] text-slate-400 font-medium italic">Silakan tambahkan data kontingen baru untuk memulai pendaftaran.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contingents->hasPages())
            <div class="p-4 bg-slate-50/50 border-t border-slate-100">
                {{ $contingents->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Kontingen?',
            text: `Data "${name}" akan dihapus secara permanen beserta seluruh file terkait!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[1.5rem]',
                confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3',
                cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteContingent(id);
            }
        })
    }
</script>
@endpush
