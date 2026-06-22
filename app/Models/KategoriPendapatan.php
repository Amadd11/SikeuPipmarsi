<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPendapatan extends Model
{
    protected $table = 'kategori_pendapatan';

    protected $fillable = [
        'nama',
    ];

    public function pendapatan(): HasMany
    {
        return $this->hasMany(RencanaPendapatan::class);
    }
}
