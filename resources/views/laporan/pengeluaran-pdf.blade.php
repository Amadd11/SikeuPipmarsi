<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengeluaran</title>
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
        .section-title { font-size: 12px; font-weight: bold; color: #4c1d95; margin: 20px 0 8px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; }
    </style>
</head>
<body>

@include('laporan._header', [
    'judul' => 'Laporan Rencana Pengeluaran' . ($filterBidang ? ' — ' . $filterBidang : ''),
    'periode' => $periode ?? '-',
])

<table>
    <thead>
        <tr>
            <th class="text-center" style="width: 35px;">No</th>
            <th>Bidang Kerja</th>
            <th>Kategori</th>
            <th>Nama Kegiatan</th>
            <th>Keterangan</th>
            <th class="text-right">Anggaran</th>
            <th class="text-right">Realisasi</th>
            <th class="text-right">Sisa</th>
            <th class="text-center">Serapan</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $i => $item)
            @php
                $sisa = $item->jumlah_anggaran - $item->jumlah_realisasi;
                $pct = $item->jumlah_anggaran > 0 ? round(($item->jumlah_realisasi / $item->jumlah_anggaran) * 100, 1) : 0;
            @endphp
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item->bidangKerja->nama ?? '-' }}</td>
                <td>{{ $item->kategoriPengeluaran->nama ?? '-' }}</td>
                <td>{{ $item->nama_kegiatan }}</td>
                <td>{{ Str::limit($item->keterangan, 40) ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_anggaran, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah_realisasi, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                <td class="text-center">{{ $pct }}%</td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center" style="padding: 20px; color: #999;">Tidak ada data pengeluaran.</td>
            </tr>
        @endforelse
    </tbody>

    @if ($data->count() > 0)
    <tfoot>
        <tr class="summary-row">
            <td colspan="5" class="text-right font-bold">TOTAL</td>
            <td class="text-right">Rp {{ number_format($summary['totalAnggaran'], 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($summary['totalRealisasi'], 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($summary['sisaAnggaran'], 0, ',', '.') }}</td>
            <td class="text-center">
                @php $totalPct = $summary['totalAnggaran'] > 0 ? round(($summary['totalRealisasi'] / $summary['totalAnggaran']) * 100, 1) : 0; @endphp
                {{ $totalPct }}%
            </td>
        </tr>
    </tfoot>
    @endif
</table>

@include('laporan._footer')

</body>
</html>
