<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi</title>
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
        .section-title { font-size: 12px; font-weight: bold; color: #4c1d95; margin: 25px 0 10px; padding-bottom: 4px; border-bottom: 2px solid #4c1d95; }
        .summary-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 12px 15px; margin-bottom: 20px; }
        .summary-box .item { display: inline-block; width: 30%; }
        .summary-box .label { font-size: 9px; color: #888; text-transform: uppercase; }
        .summary-box .value { font-size: 14px; font-weight: bold; color: #333; }
    </style>
</head>
<body>

@include('laporan._header', [
    'judul' => 'Laporan Rekapitulasi Keuangan',
    'periode' => $periode ?? '-',
])

{{-- Ringkasan Pendapatan --}}
<div class="section-title">A. Ringkasan Pendapatan</div>
<div class="summary-box">
    <table style="margin: 0;">
        <tr>
            <td style="border: none; width: 33%;"><span class="label">Total Rencana</span><br><span class="value">Rp {{ number_format($totalRencana, 0, ',', '.') }}</span></td>
            <td style="border: none; width: 33%;"><span class="label">Total Realisasi</span><br><span class="value">Rp {{ number_format($totalRealisasiPend, 0, ',', '.') }}</span></td>
            <td style="border: none; width: 33%;"><span class="label">Sisa Target</span><br><span class="value">Rp {{ number_format($sisaPendapatan, 0, ',', '.') }}</span></td>
        </tr>
    </table>
</div>

{{-- Ringkasan Pengeluaran Per Bidang --}}
<div class="section-title">B. Anggaran Per Bidang Kerja</div>
<table>
    <thead>
        <tr>
            <th class="text-center" style="width: 35px;">No</th>
            <th>Bidang Kerja</th>
            <th class="text-right">Anggaran</th>
            <th class="text-right">Realisasi</th>
            <th class="text-right">Sisa</th>
            <th class="text-center">Serapan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ringkasanPengeluaran as $i => $bidang)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $bidang->nama }}</td>
                <td class="text-right">Rp {{ number_format($bidang->total_anggaran, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($bidang->total_realisasi, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($bidang->sisa_anggaran, 0, ',', '.') }}</td>
                <td class="text-center">{{ $bidang->persen }}%</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="summary-row">
            <td colspan="2" class="text-right">TOTAL</td>
            <td class="text-right">Rp {{ number_format($totalAnggaran, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($totalRealisasiPeng, 0, ',', '.') }}</td>
            <td class="text-right">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</td>
            <td class="text-center">
                {{ $totalAnggaran > 0 ? round(($totalRealisasiPeng / $totalAnggaran) * 100, 1) : 0 }}%
            </td>
        </tr>
    </tfoot>
</table>

{{-- Ringkasan Indikator Mutu --}}
<div class="section-title">C. Capaian Indikator Mutu Per Bidang</div>
<table>
    <thead>
        <tr>
            <th class="text-center" style="width: 35px;">No</th>
            <th>Bidang Kerja</th>
            <th class="text-center">Total Indikator</th>
            <th class="text-center">Tercapai</th>
            <th class="text-center">Proses</th>
            <th class="text-center">Tdk Tercapai</th>
            <th class="text-center">Belum</th>
            <th class="text-center">% Tercapai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ringkasanIndikator as $i => $item)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $item['bidang'] }}</td>
                <td class="text-center">{{ $item['total'] }}</td>
                <td class="text-center">{{ $item['tercapai'] }}</td>
                <td class="text-center">{{ $item['proses'] }}</td>
                <td class="text-center">{{ $item['tidak_tercapai'] }}</td>
                <td class="text-center">{{ $item['belum'] }}</td>
                <td class="text-center">{{ $item['persen'] }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('laporan._footer')

</body>
</html>
