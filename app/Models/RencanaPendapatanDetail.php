<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RencanaPendapatanDetail extends Model
{
    protected $table = 'pendapatan_detail';

    protected $fillable = [
        'pendapatan_id',
        'uraian',
        'satuan',
        'jumlah',
        'kuantitas',
        'total',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'total'  => 'decimal:2',
    ];

    public function pendapatan(): BelongsTo
    {
        return $this->belongsTo(RencanaPendapatan::class, 'pendapatan_id');
    }

    protected static function booted()
    {
        static::saving(function ($detail) {
            $detail->total = $detail->jumlah * $detail->kuantitas;
        });
    }
}
