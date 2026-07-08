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

class TransaksiExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected Collection $data;
    protected string $periode;
    protected string $filterInfo;
    protected float $totalPemasukan;
    protected float $totalPengeluaran;
    protected int $headerRows = 0;

    public function __construct(Collection $data, string $periode, string $filterInfo = '')
    {
        $this->data             = $data;
        $this->periode          = $periode;
        $this->filterInfo       = $filterInfo;
        $this->totalPemasukan   = $data->where('jenis', 'pemasukan')->sum('jumlah');
        $this->totalPengeluaran = $data->where('jenis', 'pengeluaran')->sum('jumlah');
    }

    public function collection(): Collection
    {
        return $this->data->map(function ($item, $index) {
            return [
                $index + 1,
                $item->kode_transaksi,
                $item->tanggal?->format('d/m/Y'),
                $item->jenis === 'pemasukan' ? 'Masuk' : 'Keluar',
                $item->uraian,
                $item->bidangKerja->nama ?? '-',
                $item->nomor_bukti ?? '-',
                $item->jumlah,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kode Transaksi', 'Tanggal', 'Jenis', 'Uraian', 'Bidang Kerja', 'No. Bukti', 'Jumlah'];
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
                $lastCol = 'H';

                // Summary rows
                $dataCount = $this->data->count();
                $row = $this->headerRows + 1 + 1 + $dataCount;

                $summaryData = [
                    ['Total Pemasukan', $this->totalPemasukan],
                    ['Total Pengeluaran', $this->totalPengeluaran],
                    ['Saldo', $this->totalPemasukan - $this->totalPengeluaran],
                ];

                foreach ($summaryData as $s) {
                    $sheet->setCellValue("G{$row}", $s[0]);
                    $sheet->setCellValue("H{$row}", $s[1]);
                    $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F3F0FF']],
                        'borders' => ['top' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4C1D95']]],
                    ]);
                    $row++;
                }

                // Currency format
                $dataStartRow = $this->headerRows + 2;
                $sheet->getStyle("H{$dataStartRow}:H{$row}")->getNumberFormat()->setFormatCode('#,##0');

                // Borders
                $headingRow = $this->headerRows + 1;
                $sheet->getStyle("A{$headingRow}:{$lastCol}" . ($row - 1))->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                ]);
            },
        ];
    }
}
