<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RencanaPendapatan extends Model
{
    use SoftDeletes;

    protected $table = 'pendapatan';

    protected $fillable = [
        'tahun_anggaran_id',
        'kategori_pendapatan_id',
        'nama_sumber',
        'jumlah_rencana',
        'jumlah_realisasi',
        'keterangan',
        'status',
    ];

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class);
    }

    public function kategoriPendapatan(): BelongsTo
    {
        return $this->belongsTo(KategoriPendapatan::class);
    }

    public function transaksi(): MorphMany
    {
        return $this->morphMany(Transaksi::class, 'transaksable');
    }

    public function details()
    {
        return $this->hasMany(RencanaPendapatanDetail::class, 'pendapatan_id');
    }
}
