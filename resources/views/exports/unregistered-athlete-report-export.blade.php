<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold; font-size: 14px;">LAPORAN KONTINGEN &amp; ATLET KOSONG</th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center; font-weight: bold; font-size: 12px;">Distribusi Atlet per Kontingen pada setiap Nomor Pertandingan</th>
        </tr>
        <tr>
            <th colspan="3"></th>
        </tr>
    </thead>
    <tbody>
        <!-- TABEL 1: NOMOR PERTANDINGAN -->
        <tr>
            <td colspan="3" style="font-weight: bold; background-color: #3b82f6; color: #ffffff;">DAFTAR NOMOR PERTANDINGAN &amp; KONTINGEN PESERTA</td>
        </tr>
        <tr>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #f3f4f6; text-align: center;">ID</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #f3f4f6; width: 40px;">Nomor Pertandingan</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #f3f4f6; width: 50px;">Kontingen &amp; Atlet</th>
        </tr>
        @foreach($matchData as $match)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $match['id'] }}</td>
                <td style="border: 1px solid #000; font-weight: bold;">
                    {{ $match['name'] }}<br>
                    <span style="font-size: 10px; color: #4b5563;">({{ $match['age_group'] }})</span>
                </td>
                <td style="border: 1px solid #000;">
                    @if(empty($match['contingents']))
                        Belum ada peserta terdaftar
                    @else
                        @foreach($match['contingents'] as $contingentName => $athletes)
                            [{{ $contingentName }}]
                            @foreach($athletes as $athleteName)
                                - {{ $athleteName }}
                            @endforeach
                            
                        @endforeach
                    @endif
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="3"></td>
        </tr>

        <!-- TABEL 2: ATLET KOSONG -->
        <tr>
            <td colspan="3" style="font-weight: bold; background-color: #ef4444; color: #ffffff;">DAFTAR ATLET BELUM TERDAFTAR DI NOMOR PERTANDINGAN</td>
        </tr>
        <tr>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #f3f4f6; text-align: center;">No</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #f3f4f6;">Nama Atlet</th>
            <th style="border: 1px solid #000; font-weight: bold; background-color: #f3f4f6;">Kontingen</th>
        </tr>
        @foreach($unregisteredAthletes as $index => $athlete)
            <tr>
                <td style="border: 1px solid #000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000;">{{ strtoupper($athlete['name']) }}</td>
                <td style="border: 1px solid #000;">{{ $athlete['contingent'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
