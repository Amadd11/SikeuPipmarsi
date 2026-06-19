<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapaianIndikator extends Model
{
    protected $table = 'capaian_indikator';

    protected $fillable = [
        'indikator_mutu_id',
        'tahun_anggaran_id',
        'status',
        'catatan',
        'updated_by',
        'updated_at_manual',
    ];

    public function indikatorMutu(): BelongsTo
    {
        return $this->belongsTo(IndikatorMutu::class);
    }

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
