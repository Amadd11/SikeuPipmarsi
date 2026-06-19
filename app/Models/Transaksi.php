<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaksi extends Model
{
    use SoftDeletes;

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
        'dicatat_oleh',
        'created_by',
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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
