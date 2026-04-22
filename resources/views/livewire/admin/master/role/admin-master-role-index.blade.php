<div class="space-y-6 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Master Roles</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider italic">Pengelolaan Jabatan dan Otoritas Akses Sistem</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('admin.master.roles.create') }}" wire:navigate
                class="flex-1 md:flex-none bg-orange-600 hover:bg-orange-700 text-white px-5 py-2.5 rounded-xl font-black shadow-lg shadow-orange-600/20 transition-all flex items-center justify-center gap-2 active:scale-95 text-[15px] uppercase tracking-widest">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Role</span>
            </a>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="bg-white rounded-2xl p-2 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center gap-2">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-800">
                <i class="fas fa-search text-[15px]"></i>
            </span>
            <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama role..."
                class="pl-10 !border-none shadow-none text-[15px] font-bold" />
        </div>
    </div>

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($roles as $role)
            <div class="group bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-600/5 transition-all duration-500 relative overflow-hidden">
                <!-- BG Decoration -->
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-slate-50 rounded-full group-hover:bg-orange-50 group-hover:scale-150 transition-all duration-700 pointer-events-none"></div>
                
                <div class="relative z-10 space-y-4">
                    <div class="flex justify-between items-start">
                        <div class="w-12 h-12 bg-slate-50 group-hover:bg-orange-100 rounded-2xl flex items-center justify-center text-slate-800 group-hover:text-orange-600 transition-colors duration-500 shadow-sm">
                            <i class="fas fa-user-shield text-xl"></i>
                        </div>
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.master.roles.edit', $role->id) }}" wire:navigate
                                class="w-10 h-10 flex items-center justify-center text-slate-300 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all">
                                <i class="fas fa-edit text-[15px]"></i>
                            </a>
                            @if($role->name !== 'Super Admin')
                                <button type="button" 
                                    onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}')"
                                    class="w-10 h-10 flex items-center justify-center text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                    <i class="fas fa-trash-alt text-[15px]"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-base font-black text-slate-800 tracking-tight group-hover:text-orange-600 transition-colors">{{ $role->name }}</h3>
                        <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest italic">Default System Role</p>
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <div class="flex flex-col">
                            <span class="text-xl font-black text-slate-800 leading-none tracking-tighter">{{ $role->users_count }}</span>
                            <span class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Total Users</span>
                        </div>
                        <div class="h-8 w-px bg-slate-100"></div>
                        <div class="flex flex-col">
                            <span class="text-[15px] font-black text-slate-800 uppercase tracking-widest">{{ $role->permissions->count() }}</span>
                            <span class="text-[15px] font-black text-slate-800 uppercase tracking-widest">Capabilities</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-[2rem] border border-dashed border-slate-200 flex flex-col items-center gap-4">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-200">
                    <i class="fas fa-user-lock text-3xl"></i>
                </div>
                <p class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Tidak ada role ditemukan.</p>
            </div>
        @endforelse
    </div>

    @if($roles->hasPages())
        <div class="pt-4">
            {{ $roles->links('livewire.admin.pagination') }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Role?',
            text: `Role "${name}" akan dihapus secara permanen. Pengguna dengan role ini akan kehilangan akses!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[1.5rem]',
                confirmButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3',
                cancelButton: 'rounded-xl font-bold uppercase tracking-widest text-[15px] px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteRole(id);
            }
        })
    }
</script>
@endpush
