<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; text-align: center;">LAPORAN NOMOR DAN KELAS PERTANDINGAN</th>
        </tr>
        <tr>
            <th colspan="4" style="font-weight: bold; text-align: center;">KONTINGEN:
                {{ strtoupper($contingent->name) }}</th>
        </tr>
        <tr>
            <th colspan="4"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($matchGroups as $group)
            @php
                $athletes = $group['athletes'];
                $techniques = $group['techniques'];
                $maxCount = max(count($athletes), count($techniques));
            @endphp
            <tr>
                <td colspan="4"
                    style="font-weight: bold; background-color: #f1f5f9; border: 1px solid #000; padding: 10px;">
                    NOMOR PERTANDINGAN: {{ strtoupper($group['match_name']) }}
                </td>
            </tr>
            <tr style="background-color: #cbd5e1; border: 1px solid #000;">
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">NO</th>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">NAMA LENGKAP</th>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">TINGKAT</th>
                <th style="font-weight: bold; border: 1px solid #000; text-align: center;">URUTAN KOMPOSISI YANG DIMAINKAN</th>
            </tr>
            @for ($i = 0; $i < $maxCount; $i++)
                <tr>
                    <td style="border: 1px solid #000; text-align: center; vertical-align: top;">
                        {{ $i < count($athletes) ? $i + 1 : '' }}
                    </td>
                    <td style="border: 1px solid #000; vertical-align: top;">
                        {{ $i < count($athletes) ? strtoupper($athletes[$i]->athlete_name) : '' }}
                    </td>
                    <td style="border: 1px solid #000; text-align: center; vertical-align: top;">
                        {{ $i < count($athletes) ? $athletes[$i]->tingkat : '' }}
                    </td>
                    <td style="border: 1px solid #000; vertical-align: top;">
                        {{ $i < count($techniques) ? $techniques[$i] : '' }}
                    </td>
                </tr>
            @endfor
            <tr>
                <td colspan="4" style="height: 20px;"></td>
            </tr>
        @endforeach
    </tbody>
</table>