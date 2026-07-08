<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PengeluaranExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected Collection $data;
    protected array $summary;
    protected string $periode;
    protected string $filterBidang;
    protected int $headerRows = 0;

    public function __construct(Collection $data, array $summary, string $periode, string $filterBidang = '')
    {
        $this->data         = $data;
        $this->summary      = $summary;
        $this->periode      = $periode;
        $this->filterBidang = $filterBidang;
    }

    public function collection(): Collection
    {
        return $this->data->map(function ($item, $index) {
            $sisa = $item->jumlah_anggaran - $item->jumlah_realisasi;
            $pct = $item->jumlah_anggaran > 0
                ? round(($item->jumlah_realisasi / $item->jumlah_anggaran) * 100, 1) . '%'
                : '0%';

            return [
                $index + 1,
                $item->bidangKerja->nama ?? '-',
                $item->kategoriPengeluaran->nama ?? '-',
                $item->nama_kegiatan,
                $item->keterangan ?? '-',
                $item->jumlah_anggaran,
                $item->jumlah_realisasi,
                $sisa,
                $pct,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Bidang Kerja', 'Kategori', 'Nama Kegiatan', 'Keterangan', 'Anggaran', 'Realisasi', 'Sisa', 'Serapan'];
    }

    public function styles(Worksheet $sheet): array
    {
        $dataStartRow = $this->headerRows + 1;
        return [
            $dataStartRow => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4C1D95']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastCol = 'I';

                // Summary row
                $dataCount = $this->data->count();
                $summaryRow = $this->headerRows + 1 + 1 + $dataCount;
                $sheet->setCellValue("E{$summaryRow}", 'TOTAL');
                $sheet->setCellValue("F{$summaryRow}", $this->summary['totalAnggaran']);
                $sheet->setCellValue("G{$summaryRow}", $this->summary['totalRealisasi']);
                $sheet->setCellValue("H{$summaryRow}", $this->summary['sisaAnggaran']);
                $totalPct = $this->summary['totalAnggaran'] > 0
                    ? round(($this->summary['totalRealisasi'] / $this->summary['totalAnggaran']) * 100, 1) . '%'
                    : '0%';
                $sheet->setCellValue("I{$summaryRow}", $totalPct);

                $sheet->getStyle("A{$summaryRow}:{$lastCol}{$summaryRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F0FF']],
                    'borders' => ['top' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '4C1D95']]],
                ]);

                // Format currency
                $dataStartRow = $this->headerRows + 2;
                $sheet->getStyle("F{$dataStartRow}:H{$summaryRow}")->getNumberFormat()->setFormatCode('#,##0');

                // Borders
                $headingRow = $this->headerRows + 1;
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$summaryRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                ]);
            },
        ];
    }
}
