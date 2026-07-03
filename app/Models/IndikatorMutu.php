<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndikatorMutu extends Model
{
    protected $table = 'indikator_mutu';

    protected $fillable = [
        'kode',
        'bidang_kerja_id',
        'nama',
        'target',
        'periode',
        'status',
        'catatan',
        'urutan',
    ];

    public function bidangKerja(): BelongsTo
    {
        return $this->belongsTo(BidangKerja::class);
    }

    public function pengeluaran(): HasMany
    {
        return $this->hasMany(RencanaPengeluaran::class);
    }

    public function capaianIndikator(): HasMany
    {
        return $this->hasMany(CapaianIndikator::class);
    }

    public function auditMonitoring(): HasMany
    {
        return $this->hasMany(AuditMonitoring::class);
    }
}
