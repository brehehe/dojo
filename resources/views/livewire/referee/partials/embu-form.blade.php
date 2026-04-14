<div>
    <!-- Current Performer Info -->
    <div class="mb-10 text-center bg-slate-50 rounded-[2rem] p-8 border border-slate-100">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">PESERTA TAMPIL</p>
        <div class="inline-flex flex-col items-center">
            @if($activeMatch->active_registration_id)
                @php
                    $reg = \App\Models\Registration::with('athletes', 'contingent')->find($activeMatch->active_registration_id);
                @endphp
                <div class="w-20 h-20 bg-white rounded-3xl shadow-sm flex items-center justify-center mb-4 border border-slate-200 text-orange-500">
                    <i class="fas fa-user-ninja text-3xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800 uppercase leading-none mb-2">
                    @foreach($reg->athletes as $athlete)
                        {{ $athlete->name }}{{ !$loop->last ? ' & ' : '' }}
                    @endforeach
                </h3>
                <p class="px-4 py-1.5 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                    {{ $reg->contingent->name }}
                </p>
            @else
                <div class="text-slate-400 italic text-sm">Menunggu Panitera memanggil peserta...</div>
            @endif
        </div>
    </div>

    <!-- Section: Penguasaan Teknik (60) -->
    <div class="space-y-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="h-px flex-1 bg-slate-100"></div>
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-fist-raised text-orange-500"></i>
                PENGUASAAN TEKNIK (60)
            </h4>
            <div class="h-px flex-1 bg-slate-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $techniqueItems = [
                    'goho_1' => ['label' => 'Serangan, bertahan, balasan', 'cat' => 'GOHO'],
                    'goho_2' => ['label' => 'Lima unsur serangan', 'cat' => 'GOHO'],
                    'goho_3' => ['label' => 'Kombinasi & timing', 'cat' => 'GOHO'],
                    'juho_4' => ['label' => 'Shuha, nukiwaza, gyaku waza', 'cat' => 'JUHO'],
                    'juho_5' => ['label' => 'Nage waza, katame waza, dll', 'cat' => 'JUHO'],
                    'juho_6' => ['label' => 'Kelancaran & kontrol', 'cat' => 'JUHO'],
                ];
            @endphp

            @foreach($techniqueItems as $key => $item)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-md">{{ $item['cat'] }}</span>
                        <span class="text-lg font-black text-slate-800">{{ number_format($embuItems[$key], 1) }}</span>
                    </div>
                    <p class="text-xs font-bold text-slate-600 mb-4 line-clamp-1">{{ $item['label'] }}</p>
                    <input 
                        type="range" 
                        wire:model.live="embuItems.{{ $key }}" 
                        min="0" max="10" step="0.5"
                        class="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-orange-500"
                    >
                </div>
            @endforeach
        </div>

        <!-- Section: Ekspresi (40) -->
        <div class="flex items-center gap-4 mt-12 mb-4">
            <div class="h-px flex-1 bg-slate-100"></div>
            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-heart text-rose-500"></i>
                EKSPRESI (40)
            </h4>
            <div class="h-px flex-1 bg-slate-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $expressionItems = [
                    'ekspresi_1' => ['label' => 'Rangkaian, Irama, Harmoni'],
                    'ekspresi_2' => ['label' => 'Tai gamae, Kuda-kuda, Keindahan'],
                    'ekspresi_3' => ['label' => 'Semangat, Disiplin'],
                    'ekspresi_4' => ['label' => 'Nafas, Pandangan mata, Zanshin'],
                ];
            @endphp

            @foreach($expressionItems as $key => $item)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-md">EKSPRESI</span>
                        <span class="text-lg font-black text-slate-800">{{ number_format($embuItems[$key], 1) }}</span>
                    </div>
                    <p class="text-xs font-bold text-slate-600 mb-4 line-clamp-1">{{ $item['label'] }}</p>
                    <input 
                        type="range" 
                        wire:model.live="embuItems.{{ $key }}" 
                        min="0" max="10" step="0.5"
                        class="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-orange-500"
                    >
                </div>
            @endforeach
        </div>
    </div>
</div>
