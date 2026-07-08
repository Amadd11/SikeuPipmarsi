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

class PendapatanExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected Collection $data;
    protected array $summary;
    protected string $periode;
    protected int $headerRows = 0;

    public function __construct(Collection $data, array $summary, string $periode)
    {
        $this->data    = $data;
        $this->summary = $summary;
        $this->periode = $periode;
    }

    public function collection(): Collection
    {
        return $this->data->map(function ($item, $index) {
            $selisih = $item->jumlah_rencana - $item->jumlah_realisasi;
            $pct = $item->jumlah_rencana > 0
                ? round(($item->jumlah_realisasi / $item->jumlah_rencana) * 100, 1) . '%'
                : '0%';

            return [
                $index + 1,
                $item->kategoriPendapatan->nama ?? '-',
                $item->nama_sumber,
                $item->keterangan ?? '-',
                $item->jumlah_rencana,
                $item->jumlah_realisasi,
                $selisih,
                $pct,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kategori', 'Nama Sumber', 'Keterangan', 'Jumlah Rencana', 'Jumlah Realisasi', 'Selisih', '% Realisasi'];
    }

    public function styles(Worksheet $sheet): array
    {
        $dataStartRow = $this->headerRows + 1; // heading row
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
                $lastCol = 'H';

                // Summary row
                $dataCount = $this->data->count();
                $summaryRow = $this->headerRows + 1 + 1 + $dataCount; // header rows + heading + data
                $sheet->setCellValue("A{$summaryRow}", '');
                $sheet->setCellValue("D{$summaryRow}", 'TOTAL');
                $sheet->setCellValue("E{$summaryRow}", $this->summary['totalRencana']);
                $sheet->setCellValue("F{$summaryRow}", $this->summary['totalRealisasi']);
                $sheet->setCellValue("G{$summaryRow}", $this->summary['totalRencana'] - $this->summary['totalRealisasi']);

                $totalPct = $this->summary['totalRencana'] > 0
                    ? round(($this->summary['totalRealisasi'] / $this->summary['totalRencana']) * 100, 1) . '%'
                    : '0%';
                $sheet->setCellValue("H{$summaryRow}", $totalPct);

                $sheet->getStyle("A{$summaryRow}:{$lastCol}{$summaryRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F0FF']],
                    'borders' => ['top' => ['borderStyle' => Border::BORDER_DOUBLE, 'color' => ['rgb' => '4C1D95']]],
                ]);

                // Format currency columns
                $dataStartRow = $this->headerRows + 2; // first data row
                $dataEndRow = $summaryRow;
                $sheet->getStyle("E{$dataStartRow}:G{$dataEndRow}")->getNumberFormat()->setFormatCode('#,##0');

                // Borders on data
                $headingRow = $this->headerRows + 1;
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$summaryRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                    ],
                ]);
            },
        ];
    }
}
