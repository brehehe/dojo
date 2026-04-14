    <!-- Match Info -->
    <div class="grid grid-cols-2 gap-4 mb-10">
        @php
            $names = ['aka' => 'Peserta AKA', 'shiro' => 'Peserta SHIRO'];
            $contingents = ['aka' => '-', 'shiro' => '-'];
            
            if($activeMatch->active_bracket_node) {
                [$r, $m] = explode('_', $activeMatch->active_bracket_node);
                $node = $activeMatch->drawing_data['rounds'][$r][$m] ?? null;
                if($node) {
                    $names['aka'] = $node['athlete1']['name'] ?? 'TBD';
                    $contingents['aka'] = $node['athlete1']['contingent_name'] ?? '-';
                    $names['shiro'] = $node['athlete2']['name'] ?? 'TBD';
                    $contingents['shiro'] = $node['athlete2']['contingent_name'] ?? '-';
                }
            }
        @endphp
        <!-- AKA (Red) -->
        <div class="bg-rose-50 border-2 border-rose-100 rounded-[2rem] p-6 text-center">
            <p class="text-[10px] font-black text-rose-500 uppercase tracking-widest mb-3">PITA MERAH (AKA)</p>
            <div class="w-14 h-14 bg-white rounded-2xl shadow-sm mx-auto mb-3 flex items-center justify-center text-rose-500">
                <i class="fas fa-ninja text-2xl"></i>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight line-clamp-1">{{ $names['aka'] }}</h4>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $contingents['aka'] }}</p>
        </div>

        <!-- SHIRO (White) -->
        <div class="bg-slate-50 border-2 border-slate-200 rounded-[2rem] p-6 text-center">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">PITA PUTIH (SHIRO)</p>
            <div class="w-14 h-14 bg-white rounded-2xl shadow-sm mx-auto mb-3 flex items-center justify-center text-slate-400">
                <i class="fas fa-ninja text-2xl"></i>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-tight line-clamp-1">{{ $names['shiro'] }}</h4>
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $contingents['shiro'] }}</p>
        </div>
    </div>

    <!-- Scoring Board -->
    <div class="space-y-4">
        @php
            $points = [
                'mujoken' => ['label' => 'Mujoken / Peringatan', 'pts' => 15, 'icon' => 'fa-exclamation-triangle'],
                'ippon' => ['label' => 'Ippon / Telak', 'pts' => 10, 'icon' => 'fa-star'],
                'wazaari' => ['label' => 'Waza Ari / Poin', 'pts' => 5, 'icon' => 'fa-circle'],
                'batsu5' => ['label' => 'Hasil Batsu 5', 'pts' => 5, 'icon' => 'fa-minus-circle'],
                'batsu10' => ['label' => 'Hasil Batsu 10', 'pts' => 10, 'icon' => 'fa-times-circle'],
                'yusei' => ['label' => 'Yusei Kachi', 'pts' => 5, 'icon' => 'fa-check-circle'],
            ];
        @endphp

        @foreach($points as $key => $point)
            <div class="grid grid-cols-5 items-center gap-2">
                <!-- RED Count -->
                <div class="col-span-1 flex flex-col items-center">
                    <button wire:click="$wire.randoriItems.aka.{{ $key }}++" class="w-full py-3 bg-rose-500 text-white rounded-xl text-xs font-black active:scale-95">+</button>
                    <span class="text-sm font-black text-rose-600 mt-1">{{ $randoriItems['aka'][$key] }}</span>
                    <button wire:click="if($wire.randoriItems.aka.{{ $key }} > 0) $wire.randoriItems.aka.{{ $key }}--" class="text-[9px] font-bold text-rose-300 mt-1">-1</button>
                </div>

                <!-- Label -->
                <div class="col-span-3 bg-white border border-slate-100 rounded-2xl p-3 flex flex-col items-center justify-center shadow-sm">
                    <i class="fas {{ $point['icon'] }} text-xs text-slate-300 mb-1"></i>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">{{ $point['label'] }}</p>
                    <p class="text-[10px] font-bold text-orange-500 mt-1">Nilai: {{ $point['pts'] }}</p>
                </div>

                <!-- WHITE Count -->
                <div class="col-span-1 flex flex-col items-center">
                    <button wire:click="$wire.randoriItems.shiro.{{ $key }}++" class="w-full py-3 bg-slate-900 text-white rounded-xl text-xs font-black active:scale-95">+</button>
                    <span class="text-sm font-black text-slate-900 mt-1">{{ $randoriItems['shiro'][$key] }}</span>
                    <button wire:click="if($wire.randoriItems.shiro.{{ $key }} > 0) $wire.randoriItems.shiro.{{ $key }}--" class="text-[9px] font-bold text-slate-400 mt-1">-1</button>
                </div>
            </div>
        @endforeach
    </div>
</div>
