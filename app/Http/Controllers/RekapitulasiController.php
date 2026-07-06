<?php

namespace App\Http\Controllers;

use App\Models\RencanaPendapatan;
use App\Models\TahunAnggaran;
use App\Services\IndikatorMutuService;
use App\Services\RencanaPengeluaranService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RekapitulasiController extends Controller
{
    public function __construct(
        private readonly RencanaPengeluaranService $pengeluaranService,
        private readonly IndikatorMutuService $indikatorService,
    ) {}

    public function index(Request $request): View
    {
        // ── Tahun Anggaran ────────────────────────────────────────────────────
        $tahunAnggaranList = TahunAnggaran::query()->orderByDesc('tahun')->get();

        $tahunAnggaranId = $request->integer('tahun') ?: optional(
            TahunAnggaran::query()->where('is_aktif', true)->first()
        )->id;

        $activeTahun = $tahunAnggaranList->firstWhere('id', $tahunAnggaranId);

        // ── Ringkasan Pendapatan ──────────────────────────────────────────────
        $summaryPendapatanRaw = RencanaPendapatan::query()
            ->when($tahunAnggaranId, fn ($q) => $q->where('tahun_anggaran_id', $tahunAnggaranId))
            ->selectRaw('COALESCE(SUM(jumlah_rencana), 0) AS total_rencana, COALESCE(SUM(jumlah_realisasi), 0) AS total_realisasi')
            ->first();
        $totalRencana       = (float) ($summaryPendapatanRaw->total_rencana ?? 0);
        $totalRealisasiPend = (float) ($summaryPendapatanRaw->total_realisasi ?? 0);
        $sisaPendapatan     = $totalRencana - $totalRealisasiPend;

        // ── Ringkasan Anggaran Per Bidang (Pengeluaran) ───────────────────────
        $ringkasanPengeluaran = $this->pengeluaranService->getRingkasanAllBidang($tahunAnggaranId);

        $summaryPengeluaran = $this->pengeluaranService->getSummaryAll($tahunAnggaranId);

        // ── Ringkasan Capaian Indikator Mutu Per Bidang ───────────────────────
        $ringkasanIndikator = $this->indikatorService->getRingkasanByBidang();

        return view('rekapitulasi.index', [
            'tahunAnggaranList'    => $tahunAnggaranList,
            'activeTahun'          => $tahunAnggaranId,
            'activeTahunObj'       => $activeTahun,
            // Pendapatan
            'totalRencana'         => $totalRencana,
            'totalRealisasiPend'   => $totalRealisasiPend,
            'sisaPendapatan'       => $sisaPendapatan,
            // Pengeluaran
            'ringkasanPengeluaran' => $ringkasanPengeluaran,
            'totalAnggaran'        => $summaryPengeluaran['totalAnggaran'],
            'totalRealisasiPeng'   => $summaryPengeluaran['totalRealisasi'],
            'sisaAnggaran'         => $summaryPengeluaran['sisaAnggaran'],
            // Indikator Mutu
            'ringkasanIndikator'   => $ringkasanIndikator,
        ]);
    }
}
