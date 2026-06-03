<table>
    <thead>
        <tr>
            <th colspan="5" style="font-weight: bold; text-align: center; font-size: 14pt;">LAPORAN DAFTAR SEMUA ATLET &amp; NOMOR PERTANDINGAN</th>
        </tr>
        <tr>
            <th colspan="5"></th>
        </tr>
        <tr>
            <th colspan="5" style="font-weight: bold; font-size: 12pt; background-color: #dbeafe; border: 1px solid #000;">DAFTAR ATLET</th>
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
        @forelse($allAthletes as $a)
            @php
                $nomorWithType = [];
                foreach ($a['nomorList'] as $idx => $nomor) {
                    $nomorName = $a['nomorNameList'][$idx] ?? $nomor;
                    $nomorWithType[] = $nomorName . ' (' . $a['typeList'][$idx] . ')';
                }
            @endphp
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $no++ }}</td>
                <td style="border: 1px solid #000; font-weight: bold;">{{ strtoupper($a['nama']) }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold; @if($a['jumlahNomor'] > 1) background-color: #fee2e2; color: #b91c1c; @elseif($a['jumlahNomor'] == 1) background-color: #e0f7e8; color: #15803d; @else background-color: #f3f4f6; color: #6b7280; @endif">{{ $a['jumlahNomor'] }} nomor</td>
                <td style="border: 1px solid #000;">{{ implode(', ', $nomorWithType) }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $a['totalMuncul'] }} match</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="border: 1px solid #000; text-align: center; italic: true;">Tidak ada atlet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
