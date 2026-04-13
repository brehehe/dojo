<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center;">LAPORAN PENDAFTARAN PER NAMA (REGISTRATION BY NAME)</th>
        </tr>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center;">KONTINGEN: {{ strtoupper($contingent->name) }}</th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr style="background-color: #cbd5e1; border: 1px solid #000;">
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 50px;">NO</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 300px;">NAMA LENGKAP</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 50px;">L/P</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 80px;">TINGKAT</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 120px;">TANGGAL LAHIR</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 80px;">STATUS</th>
            <th style="font-weight: bold; border: 1px solid #000; text-align: center; width: 250px;">JABATAN / DOJO</th>
        </tr>
    </thead>
    <tbody>
        @foreach($participants as $index => $p)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ strtoupper($p->name) }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $p->gender == 'Male' ? 'L' : ($p->gender == 'Female' ? 'P' : '') }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $p->tingkat }}</td>
                <td style="border: 1px solid #000; text-align: center;">{{ $p->birth_date ? date('d/m/Y', strtotime($p->birth_date)) : '-' }}</td>
                <td style="border: 1px solid #000; text-align: center; font-weight: bold;">{{ $p->status_code }}</td>
                <td style="border: 1px solid #000;">{{ $p->info }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
