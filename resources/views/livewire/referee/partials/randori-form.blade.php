<div class="space-y-8">
    
    <!-- Info Header Layout from Image -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <span class="text-[15px] uppercase text-slate-800 font-bold w-20">Hari :</span>
            <span class="text-[15px] font-black text-slate-800 bg-slate-50 px-3 py-1 rounded-lg flex-1 border border-slate-100">{{ \Carbon\Carbon::parse($assignedSession->date ?? now())->translatedFormat('l') }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <span class="text-[15px] uppercase text-slate-800 font-bold w-20">Tanggal :</span>
            <span class="text-[15px] font-black text-slate-800 bg-slate-50 px-3 py-1 rounded-lg flex-1 border border-slate-100 whitespace-nowrap">{{ \Carbon\Carbon::parse($assignedSession->date ?? now())->translatedFormat('d F Y') }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <span class="text-[15px] uppercase text-slate-800 font-bold w-20">Tingkat :</span>
            <span class="text-[15px] font-black text-slate-800 bg-slate-50 px-3 py-1 rounded-lg flex-1 border border-slate-100">{{ $activeMatch->ageGroup->name ?? '-' }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <span class="text-[15px] uppercase text-slate-800 font-bold w-20">Kelas :</span>
            <span class="text-[15px] font-black text-slate-800 bg-slate-50 px-3 py-1 rounded-lg flex-1 border border-slate-100">{{ $activeMatch->weightGroup->name ?? '-' }}</span>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
            <span class="text-[15px] uppercase text-slate-800 font-bold w-20">Court :</span>
            <span class="text-[15px] font-black text-slate-800 bg-slate-50 px-3 py-1 rounded-lg flex-1 border border-slate-100 text-center">{{ $assignedCourt->order ?? '-' }}</span>
        </div>
    </div>

    <!-- Athlete Info -->
    @php
        $aka = null;
        $shiro = null;
        if ($activeMatch && $activeMatch->active_bracket_node) {
            $parts = explode('_', $activeMatch->active_bracket_node);
            $bracket = $parts[0] ?? 'ub';
            $rIdx = $parts[1] ?? 0;
            $mIdx = $parts[2] ?? 0;
            
            $data = $activeMatch->drawing_data ?? [];
            $matchObj = null;
            if ($bracket === 'ub') {
                $matchObj = $data['upper_bracket']['rounds'][$rIdx][$mIdx] ?? null;
            } elseif ($bracket === 'lb') {
                $matchObj = $data['lower_bracket']['rounds'][$rIdx][$mIdx] ?? null;
            } elseif ($bracket === 'gf') {
                $matchObj = $data['grand_final'] ?? null;
            }
            
            $akaData = $matchObj['athlete1'] ?? null;
            $shiroData = $matchObj['athlete2'] ?? null;
            
            $aka = $akaData ? \App\Models\Athlete::find($akaData['id'] ?? null) : null;
            $shiro = $shiroData ? \App\Models\Athlete::find($shiroData['id'] ?? null) : null;
        }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- AKA Card -->
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6">
            <h3 class="text-xl font-black text-rose-700 uppercase tracking-widest mb-6 flex items-center">
                <i class="fas fa-tag mr-2 -rotate-45"></i> PITA MERAH (AKA)
            </h3>
            <div class="space-y-4">
                <div class="flex items-center">
                    <span class="w-24 text-[15px] font-bold text-rose-900 uppercase">Nama :</span>
                    <div class="flex-1 bg-white border border-rose-100 rounded-xl px-4 py-2 font-black text-rose-900">{{ $aka->name ?? 'Belum Ditentukan' }}</div>
                </div>
                <div class="flex items-center">
                    <span class="w-24 text-[15px] font-bold text-rose-900 uppercase">Kontingen :</span>
                    <div class="flex-1 bg-white border border-rose-100 rounded-xl px-4 py-2 font-black text-rose-900">{{ $aka ? ($aka->registrations->first()->contingent->name ?? '-') : '-' }}</div>
                </div>
            </div>
        </div>

        <!-- SHIRO Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
            <h3 class="text-xl font-black text-blue-700 uppercase tracking-widest mb-6 flex items-center">
                <i class="fas fa-tag mr-2 text-slate-800 -rotate-45"></i> PITA PUTIH (SHIRO)
            </h3>
            <div class="space-y-4">
                <div class="flex items-center">
                    <span class="w-24 text-[15px] font-bold text-blue-900 uppercase">Nama :</span>
                    <div class="flex-1 bg-white border border-blue-100 rounded-xl px-4 py-2 font-black text-blue-900">{{ $shiro->name ?? 'Belum Ditentukan' }}</div>
                </div>
                <div class="flex items-center">
                    <span class="w-24 text-[15px] font-bold text-blue-900 uppercase">Kontingen :</span>
                    <div class="flex-1 bg-white border border-blue-100 rounded-xl px-4 py-2 font-black text-blue-900">{{ $shiro ? ($shiro->registrations->first()->contingent->name ?? '-') : '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scoring Table -->
    <div class="bg-white border text-[15px] border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="grid grid-cols-2">
            <!-- Header AKA -->
            <div class="bg-rose-700 text-white text-center py-4 font-black uppercase tracking-widest">
                PITA MERAH (AKA)
            </div>
            <!-- Header SHIRO -->
            <div class="bg-slate-800 text-white text-center py-4 font-black uppercase tracking-widest">
                PITA PUTIH (SHIRO)
            </div>
        </div>

        <table class="w-full text-center border-collapse">
            <thead class="bg-slate-900 text-slate-300 text-[15px]">
                <tr>
                    <th class="py-3 px-2 border border-slate-700">KEPUTUSAN WASIT</th>
                    <th class="py-3 px-2 border border-slate-700">KETERANGAN</th>
                    <th class="py-3 px-2 border border-slate-700">NILAI</th>
                    <th class="py-3 px-2 border border-slate-700">TOTAL PT</th>
                    <th class="py-3 px-2 border border-slate-700">INPUT JML</th>

                    <th class="py-3 px-2 border border-slate-700">KEPUTUSAN WASIT</th>
                    <th class="py-3 px-2 border border-slate-700">KETERANGAN</th>
                    <th class="py-3 px-2 border border-slate-700">NILAI</th>
                    <th class="py-3 px-2 border border-slate-700">TOTAL PT</th>
                    <th class="py-3 px-2 border border-slate-700">INPUT JML</th>
                </tr>
            </thead>
            <tbody class="font-bold text-[15px]">
                @php
                    $randoriRows = [
                        ['lbl' => 'PERINGATAN', 'desc' => 'Mujoken Kachi', 'val' => 15, 'key' => 'mujoken'],
                        ['lbl' => '1.', 'desc' => 'Ippon', 'val' => 10, 'key' => 'ippon'],
                        ['lbl' => '2.', 'desc' => 'Waza Ari', 'val' => 5, 'key' => 'wazaari'],
                        ['lbl' => '3.', 'desc' => 'Hasil Batsu 5', 'val' => 5, 'key' => 'batsu5'],
                        ['lbl' => '4.', 'desc' => 'Hasil Batsu 10', 'val' => 10, 'key' => 'batsu10'],
                        ['lbl' => '5.', 'desc' => 'Yusei Kachi', 'val' => 5, 'key' => 'yusei'],
                    ];
                @endphp

                @foreach($randoriRows as $row)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <!-- AKA Columns -->
                        <td class="py-4 border-r border-slate-100 text-slate-900">{{ $row['lbl'] }}</td>
                        <td class="py-4 border-r border-slate-100">{{ $row['desc'] }}</td>
                        <td class="py-4 border-r border-slate-100 text-slate-800">{{ $row['val'] }}</td>
                        <td class="py-4 border-r border-slate-100 font-black text-rose-600 text-lg">
                            {{ $randoriItems['aka'][$row['key']] * $row['val'] }}
                        </td>
                        <td class="py-3 border-r-2 border-slate-300">
                            <input type="number" min="0" max="10" wire:model.live.debounce.300ms="randoriItems.aka.{{ $row['key'] }}" class="w-16 text-center mx-auto px-2 py-1 border-2 border-slate-200 rounded-lg focus:border-rose-500 font-black text-slate-800">
                        </td>

                        <!-- SHIRO Columns -->
                        <td class="py-4 border-r border-slate-100 text-slate-900">{{ $row['lbl'] }}</td>
                        <td class="py-4 border-r border-slate-100">{{ $row['desc'] }}</td>
                        <td class="py-4 border-r border-slate-100 text-slate-800">{{ $row['val'] }}</td>
                        <td class="py-4 border-r border-slate-100 font-black text-blue-600 text-lg">
                            {{ $randoriItems['shiro'][$row['key']] * $row['val'] }}
                        </td>
                        <td class="py-3">
                            <input type="number" min="0" max="10" wire:model.live.debounce.300ms="randoriItems.shiro.{{ $row['key'] }}" class="w-16 text-center mx-auto px-2 py-1 border-2 border-slate-200 rounded-lg focus:border-blue-500 font-black text-slate-800">
                        </td>
                    </tr>
                @endforeach

                <!-- Total Row -->
                <tr class="bg-slate-200 font-black text-base uppercase">
                    <td colspan="3" class="py-4 text-right pr-6">TOTAL MERAH</td>
                    <td colspan="2" class="py-4 text-center text-rose-700 text-2xl border-r-2 border-slate-300 bg-rose-100">{{ $totalAka }}</td>
                    
                    <td colspan="3" class="py-4 text-right pr-6">TOTAL PUTIH</td>
                    <td colspan="2" class="py-4 text-center text-blue-700 text-2xl bg-blue-100">{{ $totalShiro }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Manager/Wasit Utama Info Footer -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6">
        <div class="col-span-1 md:col-span-3">
            <div class="flex items-center gap-4 border border-slate-200 p-4 rounded-xl shadow-sm bg-white">
                <i class="fas fa-gavel text-slate-300 mx-2"></i>
                <span class="text-[15px] font-black uppercase text-slate-900 w-32">Wasit Utama :</span>
                <span class="font-bold text-slate-800">
                    <!-- Get Chief Judge for this Court (Index 1) -->
                    {{ $assignedCourt && $assignedSession ? (\App\Models\ScheduleReferee::where('court_id', $assignedCourt->id)->where('session_time_id', $assignedSession->id)->where('judge_index', 1)->first()?->referee->name ?? '-') : '-' }}
                </span>
            </div>
        </div>
        <div class="col-span-1 md:col-span-1 border border-slate-200 p-4 rounded-xl shadow-sm bg-white">
            <span class="block text-[15px] font-black uppercase text-slate-800 mb-2">Manager Kontingen (Merah) :</span>
            <span class="font-bold text-slate-800">{{ $aka ? ($aka->registrations->first()?->contingent->officials->first()?->name ?? '-') : '-' }}</span>
        </div>
        <div class="col-span-2 md:col-span-2 border border-slate-200 p-4 rounded-xl shadow-sm bg-white">
            <span class="block text-[15px] font-black uppercase text-slate-800 mb-2">Manager Kontingen (Putih) :</span>
            <span class="font-bold text-slate-800">{{ $shiro ? ($shiro->registrations->first()?->contingent->officials->first()?->name ?? '-') : '-' }}</span>
        </div>
    </div>
</div>
