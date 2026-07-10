<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditMonitoring extends Model
{
    use HasFactory;
    
    protected $table = 'audit_monitorings';

    protected $fillable = [
        'indikator_mutu_id',
        'tahun_anggaran_id',
        'uraian_pelaksanaan',
        'kendala',
        'faktor_pendukung',
        'perbaikan',
        'rencana_tindak_lanjut',
        'pic',
        'tanggal_penyelesaian',
    ];

    protected $casts = [
        'tanggal_penyelesaian' => 'date',
    ];

    public function indikatorMutu(): BelongsTo
    {
        return $this->belongsTo(IndikatorMutu::class);
    }

    public function tahunAnggaran(): BelongsTo
    {
        return $this->belongsTo(TahunAnggaran::class);
    }
}
