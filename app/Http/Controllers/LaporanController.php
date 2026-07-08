<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    /**
     * Export Laporan Pendapatan (PDF / Excel).
     */
    public function pendapatan(Request $request)
    {
        $request->validate([
            'format'            => ['required', 'in:pdf,excel'],
            'tahun_anggaran_id' => ['nullable', 'exists:tahun_anggaran,id'],
        ]);

        return $this->reportService->exportPendapatan(
            $request->input('format'),
            $request->only(['tahun_anggaran_id'])
        );
    }

    /**
     * Export Laporan Pengeluaran (PDF / Excel).
     */
    public function pengeluaran(Request $request)
    {
        $request->validate([
            'format'            => ['required', 'in:pdf,excel'],
            'tahun_anggaran_id' => ['nullable', 'exists:tahun_anggaran,id'],
            'bidang_kerja_id'   => ['nullable', 'exists:bidang_kerja,id'],
        ]);

        return $this->reportService->exportPengeluaran(
            $request->input('format'),
            $request->only(['tahun_anggaran_id', 'bidang_kerja_id'])
        );
    }

    /**
     * Export Laporan Transaksi (PDF / Excel).
     */
    public function transaksi(Request $request)
    {
        $request->validate([
            'format'            => ['required', 'in:pdf,excel'],
            'tahun_anggaran_id' => ['nullable', 'exists:tahun_anggaran,id'],
            'bidang_kerja_id'   => ['nullable', 'exists:bidang_kerja,id'],
            'jenis'             => ['nullable', 'in:pemasukan,pengeluaran'],
            'tanggal_dari'      => ['nullable', 'date'],
            'tanggal_sampai'    => ['nullable', 'date', 'after_or_equal:tanggal_dari'],
        ]);

        return $this->reportService->exportTransaksi(
            $request->input('format'),
            $request->only(['tahun_anggaran_id', 'bidang_kerja_id', 'jenis', 'tanggal_dari', 'tanggal_sampai'])
        );
    }

    /**
     * Export Laporan Rekapitulasi (PDF / Excel).
     */
    public function rekapitulasi(Request $request)
    {
        $request->validate([
            'format'            => ['required', 'in:pdf,excel'],
            'tahun_anggaran_id' => ['nullable', 'exists:tahun_anggaran,id'],
        ]);

        return $this->reportService->exportRekapitulasi(
            $request->input('format'),
            $request->only(['tahun_anggaran_id'])
        );
    }
}
