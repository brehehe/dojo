<?php

use Livewire\Component;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\Athlete;
use App\Models\Official;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    // A. DATA KONTINGEN
    public string $contingent_city = '';
    public string $contingent_name = '';
    public string $leader_name = '';
    public string $leader_phone = '';
    public string $leader_email = '';
    public string $address = '';
    public $transfer_proof;

    // B. OFFICIAL 
    public array $officials = [
        ['name' => '', 'role' => '', 'phone' => '']
    ];

    // C. ATLET
    public array $athletes = [
        [
            'name' => '', 'gender' => 'Male', 'birth_date' => '', 'age_group' => 'Pemula',
            'rank' => 'Kyu 6', 'dojo_origin' => '', 'city' => '', 'nik' => '',
            'bpjs_number' => '', 'bpjs_status' => 'Aktif', 'bpjs_card' => null,
            'event1' => '', 'event2' => '', 'event3' => '', 'identity_document' => null
        ]
    ];
    
    // D. PERNYATAAN
    public string $sim_perkemi_confirm = 'Ya';

    public $kyuLevels;
    public bool $is_success = false;
    
    // Payment & Pricing
    public string $payment_method = 'BCA';
    public int $unique_code = 0;
    public string $referral_code = '';
    public int $contingent_fee = 500000;
    public int $athlete_fee = 400000;

    public function mount()
    {
        $this->unique_code = rand(100, 999);
        $this->kyuLevels = App\Models\KyuLevel::orderBy('order', 'asc')->get();
    }

    protected function rules()
    {
        return [
            'contingent_city' => 'required',
            'contingent_name' => 'required|min:3',
            'leader_name' => 'required|min:3',
            'leader_phone' => 'required',
            'transfer_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            
            'officials.*.name' => 'required|min:3',
            'officials.*.role' => 'required',
            
            'athletes.*.name' => 'required|min:3',
            'athletes.*.gender' => 'required|in:Male,Female',
            'athletes.*.age_group' => 'required',
            'athletes.*.rank' => 'required',
            'athletes.*.dojo_origin' => 'required',
            'athletes.*.nik' => 'required',
            'athletes.*.bpjs_number' => 'required',
            'athletes.*.bpjs_status' => 'required|in:Aktif',
            'athletes.*.bpjs_card' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'athletes.*.identity_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function addOfficial()
    {
        $this->officials[] = ['name' => '', 'role' => '', 'phone' => ''];
    }

    public function removeOfficial($index)
    {
        if (count($this->officials) > 1) {
            unset($this->officials[$index]);
            $this->officials = array_values($this->officials);
        }
    }

    public function addAthlete()
    {
        $this->athletes[] = [
            'name' => '', 'gender' => 'Male', 'birth_date' => '', 'age_group' => 'Pemula',
            'rank' => 'Kyu 6', 'dojo_origin' => '', 'city' => '', 'nik' => '',
            'bpjs_number' => '', 'bpjs_status' => 'Aktif', 'bpjs_card' => null,
            'event1' => '', 'event2' => '', 'event3' => '', 'identity_document' => null
        ];
    }
    
    public function removeAthlete($index)
    {
        if (count($this->athletes) > 1) {
            unset($this->athletes[$index]);
            $this->athletes = array_values($this->athletes);
        }
    }
    
    public function getEventOptions($ageGroup, $gender)
    {
        return Category::where('age_group', $ageGroup)
            ->where('gender', $gender)
            ->pluck('name', 'id');
    }

    public function getTotalProperty()
    {
        return $this->contingent_fee + (count($this->athletes) * $this->athlete_fee);
    }

    public function getFinalTotalProperty()
    {
        return $this->getTotalProperty() + $this->unique_code;
    }

    public function submit()
    {
        $this->validate();
        
        foreach ($this->athletes as $index => $athlete) {
            if ($athlete['bpjs_status'] !== 'Aktif') {
                $this->addError("athletes.{$index}.bpjs_status", 'Status BPJS harus Aktif.');
                return;
            }
            if (empty($athlete['event1']) && empty($athlete['event2']) && empty($athlete['event3'])) {
                $this->addError("athletes.{$index}.events", 'Minimal 1 nomor pertandingan harus dipilih.');
                return;
            }
            $events = array_filter([$athlete['event1'], $athlete['event2'], $athlete['event3']]);
            if (count($events) !== count(array_unique($events))) {
                $this->addError("athletes.{$index}.events", 'Nomor pertandingan tidak boleh ganda.');
                return;
            }
        }
        
        DB::transaction(function () {
            $transferPath = $this->transfer_proof ? $this->transfer_proof->store('transfer_proofs', 'public') : null;

            $contingent = Contingent::create([
                'name' => $this->contingent_name,
                'kab_kota' => $this->contingent_city,
                'leader_name' => $this->leader_name,
                'leader_phone' => $this->leader_phone,
                'email' => $this->leader_email,
                'address' => $this->address,
                'transfer_proof_path' => $transferPath,
                'sim_perkemi_confirm' => $this->sim_perkemi_confirm,
                'total_cost' => $this->getTotalProperty(),
                'status' => 'pending',
                'referral_code' => 'KEMPO-' . strtoupper(Str::random(5)),
                'payment_method' => $this->payment_method,
                'unique_code' => $this->unique_code,
                'final_amount' => $this->getFinalTotalProperty(),
            ]);
            
            $this->referral_code = $contingent->referral_code;

            foreach ($this->officials as $officialData) {
                $contingent->officials()->create($officialData);
            }

            foreach ($this->athletes as $athleteData) {
                $bpjsPath = $athleteData['bpjs_card'] ? $athleteData['bpjs_card']->store('bpjs_cards', 'public') : null;
                $identityPath = $athleteData['identity_document'] ? $athleteData['identity_document']->store('identity_docs', 'public') : null;

                $athlete = $contingent->athletes()->create([
                    'name' => $athleteData['name'],
                    'gender' => $athleteData['gender'],
                    'birth_date' => $athleteData['birth_date'],
                    'age_group' => $athleteData['age_group'],
                    'rank' => $athleteData['rank'],
                    'dojo_origin' => $athleteData['dojo_origin'],
                    'city' => $athleteData['city'],
                    'nik' => $athleteData['nik'],
                    'bpjs_number' => $athleteData['bpjs_number'],
                    'bpjs_status' => $athleteData['bpjs_status'],
                    'bpjs_card_path' => $bpjsPath,
                    'identity_document_path' => $identityPath,
                ]);
                
                $events = array_filter([$athleteData['event1'], $athleteData['event2'], $athleteData['event3']]);
                if (!empty($events)) {
                    $athlete->categories()->attach($events);
                }
            }
        });
        
        $this->is_success = true;
    }
};
?>

<div>
    <style>
        .kempo-form-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #eef2f7;
            padding: 30px 20px;
            color: #1a2a3a;
            min-height: 100vh;
        }

        .kempo-form-container * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .kempo-form-container .form-inner {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 25px 30px 45px;
        }

        .kempo-form-container h1 {
            font-size: 1.9rem;
            color: #b22234;
            border-left: 8px solid #ffcc00;
            padding-left: 20px;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .kempo-form-container .subhead {
            color: #2c5282;
            margin-bottom: 25px;
            font-weight: 500;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 12px;
        }

        .kempo-form-container .section {
            background: #f9fafc;
            border-radius: 20px;
            padding: 20px 25px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #e2edf2;
        }

        .kempo-form-container .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0f3b5c;
            background: #e6f0fa;
            display: inline-block;
            padding: 5px 18px;
            border-radius: 40px;
            letter-spacing: -0.2px;
        }

        .kempo-form-container .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .kempo-form-container .form-group {
            flex: 1;
            min-width: 200px;
        }

        .kempo-form-container label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #1e4663;
            font-size: 0.9rem;
        }

        .kempo-form-container label .required {
            color: #e53e3e;
            margin-left: 3px;
        }

        .kempo-form-container input, 
        .kempo-form-container select, 
        .kempo-form-container textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
            transition: 0.2s;
            background: white;
            color: #1a2a3a;
        }

        .kempo-form-container input:focus, 
        .kempo-form-container select:focus, 
        .kempo-form-container textarea:focus {
            outline: none;
            border-color: #b22234;
            box-shadow: 0 0 0 3px rgba(178,34,52,0.2);
        }
        
        .kempo-form-container input[type="file"] {
            padding: 9px 14px;
        }

        .kempo-form-container .inline-hint {
            font-size: 0.75rem;
            color: #5a6e7c;
            margin-top: 5px;
        }

        .kempo-form-container .atlet-card {
            background: #ffffff;
            border-radius: 20px;
            margin-bottom: 25px;
            padding: 20px;
            border: 1px solid #cfdfed;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            transition: all 0.2s;
        }

        .kempo-form-container .atlet-card:hover {
            box-shadow: 0 6px 14px rgba(0,0,0,0.08);
        }

        .kempo-form-container .atlet-header {
            font-weight: bold;
            font-size: 1.2rem;
            background: #eef3fc;
            padding: 8px 15px;
            border-radius: 30px;
            margin-bottom: 20px;
            color: #004070;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .kempo-form-container .btn-add, 
        .kempo-form-container .btn-remove, 
        .kempo-form-container .submit-btn, 
        .kempo-form-container .reset-btn {
            border: none;
            padding: 10px 22px;
            font-weight: bold;
            border-radius: 40px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .kempo-form-container .btn-add {
            background: #1e6f3f;
            color: white;
            margin-top: 8px;
        }

        .kempo-form-container .btn-add:hover {
            background: #0f5a34;
            transform: translateY(-1px);
        }

        .kempo-form-container .btn-remove {
            background: #b91c1c;
            color: white;
            font-size: 0.8rem;
            padding: 5px 15px;
            margin-top: 12px;
        }

        .kempo-form-container .btn-remove:hover {
            background: #991b1b;
        }

        .kempo-form-container .submit-btn {
            background: #b22234;
            color: white;
            padding: 14px 32px;
            font-size: 1.1rem;
            margin-right: 15px;
        }

        .kempo-form-container .submit-btn:hover {
            background: #8b1a1a;
        }

        .kempo-form-container .reset-btn {
            background: #4a5568;
            color: white;
        }

        .kempo-form-container .reset-btn:hover {
            background: #2d3748;
        }

        .kempo-form-container .button-group {
            margin-top: 25px;
            display: flex;
            justify-content: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }

        .kempo-form-container .info-box {
            background: #fef9e6;
            border-left: 6px solid #ffb347;
            padding: 12px 20px;
            border-radius: 14px;
            margin: 20px 0;
            font-size: 0.85rem;
        }

        .kempo-form-container .warning-box {
            background: #fee2e2;
            border-left: 6px solid #dc2626;
            padding: 12px 20px;
            border-radius: 14px;
            margin: 15px 0;
            font-size: 0.85rem;
        }

        .kempo-form-container .footer-note {
            margin-top: 35px;
            text-align: center;
            font-size: 0.8rem;
            color: #5a6874;
            border-top: 1px solid #dce5ec;
            padding-top: 20px;
        }

        .kempo-form-container .bpjs-status {
            background: #f0f9ff;
            padding: 8px 12px;
            border-radius: 12px;
            margin-top: 8px;
        }
        
        .kempo-form-container .error-msg {
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 4px;
            font-weight: bold;
        }

        @media (max-width: 700px) {
            .kempo-form-container .form-inner {
                padding: 15px;
            }
            .kempo-form-container .section {
                padding: 15px;
            }
        }
    </style>

    <div class="kempo-form-container">
        <div class="form-inner">
            @if(!$is_success)
                <h1>🏆 PIALA WALIKOTA SURABAYA 2026</h1>
                <div class="subhead">CABANG OLAHRAGA SHORINJI KEMPO | "Generasi Juara, Inspirasi Nusantara" <br> Surabaya, 29-31 Mei 2026 - Gelora Pancasila</div>

                <form wire:submit.prevent="submit">
                    <!-- ==================== DATA KONTINGEN ==================== -->
                    <div class="section">
                        <div class="section-title">A. DATA KONTINGEN (PERKEMI KABUPATEN/KOTA)</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Kabupaten / Kota <span class="required">*</span></label>
                                <select wire:model="contingent_city" required>
                                    <option value="">Pilih Kabupaten/Kota</option>
                                    <option value="Kota Surabaya">Kota Surabaya</option>
                                    <option value="Kota Malang">Kota Malang</option>
                                    <option value="Kota Kediri">Kota Kediri</option>
                                    <option value="Kabupaten Sidoarjo">Kabupaten Sidoarjo</option>
                                    <option value="Kabupaten Gresik">Kabupaten Gresik</option>
                                    <!-- Simplified list for brevity -->
                                    <option value="Lainnya">Lainnya...</option>
                                </select>
                                @error('contingent_city') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Nama Kontingen (boleh lebih dari 1 tim) <span class="required">*</span></label>
                                <input type="text" wire:model="contingent_name" placeholder="Contoh: PERKEMI SURABAYA 1" required>
                                @error('contingent_name') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Manager Tim / Penanggung Jawab <span class="required">*</span></label>
                                <input type="text" wire:model="leader_name" required>
                                @error('leader_name') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nomor HP/WA Manager <span class="required">*</span></label>
                                <input type="tel" wire:model="leader_phone" required>
                                @error('leader_phone') <div class="error-msg">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label>Email Official</label>
                                <input type="email" wire:model="leader_email">
                            </div>
                            <div class="form-group">
                                <label>Alamat Lengkap Sekretariat</label>
                                <input type="text" wire:model="address">
                            </div>
                        </div>
                        <div class="info-box">
                            ✅ Berdasarkan THB: Setiap kontingen wajib membayar kontribusi kontingen Rp 500.000,- dan biaya per atlet Rp 400.000,-. <br>
                            <!-- 📌 Silakan totalkan pembayaran di bawah (sebelum D) dan transfer ke Rekening yang Anda pilih. -->
                        </div>
                    </div>

                    <!-- ==================== OFFICIAL PENDAMPING ==================== -->
                    <div class="section">
                        <div class="section-title">B. OFFICIAL PENDAMPING (min 1 per kontingen)</div>
                        <div id="officialsContainer">
                            @foreach($officials as $index => $official)
                            <div class="official-entry" style="margin-bottom: 15px; background:#fefdf7; padding:12px; border-radius:16px;">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nama Official</label>
                                        <input type="text" wire:model="officials.{{ $index }}.name" placeholder="Nama Lengkap">
                                        @error('officials.'.$index.'.name') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Jabatan (Pelatih/Wasit/Manager)</label>
                                        <input type="text" wire:model="officials.{{ $index }}.role" placeholder="Pelatih / Asisten">
                                    </div>
                                    <div class="form-group">
                                        <label>No. HP / ID</label>
                                        <input type="text" wire:model="officials.{{ $index }}.phone">
                                    </div>
                                    @if(count($officials) > 1)
                                    <div style="display:flex; align-items:center;">
                                        <button type="button" class="btn-remove" wire:click="removeOfficial({{ $index }})">Hapus</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add" wire:click="addOfficial">+ Tambah Official</button>
                        <div class="inline-hint">Setiap atlet wajib didampingi minimal 1 official saat bertanding (THB Pasal K).</div>
                    </div>

                    <!-- ==================== DAFTAR ATLET DINAMIS ==================== -->
                    <div class="section">
                        <div class="section-title">C. DATA ATLET (PESERTA) - Maks 3 nomor pertandingan per atlet</div>
                        
                        @if($errors->has('athletes.*.events') || $errors->has('athletes.*.bpjs_status'))
                            <div class="warning-box">
                                Terdapat kesalahan pada kelengkapan data BPJS atau Nomor Pertandingan Atlet di bawah ini. Harap periksa kembali.
                            </div>
                        @endif

                        <div id="athletesContainer">
                            @foreach($athletes as $index => $athlete)
                            <div class="atlet-card" wire:key="athlete-{{ $index }}">
                                <div class="atlet-header">
                                    <span>🥋 ATLET #{{ $index + 1 }}</span>
                                    @if(count($athletes) > 1)
                                        <button type="button" class="btn-remove" wire:click="removeAthlete({{ $index }})" style="margin:0; padding: 4px 12px;">Hapus Atlet</button>
                                    @endif
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nama Lengkap <span class="required">*</span></label>
                                        <input type="text" wire:model="athletes.{{ $index }}.name" required>
                                        @error('athletes.'.$index.'.name') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Jenis Kelamin <span class="required">*</span></label>
                                        <select wire:model.live="athletes.{{ $index }}.gender">
                                            <option value="Male">Laki-laki</option>
                                            <option value="Female">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tanggal Lahir</label>
                                        <input type="date" wire:model="athletes.{{ $index }}.birth_date">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Kelompok Usia (sesuai THB) <span class="required">*</span></label>
                                        <select wire:model.live="athletes.{{ $index }}.age_group">
                                            <option value="Pemula">Pemula (8-12 th)</option>
                                            <option value="Remaja A">Remaja A (>12-15 th)</option>
                                            <option value="Remaja B">Remaja B (>15-18 th)</option>
                                            <option value="Dewasa A">Dewasa A (>18-23 th)</option>
                                            <option value="Dewasa B (Senior)">Dewasa B (24-35 th)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Tingkatan Kyu / Dan (SIM PERKEMI) <span class="required">*</span></label>
                                        <select wire:model="athletes.{{ $index }}.rank">
                                            @foreach($kyuLevels as $kyu)
                                                <option value="{{ $kyu->name }}">{{ $kyu->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Asal Dojo <span class="required">*</span></label>
                                        <input type="text" wire:model="athletes.{{ $index }}.dojo_origin" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Asal Kota/Kabupaten <span class="required">*</span></label>
                                        <input type="text" wire:model="athletes.{{ $index }}.city" required>
                                    </div>
                                    <div class="form-group">
                                        <label>NIK / Nomor Induk Kenshi <span class="required">*</span></label>
                                        <input type="text" wire:model="athletes.{{ $index }}.nik" required>
                                    </div>
                                </div>
                                
                                <!-- SECTION BPJS KETENAGAKERJAAN -->
                                <div class="bpjs-status" style="background: #eef8ff; padding: 16px; border-radius: 12px; margin: 15px 0; border:1px solid #c2e2f5;">
                                    <div style="font-weight: 700; margin-bottom: 12px; color: #1e4663;">🛡️ DATA BPJS KETENAGAKERJAAN (WAJIB)</div>
                                    <div class="form-row" style="margin-bottom:0;">
                                        <div class="form-group">
                                            <label>Nomor BPJS Ketenagakerjaan <span class="required">*</span></label>
                                            <input type="text" wire:model="athletes.{{ $index }}.bpjs_number" placeholder="Contoh: 123456789012345" required>
                                            <div class="inline-hint">Nomor kepesertaan aktif</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Status Kepesertaan BPJS <span class="required">*</span></label>
                                            <select wire:model="athletes.{{ $index }}.bpjs_status">
                                                <option value="Aktif">Aktif</option>
                                                <option value="Tidak Aktif">Tidak Aktif</option>
                                                <option value="Dalam Proses">Dalam Proses</option>
                                            </select>
                                            @error('athletes.'.$index.'.bpjs_status') <div class="error-msg">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Kartu BPJS (softcopy)</label>
                                            <input type="file" wire:model="athletes.{{ $index }}.bpjs_card" accept="image/*,.pdf">
                                            @error('athletes.'.$index.'.bpjs_card') <div class="error-msg">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Nomor Pertandingan 1</label>
                                        <select wire:model="athletes.{{ $index }}.event1">
                                            <option value="">Pilih nomor</option>
                                            @foreach($this->getEventOptions($athlete['age_group'], $athlete['gender']) as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                        @error('athletes.'.$index.'.events') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Pertandingan 2 (opsional)</label>
                                        <select wire:model="athletes.{{ $index }}.event2">
                                            <option value="">Pilih nomor</option>
                                            @foreach($this->getEventOptions($athlete['age_group'], $athlete['gender']) as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor Pertandingan 3 (opsional)</label>
                                        <select wire:model="athletes.{{ $index }}.event3">
                                            <option value="">Pilih nomor</option>
                                            @foreach($this->getEventOptions($athlete['age_group'], $athlete['gender']) as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row" style="margin-bottom:0;">
                                    <div class="form-group">
                                        <label>Upload scan KTP/Akte & KK (softcopy)</label>
                                        <input type="file" wire:model="athletes.{{ $index }}.identity_document" accept="image/*,.pdf">
                                        @error('athletes.'.$index.'.identity_document') <div class="error-msg">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add" wire:click="addAthlete" style="margin-top: 10px;">+ Tambah Atlet Baru</button>
                        <div class="inline-hint">* Setiap atlet wajib memenuhi syarat: Domisili Jatim, KTP/Akte, KK, SIM PERKEMI valid, batas usia sesuai kelompok.</div>
                        <div class="warning-box">
                            ⚠️ **PENTING:** Seluruh atlet WAJIB terdaftar sebagai peserta BPJS Ketenagakerjaan dengan status kepesertaan aktif (sesuai THB Pasal L ayat 4).
                        </div>
                    </div>

                    <!-- ==================== PEMBAYARAN ==================== -->
                    <div class="section">
                        <div class="section-title">D. PEMBAYARAN & TOTAL BIAYA</div>
                        <div class="atlet-card" style="background:#fefdf7; border:2px dashed #b22234;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:1.1rem;">
                                <span>1. Kontribusi Kontingen</span>
                                <strong>Rp {{ number_format($contingent_fee, 0, ',', '.') }}</strong>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:10px; font-size:1.1rem;">
                                <span>2. Total Atlet ({{ count($athletes) }} x Rp {{ number_format($athlete_fee, 0, ',', '.') }})</span>
                                <strong>Rp {{ number_format(count($athletes) * $athlete_fee, 0, ',', '.') }}</strong>
                            </div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:1.1rem;">
                                <span>3. Kode Unik Sistem</span>
                                <strong>Rp {{ $unique_code }}</strong>
                            </div>
                            <div style="display:flex; justify-content:space-between; border-top:2px solid #cbd5e1; padding-top:15px; font-size:1.4rem; color:#b22234;">
                                <strong>TOTAL YANG HARUS DITRANSFER</strong>
                                <strong>Rp {{ number_format($this->getFinalTotalProperty(), 0, ',', '.') }}</strong>
                            </div>
                            
                            <div class="form-group" style="margin-top:25px;">
                                <label>Pilih Metode Pembayaran / Tujuan Transfer <span class="required">*</span></label>
                                <select wire:model.live="payment_method" required>
                                    <option value="BCA">BCA</option>
                                    <option value="MANDIRI">MANDIRI</option>
                                    <option value="CASH">Tunai (Di Sekretariat)</option>
                                </select>
                            </div>
                            
                            @if($payment_method == 'BCA')
                                <div class="info-box" style="margin-top:15px; background:#e6f0fa; border-left:6px solid #2c5282;">
                                    BCA <br>
                                    No Rekening: <strong>730.947.3196</strong> <br>
                                    Atas Nama: <strong>PERKEMI PENGKOT SURABAYA</strong>
                                </div>
                            @elseif($payment_method == 'MANDIRI')
                                <div class="info-box" style="margin-top:15px; background:#e6f0fa; border-left:6px solid #2c5282;">
                                    Bank Mandiri <br>
                                    No Rekening: <strong>141.00.xxxxxxx</strong> <br>
                                    Atas Nama: <strong>PERKEMI SURABAYA</strong>
                                </div>
                            @elseif($payment_method == 'CASH')
                                <div class="info-box" style="margin-top:15px; background:#e6f0fa; border-left:6px solid #2c5282;">
                                    Pembayaran tunai dapat dilakukan langsung di Sekretariat PERKEMI Kota Surabaya atau On The Spot.
                                </div>
                            @endif
                            
                            <hr style="margin: 20px 0; border: 1px solid #e2edf2;">
                            <div class="form-row" style="margin-bottom: 0;">
                                <div class="form-group">
                                    <label>Upload Bukti Transfer Pembayaran (Wajib untuk Non-Tunai) <span class="required">*</span></label>
                                    <input type="file" wire:model="transfer_proof" accept="image/*,.pdf">
                                    <div class="inline-hint">Ukuran file maksimal: 2MB. Format: JPG/PNG/PDF</div>
                                    @error('transfer_proof') <div class="error-msg">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==================== PERNYATAAN KELENGKAPAN ==================== -->
                    <div class="section">
                        <div class="section-title">E. PERNYATAAN KELENGKAPAN ADMINISTRASI</div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Apakah seluruh atlet terdaftar sebagai anggota PERKEMI dan memiliki SIM PERKEMI valid?</label>
                                <select wire:model="sim_perkemi_confirm">
                                    <option value="Ya">Ya, semua valid</option>
                                    <option value="Tidak">Tidak / dalam proses</option>
                                </select>
                            </div>
                        </div>
                        <div class="info-box">
                            📌 Catatan: Technical Meeting akan dilaksanakan 3 kali (akhir April, pertengahan Mei, H-1). <br>
                            📌 Pendaftaran Tahap I (by number) via website smart-perkemi.id/piala_walikotasby2026 + Lampiran A; Tahap II (by name) paling lambat 15 Mei 2026.
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="submit-btn" style="position:relative;">
                            <span wire:loading.remove>Kirim Pendaftaran (Simpan Data)</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                        <button type="button" class="reset-btn" onclick="if(confirm('Reset semua data formulir?')) location.reload();">Reset Formulir</button>
                    </div>
                    <div class="footer-note">
                        * Dengan mengirimkan formulir ini, kami menyatakan bahwa data yang diisi adalah benar sesuai dengan dokumen resmi dan siap mengikuti seluruh ketentuan dalam Technical Handbook Kejuaraan Piala Walikota 2026.<br>
                        Panitia berhak menolak peserta jika persyaratan tidak terpenuhi. Keputusan panitia bersifat final.
                    </div>
                </form>
            @else
                <!-- Success Message Overhauled for Kempo Theme -->
                <div style="text-align: center; padding: 60px 20px;">
                    <div style="background:#1e6f3f; color:white; width: 80px; height: 80px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px; font-size:35px;">✓</div>
                    <h1 style="color:#1e6f3f; font-size: 2.2rem; margin-bottom: 20px; border-left:none; padding-left:0;">Pendaftaran Berhasil Terkirim!</h1>
                    
                    <div style="background:#fef9e6; border-radius:14px; padding:25px; display:inline-block; text-align:left; border:2px dashed #ffb347; margin-bottom:30px;">
                        <h2 style="font-size:1.3rem; margin-bottom:15px; color:#b22234;">Ringkasan Pendaftaran</h2>
                        <div style="margin-bottom:8px;">Kode Pendaftaran: <strong style="font-size:1.2rem;">{{ $referral_code }}</strong></div>
                        <div style="margin-bottom:8px;">Nama Kontingen: <strong>{{ $contingent_name }}</strong></div>
                        <div style="margin-bottom:8px;">Metode Pembayaran: <strong>{{ $payment_method }}</strong></div>
                        <hr style="margin:15px 0; border:1px solid #e2e8f0;">
                        <div style="font-size:1.1rem;">Total Tagihan: <strong style="font-size:1.4rem; color:#b22234;">Rp {{ number_format($this->getFinalTotalProperty(), 0, ',', '.') }}</strong></div>
                        <div style="font-size:0.85rem; color:#64748b; margin-top:5px;">(Sudah termasuk kode unik dan iuran kontingen)</div>
                    </div>
                    
                    <p style="font-size: 1.1rem; color: #5a6e7c; margin-bottom: 30px;">
                        Terima kasih, data kontingen beserta kelengkapannya telah tercatat. Silakan simpan Kode Pendaftaran di atas. Jika memilih transfer manual, pastikan nominal sesuai hingga 3 digit terakhir.
                    </p>
                    <button type="button" class="btn-add" onclick="location.reload();" style="font-size: 1rem; padding: 12px 30px;">Daftar Kontingen Lain / Kembali</button>
                </div>
            @endif
        </div>
    </div>
</div>