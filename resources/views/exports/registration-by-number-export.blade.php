<table>
    <tr>
        <td colspan="4" style="font-weight: bold;">I. {{ $contingent->name }}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="4">II. Dengan ini kami menyatakan akan mengikuti 1.1. Kejuaraan Shorinji Kempo Antar Dojo 2024</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="4">III. Perkiraan sementara kekuatan kontingen kami akan terdiri dari</td>
    </tr>
    <tr>
        <td colspan="4" style="font-weight: bold;">{{ $totalAthletes + $totalOfficials }} peserta, dengan susunan:</td>
    </tr>
    <tr>
        <td colspan="4">{{ $totalAthletes }} atlet dan</td>
    </tr>
    <tr>
        <td colspan="4">{{ $totalOfficials }} ofisial.</td>
    </tr>
    <tr>
        <td colspan="4" style="font-weight: bold;">Akan mengikuti {{ $totalFollowed }} nomor dan kelas pertandingan,</td>
    </tr>
    <tr>
        <td colspan="4">dengan rincian:</td>
    </tr>

    @php
        $genderMap = [
            'Male' => 'Kelompok Putra',
            'Female' => 'Kelompok Putri',
            'Mix' => 'Kelompok Campuran'
        ];
    @endphp

    @foreach($genderMap as $genderKey => $genderLabel)
        @if(isset($matchNumbers[$genderKey]))
            <tr>
                <td></td>
            </tr>
            <tr style="background-color: #cbd5e1; border: 1px solid #000;">
                <td colspan="2" style="font-weight: bold;">{{ $genderLabel }}</td>
                <td style="text-align: center; font-weight: bold; border: 1px solid #000;">Ya</td>
                <td style="text-align: center; font-weight: bold; border: 1px solid #000;">Tidak</td>
            </tr>
            
            @php
                $groupedByAge = $matchNumbers[$genderKey]->groupBy('age_group_id');
            @endphp

            @foreach($groupedByAge as $ageId => $matches)
                <tr>
                    <td colspan="4" style="font-style: italic; font-weight: bold; padding-top: 5px;">{{ $ageGroups[$ageId]?->name ?? 'N/A' }}</td>
                </tr>
                @foreach($matches as $index => $match)
                    <tr>
                        <td style="text-align: center; border: 1px solid #000;">{{ $index + 1 }}</td>
                        <td style="border: 1px solid #000;">{{ $match->name }}</td>
                        <td style="text-align: center; border: 1px solid #000;">{{ in_array($match->id, $followedMatchNumberIds) ? '✓' : '' }}</td>
                        <td style="text-align: center; border: 1px solid #000;">{{ !in_array($match->id, $followedMatchNumberIds) ? '✓' : '' }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endif
    @endforeach

    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="4">Demikianlah Pendaftaran Tahap Pertama (Registration by Number) ini kami buat dengan sebenarnya.</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="text-align: center;">{{ $contingent->kab_kota ?? 'Surabaya' }}, {{ date('d F Y') }}</td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="text-align: center;">{{ $contingent->name }}</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="text-align: center;">Ketua Umum,</td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td colspan="2" style="text-align: center; font-weight: bold; text-decoration: underline;">Nama : {{ $contingent->leader_name }}</td>
    </tr>
</table>
