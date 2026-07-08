<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Identitas Instansi
    |--------------------------------------------------------------------------
    |
    | Informasi instansi yang ditampilkan pada header laporan.
    |
    */

    'instansi' => [
        'nama'   => env('LAPORAN_INSTANSI_NAMA', 'UNIVERSITAS CONTOH'),
        'unit'   => env('LAPORAN_INSTANSI_UNIT', 'Unit Kerja PIPMARSI'),
        'alamat' => env('LAPORAN_INSTANSI_ALAMAT', 'Jl. Contoh No. 1, Kota, Provinsi'),
        'logo'   => env('LAPORAN_INSTANSI_LOGO', 'images/logo-instansi.png'),
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Settings
    |--------------------------------------------------------------------------
    */

    'pdf' => [
        'paper'       => 'a4',
        'orientation' => 'landscape',
    ],

];
