<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RencanaPengeluaran extends Model
{
    use SoftDeletes;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'tahun_anggaran_id',
        'bidang_kerja_id',
        'kategori_pengeluaran_id',
        'indikator_mutu_id',
        'nama_kegiatan',
        'jumlah_anggaran',
        'jumlah_realisasi',
        'keterangan',
    ];

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class);
    }

    public function bidangKerja(): BelongsTo
    {
        return $this->belongsTo(BidangKerja::class);
    }

    public function kategoriPengeluaran(): BelongsTo
    {
        return $this->belongsTo(KategoriPengeluaran::class);
    }

    public function indikatorMutu(): BelongsTo
    {
        return $this->belongsTo(IndikatorMutu::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function transaksi(): MorphMany
    {
        return $this->morphMany(Transaksi::class, 'transaksable');
    }
}
