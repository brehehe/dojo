<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold; text-align: center; font-size: 14pt;">LAPORAN DETEKSI ATLET MULTI-NOMOR &amp; REKOMENDASI JADWAL ANTI BENTROK</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 11pt; color: #555555;">Simulasi Lapangan (Court): {{ $courtCount }} | Jumlah Hari: {{ $hariCount }}</th>
        </tr>
        <tr>
            <th colspan="5"></th>
        </tr>
        <tr>
            <th colspan="5" style="font-weight: bold; font-size: 12pt; background-color: #fee2e2; border: 1px solid #000;">1. DAFTAR ATLET BERMAIN DI LEBIH DARI 1 NOMOR (WAJIB JEDA JADWAL)</th>
        </tr>
        <tr style="background-color: #f1f5f9; border: 1px solid #000;">
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 5%;">NO</th>
            <th style="font-weight: bold; border: 1px solid #000; width: 30%;">NAMA ATLET</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 15%;">JUMLAH NOMOR</th>
            <th style="font-weight: bold; border: 1px solid #000; width: 35%;">DAFTAR NOMOR (TIPE)</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 15%;">TOTAL MATCH</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @forelse($multiAthletes as $a)
            @php
                $nomorWithType = [];
                foreach ($a['nomorList'] as $idx => $nomor) {
                    $nomorWithType[] = $nomor . ' (' . $a['typeList'][$idx] . ')';
                }
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $no++ }}</td>
                <td style="border: 1px solid #000; font-weight: bold;">{{ strtoupper($a['nama']) }}</td>
                <td style="border: 1px solid #000; text-align: center; background-color: #fee2e2; color: #b91c1c; font-weight: bold;">{{ $a['jumlahNomor'] }} nomor</td>
                <td style="border: 1px solid #000;">{{ implode(', ', $nomorWithType) }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $a['totalMuncul'] }} match</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="border: 1px solid #000; text-align: center; italic: true;">Tidak ada atlet dengan multi-nomor. Semua atlet terdaftar di 1 nomor saja.</td>
            </tr>
        @endforelse

        <tr>
            <td colspan="5" style="height: 25px;"></td>
        </tr>

        <tr>
            <th colspan="5" style="font-weight: bold; font-size: 12pt; background-color: #e0f7e8; border: 1px solid #000;">2. DETAIL RINGKASAN SELURUH ATLET</th>
        </tr>
        <tr style="background-color: #f1f5f9; border: 1px solid #000;">
            <th style="font-weight: bold; border: 1px solid #000; text-align: center;">NO</th>
            <th style="font-weight: bold; border: 1px solid #000;">NAMA ATLET</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center;">JUMLAH NOMOR</th>
            <th style="font-weight: bold; border: 1px solid #000;">STATUS</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center;">TOTAL MATCH</th>
        </tr>
        @php $noAll = 1; @endphp
        @foreach($multiAthletes as $a)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $noAll++ }}</td>
                <td style="border: 1px solid #000;">{{ $a['nama'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $a['jumlahNomor'] }}</td>
                <td style="border: 1px solid #000; color: #b91c1c; font-weight: bold;">Multi ({{ $a['jumlahNomor'] }} nomor) - Resiko Tinggi</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $a['totalMuncul'] }}</td>
            </tr>
        @endforeach
        @foreach($normalAthletes as $a)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $noAll++ }}</td>
                <td style="border: 1px solid #000;">{{ $a['nama'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $a['jumlahNomor'] }}</td>
                <td style="border: 1px solid #000; color: #15803d;">Normal (1 nomor)</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $a['totalMuncul'] }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="5" style="height: 25px;"></td>
        </tr>

        <tr>
            <th colspan="5" style="font-weight: bold; font-size: 12pt; background-color: #dbeafe; border: 1px solid #000;">3. REKOMENDASI JADWAL PERTANDINGAN (ANTI BENTROK)</th>
        </tr>
        <tr style="background-color: #f1f5f9; border: 1px solid #000;">
            <th style="font-weight: bold; border: 1px solid #000; text-align: center;">HARI</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center;">WAKTU</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center;">LAPANGAN / COURT</th>
            <th style="font-weight: bold; border: 1px solid #000;">NOMOR PERTANDINGAN</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center;">ROUND &amp; ATLET</th>
        </tr>
        @forelse($scheduledMatches as $sm)
            <tr>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">HARI {{ $sm['day'] }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $sm['time'] }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $sm['court'] }}</td>
                <td style="border: 1px solid #000;">[{{ $sm['nomorId'] }}] {{ $sm['nomorName'] }} ({{ $sm['type'] }})</td>
                <td style="border: 1px solid #000;">{{ $sm['roundName'] }}: {{ implode(' vs ', $sm['athletes']) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="border: 1px solid #000; text-align: center; italic: true;">Belum ada data match yang dijadwalkan.</td>
            </tr>
        @endforelse
    </tbody>
</table>
