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

    // Contingent Info
    public string $contingent_name = '';
    public string $leader_name = '';
    public string $leader_phone = '';
    public string $leader_email = '';
    public string $address = '';
    
    // Officials
    public array $officials = [
        ['name' => '', 'role' => 'Official', 'phone' => '']
    ];

    // Athletes
    public array $athletes = [
        ['name' => '', 'nik' => '', 'gender' => 'Male', 'birth_date' => '', 'weight' => '', 'kyu' => '', 'dojo_origin' => '', 'city' => '', 'bpjs_number' => '', 'bpjs_status' => false, 'bpjs_card' => null, 'match_type' => 'Pemula', 'category_ids' => []]
    ];
    
    public $kyuLevels;

    // Payment
    public string $payment_method = 'BCA';
    public int $unique_code = 0;
    
    // UI state
    public bool $is_success = false;
    public string $referral_code = '';
    
    // Pricing constants
    public int $contingent_fee = 300000;
    public int $athlete_fee = 300000;

    public function mount()
    {
        $this->unique_code = rand(100, 999);
        $this->kyuLevels = App\Models\KyuLevel::orderBy('order', 'desc')->get();
    }

    protected function rules()
    {
        return [
            'contingent_name' => 'required|min:3',
            'leader_name' => 'required|min:3',
            'leader_phone' => 'required',
            'leader_email' => 'required|email',
            'address' => 'required',
            'payment_method' => 'required',
            'officials.*.name' => 'required|min:3',
            'athletes.*.name' => 'required|min:3',
            'athletes.*.nik' => 'required|digits:16',
            'athletes.*.gender' => 'required|in:Male,Female',
            'athletes.*.birth_date' => 'required|date',
            'athletes.*.match_type' => 'required|in:Pemula,Remaja,Dewasa',
            'athletes.*.category_ids' => 'required|array|min:1|max:3',
            'athletes.*.bpjs_card' => 'nullable|image|max:2048',
        ];
    }

    public function addOfficial()
    {
        $this->officials[] = ['name' => '', 'role' => 'Official', 'phone' => ''];
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
        $this->athletes[] = ['name' => '', 'nik' => '', 'gender' => 'Male', 'birth_date' => '', 'weight' => '', 'kyu' => '', 'dojo_origin' => '', 'city' => '', 'bpjs_number' => '', 'bpjs_status' => false, 'bpjs_card' => null, 'match_type' => 'Pemula', 'category_ids' => []];
    }
    
    public function removeAthlete($index)
    {
        if (count($this->athletes) > 1) {
            unset($this->athletes[$index]);
            $this->athletes = array_values($this->athletes);
        }
    }
    
    public function getTotalProperty()
    {
        return $this->contingent_fee + (count($this->athletes) * $this->athlete_fee);
    }

    public function getFinalTotalProperty()
    {
        return $this->getTotalProperty() + $this->unique_code;
    }

    public function getAge($birthDate)
    {
        if (!$birthDate) return null;
        return \Carbon\Carbon::parse($birthDate)->age;
    }
    
    public function submit()
    {
        $this->validate();
        
        DB::transaction(function () {
            $contingent = Contingent::create([
                'name' => $this->contingent_name,
                'leader_name' => $this->leader_name,
                'leader_phone' => $this->leader_phone,
                'email' => $this->leader_email,
                'address' => $this->address,
                'total_cost' => $this->getTotalProperty(),
                'status' => 'pending',
                'referral_code' => 'DOJO-' . strtoupper(Str::random(5)),
                'payment_method' => $this->payment_method,
                'unique_code' => $this->unique_code,
                'final_amount' => $this->getFinalTotalProperty(),
            ]);
            
            $this->referral_code = $contingent->referral_code;

            // Save Officials
            foreach ($this->officials as $officialData) {
                $contingent->officials()->create($officialData);
            }

            // Save Athletes
            foreach ($this->athletes as $athleteData) {
                $bpjsPath = null;
                if ($athleteData['bpjs_card']) {
                    $bpjsPath = $athleteData['bpjs_card']->store('bpjs_cards', 'public');
                }

                $athlete = $contingent->athletes()->create([
                    'name' => $athleteData['name'],
                    'nik' => $athleteData['nik'],
                    'gender' => $athleteData['gender'],
                    'birth_date' => $athleteData['birth_date'],
                    'weight' => $athleteData['weight'],
                    'kyu' => $athleteData['kyu'],
                    'dojo_origin' => $athleteData['dojo_origin'],
                    'city' => $athleteData['city'],
                    'bpjs_number' => $athleteData['bpjs_number'],
                    'bpjs_status' => $athleteData['bpjs_status'],
                    'bpjs_card_path' => $bpjsPath,
                    'age' => $this->getAge($athleteData['birth_date']),
                    'match_type' => $athleteData['match_type'],
                ]);
                
                $athlete->categories()->attach($athleteData['category_ids']);
            }
        });
        
        $this->is_success = true;
    }
};
?>

<div class="max-w-full mx-auto-">
    @if(!$is_success)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Header -->
                <div class="bg-indigo-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-indigo-600/20">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10">
                        <h1 class="text-4xl font-black font-title uppercase tracking-tighter mb-2">Registarsi Portal</h1>
                        <p class="text-indigo-100 opacity-80 uppercase tracking-widest text-xs font-bold">🏆 PIALA WALIKOTA SURABAYA 2026</p>
                        <span class="text-indigo-100 opacity-80 uppercase tracking-widest text-xs font-bold">CABANG OLAHRAGA SHORINJI KEMPO | "Generasi Juara, Inspirasi Nusantara"</span><br>
                        <span class="text-indigo-100 opacity-80 uppercase tracking-widest text-xs font-bold">Surabaya, 29-31 Mei 2026 - Gelora Pancasila</span>
                    </div>
                </div>

                <!-- Section 1: Data Kontingen -->
                <div class="glass p-10 rounded-[2.5rem] border-indigo-500/10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black font-title uppercase tracking-tight">1. DATA KONTINGEN</h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block ml-1">Nama Perkemi / Klub</label>
                            <input type="text" wire:model="contingent_name" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-white dark:bg-white text-zinc-900 outline-none focus:border-indigo-600 transition-all font-bold" placeholder="Perkemi Garuda">
                            @error('contingent_name') <span class="text-rose-500 text-[10px] font-bold italic ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block ml-1">Penanggung Jawab</label>
                            <input type="text" wire:model="leader_name" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-white dark:bg-white text-zinc-900 outline-none focus:border-indigo-600 transition-all font-bold" placeholder="Nama Lengkap">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block ml-1">Email</label>
                            <input type="email" wire:model="leader_email" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-white dark:bg-white text-zinc-900 outline-none focus:border-indigo-600 transition-all font-bold">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block ml-1">No WhatsApp</label>
                            <input type="text" wire:model="leader_phone" class="w-full px-6 py-4 rounded-2xl border border-zinc-200 bg-white dark:bg-white text-zinc-900 outline-none focus:border-indigo-600 transition-all font-bold">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Data Official -->
                <div class="glass p-10 rounded-[2.5rem] border-indigo-500/10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <h2 class="text-2xl font-black font-title uppercase tracking-tight">2. DATA OFFICIAL</h2>
                        </div>
                        <button wire:click="addOfficial" class="text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-400 transition-colors">+ Tambah Official</button>
                    </div>

                    <div class="space-y-4">
                        @foreach($officials as $index => $official)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6 bg-white rounded-3xl border border-zinc-200 relative">
                                @if(count($officials) > 1)
                                    <button wire:click="removeOfficial({{ $index }})" class="absolute -top-2 -right-2 w-6 h-6 bg-rose-500 text-white rounded-full flex items-center justify-center text-xs">×</button>
                                @endif
                                <div class="space-y-1">
                                    <label class="text-[9px] font-black uppercase text-zinc-400">Nama Official</label>
                                    <input type="text" wire:model="officials.{{ $index }}.name" class="w-full bg-transparent border-none p-0 focus:ring-0 font-bold text-zinc-900" placeholder="Nama Lengkap">
                                </div>
                                <div class="space-y-1 border-l border-zinc-100 pl-4">
                                    <label class="text-[9px] font-black uppercase text-zinc-400">Jabatan</label>
                                    <input type="text" wire:model="officials.{{ $index }}.role" class="w-full bg-transparent border-none p-0 focus:ring-0 font-bold text-zinc-900" placeholder="Manager/Sensei">
                                </div>
                                <div class="space-y-1 border-l border-zinc-100 pl-4">
                                    <label class="text-[9px] font-black uppercase text-zinc-400">No HP</label>
                                    <input type="text" wire:model="officials.{{ $index }}.phone" class="w-full bg-transparent border-none p-0 focus:ring-0 font-bold text-zinc-900" placeholder="08xxx">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Section 3: Data Atlet -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-600/20 rounded-xl flex items-center justify-center text-indigo-500 font-black">3</div>
                            <h2 class="text-xl font-black font-title uppercase tracking-tight">3. DATA ATLET</h2>
                        </div>
                        <button wire:click="addAthlete" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg">Tambah Atlet</button>
                    </div>

                    @foreach($athletes as $index => $athlete)
                        <div wire:key="athlete-{{ $index }}" class="glass p-10 rounded-[2.5rem] relative">
                             @if(count($athletes) > 1)
                                <button wire:click="removeAthlete({{ $index }})" class="absolute top-8 right-8 text-zinc-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Nama Lengkap</label>
                                    <input type="text" wire:model.live="athletes.{{ $index }}.name" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold outline-none focus:border-indigo-600">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">NIK (16 Digit)</label>
                                    <input type="text" wire:model="athletes.{{ $index }}.nik" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold outline-none focus:border-indigo-600">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Jenis Kelamin</label>
                                        <select wire:model.live="athletes.{{ $index }}.gender" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold">
                                            <option value="Male">PUTRA</option>
                                            <option value="Female">PUTRI</option>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Tgl Lahir</label>
                                        <input type="date" wire:model.live="athletes.{{ $index }}.birth_date" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                     <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Usia</label>
                                        <div class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-900 font-black">{{ $this->getAge($athlete['birth_date']) ?? '-' }} Tahun</div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Tingkatan Kyu</label>
                                        <select wire:model="athletes.{{ $index }}.kyu" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold">
                                            <option value="">Pilih Kyu</option>
                                            @foreach($kyuLevels as $kyu)
                                                <option value="{{ $kyu->name }}">{{ $kyu->name }} ({{ $kyu->color }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-4">
                                    <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Tipe Pertandingan</label>
                                    <div class="flex gap-4">
                                        @foreach(['Pemula', 'Remaja', 'Dewasa'] as $type)
                                            <label @class([
                                                'flex-1 flex items-center justify-center gap-3 p-4 rounded-2xl border transition-all cursor-pointer font-black uppercase text-xs',
                                                'bg-indigo-600 border-indigo-600 text-white shadow-lg' => $athlete['match_type'] === $type,
                                                'bg-white border-zinc-200 text-zinc-400' => $athlete['match_type'] !== $type
                                            ])>
                                                <input type="radio" wire:model.live="athletes.{{ $index }}.match_type" value="{{ $type }}" class="hidden">
                                                {{ $type }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Asal Perkemi</label>
                                    <input type="text" wire:model="athletes.{{ $index }}.dojo_origin" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Kota</label>
                                    <input type="text" wire:model="athletes.{{ $index }}.city" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold">
                                </div>
                                <div class="grid grid-cols-2 gap-4 md:col-span-2">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">No BPJS</label>
                                        <input type="text" wire:model="athletes.{{ $index }}.bpjs_number" class="w-full px-5 py-3 rounded-xl border border-zinc-200 bg-white text-zinc-900 font-bold">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Status BPJS</label>
                                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border border-zinc-200 cursor-pointer">
                                            <input type="checkbox" wire:model="athletes.{{ $index }}.bpjs_status" class="w-5 h-5 rounded text-indigo-600">
                                            <span class="text-xs font-bold uppercase">AKTIFF</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block">Upload Kartu BPJS (Opsional)</label>
                                    <input type="file" wire:model="athletes.{{ $index }}.bpjs_card" class="w-full text-xs text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                </div>
                            </div>

                            <div class="mt-8 pt-8 border-t border-zinc-100">
                                <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block mb-4">Pilih Pertandingan (Maks 3 Kategori)</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @php
                                        $filteredCategories = App\Models\Category::where('gender', $athlete['gender'])
                                            ->where('match_type', $athlete['match_type'])
                                            ->get();
                                    @endphp
                                    @foreach($filteredCategories as $category)
                                        <label @class([
                                            'flex items-center gap-3 p-4 rounded-2xl border transition-all cursor-pointer group',
                                            'bg-indigo-600/5 border-indigo-600' => in_array($category->id, $athlete['category_ids']),
                                            'bg-white border-zinc-100 opacity-50 grayscale pointer-events-none' => count($athlete['category_ids']) >= 3 && !in_array($category->id, $athlete['category_ids']),
                                            'bg-white border-zinc-100' => !in_array($category->id, $athlete['category_ids']) && count($athlete['category_ids']) < 3
                                        ])>
                                            <input type="checkbox" wire:model.live="athletes.{{ $index }}.category_ids" value="{{ $category->id }}" class="w-5 h-5 rounded border-zinc-300 text-indigo-600 focus:ring-0">
                                            <div class="flex-1">
                                                <span class="block text-sm font-bold uppercase tracking-tight">{{ $category->name }}</span>
                                                <span class="block text-[10px] text-zinc-400 font-black uppercase">{{ $category->type }} - {{ $category->age_group }}</span>
                                            </div>
                                        </label>
                                    @endforeach

                                    @if($filteredCategories->isEmpty())
                                        <div class="md:col-span-2 p-8 text-center bg-zinc-50 rounded-3xl border border-dashed border-zinc-200">
                                            <p class="text-[10px] font-black uppercase text-zinc-400 tracking-widest leading-relaxed">
                                                Tidak ada kategori tersedia untuk<br>
                                                <span class="text-indigo-600">{{ $athlete['match_type'] }} ({{ $athlete['gender'] == 'Male' ? 'PUTRA' : 'PUTRI' }})</span>
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                @if(count($athlete['category_ids']) >= 3)
                                    <p class="text-[9px] text-amber-600 font-black uppercase mt-2">Maksimal 3 kategori tercapai.</p>
                                @endif
                                @error('athletes.'.$index.'.category_ids') <span class="text-rose-500 text-[10px] font-bold mt-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sticky Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <!-- Section 4 & 5: Total & Payment -->
                    <div class="bg-indigo-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl">
                         <h3 class="text-lg font-black font-title uppercase tracking-tight mb-8">4. TOTAL & PEMBAYARAN</h3>
                         
                         <div class="space-y-4 mb-8">
                            <div class="flex justify-between items-center text-indigo-100 text-sm italic">
                                <span>Iuran Kontingen</span>
                                <span class="font-bold">Rp {{ number_format($contingent_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-indigo-100 text-sm italic border-b border-white/10 pb-4">
                                <span>Atlet ({{ count($athletes) }} x Rp {{ number_format($athlete_fee, 0, ',', '.') }})</span>
                                <span class="font-bold">Rp {{ number_format(count($athletes) * $athlete_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-indigo-100 text-xs py-2">
                                <span class="italic">Kode Unik Verifikasi</span>
                                <span class="font-black text-rose-300">+{{ $unique_code }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-[10px] font-black uppercase tracking-widest">TOTAL AKHIR</span>
                                <span class="text-2xl font-black font-title">Rp {{ number_format($this->getFinalTotalProperty(), 0, ',', '.') }}</span>
                            </div>
                         </div>

                         <div class="space-y-4 pt-6 border-t border-white/10">
                            <label class="text-[10px] font-black uppercase tracking-widest">5. METODE BAYAR</label>
                            <select wire:model.live="payment_method" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-sm font-bold outline-none">
                                <option value="BCA" class="text-zinc-900">BCA (Manual Transfer)</option>
                                <option value="MANDIRI" class="text-zinc-900">MANDIRI (Manual Transfer)</option>
                                <option value="QRIS" class="text-zinc-900">QRIS (Auto-Confirm)</option>
                            </select>
                            
                            <div class="p-4 bg-white/5 rounded-2xl text-[10px] space-y-2 border border-white/5">
                                @if($payment_method === 'BCA')
                                    <p class="font-bold">BCA: 123456789</p>
                                    <p class="opacity-70">A/N: PANITIA DOJO CUP</p>
                                @elseif($payment_method === 'MANDIRI')
                                    <p class="font-bold">MANDIRI: 987654321</p>
                                    <p class="opacity-70">A/N: PANITIA DOJO CUP</p>
                                @endif
                            </div>
                         </div>

                         <button wire:click="submit" class="w-full bg-white text-indigo-600 py-5 rounded-2xl font-black uppercase tracking-widest text-[10px] mt-8 hover:bg-indigo-50 transition-all shadow-xl active:scale-95 flex items-center justify-center gap-3">
                             KIRIM PENDAFTARAN
                             <div wire:loading class="w-4 h-4 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                         </button>
                    </div>

                    <div class="p-8 rounded-[2.5rem] bg-amber-500/5 border border-amber-500/10 text-[10px] text-zinc-500 italic leading-relaxed">
                        Data atlet dan official yang dikirimkan bersifat final. Gunakan tombol simpan draft jika pendaftaran belum lengkap (Fitur segera hadir).
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Success Screen -->
        <div class="max-w-3xl mx-auto py-20 text-center animate-fade-in">
            <div class="w-24 h-24 bg-emerald-600 text-white rounded-full flex items-center justify-center mx-auto mb-10 shadow-2xl">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-4xl font-black font-title uppercase mb-4 tracking-tighter">DATA TELAH <span class="text-emerald-500 italic">DITERIMA!</span></h1>
            <p class="text-zinc-500 uppercase tracking-widest text-xs font-bold mb-12">Segera lakukan pembayaran sesuai nominal di bawah ini.</p>

            <div class="glass p-12 rounded-[3.5rem] relative overflow-hidden bg-white shadow-2xl">
                <div class="absolute top-0 left-0 w-full h-2 bg-emerald-600"></div>
                
                <div class="mb-12">
                     <span class="text-[10px] font-black uppercase text-zinc-400 tracking-widest mb-2 block">TOTAL YANG HARUS DITRANSFER</span>
                     <h2 class="text-5xl font-black font-title text-indigo-600">Rp {{ number_format($this->getFinalTotalProperty(), 0, ',', '.') }}</h2>
                     <p class="text-[10px] text-rose-500 font-bold mt-2 italic">*PENTING: Transfer harus tepat hingga 3 digit terakhir.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                     <div class="space-y-4">
                        <span class="text-[10px] font-black uppercase text-zinc-400 tracking-widest block">INFO PEMBAYARAN</span>
                        <div class="p-6 bg-zinc-50 rounded-3xl border border-zinc-100">
                            <p class="text-xs text-zinc-500 uppercase font-black mb-1">{{ $payment_method }}</p>
                            <p class="text-2xl font-black font-mono tracking-widest text-zinc-900">
                                {{ $payment_method == 'BCA' ? '123456789' : ($payment_method == 'MANDIRI' ? '987654321' : 'QRIS_CODE') }}
                            </p>
                            <p class="text-[10px] font-bold text-indigo-500 uppercase mt-2">A/N PANITIA DOJO CUP 2026</p>
                        </div>
                     </div>
                     <div class="space-y-4">
                        <span class="text-[10px] font-black uppercase text-zinc-400 tracking-widest block">KODE REGISTRASI</span>
                        <div class="p-6 bg-zinc-900 text-white rounded-3xl border border-zinc-800">
                             <p class="text-3xl font-black font-title tracking-[0.2em]">{{ $referral_code }}</p>
                             <p class="text-[9px] text-zinc-500 uppercase font-black mt-2 italic">Screenshot & Lampirkan di WA Konfirmasi.</p>
                        </div>
                     </div>
                </div>
            </div>

            <div class="mt-12 flex items-center justify-center gap-6">
                <a href="/" class="text-xs font-black uppercase tracking-widest text-zinc-400 hover:text-indigo-600 transition-colors">Kembali ke Beranda</a>
                <div class="w-px h-4 bg-zinc-200"></div>
                <button class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-indigo-600/20">Konfirmasi via WhatsApp</button>
            </div>
        </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>


<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>