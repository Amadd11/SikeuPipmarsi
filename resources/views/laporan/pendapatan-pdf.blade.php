<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 10px; color: #333; padding: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background-color: #4c1d95; color: #fff; font-weight: bold; text-align: left; padding: 8px 10px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background-color: #f9fafb; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .summary-row td { background-color: #f3f0ff !important; font-weight: bold; border-top: 2px solid #4c1d95; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 8px; font-weight: bold; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>

@include('laporan._header', [
    'judul' => 'Laporan Rencana Pendapatan',
    'periode' => $periode ?? '-',
])

<table>
    <thead>
        <tr>
            <th class="text-center" style="width: 35px;">No</th>
            <th>Kategori</th>
            <th>Nama Sumber Pendapatan</th>
            <th>Keterangan</th>
            <th class="text-right">Jumlah Rencana</th>
            <th class="text-right">Jumlah Realisasi</th>
            <th class="text-right">Selisih</th>
            <th class="text-center">% Realisasi</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $i => $item)
            @php
                $selisih = $item->jumlah_rencana - $item->jumlah_realisasi;
                $pct = $item->jumlah_rencana > 0 ? round(($item->jumlah_realisasi / $item->jumlah_rencana) * 100, 1) : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->kategoriPendapatan->nama ?? '-' }}</td>
                <td>{{ $item->nama_sumber }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_rencana, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_realisasi, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($selisih, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="badge {{ $pct >= 80 ? 'badge-success' : 'badge-warning' }}">{{ $pct }}%</span>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px; color: #999;">Tidak ada data pendapatan.</td>
            </tr>
        @endforelse
    </tbody>

    @if ($data->count() > 0)
    <tfoot>
        <tr class="summary-row">
            <td colspan="4" class="text-right font-bold">TOTAL</td>
            <td class="text-right">Rp {{ number_format($summary['totalRencana'], 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($summary['totalRealisasi'], 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($summary['totalRencana'] - $summary['totalRealisasi'], 0, ',', '.') }}</td>
            <td class="text-center">
                @php $totalPct = $summary['totalRencana'] > 0 ? round(($summary['totalRealisasi'] / $summary['totalRencana']) * 100, 1) : 0; @endphp
                {{ $totalPct }}%
            </td>
        </tr>
    </tfoot>
    @endif
</table>

@include('laporan._footer')

</body>
</html>
