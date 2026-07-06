<?php

namespace App\Http\Controllers;

use App\Models\AuditMonitoring;
use App\Models\BidangKerja;
use App\Models\IndikatorMutu;
use App\Models\RencanaPendapatan;
use App\Models\RencanaPengeluaran;
use App\Models\StandarTarif;
use App\Models\TahunAnggaran;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // ── Tahun Anggaran Aktif ──────────────────────────────────────────────
        $tahunAktif = TahunAnggaran::query()->where('is_aktif', true)->first();
        $tahunId    = $tahunAktif?->id;

        // ── Keuangan ──────────────────────────────────────────────────────────
        $totalPendapatanRencana = RencanaPendapatan::query()
            ->when($tahunId, fn ($q) => $q->where('tahun_anggaran_id', $tahunId))
            ->sum('jumlah_rencana');

        $totalAnggaranBelanja = RencanaPengeluaran::query()
            ->when($tahunId, fn ($q) => $q->where('tahun_anggaran_id', $tahunId))
            ->sum('jumlah_anggaran');

        $realisasiPendapatan = Transaksi::query()
            ->where('jenis', 'pemasukan')
            ->when($tahunId, fn ($q) => $q->where('tahun_anggaran_id', $tahunId))
            ->sum('jumlah');

        $realisasiPengeluaran = Transaksi::query()
            ->where('jenis', 'pengeluaran')
            ->when($tahunId, fn ($q) => $q->where('tahun_anggaran_id', $tahunId))
            ->sum('jumlah');

        $saldoKas   = $realisasiPendapatan - $realisasiPengeluaran;
        $serapan    = $totalAnggaranBelanja > 0
            ? round(($realisasiPengeluaran / $totalAnggaranBelanja) * 100, 1)
            : 0;
        $pctPendapatan = $totalPendapatanRencana > 0
            ? round(($realisasiPendapatan / $totalPendapatanRencana) * 100, 1)
            : 0;

        // ── Indikator Mutu ────────────────────────────────────────────────────
        $totalIndikator   = IndikatorMutu::query()->count();
        $tercapai         = IndikatorMutu::query()->where('status', 'tercapai')->count();
        $proses           = IndikatorMutu::query()->where('status', 'proses')->count();
        $tidakTercapai    = IndikatorMutu::query()->where('status', 'tidak tercapai')->count();
        $belum            = IndikatorMutu::query()->where('status', 'belum')->count();
        $persenTercapai   = $totalIndikator > 0 ? round(($tercapai / $totalIndikator) * 100) : 0;

        // ── Audit Monitoring ─────────────────────────────────────────────────
        $totalAudit       = AuditMonitoring::query()
            ->when($tahunId, fn ($q) => $q->where('tahun_anggaran_id', $tahunId))
            ->count();
        $auditSelesai     = AuditMonitoring::query()
            ->when($tahunId, fn ($q) => $q->where('tahun_anggaran_id', $tahunId))
            ->whereNotNull('tanggal_penyelesaian')
            ->count();

        // ── Standar Tarif ─────────────────────────────────────────────────────
        $totalStandarTarif = StandarTarif::query()->count();

        // ── Serapan per Bidang ────────────────────────────────────────────────
        $serapanBidang = BidangKerja::query()
            ->withSum(
                ['rencanaPengeluaran as total_anggaran' => fn ($q) =>
                    $tahunId ? $q->where('tahun_anggaran_id', $tahunId) : $q],
                'jumlah_anggaran'
            )
            ->withSum(
                ['rencanaPengeluaran as total_realisasi' => fn ($q) =>
                    $tahunId ? $q->where('tahun_anggaran_id', $tahunId) : $q],
                'jumlah_realisasi'
            )
            ->orderBy('urutan')
            ->get()
            ->map(function ($b) {
                $b->pct_serapan = ($b->total_anggaran ?? 0) > 0
                    ? round((($b->total_realisasi ?? 0) / $b->total_anggaran) * 100, 1)
                    : 0;
                return $b;
            });

        // ── Transaksi Terbaru ─────────────────────────────────────────────────
        $transaksiTerbaru = Transaksi::query()
            ->with('bidangKerja')
            ->when($tahunId, fn ($q) => $q->where('tahun_anggaran_id', $tahunId))
            ->latest('tanggal')
            ->limit(6)
            ->get();

        // ── Peringatan ────────────────────────────────────────────────────────
        $peringatan = collect();

        if ($tidakTercapai > 0) {
            $peringatan->push([
                'level'   => 'red',
                'icon'    => 'cancel',
                'message' => "{$tidakTercapai} indikator mutu berstatus Tidak Tercapai.",
                'link'    => route('indikator-mutu.index'),
            ]);
        }
        if ($serapan >= 90) {
            $peringatan->push([
                'level'   => 'amber',
                'icon'    => 'warning',
                'message' => "Serapan anggaran sudah mencapai {$serapan}% — mendekati batas.",
                'link'    => route('pengeluaran.index'),
            ]);
        }
        if ($totalAudit > 0 && $auditSelesai < $totalAudit) {
            $pending = $totalAudit - $auditSelesai;
            $peringatan->push([
                'level'   => 'blue',
                'icon'    => 'manage_search',
                'message' => "{$pending} audit monitoring belum diselesaikan.",
                'link'    => route('audit-monitoring.index'),
            ]);
        }

        return view('dashboard', compact(
            'tahunAktif',
            'totalPendapatanRencana',
            'totalAnggaranBelanja',
            'realisasiPendapatan',
            'realisasiPengeluaran',
            'saldoKas',
            'serapan',
            'pctPendapatan',
            'totalIndikator',
            'tercapai',
            'proses',
            'tidakTercapai',
            'belum',
            'persenTercapai',
            'totalAudit',
            'auditSelesai',
            'totalStandarTarif',
            'serapanBidang',
            'transaksiTerbaru',
            'peringatan',
        ));
    }
}
