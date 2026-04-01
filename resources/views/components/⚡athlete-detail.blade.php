<?php

use Livewire\Component;
use App\Models\Athlete;

new class extends Component
{
    public Athlete $athlete;

    public function mount(Athlete $athlete)
    {
        $this->athlete = $athlete->load(['contingent', 'categories']);
    }
};
?>

<div class="min-h-screen bg-zinc-950 py-20 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-12">
            <a href="/" class="group inline-flex items-center gap-3 text-zinc-500 hover:text-white transition-colors">
                <div class="w-10 h-10 rounded-xl bg-zinc-900 flex items-center justify-center group-hover:bg-rose-600 transition-colors">
                    <svg class="w-5 h-5 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="font-black uppercase tracking-widest text-xs">Kembali ke Portal</span>
            </a>
        </div>

        <!-- Hero Profile -->
        <div class="relative mb-8 p-12 rounded-[3rem] bg-zinc-900 border border-zinc-800 overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-rose-600/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row gap-10 items-center md:items-start text-center md:text-left">
                <div class="w-32 h-32 rounded-3xl bg-rose-600 flex items-center justify-center text-5xl font-black italic text-white shadow-2xl shadow-rose-600/20">
                    {{ substr($athlete->name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-3 mb-4 justify-center md:justify-start">
                        <span class="bg-rose-600/10 text-rose-500 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">Athlete Profile</span>
                        <span class="bg-emerald-600/10 text-emerald-500 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">Active Member</span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black font-title uppercase tracking-tighter mb-2">{{ $athlete->name }}</h1>
                    <p class="text-zinc-500 text-lg uppercase tracking-widest font-bold">{{ $athlete->contingent->name }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Bio -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Achievements -->
                <div class="glass p-10 rounded-[2.5rem]">
                    <h3 class="text-xl font-black font-title mb-6 uppercase tracking-tight text-rose-500">HIERARKI PRESTASI</h3>
                    <div class="p-6 bg-zinc-950/50 rounded-2xl border border-rose-500/10 italic text-zinc-400 leading-relaxed relative">
                         <span class="absolute -top-4 -left-2 text-6xl text-rose-600/10 font-serif">"</span>
                         {{ $athlete->achievement_history ?: 'Belum ada riwayat prestasi tercatat.' }}
                    </div>
                </div>

                <!-- Current Participation -->
                <div class="glass p-10 rounded-[2.5rem]">
                    <h3 class="text-xl font-black font-title mb-6 uppercase tracking-tight text-rose-500">PARTISIPASI DOJO CUP 2026</h3>
                    <div class="space-y-4">
                        @foreach($athlete->categories as $category)
                            <div class="flex items-center justify-between p-6 bg-zinc-900/50 rounded-2xl border border-zinc-800">
                                <div>
                                    <h4 class="font-bold uppercase tracking-tight">{{ $category->name }}</h4>
                                    <p class="text-xs text-zinc-500 uppercase tracking-widest mt-1">{{ $category->type }} - {{ $category->age_group }}</p>
                                </div>
                                <div class="w-12 h-12 rounded-xl bg-rose-600/10 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($athlete->categories->isEmpty())
                            <p class="text-zinc-500 italic text-sm">Belum terdaftar di kategori pertandingan manapun.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side: Personal Info -->
            <div class="space-y-8">
                <div class="bg-zinc-900 border border-zinc-800 p-10 rounded-[2.5rem]">
                    <h3 class="text-xl font-black font-title mb-8 uppercase tracking-tight text-rose-500">BIODATA RESMI</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block mb-2">NIK TERVERIFIKASI</label>
                            <p class="font-mono text-lg font-bold tracking-widest">{{ $athlete->nik }}</p>
                        </div>
                        <div class="h-px bg-zinc-800"></div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block mb-2">JENIS KELAMIN</label>
                            <p class="font-bold uppercase tracking-widest">{{ $athlete->gender == 'Male' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</p>
                        </div>
                        <div class="h-px bg-zinc-800"></div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block mb-1">TANGGAL LAHIR</label>
                            <p class="font-bold">{{ \Carbon\Carbon::parse($athlete->birth_date)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="h-px bg-zinc-800"></div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-zinc-500 tracking-widest block mb-1">BERAT BADAN</label>
                            <p class="font-bold">{{ $athlete->weight }} KG</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 rounded-[2rem] bg-rose-600/5 border border-rose-600/20 text-center">
                    <p class="text-xs font-bold text-rose-500 uppercase tracking-widest leading-relaxed">Seluruh data yang ditampilkan telah melalui proses verifikasi panitia Perkemi Cup 2026.</p>
                </div>
            </div>
        </div>
    </div>
</div>