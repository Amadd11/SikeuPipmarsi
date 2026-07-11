<?php

use App\Http\Controllers\AuditMonitoringController;
use App\Http\Controllers\BidangKerjaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndikatorMutuController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\RencanaPendapatanController;
use App\Http\Controllers\RencanaPengeluaranController;
use App\Http\Controllers\StandarTarifController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;





Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('pendapatan', RencanaPendapatanController::class)->except('show');
    Route::resource('pengeluaran', RencanaPengeluaranController::class)->except('show');
    Route::resource('transaksi', TransaksiController::class)->except('show');
    Route::resource('indikator-mutu', IndikatorMutuController::class)->except('show');
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])->name('rekapitulasi.index');
    Route::resource('audit-monitoring', AuditMonitoringController::class)->except('show');
    Route::resource('standar-tarif', StandarTarifController::class);

    // ── Laporan / Export ──────────────────────────────────────────────────
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('pendapatan', [LaporanController::class, 'pendapatan'])->name('pendapatan');
        Route::get('pengeluaran', [LaporanController::class, 'pengeluaran'])->name('pengeluaran');
        Route::get('transaksi', [LaporanController::class, 'transaksi'])->name('transaksi');
        Route::get('rekapitulasi', [LaporanController::class, 'rekapitulasi'])->name('rekapitulasi');
    });
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::resource('users', UserController::class)->except('show');
    Route::resource('bidang-kerja', BidangKerjaController::class)->except('show');
    Route::resource('kategori-pendapatan', App\Http\Controllers\KategoriPendapatanController::class)->except('show');
    Route::resource('kategori-pengeluaran', App\Http\Controllers\KategoriPengeluaranController::class)->except('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
