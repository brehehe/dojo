<?php

use Livewire\Component;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\Athlete;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

new class extends Component
{
    public int $step = 1;
    
    // Step 1: Contingent Info
    public string $contingent_name = '';
    public string $leader_name = '';
    public string $leader_phone = '';
    public string $leader_email = '';
    public string $address = '';
    
    // Step 2: Athletes
    public array $athletes = [
        ['name' => '', 'nik' => '', 'gender' => 'Male', 'birth_date' => '', 'weight' => '', 'achievement_history' => '', 'category_ids' => []]
    ];
    
    // Pricing constants
    public int $contingent_fee = 500000;
    public int $athlete_fee = 400000;
    
    public function nextStep()
    {
        $this->validateStep();
        $this->step++;
    }
    
    public function prevStep()
    {
        $this->step--;
    }
    
    protected function validateStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'contingent_name' => 'required|min:3',
                'leader_name' => 'required|min:3',
                'leader_phone' => 'required',
                'leader_email' => 'required|email',
                'address' => 'required',
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'athletes.*.name' => 'required|min:3',
                'athletes.*.gender' => 'required|in:Male,Female',
                'athletes.*.birth_date' => 'required|date',
            ]);
        } elseif ($this->step === 3) {
            $this->validate([
                'athletes.*.category_ids' => 'required|array|min:1',
            ]);
        }
    }
    
    public function addAthlete()
    {
        $this->athletes[] = ['name' => '', 'nik' => '', 'gender' => 'Male', 'birth_date' => '', 'weight' => '', 'achievement_history' => '', 'category_ids' => []];
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
    
    public function submit()
    {
        $this->validateStep();
        
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
            ]);
            
            foreach ($this->athletes as $athleteData) {
                $athlete = $contingent->athletes()->create([
                    'name' => $athleteData['name'],
                    'nik' => $athleteData['nik'],
                    'gender' => $athleteData['gender'],
                    'birth_date' => $athleteData['birth_date'],
                    'weight' => $athleteData['weight'],
                    'achievement_history' => $athleteData['achievement_history'],
                ]);
                
                $athlete->categories()->attach($athleteData['category_ids']);
            }
        });
        
        $this->step = 5; // Success step
    }
};
?>

<div class="max-w-4xl mx-auto p-4 sm:p-8 bg-white dark:bg-zinc-900 shadow-2xl rounded-3xl border border-zinc-200 dark:border-zinc-800 backdrop-blur-xl bg-opacity-90">
    <div class="mb-10 relative">
        <div class="flex justify-between mb-4">
            @foreach([1, 2, 3, 4] as $index)
                <div @class([
                    'w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-500 z-10',
                    'bg-indigo-600 text-white ring-4 ring-indigo-200 dark:ring-indigo-900 scale-110 shadow-lg' => $step === $index,
                    'bg-emerald-500 text-white' => $step > $index,
                    'bg-zinc-100 dark:bg-zinc-800 text-zinc-400' => $step < $index,
                ])>
                    {{ $step > $index ? '✓' : $index }}
                </div>
            @endforeach
        </div>
        <div class="absolute top-5 left-0 w-full h-0.5 bg-zinc-100 dark:bg-zinc-800 -z-0"></div>
        <div class="absolute top-5 left-0 h-0.5 bg-indigo-600 transition-all duration-500" style="width: {{ ($step - 1) / 3 * 100 }}%"></div>
    </div>

    @if($step === 1)
        <div class="space-y-6 animate-fade-in">
            <div>
                <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">Detail Kontingen</h2>
                <p class="text-zinc-500 mt-2">Informasi dasar tentang tim atau dojo Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Nama Kontingen / Perkemi</label>
                    <input type="text" wire:model="contingent_name" class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="Masukkan nama dojo">
                    @error('contingent_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Nama Penanggung Jawab</label>
                    <input type="text" wire:model="leader_name" class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="Nama lengkap">
                    @error('leader_name') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Nomor WhatsApp</label>
                    <input type="text" wire:model="leader_phone" class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="08xxxxxx">
                    @error('leader_phone') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Email Utama</label>
                    <input type="email" wire:model="leader_email" class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 transition-all outline-none" placeholder="email@contoh.com">
                    @error('leader_email') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Alamat Lengkap</label>
                    <textarea wire:model="address" class="w-full px-4 py-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 transition-all outline-none h-24" placeholder="Alamat dojo..."></textarea>
                    @error('address') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
    @elseif($step === 2)
        <div class="space-y-6 animate-fade-in text-white/90">
            <div class="flex justify-between items-center bg-indigo-50 dark:bg-indigo-900/30 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                <div>
                    <h2 class="text-2xl font-bold text-indigo-900 dark:text-indigo-100">Daftar Atlet</h2>
                    <p class="text-indigo-600 dark:text-indigo-400">Tambahkan semua atlet kontingen Anda.</p>
                </div>
                <button wire:click="addAthlete" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg transition-transform active:scale-95 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Atlet
                </button>
            </div>

            <div class="space-y-4">
                @foreach($athletes as $index => $athlete)
                    <div wire:key="athlete-{{ $index }}" class="p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 relative group transition-all hover:ring-2 hover:ring-indigo-500/20">
                        @if(count($athletes) > 1)
                            <button wire:click="removeAthlete({{ $index }})" class="absolute top-4 right-4 text-zinc-400 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold uppercase text-zinc-500 mb-1 block">Nama Lengkap</label>
                                <input type="text" wire:model="athletes.{{ $index }}.name" class="w-full px-3 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 outline-none" placeholder="Nama Lengkap">
                                @error('athletes.'.$index.'.name') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-xs font-bold uppercase text-zinc-500 mb-1 block">NIK (16 Digit)</label>
                                <input type="text" wire:model="athletes.{{ $index }}.nik" class="w-full px-3 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 outline-none" placeholder="3201...">
                                @error('athletes.'.$index.'.nik') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-zinc-500 mb-1 block">Jenis Kelamin</label>
                                <select wire:model="athletes.{{ $index }}.gender" class="w-full px-3 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 outline-none">
                                    <option value="Male">Putra</option>
                                    <option value="Female">Putri</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase text-zinc-500 mb-1 block">Tgl Lahir</label>
                                <input type="date" wire:model="athletes.{{ $index }}.birth_date" class="w-full px-3 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 outline-none">
                                @error('athletes.'.$index.'.birth_date') <span class="text-rose-500 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-4 mt-2">
                                <label class="text-xs font-bold uppercase text-zinc-500 mb-1 block">Riwayat Juara / Prestasi (Optional)</label>
                                <textarea wire:model="athletes.{{ $index }}.achievement_history" rows="2" class="w-full px-3 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 outline-none text-sm" placeholder="Contoh: Juara 1 Kumite Jakarta Open 2024, Best Of The Best Bandung Cup 2025..."></textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($step === 3)
        <div class="space-y-6 animate-fade-in text-white/90">
            <div>
                <h2 class="text-2xl font-bold">Pilih Kategori Pertandingan</h2>
                <p class="text-zinc-500">Tentukan kategori yang akan diikuti setiap atlet.</p>
            </div>

            @foreach($athletes as $index => $athlete)
                <div class="mb-8 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/50">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">{{ $index + 1 }}</div>
                        <h3 class="text-lg font-bold">{{ $athlete['name'] ?: 'Atlet '.($index + 1) }} ({{ $athlete['gender'] === 'Male' ? 'Putra' : 'Putri' }})</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @php
                            $availableCategories = \App\Models\Category::where('gender', $athlete['gender'])->get();
                        @endphp
                        @foreach($availableCategories as $category)
                            <label class="flex items-start p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-indigo-500 cursor-pointer group transition-all">
                                <input type="checkbox" wire:model="athletes.{{ $index }}.category_ids" value="{{ $category->id }}" class="mt-1 w-5 h-5 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <span class="block font-semibold group-hover:text-indigo-600 transition-colors">{{ $category->name }}</span>
                                    <span class="text-xs text-zinc-500 uppercase">{{ $category->type }} - {{ $category->age_group }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('athletes.'.$index.'.category_ids') <span class="text-rose-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>
    @elseif($step === 4)
        <div class="space-y-8 animate-fade-in text-white/90">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-indigo-600 uppercase tracking-widest">Ringkasan Pendaftaran</h2>
                <p class="text-zinc-500 mt-2">Periksa kembali data Anda sebelum melakukan pembayaran.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-zinc-50 dark:bg-zinc-800/50 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800">
                    <h3 class="text-lg font-bold mb-4 border-b border-zinc-200 pb-2">Info Kontingen</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between"><dt class="text-zinc-500">Perkemi:</dt> <dd class="font-bold">{{ $contingent_name }}</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">Penanggung Jawab:</dt> <dd class="font-bold">{{ $leader_name }}</dd></div>
                        <div class="flex justify-between"><dt class="text-zinc-500">WA:</dt> <dd class="font-bold">{{ $leader_phone }}</dd></div>
                    </dl>
                </div>
                
                <div class="bg-zinc-50 dark:bg-zinc-800/50 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800">
                    <h3 class="text-lg font-bold mb-4 border-b border-zinc-200 pb-2">Rincian Biaya</h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-zinc-500 italic">Biaya Kontingen</span>
                            <span class="font-mono">Rp {{ number_format($contingent_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-4">
                            <span class="text-zinc-500 italic">Atlet ({{ count($athletes) }} x Rp {{ number_format($athlete_fee, 0, ',', '.') }})</span>
                            <span class="font-mono">Rp {{ number_format(count($athletes) * $athlete_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-extrabold text-indigo-600 pt-2">
                            <span>TOTAL TAGIHAN</span>
                            <span class="font-mono">Rp {{ number_format($this->getTotalProperty(), 0, ',', '.') }}</span>
                        </div>
                    </dl>
                </div>
            </div>
            
            <div class="bg-amber-50 dark:bg-amber-900/20 p-6 rounded-2xl border border-amber-200 dark:border-amber-800/50 flex gap-4">
                <svg class="w-8 h-8 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-amber-800 dark:text-amber-200 text-sm">
                    <p class="font-bold mb-1">Penting!</p>
                    <p>Setelah melakukan klik **"Kirim Pendaftaran"**, data tidak dapat diubah secara mandiri. Silakan pastikan semua nama atlet dan kategori sudah benar.</p>
                </div>
            </div>
        </div>
    @elseif($step === 5)
        <div class="text-center py-20 animate-fade-in text-white/90">
            <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-2xl pulse-animation">
                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="text-5xl font-black mb-4">BERHASIL!</h1>
            <p class="text-xl text-zinc-500 mb-10">Pendaftaran Anda telah kami terima.</p>
            
            <div class="max-w-md mx-auto bg-zinc-50 dark:bg-zinc-800/50 p-8 rounded-3xl border border-dashed border-zinc-300 dark:border-zinc-700 space-y-6">
                <div>
                    <span class="text-xs uppercase text-zinc-500 font-bold tracking-widest block mb-2">Total Transfer</span>
                    <h2 class="text-4xl font-mono font-black text-indigo-600">Rp {{ number_format($this->getTotalProperty(), 0, ',', '.') }}</h2>
                </div>
                <div class="space-y-4 pt-4 border-t border-zinc-200">
                    <p class="text-sm font-bold">Silakan transfer ke rekening berikut:</p>
                    <div class="bg-white dark:bg-zinc-900 p-4 rounded-xl shadow-inner text-left font-mono">
                        <p class="text-zinc-500">Bank Central Asia (BCA)</p>
                        <p class="text-xl font-black">123 - 4567 - 890</p>
                        <p class="text-xs opacity-60">A/N PANITIA DOJO CUP</p>
                    </div>
                </div>
                <p class="text-xs text-zinc-400 italic">Simpan bukti transfer dan konfirmasi via link WhatsApp panitia di bawah.</p>
            </div>
            
            <div class="mt-12">
                <a href="/" class="text-indigo-600 font-bold hover:underline">Kembali ke Beranda</a>
            </div>
        </div>
    @endif

    @if($step < 5)
        <div class="flex justify-between mt-12 pt-8 border-t border-zinc-100 dark:border-zinc-800">
            @if($step > 1)
                <button wire:click="prevStep" class="px-8 py-3 rounded-2xl font-bold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali
                </button>
            @else
                <div></div>
            @endif

            @if($step < 4)
                <button wire:click="nextStep" class="bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 px-10 py-4 rounded-2xl font-black shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                    Lanjutkan
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            @else
                <button wire:click="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-10 py-4 rounded-2xl font-black shadow-xl hover:scale-105 active:scale-95 transition-all flex items-center gap-2 shadow-emerald-500/30">
                    Kirim Pendaftaran
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            @endif
        </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
    }
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(16, 185, 129, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>