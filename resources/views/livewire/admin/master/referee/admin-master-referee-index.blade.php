<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Master Wasit</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Database Perwasitan & Lisensi</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-plus-circle text-xs"></i>
                <span class="uppercase text-[9px] tracking-[0.2em]">Tambah Wasit</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-3 bg-white rounded-lg p-1.5 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-search text-[10px]"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, email, atau tingkat sertifikasi..."
                    class="w-full pl-10 pr-4 py-2 bg-slate-50 border-0 rounded-md text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-orange-500/20 transition-all text-[11px] font-bold italic">
            </div>
            
            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-md min-w-fit w-full md:w-auto">
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage" class="bg-transparent border-0 text-slate-700 text-[11px] font-black focus:ring-0 cursor-pointer p-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-lg p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total Referee</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black text-white leading-none tracking-tighter">{{ $referees->total() }}</span>
                <span class="text-[9px] font-black text-white/70 uppercase tracking-widest">Officials</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-3 px-6 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Personal Wasit</th>
                        <th class="py-3 px-6 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Sertifikasi</th>
                        <th class="py-3 px-6 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100">Kontak</th>
                        <th class="py-3 px-6 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] border-b border-slate-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 font-medium divide-y divide-slate-50">
                    @forelse($referees as $referee)
                        <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center text-slate-600 font-black shadow-sm group-hover:scale-110 transition-transform duration-500 overflow-hidden">
                                            @if($referee->photo)
                                                <img src="{{ Storage::url($referee->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ $referee->user->initials() }}
                                            @endif
                                        </div>
                                        <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-blue-500 border-2 border-white rounded-full"></div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors text-[13px]">{{ $referee->user->name }}</span>
                                        <span class="text-[9px] text-slate-400 font-extrabold uppercase tracking-widest italic">{{ $referee->nik ?: 'NIK BELUM DISET' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-0.5 rounded-md border bg-blue-50 text-blue-600 border-blue-100 text-[8px] font-black uppercase tracking-widest shadow-sm w-fit">
                                        {{ $referee->certification_level ?: 'Tanpa Sertifikasi' }}
                                    </span>
                                    <span class="text-[9px] text-slate-400 font-bold tracking-tight pl-1">{{ $referee->license_number ?: '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-600">{{ $referee->phone ?: '-' }}</span>
                                    <span class="text-[9px] text-slate-400 font-medium lowercase tracking-tight">{{ $referee->user->email }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-2 group-hover:translate-x-0 transition-transform duration-300">
                                    <button wire:click="showEditModal({{ $referee->id }})"
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all border border-transparent hover:border-blue-100 shadow-sm hover:shadow-md">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                title: 'Hapus Wasit?',
                                                text: 'Data {{ $referee->user->name }} dan akun loginnya akan dihapus permanen!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#2563eb',
                                                cancelButtonColor: '#64748b',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal',
                                                customClass: {
                                                    popup: 'rounded-[2rem]',
                                                    confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3',
                                                    cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-xs px-6 py-3'
                                                }
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    $wire.deleteReferee({{ $referee->id }})
                                                }
                                            })"
                                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all border border-transparent hover:border-red-100 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-32 text-center">
                                <div class="flex flex-col items-center gap-6">
                                    <div class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center text-slate-200">
                                        <i class="fas fa-user-tie text-5xl"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="font-black text-slate-400 uppercase tracking-[0.2em] text-sm">Data Wasit Kosong</p>
                                        <p class="text-slate-400 text-xs font-medium">Klik "Tambah Wasit" untuk registrasi perwasitan baru.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($referees->hasPages())
            <div class="py-3 px-6 bg-slate-50/50 border-t border-slate-100">
                {{ $referees->links() }}
            </div>
        @endif
    </div>

    <!-- Referee Modal -->
    @if($showingRefereeModal)
        <div class="fixed inset-0 z-[200] overflow-y-auto" role="dialog" aria-modal="true" x-data x-init="$el.focus()">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"
                    wire:click="$set('showingRefereeModal', false)"></div>

                <div class="relative bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-white/20 animate-in zoom-in-95 duration-200">
                    <!-- Modal Header -->
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-8 text-white relative">
                        <button wire:click="$set('showingRefereeModal', false)" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="space-y-1">
                            <span class="text-[9px] font-black uppercase tracking-[0.3em] text-orange-400">Database Perwasitan</span>
                            <h3 class="text-xl font-black tracking-tight">
                                {{ $refereeIdBeingEdited ? 'Update Profil Wasit' : 'Registrasi Wasit Baru' }}
                            </h3>
                            <p class="text-white/40 text-[10px] font-bold uppercase tracking-wider">Manajemen akun login & kredensial</p>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <form wire:submit="saveReferee" class="space-y-8">
                            <!-- Section: Account -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-4 w-1 bg-orange-600 rounded-full"></div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-800">1. Akses Login</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Nama Lengkap</label>
                                        <input wire:model="name" type="text" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 focus:bg-white focus:border-orange-500/20 font-bold text-sm">
                                        @error('name') <p class="text-[9px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Email Login</label>
                                        <input wire:model="email" type="email" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 focus:bg-white focus:border-orange-500/20 font-bold text-sm">
                                        @error('email') <p class="text-[9px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Password {{ $refereeIdBeingEdited ? '(Kosongkan jika tidak diganti)' : '' }}</label>
                                        <input wire:model="password" type="password" placeholder="••••••••" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 focus:bg-white focus:border-orange-500/20 font-bold text-sm">
                                        @error('password') <p class="text-[9px] text-red-500 mt-1 italic">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Detail -->
                            <div class="space-y-4 pt-4 border-t border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="h-4 w-1 bg-orange-600 rounded-full"></div>
                                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-800">2. Profil Wasit</h4>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">NIK (KTP)</label>
                                        <input wire:model="nik" type="text" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 font-bold text-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">WhatsApp</label>
                                        <input wire:model="phone" type="text" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 font-bold text-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Sertifikasi</label>
                                        <select wire:model="certification_level" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 font-bold text-sm appearance-none">
                                            <option value="">Pilih...</option>
                                            <option value="Internasional">Internasional</option>
                                            <option value="Nasional A">Nasional A</option>
                                            <option value="Nasional B">Nasional B</option>
                                            <option value="Nasional C">Nasional C</option>
                                            <option value="Daerah">Daerah</option>
                                        </select>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">No Lisensi</label>
                                        <input wire:model="license_number" type="text" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 font-bold text-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Tempat Lahir</label>
                                        <input wire:model="birth_place" type="text" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 font-bold text-sm">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Tgl Lahir</label>
                                        <input wire:model="birth_date" type="date" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 font-bold text-sm">
                                    </div>
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 ml-1">Alamat Lengkap</label>
                                        <textarea wire:model="address" rows="2" class="w-full px-4 py-3 bg-slate-50 border-2 border-transparent rounded-xl text-slate-700 font-bold text-sm"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6">
                                <button type="submit"
                                    class="w-full bg-orange-600 hover:bg-orange-700 text-white py-4 rounded-xl font-black uppercase tracking-widest text-[11px] shadow-lg shadow-orange-600/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-save opacity-50"></i>
                                    <span>Simpan Data Perwasitan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</div>
