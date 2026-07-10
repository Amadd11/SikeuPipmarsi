<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'tahun_anggaran_id',
        'tanggal',
        'jenis',
        'uraian',
        'bidang_kerja_id',
        'transaksable_type',
        'transaksable_id',
        'jumlah',
        'nomor_bukti',
        'file_bukti',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'decimal:2',
    ];

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class);
    }

    public function bidangKerja(): BelongsTo
    {
        return $this->belongsTo(BidangKerja::class);
    }

    public function transaksable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'pemasukan'  => 'Pendapatan',
            'pengeluaran' => 'Pengeluaran',
            default  => '-',
        };
    }
}
