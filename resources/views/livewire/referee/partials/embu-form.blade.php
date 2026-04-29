<div class="space-y-8">
    <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
        <h2 class="text-xl font-black text-blue-900 uppercase tracking-widest mb-1">Daftar Penilaian Wasit</h2>
        <p
            class="text-[15px] font-bold text-blue-800 bg-blue-200/50 inline-block px-3 py-1 rounded-full uppercase tracking-wider mb-6">
            Kategori Embu
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-[15px] font-bold text-black">
            <div class="bg-white p-3 rounded-xl border border-blue-100 shadow-sm">
                <span class="text-[15px] uppercase text-slate-800 block mb-1">Kontingen</span>
                <span
                    class="text-slate-900 uppercase">{{ $activeMatch->active_registration_id ? (\App\Models\Registration::find($activeMatch->active_registration_id)->contingent->name ?? '-') : '-' }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-blue-100 shadow-sm">
                <span class="text-[15px] uppercase text-slate-800 block mb-1">Babak</span>
                <span class="text-slate-900 uppercase">{{ $activeMatch->round ?? 'Final' }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-blue-100 shadow-sm">
                <span class="text-[15px] uppercase text-slate-800 block mb-1">Jadwal (Waktu / Court)</span>
                <span class="text-slate-900 uppercase">{{ $assignedSession->name ?? '-' }} &bull;
                    {{ $assignedCourt->name ?? '-' }}</span>
            </div>
            <div class="bg-white p-3 rounded-xl border border-blue-100 shadow-sm">
                <span class="text-[15px] uppercase text-slate-800 block mb-1">Kelas / Pool</span>
                <span class="text-slate-900 uppercase">{{ $activeMatch->ageGroup->name ?? '-' }} &bull; -</span>
            </div>
        </div>
    </div>

    <!-- Urutan Komposisi -->
    <div class="bg-white border text-[15px] border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <i class="fas fa-list-ol text-slate-800"></i>
            <h3 class="font-black text-black uppercase tracking-widest">Urutan Komposisi</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $komposisiDummy = [
                    '1' => 'Kihon Dasar',
                    '2' => 'Nage Waza',
                    '3' => 'Katame Waza',
                    '4' => 'Gyaku Waza',
                    '5' => 'Shuha & Nukiwaza',
                    '6' => 'Randori & Zanshin'
                ];
            @endphp
            @foreach($komposisiDummy as $k => $v)
                <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                    <div
                        class="w-8 h-8 bg-slate-800 text-white font-black rounded-full flex items-center justify-center shrink-0">
                        {{ $k }}
                    </div>
                    <div class="font-bold text-black">{{ $v }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Panel Penilaian -->
    <div class="bg-white border text-[15px] border-slate-200 rounded-2xl overflow-x-auto shadow-sm mb-8">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-800 text-white">
                <tr>
                    <th class="px-6 py-4 font-black uppercase tracking-widest w-1/4">Aspek</th>
                    <th class="px-6 py-4 font-black uppercase tracking-widest w-1/2">Deskripsi</th>
                    <th class="px-4 py-4 font-black uppercase text-center w-16">Bobot</th>
                    <th class="px-4 py-4 font-black uppercase text-center w-16">No</th>
                    <th class="px-6 py-4 font-black uppercase text-center">Nilai</th>
                    <th class="px-4 py-4 font-black uppercase text-center">Std</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100 font-medium">
                <!-- Group Penguasaan Teknik -->
                <tr class="bg-blue-50">
                    <td colspan="6" class="px-6 py-3 font-black text-blue-900 uppercase">
                        <i class="fas fa-diamond text-blue-600 mr-2"></i> Penguasaan Teknik (60) — Goho & Juho
                    </td>
                </tr>
                @php
                    $gohoItems = [
                        ['id' => 'goho_1', 'cat' => 'GOHO', 'desc' => 'Serangan, bertahan, balasan'],
                        ['id' => 'goho_2', 'cat' => 'GOHO', 'desc' => 'Lima unsur serangan'],
                        ['id' => 'goho_3', 'cat' => 'GOHO', 'desc' => 'Kombinasi & timing'],
                        ['id' => 'juho_1', 'cat' => 'JUHO', 'desc' => 'Shuha, nukiwaza, gyaku waza'],
                        ['id' => 'juho_2', 'cat' => 'JUHO', 'desc' => 'Nage waza, katame waza, dll'],
                        ['id' => 'juho_3', 'cat' => 'JUHO', 'desc' => 'Kelancaran & kontrol'],
                    ];
                    $ekspresiItems = [
                        ['id' => 'ekspresi_1', 'cat' => 'Ekspresi', 'desc' => '1. Rangkaian, Irama, Harmoni'],
                        ['id' => 'ekspresi_2', 'cat' => 'Ekspresi', 'desc' => '2. Tai gamae, Kuda-kuda, Keindahan'],
                        ['id' => 'ekspresi_3', 'cat' => 'Ekspresi', 'desc' => '3. Semangat, Disiplin'],
                        ['id' => 'ekspresi_4', 'cat' => 'Ekspresi', 'desc' => '4. Nafas, Pandangan mata, Zanshin'],
                    ];
                @endphp
                @foreach($gohoItems as $idx => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-black">{{ $item['cat'] }}</td>
                        <td class="px-6 py-4 text-slate-900">{{ $item['desc'] }}</td>
                        <td class="px-4 py-4 text-center font-bold text-slate-800">10</td>
                        <td class="px-4 py-4 text-center font-bold text-slate-800">{{ $idx + 1 }}</td>
                        <td class="px-6 py-3 text-center">
                            <input type="number" step="0.5" min="0" max="10"
                                wire:model.live.debounce.300ms="embuItems.{{ $item['id'] }}"
                                class="w-24 text-center font-black text-xl py-2 px-3 border-2 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-slate-800">8</td>
                    </tr>
                @endforeach

                <!-- Group Ekspresi -->
                <tr class="bg-rose-50">
                    <td colspan="6" class="px-6 py-3 font-black text-rose-900 uppercase">
                        <i class="fas fa-magic text-rose-600 mr-2"></i> Ekspresi (40) — Rangkaian, Irama, Tai Gamae
                    </td>
                </tr>
                @foreach($ekspresiItems as $idx => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-black">{{ $item['cat'] }}</td>
                        <td class="px-6 py-4 text-slate-900">{{ $item['desc'] }}</td>
                        <td class="px-4 py-4 text-center font-bold text-slate-800">10</td>
                        <td class="px-4 py-4 text-center font-bold text-slate-800">{{ $idx + 1 }}</td>
                        <td class="px-6 py-3 text-center">
                            <input type="number" step="0.5" min="0" max="10"
                                wire:model.live.debounce.300ms="embuItems.{{ $item['id'] }}"
                                class="w-24 text-center font-black text-xl py-2 px-3 border-2 border-slate-200 rounded-xl focus:border-rose-500 focus:ring-rose-500">
                        </td>
                        <td class="px-4 py-4 text-center font-bold text-slate-800">8</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>