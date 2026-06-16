<table>
    <thead>
        <tr>
            <th colspan="16" style="font-weight: bold; font-size: 14px; text-align: center;">REKAP LAPORAN SELURUH JUARA</th>
        </tr>
        <tr>
            <th colspan="16" style="font-size: 10px; text-align: center; color: #7f8c8d;">Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000; text-align: center;">No</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Nomor Pertandingan</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000; text-align: center;">Tipe</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Kelompok Umur</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000; text-align: center;">Gender</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000; text-align: center;">Total Peserta</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000; text-align: center;">Total Kontingen</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000; text-align: center;">Aturan (Kuning/Hijau)</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 1</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 1 Kontingen</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 2</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 2 Kontingen</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 3 Bersama 1</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 3 Bersama 1 Kontingen</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 3 Bersama 2</th>
            <th style="font-weight: bold; background-color: #f2f2f2; border: 1px solid #000000;">Juara 3 Bersama 2 Kontingen</th>
        </tr>
    </thead>
    <tbody>
        @foreach($matchData as $idx => $m)
            @php
                $rowStyle = '';
                if ($m['color'] === 'yellow') {
                    $rowStyle = 'background-color: #fffdeb;'; // Light yellow row bg for Excel
                } else {
                    $rowStyle = 'background-color: #f0fdf4;'; // Light green row bg for Excel
                }
            @endphp
            <tr>
                <td style="{{ $rowStyle }} border: 1px solid #000000; text-align: center;">{{ $idx + 1 }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000; font-weight: bold;">{{ $m['name'] }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000; text-align: center;">{{ $m['draft_type'] }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['age_group'] }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000; text-align: center;">{{ $m['gender'] }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000; text-align: center;">{{ $m['participant_count'] }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000; text-align: center;">{{ $m['contingent_count'] }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000; text-align: center; font-weight: bold; color: {{ $m['color'] === 'yellow' ? '#d35400' : '#27ae60' }};">
                    {{ $m['color'] === 'yellow' ? 'KUNING' : 'HIJAU' }}
                </td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara1']['athlete_names'] ?? '-' }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara1']['contingent_name'] ?? '-' }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara2']['athlete_names'] ?? '-' }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara2']['contingent_name'] ?? '-' }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara3']['athlete_names'] ?? '-' }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara3']['contingent_name'] ?? '-' }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara4']['athlete_names'] ?? '-' }}</td>
                <td style="{{ $rowStyle }} border: 1px solid #000000;">{{ $m['juara4']['contingent_name'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
