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

class RekapitulasiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected Collection $ringkasanPengeluaran;
    protected array $pendapatanSummary;
    protected array $pengeluaranSummary;
    protected string $periode;
    protected int $headerRows = 0;

    public function __construct(
        Collection $ringkasanPengeluaran,
        array $pendapatanSummary,
        array $pengeluaranSummary,
        string $periode
    ) {
        $this->ringkasanPengeluaran = $ringkasanPengeluaran;
        $this->pendapatanSummary    = $pendapatanSummary;
        $this->pengeluaranSummary   = $pengeluaranSummary;
        $this->periode              = $periode;
    }

    public function collection(): Collection
    {
        return $this->ringkasanPengeluaran->map(function ($bidang, $index) {
            return [
                $index + 1,
                $bidang->nama,
                $bidang->total_anggaran,
                $bidang->total_realisasi,
                $bidang->sisa_anggaran,
                $bidang->persen . '%',
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Bidang Kerja', 'Anggaran', 'Realisasi', 'Sisa', 'Serapan'];
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
                $lastCol = 'F';

                // Summary row
                $dataCount = $this->ringkasanPengeluaran->count();
                $summaryRow = $this->headerRows + 1 + 1 + $dataCount;
                $sheet->setCellValue("B{$summaryRow}", 'TOTAL');
                $sheet->setCellValue("C{$summaryRow}", $this->pengeluaranSummary['totalAnggaran']);
                $sheet->setCellValue("D{$summaryRow}", $this->pengeluaranSummary['totalRealisasi']);
                $sheet->setCellValue("E{$summaryRow}", $this->pengeluaranSummary['sisaAnggaran']);
                $totalPct = $this->pengeluaranSummary['totalAnggaran'] > 0
                    ? round(($this->pengeluaranSummary['totalRealisasi'] / $this->pengeluaranSummary['totalAnggaran']) * 100, 1) . '%'
                    : '0%';
                $sheet->setCellValue("F{$summaryRow}", $totalPct);

                $sheet->getStyle("A{$summaryRow}:{$lastCol}{$summaryRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F0FF']],
                    'borders' => ['top' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '4C1D95']]],
                ]);

                // Pendapatan summary section below
                $infoRow = $summaryRow + 2;
                $sheet->setCellValue("A{$infoRow}", 'RINGKASAN PENDAPATAN');
                $sheet->getStyle("A{$infoRow}")->getFont()->setBold(true)->setSize(11);
                $infoRow++;
                $sheet->setCellValue("A{$infoRow}", 'Total Rencana');
                $sheet->setCellValue("C{$infoRow}", $this->pendapatanSummary['totalRencana']);
                $infoRow++;
                $sheet->setCellValue("A{$infoRow}", 'Total Realisasi');
                $sheet->setCellValue("C{$infoRow}", $this->pendapatanSummary['totalRealisasi']);
                $infoRow++;
                $sheet->setCellValue("A{$infoRow}", 'Sisa Target');
                $sheet->setCellValue("C{$infoRow}", $this->pendapatanSummary['totalRencana'] - $this->pendapatanSummary['totalRealisasi']);

                // Currency format
                $dataStartRow = $this->headerRows + 2;
                $sheet->getStyle("C{$dataStartRow}:E{$infoRow}")->getNumberFormat()->setFormatCode('#,##0');

                // Borders
                $headingRow = $this->headerRows + 1;
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$summaryRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                ]);
            },
        ];
    }
}
