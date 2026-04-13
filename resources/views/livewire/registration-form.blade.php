<div>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

        .form-container {
            /* max-width: 1200px; */
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 25px 30px 45px;
            font-family: 'Outfit', sans-serif;
        }

        h1 {
            font-size: 1.9rem;
            color: #b22234;
            border-left: 8px solid #ffcc00;
            padding-left: 20px;
            margin-bottom: 8px;
            font-weight: 900;
        }

        .subhead {
            color: #2c5282;
            margin-bottom: 25px;
            font-weight: 500;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
            font-size: 0.9rem;
        }

        .section {
            background: #f9fafc;
            border-radius: 20px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2edf2;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: #0f3b5c;
            background: #e6f0fa;
            display: inline-block;
            padding: 8px 18px;
            border-radius: 40px;
            letter-spacing: -0.2px;
            text-transform: uppercase;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        label {
            font-weight: 700;
            display: block;
            margin-bottom: 8px;
            color: #1e4663;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        label .required {
            color: #e53e3e;
            margin-left: 3px;
        }

        .form-input-custom {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 2px solid #cbd5e1;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: white;
            font-weight: 600;
        }

        .form-input-custom:focus {
            outline: none;
            border-color: #b22234;
            box-shadow: 0 0 0 3px rgba(178,34,52,0.1);
        }

        .form-input-custom:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
            border-color: #e2e8f0;
            color: #64748b;
        }

        .atlet-card {
            background: #ffffff;
            border-radius: 24px;
            margin-bottom: 25px;
            padding: 25px;
            border: 2px solid #cfdfed;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            transition: all 0.3s ease;
        }

        .atlet-card:hover {
            border-color: #b22234;
            box-shadow: 0 12px 20px rgba(0,0,0,0.05);
        }

        .atlet-header {
            font-weight: 900;
            font-size: 1.1rem;
            background: #eef3fc;
            padding: 10px 20px;
            border-radius: 30px;
            margin-bottom: 25px;
            color: #004070;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .btn-add {
            background: #1e6f3f;
            color: white;
            padding: 12px 24px;
            border-radius: 40px;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-add:hover {
            background: #14532d;
            transform: translateY(-2px);
        }

        .btn-remove {
            background: #fee2e2;
            color: #b91c1c;
            padding: 8px 16px;
            border-radius: 40px;
            font-weight: 800;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .btn-remove:hover {
            background: #fecaca;
        }

        .submit-btn {
            background: #b22234;
            color: white;
            padding: 16px 40px;
            border-radius: 40px;
            font-weight: 900;
            font-size: 1.1rem;
            transition: all 0.3s;
            box-shadow: 0 10px 15px -3px rgba(178, 34, 52, 0.3);
        }

        .submit-btn:hover {
            background: #8b1a1a;
            transform: scale(1.02);
        }

        .info-box {
            background: #fef9e6;
            border-left: 6px solid #ffb347;
            padding: 15px 20px;
            border-radius: 18px;
            margin: 20px 0;
            font-size: 0.85rem;
            color: #92400e;
            font-weight: 600;
        }

        .warning-box {
            background: #fee2e2;
            border-left: 6px solid #dc2626;
            padding: 15px 20px;
            border-radius: 18px;
            margin: 15px 0;
            font-size: 0.85rem;
            color: #991b1b;
            font-weight: 600;
        }

        .bpjs-section {
            background: #f0f9ff;
            padding: 20px;
            border-radius: 20px;
            margin: 20px 0;
            border: 1px solid #bae6fd;
        }
    </style>

    @if($is_success)
        <div class="form-container text-center py-20">
            <h1 class="text-4xl font-black mb-4">PENDAFTARAN BERHASIL!</h1>
            <p class="text-slate-500 mb-8 px-12">Data kontingen <strong>{{ $contingent_name }}</strong> telah resmi terdaftar untuk Piala Walikota Surabaya 2026.</p>
            <div class="bg-slate-50 p-8 rounded-3xl mb-12 inline-block border-2 border-orange-100">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 block mb-2">Kode Referensi</span>
                <span class="text-3xl font-black text-rose-600 tracking-widest">{{ $referral_code }}</span>
            </div>
            <div>
                <a href="/" class="submit-btn inline-block">Kembali ke Beranda</a>
            </div>
        </div>
    @else
        <div class="form-container">
            <div class="mb-10 text-center md:text-left">
                <h1>🏆 PIALA WALIKOTA SURABAYA 2026</h1>
                <div class="subhead">CABANG OLAHRAGA SHORINJI KEMPO | "Generasi Juara, Inspirasi Nusantara" <br> Surabaya, 29-31 Mei 2026 - Gelora Pancasila</div>
            </div>

            <form wire:submit.prevent="submit">
                <!-- ==================== DATA KONTINGEN ==================== -->
                <div class="section">
                    <div class="section-title">A. DATA KONTINGEN ({{ $is_authenticated ? 'PROFIL TERVERIFIKASI' : 'DATA UMUM' }})</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kabupaten / Kota <span class="required">*</span></label>
                            <input type="text" wire:model="contingent_city" class="form-input-custom" @if($is_authenticated) disabled @endif>
                            @error('contingent_city') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label>Nama Kontingen <span class="required">*</span></label>
                            <input type="text" wire:model="contingent_name" class="form-input-custom" @if($is_authenticated) disabled @endif>
                            @error('contingent_name') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label>Manager Tim <span class="required">*</span></label>
                            <input type="text" wire:model="leader_name" class="form-input-custom" @if($is_authenticated) disabled @endif>
                            @error('leader_name') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nomor HP/WA Manager <span class="required">*</span></label>
                            <input type="tel" wire:model="leader_phone" class="form-input-custom" @if($is_authenticated) disabled @endif>
                            @error('leader_phone') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label>Email Official</label>
                            <input type="email" wire:model="leader_email" class="form-input-custom" @if($is_authenticated) disabled @endif>
                            @error('leader_email') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="form-group">
                            <label>Alamat Sekretariat</label>
                            <input type="text" wire:model="address" class="form-input-custom" @if($is_authenticated) disabled @endif>
                        </div>
                    </div>
                    
                    <div class="info-box">
                        ✅ Berdasarkan THB: Setiap kontingen wajib membayar kontribusi kontingen Rp 2.500.000,- dan biaya per atlet sesuai kategori. <br>
                        📌 Transfer ke Rekening Panitia (Detail di bagian bawah formulir ini).
                    </div>
                </div>

                <!-- ==================== OFFICIAL PENDAMPING ==================== -->
                <div class="section">
                    <div class="section-title">B. OFFICIAL PENDAMPING</div>
                    <div class="space-y-4">
                        @foreach($officials as $index => $official)
                            <div class="p-6 bg-white border-2 border-slate-100 rounded-2xl" wire:key="official-{{ $index }}">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nama Official</label>
                                        <input type="text" wire:model="officials.{{ $index }}.name" class="form-input-custom">
                                    </div>
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <input type="text" wire:model="officials.{{ $index }}.role" class="form-input-custom">
                                    </div>
                                    <div class="form-group">
                                        <label>Kontak HP</label>
                                        <div class="flex gap-2">
                                            <input type="text" wire:model="officials.{{ $index }}.phone" class="form-input-custom">
                                            @if(count($officials) > 1)
                                                <button type="button" wire:click="removeOfficial({{ $index }})" class="btn-remove">HAPUS</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" wire:click="addOfficial" class="btn-add mt-6">+ TAMBAH OFFICIAL</button>
                </div>

                <!-- ==================== DATA ATLET ==================== -->
                <div class="section">
                    <div class="section-title">C. DATA ATLET (PESERTA)</div>
                    <div class="space-y-8">
                        @foreach($athletes as $index => $athlete)
                            <div class="atlet-card" wire:key="athlete-{{ $index }}">
                                <div class="atlet-header">
                                    <span>🥋 ATLET #{{ $index + 1 }}</span>
                                    @if(count($athletes) > 1)
                                        <button type="button" wire:click="removeAthlete({{ $index }})" class="btn-remove !bg-red-600 !text-white">HAPUS ATLET</button>
                                    @endif
                                </div>

                                <!-- Step 1: Selection Dropdown -->
                                <div class="mb-8 p-6 bg-blue-50/50 rounded-2xl border-2 border-blue-100/50">
                                    <label class="text-blue-900">
                                        {{ $is_authenticated ? 'PILIH DARI ATLET KONTINGEN ANDA' : 'PILIH DARI DATABASE MASTER (OPSIONAL)' }}
                                    </label>
                                    
                                    @if(!$is_authenticated && $masterAthletes->isEmpty())
                                        <div class="mt-2 p-3 bg-blue-100/50 rounded-xl text-[10px] text-blue-700 font-bold uppercase tracking-wider">
                                            💡 Silakan gunakan fitur cari NIK atau klik "Tambah Atlet" di bawah jika belum terdaftar.
                                        </div>
                                    @else
                                        <select wire:model.live="athletes.{{ $index }}.athlete_id" class="form-input-custom">
                                            <option value="">-- Cari Nama Atlet --</option>
                                            @foreach($masterAthletes as $master)
                                                <option value="{{ $master->id }}">{{ $master->name }} ({{ substr($master->nik, 0, 4) }})</option>
                                            @endforeach
                                            <option value="new" class="font-bold text-orange-600">+ REGISTRASI ATLET BARU</option>
                                        </select>
                                    @endif
                                </div>

                                @if($athlete['show_fields'])
                                    <div class="mb-6 p-6 bg-slate-50 rounded-2xl border-2 border-slate-100 flex flex-col md:flex-row items-center gap-6">
                                        <div class="relative w-28 h-28 bg-white rounded-2xl border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shrink-0">
                                            @if($athlete['photo'])
                                                <img src="{{ $athlete['photo']->temporaryUrl() }}" class="w-full h-full object-cover">
                                            @elseif(isset($athlete['athlete_id']) &&
    is_numeric($athlete['athlete_id']) &&
    ($master = \App\Models\Athlete::find($athlete['athlete_id'])) &&
    $master->photo_path)
                                                <img src="{{ asset('storage/' . $master->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="text-center p-2">
                                                    <i class="fas fa-camera text-slate-300 text-xl block mb-1"></i>
                                                    <span class="text-[8px] font-bold text-slate-400 uppercase leading-tight">Foto 3x4</span>
                                                </div>
                                            @endif
                                            <input type="file" wire:model="athletes.{{ $index }}.photo" class="absolute inset-0 opacity-0 cursor-pointer">
                                        </div>
                                        <div class="flex-1">
                                            <label class="!mb-1">Foto Profil / Pas Foto <span class="required">*</span></label>
                                            <p class="text-[9px] text-slate-400 mb-2 font-medium">Unggah pas foto formal (Background Merah/Biru) ukuran 3x4. Maksimal 2MB.</p>
                                            @error('athletes.'.$index.'.photo') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="required">*</span></label>
                                            <input type="text" wire:model="athletes.{{ $index }}.name" class="form-input-custom @error('athletes.'.$index.'.name') border-red-500 @enderror" @if($athlete['is_master_found']) disabled @endif>
                                            @error('athletes.'.$index.'.name') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>NIK <span class="required">*</span></label>
                                            <input type="text" wire:model="athletes.{{ $index }}.nik" class="form-input-custom @error('athletes.'.$index.'.nik') border-red-500 @enderror" @if($athlete['is_master_found']) disabled @endif>
                                            @error('athletes.'.$index.'.nik') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Jenis Kelamin <span class="required">*</span></label>
                                            <select wire:model="athletes.{{ $index }}.gender" class="form-input-custom" @if($athlete['is_master_found']) disabled @endif>
                                                <option value="Male">Laki-laki</option>
                                                <option value="Female">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Tempat Lahir <span class="required">*</span></label>
                                            <input type="text" wire:model="athletes.{{ $index }}.birth_place" class="form-input-custom" @if($athlete['is_master_found']) disabled @endif>
                                            @error('athletes.'.$index.'.birth_place') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Lahir <span class="required">*</span></label>
                                            <input type="date" wire:model="athletes.{{ $index }}.birth_date" class="form-input-custom" @if($athlete['is_master_found']) disabled @endif>
                                            @error('athletes.'.$index.'.birth_date') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Golongan Darah <span class="required">*</span></label>
                                            <select wire:model="athletes.{{ $index }}.blood_type" class="form-input-custom" @if($athlete['is_master_found']) disabled @endif>
                                                <option value="">Pilih...</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="O">O</option>
                                                <option value="AB">AB</option>
                                                <option value="A+">A+</option>
                                                <option value="B+">B+</option>
                                                <option value="O+">O+</option>
                                                <option value="AB+">AB+</option>
                                            </select>
                                            @error('athletes.'.$index.'.blood_type') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-span-2">
                                            <label>Alamat Rumah (Sesuai KTP/KK) <span class="required">*</span></label>
                                            <input type="text" wire:model="athletes.{{ $index }}.address" class="form-input-custom" @if($athlete['is_master_found']) disabled @endif>
                                            @error('athletes.'.$index.'.address') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Nomor HP Atlet/Orang Tua</label>
                                            <input type="tel" wire:model="athletes.{{ $index }}.phone" class="form-input-custom" @if($athlete['is_master_found']) disabled @endif>
                                            @error('athletes.'.$index.'.phone') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group">
                                            <label>Asal Dojo <span class="required">*</span></label>
                                            <input type="text" wire:model="athletes.{{ $index }}.dojo_origin" class="form-input-custom @error('athletes.'.$index.'.dojo_origin') border-red-500 @enderror">
                                            @error('athletes.'.$index.'.dojo_origin') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Kelompok Usia</label>
                                            <select wire:model.live="athletes.{{ $index }}.age_group" class="form-input-custom">
                                                <option value="">Pilih Kelompok Usia...</option>
                                                @foreach($ageGroups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                            @php
                                                $selectedAgeGroup = $ageGroups->firstWhere('id', $athlete['age_group']);
                                            @endphp
                                            @if($selectedAgeGroup)
                                                <p class="text-[10px] text-orange-600 font-bold mt-1 tracking-wider uppercase">
                                                    💰 Biaya Registrasi: Rp {{ number_format($selectedAgeGroup->price, 0, ',', '.') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Tingkatan (Rank)</label>
                                            <select wire:model="athletes.{{ $index }}.rank" class="form-input-custom">
                                                @foreach($kyuLevels as $level)
                                                    <option value="{{ $level->name }}">{{ $level->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- BPJS SECTION -->
                                    <div class="bpjs-section">
                                        <label class="!text-blue-900 mb-4 block">🛡️ DATA BPJS KETENAGAKERJAAN (WAJIB)</label>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Nomor BPJS</label>
                                                <input type="text" wire:model="athletes.{{ $index }}.bpjs_number" class="form-input-custom" placeholder="1234...">
                                                @error('athletes.'.$index.'.bpjs_number') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select wire:model="athletes.{{ $index }}.bpjs_status" class="form-input-custom">
                                                    <option value="Aktif">AKTIF</option>
                                                    <option value="Tidak Aktif">TIDAK AKTIF</option>
                                                    <option value="Dalam Proses">DALAM PROSES</option>
                                                </select>
                                                @error('athletes.'.$index.'.bpjs_status') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TOURNAMENT DATA -->
                                    <div class="bg-indigo-50/50 p-6 rounded-2xl border-2 border-indigo-100/50 space-y-4">
                                        <label class="text-indigo-900 block">DATA FISIK & KELOMPOK BERAT</label>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label>Berat Badan (KG)</label>
                                                <input type="number" step="0.1" wire:model="athletes.{{ $index }}.current_weight" class="form-input-custom">
                                                @error('athletes.'.$index.'.current_weight') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Kelompok Berat</label>
                                                <select wire:model="athletes.{{ $index }}.weight_group_id" class="form-input-custom">
                                                    <option value="">Pilih...</option>
                                                    @foreach($weightGroups as $group)
                                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('athletes.'.$index.'.weight_group_id') <p class="text-[9px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 space-y-4">
                                        <label class="!text-orange-600">NOMOR PERTANDINGAN (MAKS 3)</label>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                                            @foreach(['event1', 'event2', 'event3'] as $evField)
                                                <div class="flex flex-col gap-2">
                                                    <select wire:model.live="athletes.{{ $index }}.{{ $evField }}" class="form-input-custom @error('athletes.'.$index.'.'.$evField) border-red-500 @enderror">
                                                        <option value="">{{ strtoupper(str_replace('event', 'Kategori ', $evField)) }}...</option>
                                                        @foreach($this->getEventOptions($athlete['age_group'], $athlete['gender'], $index, $evField) as $id => $name)
                                                            <option value="{{ $id }}">{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                            @error('athletes.'.$index.'.events') <p class="text-[10px] text-red-500 font-bold mt-1 col-span-full">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="button" wire:click="addAthlete" class="btn-add mt-8">+ TAMBAH ATLET BARU</button>
                    <div class="warning-box">
                        ⚠️ **PENTING:** Seluruh atlet WAJIB terdaftar sebagai peserta BPJS Ketenagakerjaan dengan status kepesertaan aktif (THB Pasal L).
                    </div>
                </div>

                <!-- ==================== RINGKASAN PENDAFTARAN ==================== -->
                <div class="section">
                    <div class="section-title">D. RINGKASAN NOMOR PERTANDINGAN</div>
                    <div class="bg-slate-50 rounded-3xl p-6 border-2 border-slate-100">
                        <div class="space-y-4">
                            @forelse($this->matchSummary as $mId => $data)
                                <div class="p-5 bg-white rounded-2xl shadow-sm border border-slate-200" wire:key="match-summary-{{ $mId }}">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black shadow-lg">
                                                <i class="fas fa-trophy text-sm"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-black text-slate-800 uppercase text-sm leading-tight">{{ $data['name'] }}</h4>
                                                <span class="text-[9px] font-black text-orange-600 uppercase tracking-widest bg-orange-50 px-2 py-0.5 rounded leading-none">
                                                    {{ $data['age_group'] }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pendaftar:</span>
                                            <p class="text-xs font-black text-slate-700 uppercase">{{ count($data['athletes']) }} Orang</p>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                        <!-- Technique Selection -->
                                        <div class="bg-orange-50/50 rounded-2xl p-5 border border-orange-100">
                                            <div class="flex items-center justify-between mb-4">
                                                <h5 class="text-[10px] font-black text-orange-600 uppercase tracking-widest leading-none">PILIHAN TEKNIK (UNTUK SEMUA ATLET)</h5>
                                                <span class="text-[8px] font-bold text-orange-400 bg-orange-100/50 px-2 py-0.5 rounded uppercase">Wajib Pilih</span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                                                @foreach($techniques as $tech)
                                                    <label class="flex items-center gap-2 p-2 bg-white hover:bg-orange-100 rounded-xl cursor-pointer transition-all border border-slate-100 hover:border-orange-200 group"
                                                           wire:key="match-{{ $mId }}-tech-{{ $tech->id }}">
                                                        <input type="checkbox" 
                                                               wire:model.live="matchTechniques.{{ $mId }}" 
                                                               value="{{ $tech->id }}"
                                                               class="w-4 h-4 rounded-md border-slate-300 text-orange-600 focus:ring-orange-500">
                                                        <span class="text-[10px] font-bold text-slate-600 group-hover:text-orange-800 transition-colors leading-tight">{{ $tech->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Registered Athletes -->
                                        <div>
                                            <h5 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 leading-none">DAFTAR ATLET DI NOMOR INI</h5>
                                            <div class="space-y-2 border-l-2 border-slate-100 pl-4">
                                                @foreach($data['athletes'] as $athleteName)
                                                    <div class="flex items-center gap-3 py-1 animate-in slide-in-from-left-2 duration-300">
                                                        <div class="w-2 h-2 rounded-full bg-orange-400 shadow-sm shadow-orange-200"></div>
                                                        <span class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ $athleteName }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-slate-200 mx-auto mb-4 shadow-sm border border-slate-100">
                                        <i class="fas fa-clipboard-list text-2xl"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Belum ada nomor pertandingan yang dipilih</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ==================== PEMBAYARAN ==================== -->
                <div class="section">
                    <div class="section-title">E. BIAYA & PEMBAYARAN</div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Summary Card -->
                        <div class="bg-slate-900 text-white p-8 rounded-[32px] shadow-2xl relative overflow-hidden">
                            <div class="relative z-10">
                                <h3 class="text-xl font-bold mb-6 text-orange-400">Ringkasan Biaya</h3>
                                <div class="space-y-4 text-sm">
                                    <div class="flex justify-between border-b border-white/10 pb-2">
                                        <span class="text-slate-400">Pendaftaran Kontingen</span>
                                        <span class="font-mono">Rp {{ number_format($contingent_fee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-white/10 pb-2">
                                        <span class="text-slate-400">Total Biaya Atlet ({{ count($athletes) }} Orang)</span>
                                        <span class="font-mono">Rp {{ number_format($this->getTotalAthleteFee(), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-white/10 pb-2">
                                        <span class="text-slate-400">Kode Unik Verifikasi</span>
                                        <span class="font-mono text-orange-400">+ Rp {{ number_format($unique_code, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between pt-4">
                                        <span class="text-xl font-bold">TOTAL BAYAR</span>
                                        <span class="text-2xl font-black text-orange-400 font-mono italic">Rp {{ number_format($this->finalTotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <p class="mt-6 text-[10px] text-slate-400 leading-relaxed italic">
                                    * Mohon transfer tepat hingga 3 digit terakhir untuk mempercepat proses verifikasi otomatis oleh sistem.
                                </p>
                            </div>
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl"></div>
                        </div>

                        <!-- Payment Method -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">Pilih Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative cursor-pointer">
                                        <input type="radio" wire:model="payment_method" value="BSI" class="peer sr-only">
                                        <div class="p-4 border-2 border-slate-100 rounded-2xl peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-900 transition-all text-center">
                                            <div class="font-black text-xs">BANK BSI</div>
                                            <div class="text-[9px] text-slate-500">Fast Verification</div>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer">
                                        <input type="radio" wire:model="payment_method" value="BCA" class="peer sr-only">
                                        <div class="p-4 border-2 border-slate-100 rounded-2xl peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-900 transition-all text-center">
                                            <div class="font-black text-xs">BANK BCA</div>
                                            <div class="text-[9px] text-slate-500">Manual Check</div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100">
                                @if($payment_method === 'BSI')
                                    <div class="text-[11px] text-orange-900">
                                        <strong class="block mb-2 font-black">💳 REKENING TUJUAN (BSI)</strong>
                                        Bank Syariah Indonesia (BSI)<br>
                                        Nomor Rekening: <span class="font-mono font-bold">730.947.3196</span><br>
                                        Atas Nama: <span class="font-bold">PERKEMI PENGKOT SURABAYA</span>
                                    </div>
                                @else
                                    <div class="text-[11px] text-blue-900">
                                        <strong class="block mb-2 font-black">💳 REKENING TUJUAN (BCA)</strong>
                                        Bank Central Asia (BCA)<br>
                                        Nomor Rekening: <span class="font-mono font-bold">018.xxxx.xxx</span><br>
                                        Atas Nama: <span class="font-bold">ADMIN KEMPO SURABAYA</span>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="block text-sm font-bold text-slate-700 mb-2">Upload Bukti Transfer <span class="required">*</span></label>
                                <div class="relative">
                                    <input type="file" wire:model="transfer_proof" class="form-input-custom border-dashed border-2">
                                    <div wire:loading wire:target="transfer_proof" class="mt-2 text-[10px] text-orange-600 animate-pulse">
                                        Uploading file...
                                    </div>
                                </div>
                                @error('transfer_proof') <p class="text-[10px] text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-8 p-6 bg-red-50 border-2 border-red-200 rounded-[32px] animate-in shake duration-500">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-red-500 text-white rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-red-200">
                                <i class="fas fa-exclamation-triangle text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-black text-red-800 uppercase tracking-widest mb-1">Pendaftaran Belum Lengkap</h3>
                                <p class="text-[11px] text-red-600 font-medium leading-relaxed mb-4">
                                    Ditemukan {{ $errors->count() }} kolom yang bermasalah. Silakan tinjau ringkasan di bawah atau periksa kolom yang berwarna merah di atas:
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                                    @foreach ($errors->all() as $error)
                                        <div class="flex items-center gap-2 text-[10px] text-red-500 font-bold bg-white/50 p-2 rounded-xl border border-red-100">
                                            <div class="w-1.5 h-1.5 rounded-full bg-red-400"></div>
                                            {{ $error }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-6 mt-12 pb-20 justify-center">
                    <button type="submit" class="submit-btn" wire:loading.attr="disabled">
                        <span wire:loading.remove>KIRIM PENDAFTARAN FINAL</span>
                        <span wire:loading>MEMPROSES...</span>
                    </button>
                    <button type="button" wire:click="$refresh" class="btn-remove">Reset Form</button>
                </div>
            </form>
        </div>
    @endif
</div>