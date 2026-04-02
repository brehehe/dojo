<?php

use Livewire\Component;
use App\Models\Athlete;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public string $nikSearch = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function searchByNik()
    {
        $this->validate([
            'nikSearch' => 'required|min:16',
        ]);

        $athlete = Athlete::where('nik', $this->nikSearch)->first();

        if ($athlete) {
            return $this->redirect(route('athlete.detail', ['athlete' => $athlete->nik]));
        }

        session()->flash('error', 'Kombinasi NIK tidak ditemukan.');
    }

    public function with(): array
    {
        return [
            'results' => Athlete::where('name', 'ilike', '%' . $this->search . '%')
                ->whereNotNull('achievement_history')
                ->latest()
                ->paginate(5),
        ];
    }
};
?>

<div class="space-y-8">
    <!-- NIK Official Search -->
    <div class="bg-rose-600 p-10 rounded-[2.5rem] shadow-2xl shadow-rose-600/20 relative overflow-hidden group">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:scale-110 transition-transform duration-700"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-2xl font-black font-title text-white tracking-tight">VERIFIKASI NIK RESMI</h3>
            </div>
            <p class="text-rose-100 mb-8 max-w-md">Masukkan 16 digit NIK untuk melihat profil lengkap, histori pertandingan, dan status kepesertaan saat ini.</p>
            
            <form wire:submit="searchByNik" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model="nikSearch"
                        placeholder="3201xxxxxxxxxxxx" 
                        class="w-full bg-white/10 border border-white/20 rounded-2xl px-6 py-4 outline-none focus:bg-white/20 focus:border-white transition-all font-bold tracking-widest text-white placeholder:text-rose-200"
                    >
                    @if (session()->has('error'))
                        <p class="mt-2 text-sm font-bold text-yellow-300 italic">{{ session('error') }}</p>
                    @endif
                </div>
                <button type="submit" class="bg-white text-rose-600 px-10 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-rose-50 transition-all shadow-xl active:scale-95">
                    LIHAT DETAIL
                </button>
            </form>
        </div>
    </div>

    <!-- Name Search Archive -->
    <div class="glass p-10 rounded-[2.5rem]">
        <div class="mb-8">
            <h3 class="text-2xl font-black font-title mb-2 tracking-tight">ARCHIVE RECORD (NAMA)</h3>
            <p class="text-zinc-500 text-sm italic">Cari nama atlet untuk melihat kilasan rekam jejak prestasi mereka.</p>
        </div>

        <div class="relative mb-8">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Ketik nama atlet untuk pencarian cepat..." 
                class="w-full bg-zinc-900/50 border border-zinc-800 rounded-2xl px-6 py-4 outline-none focus:border-rose-600 transition-all font-medium pr-12 text-lg"
            >
            <div class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($results as $athlete)
                <div class="p-6 bg-zinc-900/30 rounded-3xl border border-zinc-800/50 hover:border-rose-500/20 transition-all group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h4 class="text-lg font-bold uppercase tracking-tight group-hover:text-rose-500 transition-colors">{{ $athlete->name }}</h4>
                            <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">{{ $athlete->contingent->name }}</span>
                        </div>
                        <a href="{{ route('athlete.detail', ['athlete' => $athlete->nik]) }}" class="bg-rose-600/10 text-rose-500 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-all">Lihat Detail</a>
                    </div>
                    <p class="text-sm text-zinc-500 italic leading-relaxed line-clamp-2">
                        "{{ $athlete->achievement_history }}"
                    </p>
                </div>
            @empty
                <!-- empty state -->
            @endforelse
        </div>

        <div class="mt-8">
            {{ $results->links() }}
        </div>
    </div>
</div>