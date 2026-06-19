<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BidangKerja extends Model
{
    protected $table = 'bidang_kerja';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'warna_hex',
        'urutan',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function indikatorMutu(): HasMany
    {
        return $this->hasMany(IndikatorMutu::class);
    }

    public function pengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
