<div class="space-y-4 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master User</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider">Kelola pengguna dan hak akses
                    sistem</p>
            </div>
        </div>
        <div class="w-full md:w-auto">
            <button wire:click="showCreateModal"
                class="w-full md:w-auto group bg-gradient-to-br from-orange-500 to-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95">
                <i class="fas fa-plus-circle text-[15px]"></i>
                <span class="uppercase text-[15px] tracking-[0.2em]">Tambah User</span>
            </button>
        </div>
    </div>

    <!-- Stats & Toolbar -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div
            class="md:col-span-3 bg-white rounded-xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-800">
                    <i class="fas fa-search text-[15px]"></i>
                </span>
                <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..."
                    variant="filter" class="pl-10 !border-none shadow-none" />
            </div>

            <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg min-w-fit w-full md:w-auto">
                <span
                    class="text-[15px] font-black uppercase tracking-widest text-slate-800 whitespace-nowrap">Show:</span>
                <select wire:model.live="perPage"
                    class="bg-transparent border-0 text-black text-[15px] font-black focus:ring-0 cursor-pointer p-0">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="all">All</option>
                </select>
            </div>
        </div>

        <div
            class="bg-gradient-to-br from-orange-500 to-orange-700 rounded-xl p-4 shadow-lg flex flex-col justify-center border border-white/10 relative overflow-hidden group">
            <div
                class="absolute -right-4 -bottom-4 w-16 h-16 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform">
            </div>
            <span class="text-[15px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5 relative z-10">Total
                Registry</span>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-xl font-black text-white leading-none tracking-tighter">{{ $users->total() }}</span>
                <span class="text-[15px] font-black text-white/70 uppercase tracking-widest">Accounts</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-800 text-white">
                    <tr class="bg-slate-800">
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Nama Pengguna</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Hak Akses</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">
                            Registry</th>
                        <th
                            class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-right w-[1%]">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($users as $user)
                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl flex items-center justify-center text-slate-900 font-black shadow-sm group-hover:scale-110 transition-transform duration-500">
                                            {{ $user->initials() }}
                                        </div>
                                        <div
                                            class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full">
                                        </div>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-800 group-hover:text-orange-600 transition-colors text-[15px]">{{ $user->name }}</span>
                                        <span class="text-[15px] text-slate-800 font-semibold">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($user->roles as $role)
                                                                    @php
                                                                        $roleColor = match ($role->name) {
                                                                            'Super Admin' => 'bg-red-50 text-red-600 border-red-100',
                                                                            'Admin' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                                            'Pendaftaran' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                                            'Pertandingan' => 'bg-violet-50 text-violet-600 border-violet-100',
                                                                            'Panitera' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                                            'Perwasitan' => 'bg-fuchsia-50 text-fuchsia-600 border-fuchsia-100',
                                                                            default => 'bg-slate-50 text-slate-900 border-slate-100'
                                                                        };
                                                                    @endphp
                                         <span
                                                                        class="px-2 py-0.5 rounded-md border {{ $roleColor }} text-[15px] font-black uppercase tracking-widest shadow-sm">
                                                                        {{ $role->name }}
                                                                    </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4 px-6 border-r border-slate-200">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[15px] font-bold text-slate-900">{{ $user->created_at->format('d M Y') }}</span>
                                    <span
                                        class="text-[15px] text-slate-800 uppercase font-black tracking-widest">{{ $user->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right border-r border-slate-200">
                                <div
                                    class="flex items-center justify-end gap-2">
                                    <button wire:click="showEditModal({{ $user->id }})"
                                        class="w-10 h-10 flex items-center justify-center text-blue-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all border border-transparent hover:border-orange-100 shadow-sm hover:shadow-md">
                                        <i class="fas fa-edit text-[15px]"></i>
                                    </button>
                                    <button type="button" onclick="Swal.fire({
                                                    title: 'Hapus User?',
                                                    text: 'Data {{ $user->name }} akan dihapus secara permanen!',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#ea580c',
                                                    cancelButtonColor: '#64748b',
                                                    confirmButtonText: 'Ya, Hapus!',
                                                    cancelButtonText: 'Batal',
                                                    customClass: {
                                                        popup: 'rounded-[2rem]',
                                                        confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3',
                                                        cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3'
                                                    }
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        $wire.deleteUser({{ $user->id }})
                                                    }
                                                })"
                                        class="w-10 h-10 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all border border-transparent hover:border-red-100 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash-alt text-[15px]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-32 text-center border-r border-slate-200">
                                <div class="flex flex-col items-center gap-6">
                                    <div
                                        class="w-24 h-24 bg-slate-50 rounded-[2.5rem] flex items-center justify-center text-slate-200">
                                        <i class="fas fa-users text-5xl"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="font-black text-slate-800 uppercase tracking-[0.2em] text-[15px]">Data Kosong
                                        </p>
                                        <p class="text-slate-800 text-[15px] font-medium">Tidak ada user yang ditemukan dalam
                                            sistem.</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="px-4 py-2 bg-slate-50/50 border-t border-slate-100">
                {{ $users->links('livewire.admin.pagination') }}
            </div>
        @endif
    </div>

    <!-- User Modal -->
    @if($showingUserModal)
        <x-modal wire:model.live="showingUserModal" title="{{ $userIdBeingEdited ? 'Perbarui User' : 'Tambah User Baru' }}"
            maxWidth="xl">
            <form wire:submit="saveUser" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Nama
                            Lengkap</label>
                        <x-input wire:model="name" type="text" placeholder="Nama Lengkap" />
                        @error('name') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Email
                            Bisnis</label>
                        <x-input wire:model="email" type="email" placeholder="example@mail.com" />
                        @error('email') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Password
                        {{ $userIdBeingEdited ? '(Opsional)' : '' }}</label>
                    <x-input wire:model="password" type="password" placeholder="••••••••" />
                    @error('password') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Tingkat
                        Otoritas</label>
                    <x-select wire:model="selectedRole" :options="$roles->pluck('name', 'name')->toArray()"
                        placeholder="Pilih Otoritas..." />
                    @error('selectedRole') <p class="text-[15px] text-red-500 mt-1.5 ml-1 font-bold italic">{{ $message }}
                    </p> @enderror
                </div>
            </form>

            <x-slot name="footer">
                <button wire:click="$set('showingUserModal', false)"
                    class="px-6 py-3 text-[15px] font-black uppercase tracking-widest text-slate-800 hover:text-slate-900 transition-all">
                    Batal
                </button>
                <button wire:click="saveUser"
                    class="bg-orange-600 hover:bg-orange-700 text-white px-10 py-3 rounded-2xl font-black uppercase tracking-widest text-[15px] shadow-xl shadow-orange-600/30 transition-all active:scale-95 flex items-center gap-2">
                    <span>Simpan Akun</span>
                    <i class="fas fa-arrow-right text-[15px] opacity-100"></i>
                </button>
            </x-slot>
        </x-modal>
    @endif
</div>