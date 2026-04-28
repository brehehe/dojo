<div class="space-y-6">
    <!-- Header/Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.registrations.index') }}"
                class="w-10 h-10 bg-white rounded-xl border border-slate-100 flex items-center justify-center text-slate-800 hover:text-orange-600 hover:border-orange-100 transition-all shadow-sm">
                <i class="fas fa-arrow-left text-[15px]"></i>
            </a>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Detail Pendaftaran</h1>
                    @if($registration->status === 'pending')
                        <span
                            class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[15px] font-black rounded-full uppercase tracking-widest border border-yellow-200">Pending</span>
                    @elseif($registration->status === 'verified')
                        <span
                            class="px-3 py-1 bg-green-100 text-green-700 text-[15px] font-black rounded-full uppercase tracking-widest border border-green-200">Verified</span>
                    @else
                        <span
                            class="px-3 py-1 bg-red-100 text-red-700 text-[15px] font-black rounded-full uppercase tracking-widest border border-red-200">Rejected</span>
                    @endif
                </div>
                <p class="text-lg text-slate-900 font-medium">Ref: <span
                        class="font-mono font-bold text-orange-600">{{ $registration->referral_code }}</span> •
                    Terdaftar pada {{ $registration->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        @if($registration->status === 'pending')
            <div class="flex items-center gap-2">
                <button wire:click="reject"
                    class="px-6 py-2.5 bg-white border-2 border-slate-100 text-slate-900 rounded-2xl text-[15px] font-black uppercase tracking-widest hover:bg-slate-50 hover:text-red-600 transition-all shadow-sm">
                    Tolak Pendaftaran
                </button>
                <button wire:click="verify"
                    class="px-6 py-2.5 bg-orange-600 text-white rounded-2xl text-[15px] font-black uppercase tracking-widest hover:bg-orange-700 transition-all shadow-lg shadow-orange-200">
                    Verifikasi Sekarang
                </button>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
        <!-- Left Column: Contingent & Payment -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Contingent Info -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative z-10">
                    <h2 class="text-[15px] font-black text-orange-600 uppercase tracking-[0.2em] mb-6">Profil Kontingen
                    </h2>
                    <div class="flex items-center gap-4 mb-6">
                        <div
                            class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-xl">
                            {{ substr($registration->contingent?->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 uppercase leading-none mb-2">
                                {{ $registration->contingent?->name }}</h3>
                            <p class="text-[15px] text-slate-800 font-bold uppercase tracking-wider italic">
                                {{ $registration->contingent?->kab_kota }}</p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-1">Manager Tim
                            </p>
                            <p class="text-[15px] font-black text-black leading-none">
                                {{ $registration->contingent?->leader_name }}</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest mb-1">Kontak &
                                Email</p>
                            <p class="text-[15px] font-black text-black leading-none mb-1">
                                {{ $registration->contingent?->leader_phone }}</p>
                            <p class="text-[15px] text-slate-800 font-medium lowercase">
                                {{ $registration->contingent?->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fee Details -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative z-10">
                    <h2 class="text-[15px] font-black text-emerald-600 uppercase tracking-[0.2em] mb-6">Rincian Biaya</h2>
                    
                    <div class="space-y-4">
                        <!-- Contingent Fee -->
                        <div class="flex justify-between items-start pb-4 border-b border-slate-50">
                            <div>
                                <p class="text-[15px] font-black text-slate-800 uppercase leading-none mb-1">Iuran Kontingen</p>
                                <p class="text-[15px] text-slate-800 font-bold uppercase tracking-wider">Pendaftaran Institusi</p>
                            </div>
                            <p class="text-[15px] font-black text-black">Rp {{ number_format($this->feeDetails['contingent_fee'], 0, ',', '.') }}</p>
                        </div>

                        <!-- Athlete Fees -->
                        @foreach($this->feeDetails['athlete_fees'] as $groupName => $fee)
                            <div class="flex justify-between items-start pb-4 border-b border-slate-50">
                                <div>
                                    <p class="text-[15px] font-black text-slate-800 uppercase leading-none mb-1">
                                        {{ $fee['count'] }}x Atlet {{ $groupName }}
                                    </p>
                                    <p class="text-[15px] text-slate-800 font-bold uppercase tracking-wider">
                                        @ Rp {{ number_format($fee['price'], 0, ',', '.') }}
                                    </p>
                                </div>
                                <p class="text-[15px] font-black text-black">Rp {{ number_format($fee['total'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach

                        <!-- Unique Code -->
                        @if($this->feeDetails['unique_code'] > 0)
                            <div class="flex justify-between items-start pb-4 border-b border-slate-50">
                                <div>
                                    <p class="text-[15px] font-black text-orange-600 uppercase leading-none mb-1">Kode Unik</p>
                                    <p class="text-[15px] text-slate-800 font-bold uppercase tracking-wider">Identifikasi Transfer</p>
                                </div>
                                <p class="text-[15px] font-black text-orange-600">+{{ number_format($this->feeDetails['unique_code'], 0, ',', '.') }}</p>
                            </div>
                        @endif

                        <!-- Final Amount -->
                        <div class="pt-2">
                            <div class="flex justify-between items-center bg-white rounded-2xl p-4 text-white">
                                <span class="text-[15px] font-black uppercase tracking-widest text-slate-900">Total Akhir</span>
                                <span class="text-[15px] font-black italic text-slate-900">Rp {{ number_format($this->feeDetails['final_amount'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Proof -->
            <div class="bg-white rounded-[2.5rem] p-8 text-slate-900 shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-[15px] font-black text-orange-400 uppercase tracking-[0.2em] mb-6">Bukti Pembayaran
                    </h2>
                    <div class="bg-slate-50 rounded-3xl p-4 border border-slate-100 mb-6 group cursor-pointer"
                        onclick="window.open('{{ asset('storage/' . $registration->transfer_proof_path) }}', '_blank')">
                        @if($registration->transfer_proof_path)
                            <img src="{{ asset('storage/' . $registration->transfer_proof_path) }}"
                                class="w-full h-48 object-cover rounded-2xl shadow-inner group-hover:scale-[1.02] transition-transform duration-500"
                                alt="Bukti Transfer">
                        @else
                            <div
                                class="h-48 flex items-center justify-center bg-slate-50 rounded-2xl border-2 border-dashed border-slate-100">
                                <p class="text-[15px] font-black text-slate-900 uppercase tracking-widest italic">Belum ada
                                    lampiran</p>
                            </div>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center text-[15px]">
                            <span class="text-slate-900 font-bold uppercase">Metode</span>
                            <span class="font-black text-slate-900">{{ $registration->payment_method }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[15px]">
                            <span class="text-slate-900 font-bold uppercase tracking-widest">Total Bayar</span>
                            <span class="text-2xl font-black italic">Rp
                                {{ number_format($registration->final_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-orange-500/10 rounded-full blur-3xl"></div>
            </div>
        </div>

        <!-- Right Column: Officials & Athletes -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Officials Table -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <h2
                    class="text-[15px] font-black text-slate-800 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                    Daftar Official Pendamping ({{ $registration->officials->count() }})
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                    @foreach($registration->officials as $off)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                            <div>
                                <h4 class="text-[15px] font-black text-black uppercase tracking-tight">{{ $off->name }}</h4>
                                <p class="text-[15px] font-bold text-slate-800 uppercase tracking-[0.1em]">
                                    {{ $off->pivot?->role ?? 'OFFICIAL' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-[15px] font-mono text-slate-900 leading-none">{{ $off->phone }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Match Numbers & Athletes Grouping -->
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                <h2
                    class="text-[15px] font-black text-slate-800 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-orange-600"></div>
                    Ringkasan Nomor Pertandingan ({{ count($groupedMatches) }})
                </h2>
                <div class="space-y-4">
                    @forelse($groupedMatches as $mId => $data)
                        <div
                            class="p-6 bg-white rounded-[2rem] border border-slate-100 relative group transition-all hover:shadow-xl hover:shadow-slate-100 duration-500 overflow-hidden">
                            <!-- Match Header -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-6 border-b border-slate-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-orange-500 text-white rounded-2xl flex items-center justify-center shadow-lg transform group-hover:rotate-6 transition-all duration-500">
                                        <span class="text-2xl font-black">{{ $loop->iteration }}</span>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-slate-800 uppercase tracking-tight leading-none mb-2">
                                            {{ $data['details']->name }}  {{ $data['details']->ageGroup?->name }} {{ $data['details']->gender_indo }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white rounded-lg shadow-sm border border-slate-100 overflow-hidden">
                                <div class="overflow-x-auto custom-scrollbar">
                                    <table class="w-full text-left border-collapse border border-slate-200 rounded-xl overflow-hidden">
                                        <thead class="bg-slate-800 text-white">
                                            <tr>
                                                <th class="px-4 py-3 text-[12px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[1%]">No.</th>
                                                <th class="px-4 py-3 text-[12px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[20%]">Nama</th>
                                                <th class="px-4 py-3 text-[12px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[20%]">Tingkat</th>
                                                <th class="px-4 py-3 text-[12px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[50%]">Urutan komposisi yang dimainkan :</th>
                                               @if ($data['details']->draft_type == 'randori')
                                                <th class="px-4 py-3 text-[12px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[10%]">Kelas</th>
                                                <th class="px-4 py-3 text-[12px] font-black uppercase tracking-widest border border-slate-700 whitespace-nowrap w-[10%]">Berat Badan</th>
                                               @endif
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-200">
                                            @php
                                                $athletes = $data['athletes'];
                                                $techniques = $data['techniques'] ?? [];
                                                $rowCount = max(count($athletes), count($techniques));
                                            @endphp
                                            @for($i = 0; $i < $rowCount; $i++)
                                                <tr class="{{ $loop->even ? 'bg-slate-100' : 'bg-white' }} hover:bg-slate-50 transition-colors group">
                                                    <td class="py-4 px-6 {{ $i < $rowCount - 1 ? 'border-b border-slate-300' : '' }} border-r border-slate-200 whitespace-nowrap">
                                                        @if($i < count($athletes))
                                                            <span class="font-bold text-[15px]">{{ $i + 1 }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap border-r border-slate-200 {{ $i < $rowCount - 1 ? 'border-b border-slate-300' : '' }}">
    @if(isset($athletes[$i]))
        <div class="flex flex-col leading-tight">
            <span class="text-[15px] font-semibold text-slate-800">
                {{ $athletes[$i]->name }}
            </span>
            <span class="text-[13px] text-slate-500">
                {{ $athletes[$i]->nik }}
            </span>
        </div>
    @else
        <span class="text-slate-400 text-sm">-</span>
    @endif
</td>
                                                    <td class="py-4 px-6 {{ $i < $rowCount - 1 ? 'border-b border-slate-300' : '' }} border-r border-slate-200 whitespace-nowrap">
                                                        @if($i < count($athletes))
                                                            <span class="font-bold text-[15px]">{{ $athletes[$i]->pivot->rank ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-4 px-6 {{ $i < $rowCount - 1 ? 'border-b border-slate-300' : '' }} border-r border-slate-200 whitespace-nowrap">
                                                        @if($i < count($techniques))
                                                            <span class="font-bold text-[15px]">{{ $i + 1 }}. {{ $allTechniques[$techniques[$i]] ?? '-' }}</span>
                                                        @endif
                                                    </td>
                                                    @if ($data['details']->draft_type == 'randori')
                                                    <td class="py-4 px-6 {{ $i < $rowCount - 1 ? 'border-b border-slate-300' : '' }} border-r border-slate-200 whitespace-nowrap">
                                                        @if($i < count($athletes))
                                                            <span class="font-bold text-[15px]">
                                                            {{ isset($athletes[$i]) 
                                                                ? rtrim(rtrim(number_format($athletes[$i]->pivot->weight, 2, '.', ''), '0'), '.') 
                                                                : '-' 
                                                            }}
                                                        </span>
                                                                                                                @endif
                                                                                                            </td>
                                                                                                            <td class="py-4 px-6 {{ $i < $rowCount - 1 ? 'border-b border-slate-300' : '' }} border-r border-slate-200 whitespace-nowrap">
                                                                                                                @if($i < count($athletes))
                                                                                                                    <span class="font-bold text-[15px]">
                                                            {{ isset($athletes[$i]) 
                                                                ? rtrim(rtrim(number_format($athletes[$i]->pivot->weight, 2, '.', ''), '0'), '.') 
                                                                : '-' 
                                                            }}
                                                        </span>
                                                        @endif
                                                    </td>
                                                    @endif
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-20 text-center">
                            <i class="fas fa-layer-group text-slate-200 text-4xl mb-4"></i>
                            <p class="text-[15px] font-black text-slate-800 uppercase tracking-widest italic">Belum ada nomor
                                pertandingan terpilih</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>