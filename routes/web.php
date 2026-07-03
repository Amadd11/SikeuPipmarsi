<?php

use App\Http\Controllers\AuditMonitoringController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndikatorMutuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RencanaPendapatanController;
use App\Http\Controllers\RencanaPengeluaranController;
use App\Http\Controllers\StandarTarifController;
use App\Http\Controllers\TransaksiController;
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
    Route::resource('audit-monitoring', AuditMonitoringController::class)->except('show');
    Route::resource('standar-tarif', StandarTarifController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
