<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    ];

    protected $casts = [
        'jumlah_anggaran' => 'decimal:2',
        'jumlah_realisasi' => 'decimal:2',
    ];

    /**
     * Accessor: Sisa Anggaran
     */
    public function getSisaAnggaranAttribute(): float
    {
        return (float) ($this->jumlah_anggaran - $this->jumlah_realisasi);
    }

    /**
     * Accessor: Persentase Realisasi
     */
    public function getPersentaseRealisasiAttribute(): float
    {
        return $this->jumlah_anggaran > 0 
            ? round(($this->jumlah_realisasi / $this->jumlah_anggaran) * 100, 1) 
            : 0;
    }

    /**
     * Accessor: Status Realisasi
     */
    public function getStatusRealisasiAttribute(): string
    {
        $persen = $this->persentase_realisasi;
        if ($persen >= 100) return 'selesai';
        if ($persen > 0) return 'berjalan';
        return 'belum';
    }

    public function details(): HasMany
    {
        return $this->hasMany(RencanaPengeluaranDetail::class, 'pengeluaran_id');
    }

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

    public function transaksi(): MorphMany
    {
        return $this->morphMany(Transaksi::class, 'transaksable');
    }
}
