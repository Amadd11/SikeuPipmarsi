<?php

namespace App\Services;

use App\Exports\PendapatanExport;
use App\Exports\PengeluaranExport;
use App\Exports\RekapitulasiExport;
use App\Exports\TransaksiExport;
use App\Models\BidangKerja;
use App\Models\RencanaPendapatan;
use App\Models\TahunAnggaran;
use App\Repositories\RencanaPendapatanRepository;
use App\Repositories\RencanaPengeluaranRepository;
use App\Repositories\TransaksiRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\TblWidth;
use PhpOffice\PhpWord\Style\Font;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportService
{
    public function __construct(
        protected RencanaPendapatanRepository $pendapatanRepo,
        protected RencanaPengeluaranRepository $pengeluaranRepo,
        protected TransaksiRepository $transaksiRepo,
        protected RencanaPengeluaranService $pengeluaranService,
        protected IndikatorMutuService $indikatorService,
    ) {}

    // =========================================================================
    // PENDAPATAN
    // =========================================================================

    public function exportPendapatan(string $format, array $filters): mixed
    {
        $tahunAnggaranId = $this->resolveTahunAnggaran($filters);
        $tahun = TahunAnggaran::find($tahunAnggaranId);
        $periode = $tahun ? ($tahun->label ?? 'TA ' . $tahun->tahun) : '-';

        $data = $this->pendapatanRepo->getAll($tahunAnggaranId);
        $summaryRaw = $this->pendapatanRepo->getSummary($tahunAnggaranId);
        $summary = [
            'totalRencana'   => (float) ($summaryRaw->total_rencana ?? 0),
            'totalRealisasi' => (float) ($summaryRaw->total_realisasi ?? 0),
        ];

        return match ($format) {
            'pdf'   => $this->pendapatanPdf($data, $summary, $periode),
            'excel' => $this->pendapatanExcel($data, $summary, $periode),
            default => abort(400, 'Format tidak valid.'),
        };
    }

    private function pendapatanPdf(Collection $data, array $summary, string $periode)
    {
        $pdf = Pdf::loadView('laporan.pendapatan-pdf', compact('data', 'summary', 'periode'));
        $pdf->setPaper(config('laporan.pdf.paper'), config('laporan.pdf.orientation'));
        return $pdf->download('Laporan_Pendapatan_' . now()->format('Ymd_His') . '.pdf');
    }

    private function pendapatanExcel(Collection $data, array $summary, string $periode)
    {
        return Excel::download(
            new PendapatanExport($data, $summary, $periode),
            'Laporan_Pendapatan_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    private function pendapatanWord(Collection $data, array $summary, string $periode): BinaryFileResponse
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection(['orientation' => 'landscape']);

        $this->addWordHeader($section, 'Laporan Rencana Pendapatan', $periode);

        // Table
        $headers = ['No', 'Kategori', 'Nama Sumber', 'Keterangan', 'Jml Rencana', 'Jml Realisasi', 'Selisih', '%'];
        $table = $this->createWordTable($section, $headers);

        foreach ($data as $i => $item) {
            $selisih = $item->jumlah_rencana - $item->jumlah_realisasi;
            $pct = $item->jumlah_rencana > 0 ? round(($item->jumlah_realisasi / $item->jumlah_rencana) * 100, 1) : 0;

            $row = $table->addRow();
            $row->addCell(600)->addText($i + 1, ['size' => 9], ['alignment' => 'center']);
            $row->addCell(2000)->addText($item->kategoriPendapatan->nama ?? '-', ['size' => 9]);
            $row->addCell(2500)->addText($item->nama_sumber, ['size' => 9]);
            $row->addCell(2000)->addText($item->keterangan ?? '-', ['size' => 9]);
            $row->addCell(2000)->addText('Rp ' . number_format($item->jumlah_rencana, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(2000)->addText('Rp ' . number_format($item->jumlah_realisasi, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(2000)->addText('Rp ' . number_format($selisih, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(1000)->addText($pct . '%', ['size' => 9], ['alignment' => 'center']);
        }

        // Summary row
        $totalRow = $table->addRow();
        $totalRow->addCell(600);
        $totalRow->addCell(2000);
        $totalRow->addCell(2500);
        $totalRow->addCell(2000)->addText('TOTAL', ['bold' => true, 'size' => 9], ['alignment' => 'right']);
        $totalRow->addCell(2000)->addText('Rp ' . number_format($summary['totalRencana'], 0, ',', '.'), ['bold' => true, 'size' => 9], ['alignment' => 'right']);
        $totalRow->addCell(2000)->addText('Rp ' . number_format($summary['totalRealisasi'], 0, ',', '.'), ['bold' => true, 'size' => 9], ['alignment' => 'right']);
        $totalRow->addCell(2000)->addText('Rp ' . number_format($summary['totalRencana'] - $summary['totalRealisasi'], 0, ',', '.'), ['bold' => true, 'size' => 9], ['alignment' => 'right']);
        $totalPct = $summary['totalRencana'] > 0 ? round(($summary['totalRealisasi'] / $summary['totalRencana']) * 100, 1) : 0;
        $totalRow->addCell(1000)->addText($totalPct . '%', ['bold' => true, 'size' => 9], ['alignment' => 'center']);

        $this->addWordFooter($section);

        return $this->downloadWord($phpWord, 'Laporan_Pendapatan');
    }

    // =========================================================================
    // PENGELUARAN
    // =========================================================================

    public function exportPengeluaran(string $format, array $filters): mixed
    {
        $tahunAnggaranId = $this->resolveTahunAnggaran($filters);
        $bidangKerjaId = !empty($filters['bidang_kerja_id']) ? (int) $filters['bidang_kerja_id'] : null;
        $tahun = TahunAnggaran::find($tahunAnggaranId);
        $periode = $tahun ? ($tahun->label ?? 'TA ' . $tahun->tahun) : '-';

        $data = $this->pengeluaranRepo->getAll($tahunAnggaranId, $bidangKerjaId);
        $summaryRaw = $this->pengeluaranRepo->getSummary($tahunAnggaranId, $bidangKerjaId);
        $summary = [
            'totalAnggaran'  => (float) ($summaryRaw->total_anggaran ?? 0),
            'totalRealisasi' => (float) ($summaryRaw->total_realisasi ?? 0),
            'sisaAnggaran'   => (float) ($summaryRaw->total_anggaran ?? 0) - (float) ($summaryRaw->total_realisasi ?? 0),
        ];

        $filterBidang = $bidangKerjaId
            ? (BidangKerja::find($bidangKerjaId)?->nama ?? '')
            : '';

        return match ($format) {
            'pdf'   => $this->pengeluaranPdf($data, $summary, $periode, $filterBidang),
            'excel' => $this->pengeluaranExcel($data, $summary, $periode, $filterBidang),
            default => abort(400, 'Format tidak valid.'),
        };
    }

    private function pengeluaranPdf(Collection $data, array $summary, string $periode, string $filterBidang)
    {
        $pdf = Pdf::loadView('laporan.pengeluaran-pdf', compact('data', 'summary', 'periode', 'filterBidang'));
        $pdf->setPaper(config('laporan.pdf.paper'), config('laporan.pdf.orientation'));
        return $pdf->download('Laporan_Pengeluaran_' . now()->format('Ymd_His') . '.pdf');
    }

    private function pengeluaranExcel(Collection $data, array $summary, string $periode, string $filterBidang)
    {
        return Excel::download(
            new PengeluaranExport($data, $summary, $periode, $filterBidang),
            'Laporan_Pengeluaran_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    private function pengeluaranWord(Collection $data, array $summary, string $periode, string $filterBidang): BinaryFileResponse
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection(['orientation' => 'landscape']);

        $title = 'Laporan Rencana Pengeluaran' . ($filterBidang ? ' — ' . $filterBidang : '');
        $this->addWordHeader($section, $title, $periode);

        $headers = ['No', 'Bidang', 'Kategori', 'Kegiatan', 'Ket.', 'Anggaran', 'Realisasi', 'Sisa', '%'];
        $table = $this->createWordTable($section, $headers);

        foreach ($data as $i => $item) {
            $sisa = $item->jumlah_anggaran - $item->jumlah_realisasi;
            $pct = $item->jumlah_anggaran > 0 ? round(($item->jumlah_realisasi / $item->jumlah_anggaran) * 100, 1) : 0;

            $row = $table->addRow();
            $row->addCell(500)->addText($i + 1, ['size' => 9], ['alignment' => 'center']);
            $row->addCell(1800)->addText($item->bidangKerja->nama ?? '-', ['size' => 9]);
            $row->addCell(1500)->addText($item->kategoriPengeluaran->nama ?? '-', ['size' => 9]);
            $row->addCell(2200)->addText($item->nama_kegiatan, ['size' => 9]);
            $row->addCell(1500)->addText(\Str::limit($item->keterangan, 30) ?? '-', ['size' => 9]);
            $row->addCell(1800)->addText('Rp ' . number_format($item->jumlah_anggaran, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(1800)->addText('Rp ' . number_format($item->jumlah_realisasi, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(1800)->addText('Rp ' . number_format($sisa, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(800)->addText($pct . '%', ['size' => 9], ['alignment' => 'center']);
        }

        $totalRow = $table->addRow();
        for ($c = 0; $c < 5; $c++) $totalRow->addCell(500);
        $totalRow->addCell(1800)->addText('Rp ' . number_format($summary['totalAnggaran'], 0, ',', '.'), ['bold' => true, 'size' => 9], ['alignment' => 'right']);
        $totalRow->addCell(1800)->addText('Rp ' . number_format($summary['totalRealisasi'], 0, ',', '.'), ['bold' => true, 'size' => 9], ['alignment' => 'right']);
        $totalRow->addCell(1800)->addText('Rp ' . number_format($summary['sisaAnggaran'], 0, ',', '.'), ['bold' => true, 'size' => 9], ['alignment' => 'right']);

        $this->addWordFooter($section);

        return $this->downloadWord($phpWord, 'Laporan_Pengeluaran');
    }

    // =========================================================================
    // TRANSAKSI
    // =========================================================================

    public function exportTransaksi(string $format, array $filters): mixed
    {
        $tahunAnggaranId = $this->resolveTahunAnggaran($filters);
        $tahun = TahunAnggaran::find($tahunAnggaranId);
        $periode = $tahun ? ($tahun->label ?? 'TA ' . $tahun->tahun) : '-';

        $txFilters = collect($filters)->only(['jenis', 'bidang_kerja_id', 'tanggal_dari', 'tanggal_sampai'])->toArray();
        $data = $this->transaksiRepo->getAll($tahunAnggaranId, $txFilters);

        // Build filter info string
        $filterParts = [];
        if (!empty($txFilters['jenis'])) {
            $filterParts[] = 'Jenis: ' . ucfirst($txFilters['jenis']);
        }
        if (!empty($txFilters['bidang_kerja_id'])) {
            $filterParts[] = 'Bidang: ' . (BidangKerja::find($txFilters['bidang_kerja_id'])?->nama ?? '-');
        }
        if (!empty($txFilters['tanggal_dari'])) {
            $filterParts[] = 'Dari: ' . $txFilters['tanggal_dari'];
        }
        if (!empty($txFilters['tanggal_sampai'])) {
            $filterParts[] = 'Sampai: ' . $txFilters['tanggal_sampai'];
        }
        $filterInfo = implode(' | ', $filterParts);

        $totalPemasukan   = (float) $data->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = (float) $data->where('jenis', 'pengeluaran')->sum('jumlah');

        return match ($format) {
            'pdf'   => $this->transaksiPdf($data, $periode, $filterInfo, $totalPemasukan, $totalPengeluaran),
            'excel' => $this->transaksiExcel($data, $periode, $filterInfo),
            default => abort(400, 'Format tidak valid.'),
        };
    }

    private function transaksiPdf(Collection $data, string $periode, string $filterInfo, float $totalPemasukan, float $totalPengeluaran)
    {
        $pdf = Pdf::loadView('laporan.transaksi-pdf', compact('data', 'periode', 'filterInfo', 'totalPemasukan', 'totalPengeluaran'));
        $pdf->setPaper(config('laporan.pdf.paper'), config('laporan.pdf.orientation'));
        return $pdf->download('Laporan_Transaksi_' . now()->format('Ymd_His') . '.pdf');
    }

    private function transaksiExcel(Collection $data, string $periode, string $filterInfo)
    {
        return Excel::download(
            new TransaksiExport($data, $periode, $filterInfo),
            'Laporan_Transaksi_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    private function transaksiWord(Collection $data, string $periode, string $filterInfo, float $totalPemasukan, float $totalPengeluaran): BinaryFileResponse
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection(['orientation' => 'landscape']);

        $this->addWordHeader($section, 'Laporan Transaksi Keuangan', $periode);

        if ($filterInfo) {
            $section->addText('Filter: ' . $filterInfo, ['size' => 9, 'italic' => true, 'color' => '666666'], ['spaceBefore' => 0, 'spaceAfter' => 200]);
        }

        $headers = ['No', 'Kode', 'Tanggal', 'Jenis', 'Uraian', 'Bidang', 'No. Bukti', 'Jumlah'];
        $table = $this->createWordTable($section, $headers);

        foreach ($data as $i => $item) {
            $row = $table->addRow();
            $row->addCell(500)->addText($i + 1, ['size' => 9], ['alignment' => 'center']);
            $row->addCell(2000)->addText($item->kode_transaksi, ['size' => 8, 'name' => 'Courier New']);
            $row->addCell(1200)->addText($item->tanggal?->format('d/m/Y'), ['size' => 9], ['alignment' => 'center']);
            $row->addCell(1000)->addText($item->jenis === 'pemasukan' ? 'Masuk' : 'Keluar', ['size' => 9]);
            $row->addCell(2500)->addText(\Str::limit($item->uraian, 40), ['size' => 9]);
            $row->addCell(1800)->addText($item->bidangKerja->nama ?? '-', ['size' => 9]);
            $row->addCell(1500)->addText($item->nomor_bukti ?? '-', ['size' => 9]);
            $row->addCell(1800)->addText('Rp ' . number_format($item->jumlah, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
        }

        // Summary
        $section->addTextBreak(1);
        $summaryTable = $section->addTable(['borderSize' => 1, 'borderColor' => 'D1D5DB']);
        $labels = [
            ['Total Pemasukan', $totalPemasukan],
            ['Total Pengeluaran', $totalPengeluaran],
            ['Saldo', $totalPemasukan - $totalPengeluaran],
        ];
        foreach ($labels as $l) {
            $r = $summaryTable->addRow();
            $r->addCell(4000)->addText($l[0], ['bold' => true, 'size' => 10]);
            $r->addCell(3000)->addText('Rp ' . number_format($l[1], 0, ',', '.'), ['bold' => true, 'size' => 10], ['alignment' => 'right']);
        }

        $this->addWordFooter($section);

        return $this->downloadWord($phpWord, 'Laporan_Transaksi');
    }

    // =========================================================================
    // REKAPITULASI
    // =========================================================================

    public function exportRekapitulasi(string $format, array $filters): mixed
    {
        $tahunAnggaranId = $this->resolveTahunAnggaran($filters);
        $tahun = TahunAnggaran::find($tahunAnggaranId);
        $periode = $tahun ? ($tahun->label ?? 'TA ' . $tahun->tahun) : '-';

        // Pendapatan summary
        $summaryPendRaw = $this->pendapatanRepo->getSummary($tahunAnggaranId);
        $totalRencana       = (float) ($summaryPendRaw->total_rencana ?? 0);
        $totalRealisasiPend = (float) ($summaryPendRaw->total_realisasi ?? 0);
        $sisaPendapatan     = $totalRencana - $totalRealisasiPend;

        // Pengeluaran per bidang
        $ringkasanPengeluaran = $this->pengeluaranService->getRingkasanAllBidang($tahunAnggaranId);
        $summaryPeng = $this->pengeluaranService->getSummaryAll($tahunAnggaranId);

        // Indikator mutu
        $ringkasanIndikator = $this->indikatorService->getRingkasanByBidang();

        $pendapatanSummary = [
            'totalRencana'   => $totalRencana,
            'totalRealisasi' => $totalRealisasiPend,
        ];

        return match ($format) {
            'pdf' => $this->rekapitulasiPdf(
                $ringkasanPengeluaran, $ringkasanIndikator,
                $totalRencana, $totalRealisasiPend, $sisaPendapatan,
                $summaryPeng['totalAnggaran'], $summaryPeng['totalRealisasi'], $summaryPeng['sisaAnggaran'],
                $periode
            ),
            'excel' => $this->rekapitulasiExcel($ringkasanPengeluaran, $pendapatanSummary, $summaryPeng, $periode),
            default => abort(400, 'Format tidak valid.'),
        };
    }

    private function rekapitulasiPdf($ringkasanPengeluaran, $ringkasanIndikator, $totalRencana, $totalRealisasiPend, $sisaPendapatan, $totalAnggaran, $totalRealisasiPeng, $sisaAnggaran, $periode)
    {
        $pdf = Pdf::loadView('laporan.rekapitulasi-pdf', compact(
            'ringkasanPengeluaran', 'ringkasanIndikator',
            'totalRencana', 'totalRealisasiPend', 'sisaPendapatan',
            'totalAnggaran', 'totalRealisasiPeng', 'sisaAnggaran',
            'periode'
        ));
        $pdf->setPaper(config('laporan.pdf.paper'), config('laporan.pdf.orientation'));
        return $pdf->download('Laporan_Rekapitulasi_' . now()->format('Ymd_His') . '.pdf');
    }

    private function rekapitulasiExcel($ringkasanPengeluaran, $pendapatanSummary, $pengeluaranSummary, $periode)
    {
        return Excel::download(
            new RekapitulasiExport($ringkasanPengeluaran, $pendapatanSummary, $pengeluaranSummary, $periode),
            'Laporan_Rekapitulasi_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    private function rekapitulasiWord($ringkasanPengeluaran, $ringkasanIndikator, $totalRencana, $totalRealisasiPend, $sisaPendapatan, $summaryPeng, $periode): BinaryFileResponse
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection(['orientation' => 'landscape']);

        $this->addWordHeader($section, 'Laporan Rekapitulasi Keuangan', $periode);

        // A. Pendapatan
        $section->addText('A. Ringkasan Pendapatan', ['bold' => true, 'size' => 12, 'color' => '4C1D95'], ['spaceAfter' => 100]);
        $t1 = $section->addTable(['borderSize' => 1, 'borderColor' => 'D1D5DB']);
        foreach ([['Total Rencana', $totalRencana], ['Total Realisasi', $totalRealisasiPend], ['Sisa Target', $sisaPendapatan]] as $r) {
            $row = $t1->addRow();
            $row->addCell(4000)->addText($r[0], ['size' => 10]);
            $row->addCell(3000)->addText('Rp ' . number_format($r[1], 0, ',', '.'), ['size' => 10, 'bold' => true], ['alignment' => 'right']);
        }

        $section->addTextBreak(1);

        // B. Pengeluaran per bidang
        $section->addText('B. Anggaran Per Bidang Kerja', ['bold' => true, 'size' => 12, 'color' => '4C1D95'], ['spaceAfter' => 100]);
        $headers2 = ['No', 'Bidang Kerja', 'Anggaran', 'Realisasi', 'Sisa', 'Serapan'];
        $t2 = $this->createWordTable($section, $headers2);
        foreach ($ringkasanPengeluaran as $i => $bidang) {
            $row = $t2->addRow();
            $row->addCell(600)->addText($i + 1, ['size' => 9], ['alignment' => 'center']);
            $row->addCell(3000)->addText($bidang->nama, ['size' => 9]);
            $row->addCell(2200)->addText('Rp ' . number_format($bidang->total_anggaran, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(2200)->addText('Rp ' . number_format($bidang->total_realisasi, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(2200)->addText('Rp ' . number_format($bidang->sisa_anggaran, 0, ',', '.'), ['size' => 9], ['alignment' => 'right']);
            $row->addCell(1200)->addText($bidang->persen . '%', ['size' => 9], ['alignment' => 'center']);
        }

        $this->addWordFooter($section);

        return $this->downloadWord($phpWord, 'Laporan_Rekapitulasi');
    }

    // =========================================================================
    // SHARED HELPERS
    // =========================================================================

    private function resolveTahunAnggaran(array $filters): int
    {
        if (!empty($filters['tahun_anggaran_id'])) {
            return (int) $filters['tahun_anggaran_id'];
        }

        $aktif = TahunAnggaran::where('is_aktif', true)->first();
        if (!$aktif) {
            abort(422, 'Tahun anggaran aktif belum tersedia.');
        }

        return $aktif->id;
    }

    private function addWordHeader($section, string $title, string $periode): void
    {

        $section->addText(
            strtoupper($title),
            ['bold' => true, 'size' => 13],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $section->addText(
            'Periode: ' . $periode,
            ['size' => 10, 'color' => '555555'],
            ['alignment' => 'center', 'spaceAfter' => 200]
        );
    }

    private function addWordFooter($section): void
    {
        $section->addTextBreak(2);
        $section->addText(
            'Dicetak oleh: ' . (auth()->user()->name ?? '-') . '  |  Tanggal: ' . now()->translatedFormat('d F Y, H:i') . ' WIB',
            ['size' => 8, 'italic' => true, 'color' => '999999'],
            ['alignment' => 'center']
        );
    }

    private function createWordTable($section, array $headers): \PhpOffice\PhpWord\Element\Table
    {
        $table = $section->addTable([
            'borderSize' => 1,
            'borderColor' => 'D1D5DB',
            'cellMargin' => 50,
        ]);

        $headerRow = $table->addRow();
        foreach ($headers as $header) {
            $cell = $headerRow->addCell(null, [
                'bgColor' => '4C1D95',
                'valign' => 'center',
            ]);
            $cell->addText($header, [
                'bold' => true,
                'size' => 9,
                'color' => 'FFFFFF',
                'allCaps' => true,
            ], ['alignment' => 'center', 'spaceAfter' => 0]);
        }

        return $table;
    }

    private function downloadWord(PhpWord $phpWord, string $filename): BinaryFileResponse
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'word_') . '.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename . '_' . now()->format('Ymd_His') . '.docx')
            ->deleteFileAfterSend(true);
    }
}
