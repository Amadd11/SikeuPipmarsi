<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAnggaran extends Model
{
    protected $table = 'tahun_anggaran';

    protected $fillable = [
        'tahun',
        'label',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function pendapatan(): HasMany
    {
        return $this->hasMany(Pendapatan::class);
    }

    public function pengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function capaianIndikator(): HasMany
    {
        return $this->hasMany(CapaianIndikator::class);
    }
}
