<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalPendapatanRencana' => 107000000,
            'realisasiPendapatan'    => 0,
            'totalAnggaranBelanja'   => 131100000,
            'realisasiPengeluaran'   => 0,
            'aktivitasTerbaru'       => collect(), // ganti dengan query nanti
        ]);
    }
}
