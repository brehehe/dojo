<div class="space-y-6 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $isEdit ? 'Update Role' : 'Tambah Role Baru' }}</h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[11px] text-slate-500 font-bold uppercase tracking-wider italic">Konfigurasi Nama Jabatan dan Hak Akses Spesifik</p>
            </div>
        </div>
        <a href="{{ route('admin.master.roles.index') }}" wire:navigate
            class="group px-4 py-2 text-slate-400 hover:text-slate-600 transition-all flex items-center gap-2">
            <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
            <span class="text-[10px] font-black uppercase tracking-widest">Kembali ke List</span>
        </a>
    </div>

    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Basic Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 space-y-6">
                <div class="flex items-center gap-3 pb-2 border-b border-slate-50">
                    <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                    <h3 class="text-[11px] font-black uppercase tracking-widest text-slate-800">Nama Role</h3>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Identitas Jabatan</label>
                    <x-input wire:model="name" type="text" placeholder="Masukkan nama role (cth: Manager)" />
                    @error('name') <p class="text-[10px] text-red-500 mt-1 italic font-bold ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <p class="text-[10px] text-slate-500 font-medium leading-relaxed italic">
                        Pastikan nama role deskriptif. Perubahan nama role akan berdampak pada identifikasi sistem di berbagai modul.
                    </p>
                </div>
                
                <button type="submit" 
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-orange-600/30 transition-all active:scale-95 flex items-center justify-center gap-3">
                    <i class="fas fa-save shadow-sm"></i>
                    <span>{{ $isEdit ? 'Update Role' : 'Simpan Role' }}</span>
                </button>
            </div>
        </div>

        <!-- Right: Permissions -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 space-y-8">
                <div class="flex items-center justify-between pb-2 border-b border-slate-50">
                    <div class="flex items-center gap-3">
                        <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                        <h3 class="text-[11px] font-black uppercase tracking-widest text-slate-800">Capabilities & Permissions</h3>
                    </div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic">Pilih fitur yang dapat diakses</p>
                </div>

                @if($permissionGroups->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($permissionGroups as $group => $permissions)
                            <div class="space-y-4">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 rounded-md bg-orange-50 text-orange-600 text-[8px] font-black uppercase tracking-widest border border-orange-100">{{ $group }}</span>
                                    <div class="h-px flex-1 bg-slate-50"></div>
                                </div>
                                <div class="grid grid-cols-1 gap-2.5">
                                    @foreach($permissions as $permission)
                                        <label class="group flex items-center gap-3 p-3 rounded-xl border border-slate-50 hover:border-orange-100 hover:bg-orange-50/30 cursor-pointer transition-all duration-300">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}"
                                                    class="w-5 h-5 rounded-lg border-slate-200 text-orange-600 focus:ring-orange-500/20 transition-all cursor-pointer">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-xs font-black text-slate-700 group-hover:text-orange-700 transition-colors capitalize">{{ str_replace('_', ' ', str_replace($group . '.', '', $permission->name)) }}</span>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic leading-none">{{ $permission->name }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-20 text-center space-y-4">
                        <div class="w-16 h-16 bg-slate-50 rounded-2xl mx-auto flex items-center justify-center text-slate-200">
                            <i class="fas fa-shield-alt text-3xl"></i>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 italic">Belum ada permission terdaftar di sistem.</p>
                            <p class="text-[9px] text-slate-400 font-medium italic">Gunakan seeder atau command line untuk membuat permission baru.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>
