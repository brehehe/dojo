<div class="space-y-6 animate-in fade-in duration-700">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">
                {{ $athleteId ? 'Manajemen Master Atlet' : 'Registrasi Atlet Global' }}
            </h1>
            <div class="flex items-center gap-2">
                <span class="h-1 w-6 bg-orange-600 rounded-full"></span>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-wider italic">Sinkronisasi Database Atlet & Riwayat Kontingen</p>
            </div>
        </div>
        <a href="{{ route('admin.master.athletes.index') }}" 
            class="group px-4 py-2 text-slate-800 hover:text-slate-900 transition-all flex items-center gap-2">
            <i class="fas fa-arrow-left text-[15px] transition-transform group-hover:-translate-x-1"></i>
            <span class="text-[15px] font-black uppercase tracking-widest">Kembali ke List</span>
        </a>
    </div>

    <!-- Step 1: NIK Verification Card (Clean White Theme) -->
    <div class="bg-white rounded-3xl p-8 shadow-xl shadow-slate-200 border border-slate-100 relative overflow-hidden group">
        <!-- Decorative Background Gradient (Subtle) -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-600/5 blur-[100px] rounded-full group-hover:bg-orange-600/10 transition-all duration-1000"></div>
        
        <div class="relative z-10 max-w-2xl mx-auto text-center space-y-6">
            <div class="space-y-2">
                <h2 class="text-lg font-black text-slate-800 uppercase tracking-widest">Langkah 1: Verifikasi Identitas</h2>
                <p class="text-[15px] text-slate-900 font-bold uppercase tracking-widest">Masukkan 16 Digit NIK untuk mencari data master atau mendaftarkan profil baru</p>
            </div>

            <div class="flex flex-col gap-4">
                <div class="relative group/input">
                    <input wire:model="nik" 
                        wire:keydown.enter="searchAthleteByNik"
                        type="text" 
                        placeholder="0000 0000 0000 0000"
                        class="w-full bg-slate-50 border-2 border-slate-100 text-slate-800 text-2xl font-black tracking-[0.2em] text-center py-5 rounded-2xl focus:ring-0 focus:border-orange-600 transition-all placeholder:text-slate-200 placeholder:tracking-normal placeholder:font-normal"
                        maxlength="16" />
                    
                    <div wire:loading wire:target="nik, searchAthleteByNik" class="absolute right-6 top-1/2 -translate-y-1/2 text-orange-600 animate-spin">
                        <i class="fas fa-circle-notch text-xl"></i>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-3">
                    <button type="button" wire:click="searchAthleteByNik"
                        class="px-8 py-4 bg-orange-600 hover:bg-orange-500 text-white rounded-2xl text-[15px] font-black uppercase tracking-widest transition-all active:scale-95 shadow-xl shadow-orange-600/20 flex items-center gap-3">
                        <i class="fas fa-search"></i>
                        Cek Database Master
                    </button>
                    <button type="button" wire:click="resetForm"
                        class="px-6 py-4 bg-slate-100 hover:bg-slate-200 text-slate-900 rounded-2xl text-[15px] font-black uppercase tracking-widest transition-all active:scale-95 flex items-center gap-3">
                        <i class="fas fa-undo"></i>
                        Reset
                    </button>
                </div>
            </div>

            @error('nik') 
                <p class="text-[15px] text-red-500 font-bold italic animate-bounce">{{ $message }}</p> 
            @enderror
        </div>
    </div>

    <!-- Step 2: Information Banners & Form -->
    @if($showForm)
    <div class="animate-in fade-in slide-in-from-bottom-6 duration-700 space-y-6">
        <!-- Status Banner -->
        @if($isEdit)
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <div class="flex items-center gap-4 text-blue-800">
                    <div class="h-10 w-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[15px] font-black uppercase tracking-widest">Profil Master Ditemukan</h4>
                        <p class="text-[15px] font-bold text-blue-600/80 mt-0.5">Sistem telah memuat data master untuk NIK: {{ $nik }}. Silakan perbarui data di bawah jika ada perubahan.</p>
                    </div>
                    <div class="px-4 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-[15px] font-black uppercase tracking-widest">
                        Mode Edit Master
                    </div>
                </div>
            </div>
        @else
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
                <div class="flex items-center gap-4 text-emerald-800">
                    <div class="h-10 w-10 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-plus-circle text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-[15px] font-black uppercase tracking-widest">Pendaftaran Profil Baru</h4>
                        <p class="text-[15px] font-bold text-emerald-600/80 mt-0.5">NIK {{ $nik }} belum terdaftar. Silakan buat profil master atlet baru dengan mengisi formulir di bawah ini.</p>
                    </div>
                    <div class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-[15px] font-black uppercase tracking-widest">
                        Penambahan Baru
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Primary Data -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Identity Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 space-y-8">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-1.5 bg-orange-600 rounded-full"></div>
                                <h3 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Biodata Lengkap (Master)</h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 col-span-2">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Nama Lengkap Sesuai Dokumen Identitas</label>
                                <x-input wire:model="name" type="text" placeholder="Gunakan huruf kapital sesuai akte..." />
                                @error('name') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Jenis Kelamin</label>
                                <x-select wire:model="gender" placeholder="Pilih Jenis Kelamin..." 
                                    :options="['Male' => 'Laki-laki', 'Female' => 'Perempuan']" />
                                @error('gender') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Tanggal Lahir</label>
                                <x-input wire:model="birth_date" type="date" />
                                @error('birth_date') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2 col-span-2 bg-slate-50 p-6 rounded-2xl border border-dotted border-slate-200">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-900 ml-1 mb-2 block">Daftarkan/Pindahkan ke Kontingen</label>
                                <x-select wire:model="contingent_id" placeholder="Pilih Kontingen Aktif..." 
                                    :options="$contingents->pluck('name', 'id')->toArray()" />
                                <p class="text-[15px] text-slate-800 mt-2 italic font-medium">* Tindakan ini akan secara otomatis mencatat riwayat perpindahan atlet.</p>
                                @error('contingent_id') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Technical & Match Data -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 space-y-8">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="h-8 w-1.5 bg-orange-600 rounded-full"></div>
                            <h3 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Spesifikasi Teknik & Pertandingan</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Tingkatan (Kyu/Dan)</label>
                                <x-select wire:model="kyu" placeholder="Pilih Kyu/Dan..." 
                                    :options="$kyuLevels->pluck('name', 'name')->toArray()" />
                                @error('kyu') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Berat Badan Aktif (KG)</label>
                                <x-input wire:model="weight" type="number" step="0.1" placeholder="00.0" />
                                @error('weight') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Dojo Utama/Asal</label>
                                <x-input wire:model="dojo_origin" type="text" placeholder="Nama Dojo atau Ranting" />
                                @error('dojo_origin') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Wilayah/Kota Domisili</label>
                                <x-input wire:model="city" type="text" placeholder="Kabupaten/Kota" />
                                @error('city') <p class="text-[15px] text-red-500 mt-1 italic font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Achievements -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 space-y-8">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-1.5 bg-orange-600 rounded-full"></div>
                                <h3 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Catatan Prestasi</h3>
                            </div>
                            <button type="button" wire:click="addAchievement"
                                class="px-4 py-2 bg-orange-50 text-orange-600 rounded-xl text-[15px] font-black uppercase tracking-widest hover:bg-orange-100 transition-all flex items-center gap-2">
                                <i class="fas fa-plus-circle"></i>
                                Tambah Record
                            </button>
                        </div>

                        <div class="space-y-4">
                            @foreach($achievement_history as $index => $achievement)
                                <div class="flex gap-3 group animate-in slide-in-from-right-4 duration-300">
                                    <div class="flex-1">
                                        <x-input wire:model="achievement_history.{{ $index }}" type="text" placeholder="Sebutkan medali, kategori, dan tahun event..." />
                                    </div>
                                    @if(count($achievement_history) > 1)
                                        <button type="button" wire:click="removeAchievement({{ $index }})"
                                            class="w-12 h-12 flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 rounded-2xl transition-all border border-transparent hover:border-red-100">
                                            <i class="fas fa-trash-alt text-[15px]"></i>
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Verification & History -->
                <div class="space-y-6">
                    <!-- History Timeline Card (White Theme) -->
                    @if($isEdit && $athleteId)
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="h-6 w-1 bg-slate-800 rounded-full"></div>
                            <h3 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Timeline Kontingen</h3>
                        </div>

                        <div class="space-y-8 relative ml-2">
                             <!-- Timeline Line -->
                            <div class="absolute left-3 top-2 bottom-2 w-0.5 bg-slate-100"></div>

                            @php
                                $histories = \App\Models\Athlete::find($athleteId)?->contingentHistories()
                                    ->with('contingent')
                                    ->get();
                            @endphp

                            @forelse($histories ?? [] as $history)
                                <div class="relative pl-10 group">
                                    <!-- Timeline Dot -->
                                    <div class="absolute left-0 top-1.5 w-6 h-6 bg-white border-2 {{ $loop->first ? 'border-orange-600' : 'border-slate-200' }} rounded-full flex items-center justify-center z-10 shadow-sm">
                                        <div class="w-2 h-2 {{ $loop->first ? 'bg-orange-600' : 'bg-slate-200' }} rounded-full"></div>
                                    </div>
                                    
                                    <div class="space-y-1.5">
                                        <p class="text-[15px] font-black text-slate-800 leading-tight uppercase tracking-tight">
                                            {{ $history->contingent->name ?? 'Kontingen Dihapus' }}
                                        </p>
                                        <div class="flex items-center gap-2 text-[15px] font-bold text-slate-800">
                                            <i class="far fa-calendar-alt text-[15px]"></i>
                                            {{ $history->moved_at->format('d M Y') }}
                                        </div>
                                        @if($history->notes)
                                            <p class="text-[15px] text-slate-900 italic mt-2 font-medium bg-slate-50 p-3 rounded-xl border border-slate-100/50">"{{ $history->notes }}"</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-10 text-center">
                                    <i class="fas fa-history text-slate-100 text-3xl mb-3"></i>
                                    <p class="text-[15px] text-slate-800 font-bold uppercase tracking-widest">Belum ada riwayat tercatat</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    @endif

                    <!-- Documents Card -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="h-8 w-1.5 bg-orange-600 rounded-full"></div>
                            <h3 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Verifikasi Dokumen</h3>
                        </div>

                        <div class="space-y-6">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Foto KTP / Akte Kelahiran</label>
                                    <x-input wire:model="identity_card" type="file" class="text-[15px]" />
                                    @if($existing_identity_card_path)
                                        <div class="flex items-center gap-2 mt-2 p-2 bg-white rounded-lg border border-slate-200">
                                            <i class="fas fa-file-image text-orange-600"></i>
                                            <span class="text-[15px] font-black text-slate-900 uppercase tracking-widest">Akte/KTP Tersedia</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Kartu BPJS Kesehatan</label>
                                    <x-input wire:model="bpjs_card" type="file" class="text-[15px]" />
                                    @if($existing_bpjs_card_path)
                                        <div class="flex items-center gap-2 mt-2 p-2 bg-white rounded-lg border border-slate-200">
                                            <i class="fas fa-shield-alt text-blue-600"></i>
                                            <span class="text-[15px] font-black text-slate-900 uppercase tracking-widest">BPJS Tersedia</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Nomor Peserta BPJS</label>
                                    <x-input wire:model="bpjs_number" type="text" placeholder="0001234..." />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 ml-1">Kredibilitas Asuransi</label>
                                    <x-select wire:model="bpjs_status" placeholder="Pilih Status..." 
                                        :options="['AKTIF' => '✅ Aktif & Valid', 'TIDAK_AKTIF' => '❌ Tidak Aktif', 'PROSES' => '⏳ Dalam Proses']" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Final Action -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                        <button type="submit" 
                            class="w-full bg-orange-600 hover:bg-orange-700 text-white py-5 rounded-2xl font-black uppercase tracking-widest text-[15px] shadow-2xl shadow-orange-600/20 transition-all active:scale-95 flex items-center justify-center gap-4 group">
                            <span>{{ $isEdit ? 'Simpan Perubahan Master' : 'Finalisasi Profil Atlet' }}</span>
                            <i class="fas fa-arrow-right text-[15px] transition-transform group-hover:translate-x-1"></i>
                        </button>
                        <p class="text-[15px] text-slate-800 text-center mt-6 font-bold uppercase tracking-widest leading-relaxed">
                            Peringatan: Seluruh data master atlet ini memicu sinkronisasi global di seluruh sistem pendaftaran Shorinji Kempo.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @endif
</div>
