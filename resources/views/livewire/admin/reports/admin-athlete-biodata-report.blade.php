<div class="p-6 bg-slate-50 min-h-screen">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        
        .biodata-container {
            font-family: 'Inter', sans-serif;
        }

        .report-card {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 15px;
            width: 100%;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .card-header {
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 14px;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
            padding-bottom: 5px;
        }

        .bio-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .bio-table th, .bio-table td {
            text-align: left;
            padding: 4px 6px;
            vertical-align: top;
        }

        .bio-table th {
            width: 120px;
            font-weight: 600;
            color: #475569;
        }

        .photo-box {
            width: 110px;
            height: 140px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-cover: cover;
        }

        .section-title {
            font-size: 10px;
            font-weight: 700;
            text-decoration: underline;
            margin-top: 15px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .achievement-list, .match-list {
            font-size: 10px;
            padding-left: 15px;
            margin: 0;
        }

        .grid-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            .grid-layout {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
            }
            .report-card {
                border: 1px solid #000 !important;
            }
        }
    </style>

    <div class="no-print mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight uppercase">Laporan Biodata Peserta</h1>
                <p class="text-sm text-slate-500 font-medium mt-1">Cetak kartu profil atlet untuk verifikasi lapangan.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-64">
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1 tracking-widest">Pilih Kontingen</label>
                    <select wire:model.live="contingent_id" class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-2 font-bold text-slate-700 focus:outline-none focus:border-rose-500 transition-all">
                        <option value="">-- Pilih Kontingen --</option>
                        @foreach($contingents as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button onclick="window.print()" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-slate-800 transition-all shadow-lg active:scale-95 mt-4">
                    <i class="fas fa-print"></i> CETAK LAPORAN
                </button>
            </div>
        </div>
    </div>

    <div class="biodata-container">
        @if(empty($athletes))
            <div class="no-print text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="fas fa-id-card text-3xl"></i>
                </div>
                <h3 class="text-slate-400 font-bold uppercase tracking-widest">Silakan Pilih Kontingen Dahulu</h3>
            </div>
        @else
            <div class="grid-layout">
                @foreach($athletes as $index => $atlet)
                    <div class="report-card">
                        <div class="card-header">BIODATA PESERTA</div>
                        
                        <div class="flex gap-4">
                            <div class="photo-box">
                                @if($atlet['photo_path'])
                                    <img src="{{ asset('storage/' . $atlet['photo_path']) }}" alt="Foto">
                                @else
                                    <div class="text-[9px] text-slate-300 font-bold uppercase text-center p-2">FOTO 3X4</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <table class="bio-table">
                                    <tr>
                                        <th>NOMOR</th>
                                        <td>: {{ $loop->iteration }}</td>
                                    </tr>
                                    <tr>
                                        <th>NAMA</th>
                                        <td class="font-bold">: {{ strtoupper($atlet['name']) }}</td>
                                    </tr>
                                    <tr>
                                        <th>JENIS KELAMIN</th>
                                        <td>: {{ $atlet['gender'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>GOLONGAN DARAH</th>
                                        <td>: {{ $atlet['blood_type'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIK</th>
                                        <td>: {{ $atlet['nik'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>TINGKAT</th>
                                        <td>: {{ strtoupper($atlet['kyu']) ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>STATUS</th>
                                        <td>: {{ $atlet['status'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>TEMPAT TANGGAL LAHIR</th>
                                        <td>: {{ strtoupper($atlet['birth_place'] ?? '-') }}, {{ $atlet['birth_date'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>ALAMAT RUMAH</th>
                                        <td>: {{ $atlet['address'] ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>TELEPON</th>
                                        <td>: {{ $atlet['phone'] ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="section-title">Keterangan Khusus Peserta Prestasi Terbaik:</div>
                        <ol class="achievement-list">
                            @forelse($atlet['achievements'] as $ach)
                                <li>{{ $ach }}</li>
                            @empty
                                <li class="italic text-slate-400">Tidak ada catatan prestasi</li>
                            @endforelse
                        </ol>

                        <div class="section-title">Nomor dan Kelas Pertandingan yang akan diikuti:</div>
                        <ol class="match-list">
                            @forelse($atlet['matches'] as $match)
                                <li class="font-bold">{{ $match['name'] }}</li>
                                <ul style="list-style-type: disc; margin-bottom: 5px;">
                                    @foreach($match['techniques'] as $tech)
                                        <li>{{ $tech }}</li>
                                    @endforeach
                                </ul>
                            @empty
                                <li class="italic text-slate-400">Belum terdaftar di kategori pertandingan</li>
                            @endforelse
                        </ol>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
