<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RencanaPengeluaranDetail extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran_detail';

    protected $fillable = [
        'pengeluaran_id',
        'uraian',
        'satuan',
        'harga',
        'kuantitas',
        'total',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'kuantitas' => 'integer',
        'total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            $model->total = $model->harga * $model->kuantitas;
        });
    }

    public function pengeluaran(): BelongsTo
    {
        return $this->belongsTo(RencanaPengeluaran::class, 'pengeluaran_id');
    }
}
