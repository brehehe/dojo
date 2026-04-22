<div class="space-y-10">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Daftar Kontingen</h1>
            <p class="text-slate-900 font-medium">Kelola pendaftaran dan verifikasi kontingen PERKEMI</p>
        </div>
        <div class="flex gap-3">
            <select wire:model.live="statusFilter"
                class="bg-white border-2 border-slate-100 rounded-2xl px-6 py-3 text-[15px] font-bold text-black focus:border-orange-500 outline-none transition-all">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="relative group">
        <i
            class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-orange-500 transition-colors"></i>
        <input type="text" wire:model.live="search" placeholder="Cari nama kontingen atau penanggung jawab..."
            class="w-full pl-16 pr-8 py-5 bg-white border-2 border-slate-100 rounded-[2rem] shadow-sm focus:border-orange-500 outline-none transition-all text-slate-800 font-semibold placeholder:text-slate-300">
    </div>

    <!-- Content Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-800 text-white">
                    <tr
                        class="text-slate-800 text-[15px] font-black uppercase tracking-[0.2em] border-b border-slate-100 leading-none">
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">ID</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Kontingen / Wilayah</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">P. Jawab / Phone</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Metode Bayar</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-right w-[1%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($contingents as $contingent)
                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                            <td class="py-6 px-8 border-r border-slate-200">
                                <span class="text-[15px] font-black text-slate-300">#{{ $contingent->id }}</span>
                            </td>
                            <td class="py-6 px-8 border-r border-slate-200">
                                <div class="flex flex-col min-w-[200px]">
                                    <span
                                        class="text-slate-800 font-extrabold tracking-tight">{{ $contingent->name }}</span>
                                    <span
                                        class="text-[15px] text-slate-800 font-bold uppercase tracking-widest">{{ $contingent->kab_kota }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 border-r border-slate-200">
                                <div class="flex flex-col">
                                    <span class="font-bold">{{ $contingent->leader_name }}</span>
                                    <span class="text-[15px] text-slate-900">{{ $contingent->leader_phone }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 border-r border-slate-200">
                                <span
                                    class="bg-slate-100 text-slate-900 px-3 py-1 rounded-full text-[15px] font-black uppercase tracking-tight">
                                    {{ $contingent->payment_method ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-6 px-8 border-r border-slate-200">
                                <span @class([
                                    'px-4 py-2 rounded-xl text-[15px] font-black uppercase tracking-[0.1em]',
                                    'bg-amber-100 text-amber-600 border border-amber-200' => $contingent->status === 'pending',
                                    'bg-emerald-100 text-emerald-600 border border-emerald-200' => $contingent->status === 'confirmed',
                                    'bg-red-100 text-red-600 border border-red-200' => $contingent->status === 'rejected',
                                ])>
                                    {{ $contingent->status }}
                                </span>
                            </td>
                            <td class="py-6 px-8 text-right border-r border-slate-200">
                                <a href="{{ route('admin.master.contingents.detail', $contingent) }}"
                                    class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-bold text-[15px] uppercase tracking-widest shadow-lg shadow-orange-600/20 transition-all active:scale-95">
                                    <i class="fas fa-search"></i>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-20 text-center border-r border-slate-200">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-folder-open text-4xl text-slate-200 mb-4"></i>
                                    <p class="text-slate-800 font-bold uppercase tracking-widest">Data tidak ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contingents->hasPages())
            <div class="mt-10 pt-10 border-t border-slate-50">
                {{ $contingents->links('livewire.admin.pagination') }}
            </div>
        @endif
    </div>
</div>