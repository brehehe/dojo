<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Nomor Pertandingan</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider italic">Master Data Konfigurasi
                    Lomba</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-plus-circle text-xs"></i>
                <span class="uppercase text-[10px] tracking-widest">Tambah Nomor</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="md:col-span-3 bg-white rounded-xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </span>
                <x-input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Cari nomor pertandingan atau kategori..." variant="filter"
                    class="pl-10 !border-none shadow-none" />
            </div>

            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full md:w-auto">
                <span
                    class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage"
                    class="bg-transparent border-0 text-slate-700 text-xs font-black focus:ring-0 cursor-pointer p-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                </select>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div
                class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform">
            </div>
            <span class="text-[8px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total
                Registry</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span
                    class="text-xl font-black text-white leading-none tracking-tighter">{{ $matchNumbers->total() }}</span>
                <span class="text-[8px] font-black text-white/70 uppercase tracking-widest">Numbers</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            Detail Nomor</th>
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            Kategori Umur</th>
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            Tipe</th>
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100">
                            Kapasitas</th>
                        <th
                            class="py-2 px-4 text-slate-400 text-[9px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($matchNumbers as $matchNumber)
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-300">
                            <td class="py-2 px-4">
                                <div class="flex flex-col gap-0.5">
                                    <span
                                        class="font-bold text-slate-800 text-sm tracking-tight group-hover:text-orange-600 transition-colors">{{ $matchNumber->name }}</span>
                                    <span class="text-[10px] text-slate-400 font-semibold italic">UUID:
                                        {{ substr($matchNumber->id, 0, 8) }}...</span>
                                </div>
                            </td>
                            <td class="py-2 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                                    <span
                                        class="text-xs font-bold text-slate-600">{{ $matchNumber->ageGroup?->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-4">
                                <span
                                    class="px-2.5 py-1 rounded-md {{ $matchNumber->draft_type === 'RANDORI' ? 'bg-blue-50 text-blue-600' : 'bg-orange-50 text-orange-600' }} text-[9px] font-black tracking-widest uppercase border {{ $matchNumber->draft_type === 'RANDORI' ? 'border-blue-100' : 'border-orange-100' }}">
                                    {{ $matchNumber->draft_type }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-users text-[10px] text-slate-300"></i>
                                    <span class="text-xs font-black text-slate-700">{{ $matchNumber->max_athletes }}</span>
                                    <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Atlet</span>
                                </div>
                            </td>
                            <td class="py-2 px-4 text-right">
                                <div
                                    class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all translate-x-4 group-hover:translate-x-0">
                                    <button wire:click="showAthletes({{ $matchNumber->id }})"
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all border border-transparent hover:border-blue-100"
                                        title="Lihat Atlet Terdaftar">
                                        <i class="fas fa-users text-xs"></i>
                                    </button>
                                    <button wire:click="showEditModal({{ $matchNumber->id }})"
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-xl transition-all border border-transparent hover:border-orange-100">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                                title: 'Hapus Data?',
                                                                text: 'Data nomor pertandingan akan dihapus!',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#ea580c',
                                                                cancelButtonColor: '#64748b',
                                                                confirmButtonText: 'Ya, Hapus!',
                                                                cancelButtonText: 'Batal',
                                                                customClass: {
                                                                    popup: 'rounded-[1.5rem]',
                                                                    confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-[10px] px-6 py-3',
                                                                    cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-[10px] px-6 py-3'
                                                                }
                                                            }).then((result) => {
                                                                if (result.isConfirmed) {
                                                                    $wire.deleteMatchNumber({{ $matchNumber->id }})
                                                                }
                                                            })"
                                        class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-24 text-center">
                                <div class="flex flex-col items-center gap-4">
                                    <div
                                        class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center text-slate-200">
                                        <i class="fas fa-layer-group text-4xl"></i>
                                    </div>
                                    <p class="font-black text-slate-400 uppercase tracking-widest text-[10px]">Data Kosong
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($matchNumbers->hasPages())
            <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
                {{ $matchNumbers->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($showingMatchNumberModal)
        <x-modal wire:model.live="showingMatchNumberModal"
            title="{{ $matchNumberIdBeingEdited ? 'Update Nomor Pertandingan' : 'Tambah Nomor Pertandingan' }}"
            maxWidth="md">
            <form wire:submit="saveMatchNumber" id="matchNumberForm" class="space-y-5">
                <!-- Nama Nomor Pertandingan -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500 ml-1">Nama Nomor
                        Pertandingan</label>
                    <x-input wire:model="name" type="text" placeholder="Contoh: Kata Individual" />
                    @error('name') <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Kelompok Umur -->
                    <div class="space-y-1.5 col-span-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500 ml-1">Kelompok
                            Umur</label>
                        <x-select wire:model="age_group_id" placeholder="Pilih Kelompok Umur..."
                            :options="$ageGroups->pluck('name', 'id')->toArray()" />
                        @error('age_group_id') <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold italic">{{ $message }}
                        </p> @enderror
                    </div>

                    <!-- Tipe Draft -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500 ml-1">Tipe Draft</label>
                        <x-select wire:model="draft_type" placeholder="Pilih Tipe..." :options="['embu' => 'Embu', 'randori' => 'Randori']" />
                        @error('draft_type') <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold italic">{{ $message }}
                        </p> @enderror
                    </div>

                    <!-- Max Atlet -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500 ml-1">Max Atlet</label>
                        <x-input wire:model="max_athletes" type="number" />
                        @error('max_athletes') <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold italic">{{ $message }}
                        </p> @enderror
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-slate-500 ml-1">Jenis
                            Kelamin</label>
                        <x-select wire:model="gender" placeholder="Pilih Jenis Kelamin..." :options="['Male' => 'Putra', 'Female' => 'Putri', 'Mix' => 'Mix']" />
                        @error('gender') <p class="text-[10px] text-red-500 mt-1 ml-1 font-bold italic">{{ $message }}
                        </p> @enderror
                    </div>
                </div>
            </form>

            <x-slot name="footer">
                <button type="button" wire:click="$set('showingMatchNumberModal', false)"
                    class="px-5 py-2.5 text-sm font-bold text-slate-400 hover:text-slate-600 transition-all active:scale-95">
                    Batal
                </button>
                <button type="submit" form="matchNumberForm"
                    class="px-10 py-3 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-2xl shadow-xl shadow-orange-600/30 transition-all active:scale-95">
                    {{ $matchNumberIdBeingEdited ? 'Update Data' : 'Simpan Perubahan' }}
                </button>
            </x-slot>
        </x-modal>
    @endif

    <!-- Athletes Detail Modal -->
    @if($showingAthletesModal)
        <x-modal wire:model.live="showingAthletesModal" 
            title="Daftar Atlet - {{ $selectedMatchNumber?->name }}" 
            maxWidth="4xl">
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm text-blue-600">
                        <i class="fas fa-info-circle text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-blue-900 uppercase tracking-widest">{{ $selectedMatchNumber?->ageGroup?->name }}</h4>
                        <p class="text-[10px] text-blue-700 font-bold italic opacity-70">Berikut adalah daftar atlet yang telah terdaftar pada nomor pertandingan ini.</p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Nama Atlet</th>
                                <th class="py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Kontingen</th>
                                <th class="py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Kyu</th>
                                <th class="py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 text-right">Berat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-slate-700">
                            @forelse($registeredAthletes as $athlete)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col gap-0.5">
                                            <span class="font-bold text-slate-800 text-xs tracking-tight">{{ $athlete['athlete_name'] }}</span>
                                            <span class="text-[9px] text-slate-400 font-bold">{{ $athlete['nik'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                            <span class="text-[10px] font-black text-slate-600 uppercase">{{ $athlete['contingent_name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[9px] font-black uppercase tracking-tighter">
                                            {{ $athlete['kyu'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="text-xs font-black text-slate-700 italic">{{ number_format($athlete['weight'], 1) }}</span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase">Kg</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-slate-200">
                                                <i class="fas fa-user-slash text-xl"></i>
                                            </div>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Belum ada atlet terdaftar</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <x-slot name="footer">
                <button type="button" wire:click="$set('showingAthletesModal', false)"
                    class="px-8 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-black rounded-xl shadow-sm transition-all active:scale-95 uppercase tracking-widest">
                    Tutup
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>