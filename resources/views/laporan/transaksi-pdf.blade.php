<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
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
        .badge-masuk { background: #d1fae5; color: #065f46; }
        .badge-keluar { background: #fee2e2; color: #991b1b; }
        .filter-info { font-size: 9px; color: #666; margin-bottom: 15px; padding: 8px 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; }
    </style>
</head>
<body>

@include('laporan._header', [
    'judul' => 'Laporan Transaksi Keuangan',
    'periode' => $periode ?? '-',
])

@if (!empty($filterInfo))
<div class="filter-info">
    <strong>Filter Aktif:</strong> {{ $filterInfo }}
</div>
@endif

<table>
    <thead>
        <tr>
            <th class="text-center" style="width: 35px;">No</th>
            <th>Kode</th>
            <th class="text-center">Tanggal</th>
            <th>Jenis</th>
            <th>Uraian</th>
            <th>Bidang Kerja</th>
            <th>Nomor Bukti</th>
            <th class="text-right">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($data as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td style="font-family: monospace; font-size: 9px;">{{ $item->kode_transaksi }}</td>
                <td class="text-center">{{ $item->tanggal?->format('d/m/Y') }}</td>
                <td>
                    <span class="badge {{ $item->jenis === 'pemasukan' ? 'badge-masuk' : 'badge-keluar' }}">
                        {{ $item->jenis === 'pemasukan' ? 'Masuk' : 'Keluar' }}
                    </span>
                </td>
                <td>{{ Str::limit($item->uraian, 45) }}</td>
                <td>{{ $item->bidangKerja->nama ?? '-' }}</td>
                <td>{{ $item->nomor_bukti ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px; color: #999;">Tidak ada data transaksi.</td>
            </tr>
        @endforelse
    </tbody>

    @if ($data->count() > 0)
    <tfoot>
        <tr class="summary-row">
            <td colspan="7" class="text-right">Total Pemasukan</td>
            <td class="text-right" style="color: #065f46;">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr class="summary-row">
            <td colspan="7" class="text-right">Total Pengeluaran</td>
            <td class="text-right" style="color: #991b1b;">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr class="summary-row">
            <td colspan="7" class="text-right">Saldo</td>
            <td class="text-right" style="color: {{ ($totalPemasukan - $totalPengeluaran) >= 0 ? '#065f46' : '#991b1b' }};">
                Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}
            </td>
        </tr>
    </tfoot>
    @endif
</table>

@include('laporan._footer')

</body>
</html>
