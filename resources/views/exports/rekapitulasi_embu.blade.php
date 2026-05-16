<table>
    <thead>
        <tr>
            <th colspan="12" style="font-weight: bold; font-size: 14pt; text-align: center;">DAFTAR KOMPILASI NILAI</th>
        </tr>
        <tr>
            <th colspan="12" style="font-weight: bold; font-size: 12pt; text-align: center;">{{ $metadata['display_name'] }}</th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center;">Babak: {{ $metadata['current_round'] }}</th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center;">
                Hari: {{ $metadata['day'] }} | Tanggal: {{ $metadata['date'] }} | Tingkat: {{ $metadata['age_group'] }} | Pool: {{ $metadata['pool'] }} | Court: {{ $metadata['court'] }}
            </th>
        </tr>
        <tr></tr> {{-- Empty row --}}
        <tr>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">NO</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000;">NAMA REGU / KONTINGEN</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">W1</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">W2</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">W3</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">W4</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">W5</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">NILAI AWAL</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">DENDA</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">NILAI AKHIR</th>
            @if ($metadata['current_round'] === 'Final')
                <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">PENYISIHAN</th>
                <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">AKUMULASI</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($registrations as $no => $item)
            @php
                $s = $item['score'];
                $athletes = $item['athletes']->pluck('name')->join(' & ');
                $contingent = $item['contingent']?->name ?? '-';
                $name = $athletes . ' (' . $contingent . ')';
            @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item['sequence_number'] ?? $no + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $name }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $s?->judge_1 ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $s?->judge_2 ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $s?->judge_3 ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $s?->judge_4 ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $s?->judge_5 ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item['nilai_awal'] }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $s?->denda ?? 0 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item['nilai_akhir'] }}</td>
                @if ($metadata['current_round'] === 'Final')
                    <td style="border: 1px solid #000000; text-align: center;">{{ isset($item['penyisihan_score']) && $item['penyisihan_score'] ? $item['penyisihan_score']->nilai_akhir : 0 }}</td>
                    <td style="border: 1px solid #000000; text-align: center;">{{ $item['accumulated_score'] }}</td>
                @endif
            </tr>
        @endforeach
        <tr></tr> {{-- Empty row --}}
        <tr>
            <th colspan="4" style="font-weight: bold; font-size: 12pt; text-align: left;">REKAP PERINGKAT - {{ strtoupper($metadata['current_round']) }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">PERINGKAT</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">NO URUT</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000;">NAMA REGU / KONTINGEN</th>
            <th style="font-weight: bold; background-color: #1e3a5f; color: #ffffff; border: 1px solid #000000; text-align: center;">NILAI AKHIR</th>
        </tr>
        @php
            $sortedForRank = $registrations->sortByDesc(function($item) use ($metadata) {
                return $metadata['current_round'] === 'Final' ? $item['accumulated_score'] : $item['nilai_akhir'];
            })->values();
        @endphp
        @foreach($sortedForRank as $index => $item)
            @php
                $athletes = $item['athletes']->pluck('name')->join(' & ');
                $contingent = $item['contingent']?->name ?? '-';
                $name = $athletes . ' (' . $contingent . ')';
            @endphp
            <tr>
                <td style="border: 1px solid #000000; text-align: center;">{{ $index + 1 }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $item['sequence_number'] ?? $index + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $name }}</td>
                <td style="border: 1px solid #000000; text-align: center;">{{ $metadata['current_round'] === 'Final' ? $item['accumulated_score'] : $item['nilai_akhir'] }}</td>
            </tr>
        @endforeach
        <tr></tr> {{-- Empty row --}}
        <tr>
            <td colspan="5" style="font-weight: bold; font-size: 11pt;">Koordinator Lapangan</td>
            <td colspan="5" style="font-weight: bold; font-size: 11pt;">Para Panitera</td>
        </tr>
        <tr>
            <td colspan="5">{{ $metadata['koordinator'] }}</td>
            <td colspan="5">1. {{ $metadata['paniteras'][0] ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="5">@if($metadata['koordinator'] === 'Drs. H. Bambang Supriyanto, M.Pd.') NIP. 19780512 200501 1 008 @endif</td>
            <td colspan="5">2. {{ $metadata['paniteras'][1] ?? '-' }}</td>
        </tr>
        @if(isset($metadata['paniteras'][2]))
        <tr>
            <td colspan="5"></td>
            <td colspan="5">3. {{ $metadata['paniteras'][2] }}</td>
        </tr>
        @endif
        @if(isset($metadata['paniteras'][3]))
        <tr>
            <td colspan="5"></td>
            <td colspan="5">4. {{ $metadata['paniteras'][3] }}</td>
        </tr>
        @endif
    </tbody>
</table>
