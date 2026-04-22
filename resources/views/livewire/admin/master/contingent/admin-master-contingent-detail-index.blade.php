<div class="space-y-6 animate-in fade-in zoom-in-95 duration-700">
    <!-- Header with Breadcrumbs -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-[15px] font-black uppercase tracking-widest text-slate-800">
                <a href="{{ route('admin.master.contingents.index') }}" wire:navigate class="hover:text-orange-600 transition-colors">Master Kontingen</a>
                <i class="fas fa-chevron-right text-[15px]"></i>
                <span class="text-slate-900">Detail Unit & Partisipasi</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight lowercase first-letter:uppercase">{{ $contingent->name }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.master.contingents.edit', $contingent->id) }}" wire:navigate
                class="bg-slate-100 hover:bg-slate-200 text-slate-900 px-4 py-2 rounded-xl text-[15px] font-black uppercase tracking-widest transition-all">
                Edit Profil Unit
            </a>
            <button onclick="window.print()" 
                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-xl text-[15px] font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 transition-all active:scale-95">
                <i class="fas fa-print mr-2"></i>Cetak Laporan
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar: Unit Snapshot -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="aspect-video bg-gradient-to-br from-orange-500 to-orange-700 flex flex-col items-center justify-center relative group p-6 text-center">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-white text-3xl mb-3 shadow-xl backdrop-blur-md">
                        <i class="fas fa-tower-observation group-hover:scale-110 transition-transform"></i>
                    </div>
                    <span class="text-[15px] font-black text-white/70 uppercase tracking-widest">Region / Kab-Kota</span>
                    <h2 class="text-lg font-black text-white tracking-tight uppercase">{{ $contingent->kab_kota }}</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Pilih Batch Registrasi</label>
                            <button wire:click="createNewRegistration" class="text-[15px] font-black text-orange-600 hover:text-orange-700 flex items-center gap-1 uppercase tracking-widest bg-orange-50 px-2 py-0.5 rounded-md border border-orange-100 transition-all active:scale-95">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                        <select wire:model.live="selectedRegistrationId" class="w-full bg-slate-50 border-slate-100 rounded-xl text-[15px] font-black uppercase tracking-widest focus:ring-orange-500/20 focus:border-orange-200 transition-all p-3 appearance-none cursor-pointer">
                            @forelse($contingent->registrations->sortByDesc('created_at') as $reg)
                                <option value="{{ $reg->id }}">
                                    BATCH #{{ $reg->referral_code }} ({{ $reg->created_at->format('M Y') }})
                                </option>
                            @empty
                                <option value="">BELUM ADA DATA</option>
                            @endforelse
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Status Pendaftaran</label>
                        <div class="px-4 py-2 rounded-xl text-center {{ ($this->registration?->status === 'VERIFIED') ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                            <span class="text-[15px] font-black uppercase tracking-widest">{{ $this->registration?->status ?? 'BELUM DAFTAR' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Stats Sidebar -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 grid grid-cols-2 gap-4">
                <div class="space-y-1 text-center border-r border-slate-50">
                    <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Total Atlet</span>
                    <p class="text-xl font-black text-slate-800">{{ $this->registration?->athletes?->count() ?? 0 }}</p>
                </div>
                <div class="space-y-1 text-center">
                    <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Official</span>
                    <p class="text-xl font-black text-slate-800">{{ $this->registration?->officials?->count() ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content (Reorganized Tabs) -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Navigation Tabs -->
            <div class="bg-white rounded-3xl p-1.5 shadow-sm border border-slate-100 flex items-center gap-1 overflow-x-auto">
                <button wire:click="switchTab('profile')" 
                    class="flex-1 py-3 px-5 rounded-2xl text-[15px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $activeTab === 'profile' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Profil Unit
                </button>
                <button wire:click="switchTab('registration')" 
                    class="flex-1 py-3 px-5 rounded-2xl text-[15px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $activeTab === 'registration' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Detail Formulir
                </button>
                <button wire:click="switchTab('atlet')" 
                    class="flex-1 py-3 px-5 rounded-2xl text-[15px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $activeTab === 'atlet' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Daftar Atlet
                </button>
                <button wire:click="switchTab('official')" 
                    class="flex-1 py-3 px-5 rounded-2xl text-[15px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $activeTab === 'official' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Tim Official
                </button>
                <button wire:click="switchTab('history')" 
                    class="flex-1 py-3 px-5 rounded-2xl text-[15px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $activeTab === 'history' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/30' : 'text-slate-800 hover:bg-slate-50' }}">
                    Riwayat
                </button>
            </div>

            <!-- Detail Display Area -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 min-h-[450px]">
                
                <!-- Tab: Profil Unit -->
                @if($activeTab === 'profile')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-50">
                            <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                            <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Identitas & Legalitas Unit</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <div class="space-y-6">
                                <div class="space-y-1.5">
                                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-orange-400"></i> Alamat Sekretariat
                                    </label>
                                    <p class="text-[15px] font-bold text-black leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 italic">
                                        {{ $contingent->address ?? 'Alamat belum dilengkapi.' }}
                                    </p>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[15px] font-black uppercase tracking-widest text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-envelope text-orange-400"></i> Email Terdaftar
                                    </label>
                                    <p class="text-[15px] font-black text-slate-800 bg-white px-4 py-2 rounded-xl border border-slate-100 w-fit">{{ $contingent->email }}</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="bg-gradient-to-br from-slate-50 to-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-4">
                                    <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-600 shadow-sm border border-orange-50">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                        <div>
                                            <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Penanggung Jawab</label>
                                            <p class="text-[15px] font-black text-slate-800 tracking-tight">{{ $contingent->leader_name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2">
                                        <label class="text-[15px] font-black uppercase tracking-widest text-slate-800">Kontak Person (WhatsApp)</label>
                                        <a href="https://wa.me/{{ $contingent->leader_phone }}" target="_blank" class="flex items-center justify-between bg-emerald-50 hover:bg-emerald-100 px-4 py-3 rounded-2xl text-emerald-600 transition-all group">
                                            <span class="text-[15px] font-black">{{ $contingent->leader_phone }}</span>
                                            <i class="fab fa-whatsapp group-hover:scale-125 transition-transform"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tab: Riwayat Pendaftaran -->
                @if($activeTab === 'history')
                    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                                <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Seluruh Riwayat Partisipasi</h4>
                            </div>
                            <button wire:click="createNewRegistration" class="px-4 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-900 border border-slate-200 rounded-lg text-[15px] font-black uppercase tracking-widest transition-all active:scale-95">
                                <i class="fas fa-plus mr-1"></i> Pendaftaran Baru
                            </button>
                        </div>

                        <div class="overflow-hidden bg-white border border-slate-100 rounded-3xl">
                            <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                <thead class="bg-slate-800 text-white">
                                    <tr>
                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Batch / Reff</th>
                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Waktu Daftar</th>
                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap">Status</th>
                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-center">Peserta</th>
                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-right w-[1%]">Biaya</th>
                                        <th class="px-4 py-3 text-[15px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap text-right w-[1%]">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @forelse($contingent->registrations->sortByDesc('created_at') as $reg)
                                        <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-4 border-r border-slate-200">
                                                <div class="flex flex-col">
                                                    <span class="text-[15px] font-black text-slate-800 tracking-wider">#{{ $reg->referral_code }}</span>
                                                    <span class="text-[15px] font-bold text-slate-800 uppercase">ID: {{ $reg->id }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-[15px] font-black text-slate-900 border-r border-slate-200">{{ $reg->created_at->format('d M Y, H:i') }}</td>
                                            <td class="px-6 py-4 border-r border-slate-200">
                                                <span class="px-2 py-1 rounded-md text-[15px] font-black uppercase {{ $reg->status === 'VERIFIED' ? 'bg-emerald-50 text-emerald-600' : 'bg-orange-50 text-orange-600' }}">
                                                    {{ $reg->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border-slate-200">
                                                <div class="flex items-center justify-center gap-2">
                                                    <span class="text-[15px] font-black text-black" title="Atlet">{{ $reg->athletes_count ?? $reg->athletes->count() }} <i class="fas fa-users text-[15px] text-slate-300"></i></span>
                                                    <span class="text-[15px] font-black text-black" title="Official">{{ $reg->officials_count ?? $reg->officials->count() }} <i class="fas fa-user-tie text-[15px] text-slate-300"></i></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right text-[15px] font-black text-slate-800 border-r border-slate-200">Rp {{ number_format($reg->final_amount, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-right border-r border-slate-200">
                                                @if($selectedRegistrationId == $reg->id)
                                                    <span class="text-[15px] font-black text-orange-600 uppercase italic">Aktif</span>
                                                @else
                                                    <button wire:click="selectRegistration({{ $reg->id }})" class="text-[15px] font-black text-slate-800 hover:text-orange-600 transition-colors uppercase tracking-widest">Pilih</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-12 text-center text-[15px] font-black text-slate-300 uppercase italic tracking-widest border-r border-slate-200">Belum ada riwayat pendaftaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- Tab: Formulir Kejuaraan -->
                @if($activeTab === 'registration')
                    <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between pb-4 border-b border-slate-50">
                            <div class="flex items-center gap-3">
                                <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                                <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Detail Registrasi & Biaya</h4>
                            </div>
                            <span class="text-[15px] font-black text-slate-800 italic">ID Registrasi: #{{ $this->registration?->id ?? '-' }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center pt-2">
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 space-y-2">
                                <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Metode Pembayaran</span>
                                <p class="text-[15px] font-black text-black">{{ $this->registration?->payment_method ?? 'TIDAK TERSEDIA' }}</p>
                            </div>
                            <div class="p-6 bg-orange-50 rounded-3xl border border-orange-100 space-y-2">
                                <span class="text-[15px] font-black uppercase tracking-widest text-orange-400">Total Tagihan</span>
                                <p class="text-lg font-black text-orange-600 leading-tight">Rp {{ number_format($this->registration?->final_amount ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 space-y-2">
                                <span class="text-[15px] font-black uppercase tracking-widest text-slate-800">Kode Unik</span>
                                <p class="text-[15px] font-black text-black">{{ $this->registration?->unique_code ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                            <div class="space-y-4">
                                <h5 class="text-[15px] font-black uppercase tracking-widest text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-history text-orange-500"></i> Metadata Registrasi
                                </h5>
                                <div class="space-y-3 bg-slate-50 p-6 rounded-3xl border border-slate-100">
                                    <div class="flex justify-between items-center text-[15px]">
                                        <span class="font-bold text-slate-800 uppercase tracking-widest">Waktu Daftar</span>
                                        <span class="font-black text-slate-800">{{ $this->registration?->created_at?->format('d M Y, H:i') ?? '-' }} WIB</span>
                                    </div>
                                    <div class="flex justify-between items-center text-[15px] pt-3 border-t border-slate-200">
                                        <span class="font-bold text-slate-800 uppercase tracking-widest">Update Terakhir</span>
                                        <span class="font-black text-slate-800">{{ $this->registration?->updated_at?->format('d M Y, H:i') ?? '-' }} WIB</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 text-center md:text-left">
                                <h5 class="text-[15px] font-black uppercase tracking-widest text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-file-invoice text-orange-500"></i> Bukti Transfer
                                </h5>
                                @if($this->registration?->transfer_proof_path)
                                    <div class="bg-white p-5 rounded-3xl border border-slate-100 flex items-center justify-between group hover:border-orange-200 transition-all shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 transition-transform group-hover:scale-110">
                                                <i class="fas fa-image"></i>
                                            </div>
                                            <span class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Bukti telah diunggah</span>
                                        </div>
                                        <a href="{{ Storage::url($this->registration->transfer_proof_path) }}" target="_blank" class="px-4 py-2 bg-orange-600 text-white rounded-xl text-[15px] font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 active:scale-95">Lihat File</a>
                                    </div>
                                @else
                                    <div class="p-8 bg-slate-50 border border-dashed border-slate-200 rounded-3xl flex flex-col items-center justify-center gap-2">
                                        <i class="fas fa-cloud-upload-alt text-slate-300 text-2xl"></i>
                                        <p class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Bukti Belum Diunggah</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tab: Daftar Atlet -->
                @if($activeTab === 'atlet')
                    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                                <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Personel Atlet Terdaftar</h4>
                            </div>
                            <span class="text-[15px] font-bold text-slate-800 italic">Total: {{ $this->registration?->athletes?->count() ?? 0 }} Orang</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($this->registration)
                                @forelse($this->registration->athletes as $athlete)
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center justify-between hover:bg-white hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 group">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-800 group-hover:text-orange-600 font-black text-[15px] shadow-sm transition-colors">
                                                {{ substr($athlete->name, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-[15px] font-black text-slate-800 tracking-tight leading-tight">{{ $athlete->name }}</span>
                                                <span class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">
                                                    {{ $athlete->gender === 'L' ? 'Putra' : 'Putri' }} • 
                                                    {{ $athlete->pivot->weight ?? '0' }} KG • 
                                                    {{ $athlete->pivot->kyu ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.master.athletes.detail', $athlete->id) }}" wire:navigate class="w-7 h-7 flex items-center justify-center rounded-lg bg-white border border-slate-100 text-slate-300 hover:text-orange-600 transition-all">
                                            <i class="fas fa-chevron-right text-[15px]"></i>
                                        </a>
                                    </div>
                                @empty
                                    <div class="col-span-2 py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                        <p class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Belum ada atlet yang didaftarkan pada angkatan ini.</p>
                                    </div>
                                @endforelse
                            @else
                                <div class="col-span-2 py-16 text-center bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200 flex flex-col items-center justify-center gap-4">
                                    <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center text-slate-200 text-3xl">
                                        <i class="fas fa-file-circle-plus"></i>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[15px] font-black uppercase tracking-[0.2em] text-slate-800 italic">Belum Ada Riwayat Pendaftaran</p>
                                        <p class="text-[15px] font-bold text-slate-300">Unit ini harus didaftarkan ke angkatan kejuaraan terlebih dahulu.</p>
                                    </div>
                                    <button wire:click="createNewRegistration" class="px-6 py-2.5 bg-orange-600 text-white rounded-xl text-[15px] font-black uppercase tracking-widest shadow-lg shadow-orange-600/20 active:scale-95 transition-all">
                                        Buat Pendaftaran (Batch) Baru
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Tab: Tim Official -->
                @if($activeTab === 'official')
                    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between border-b border-slate-50 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-5 w-1.5 bg-orange-600 rounded-full"></div>
                                <h4 class="text-[15px] font-black uppercase tracking-widest text-slate-800">Tim Official & Manajer</h4>
                            </div>
                            <span class="text-[15px] font-bold text-slate-800 italic">Total: {{ $this->registration?->officials?->count() ?? 0 }} Orang</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($this->registration)
                                @forelse($this->registration->officials as $official)
                                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 flex items-center gap-4 hover:border-orange-100 transition-colors">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-800 font-black text-[15px] shadow-sm">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[15px] font-black text-slate-800 tracking-tight leading-tight">{{ $official->name }}</span>
                                            <span class="text-[15px] font-black uppercase tracking-widest text-orange-600 italic">{{ $official->pivot->role ?? 'Official' }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-2 py-12 text-center bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                        <p class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Belum ada official terdaftar.</p>
                                    </div>
                                @endforelse
                            @else
                                <div class="col-span-2 py-12 text-center">
                                    <p class="text-[15px] font-black uppercase tracking-widest text-slate-800 italic">Data pendaftaran belum tersedia.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
