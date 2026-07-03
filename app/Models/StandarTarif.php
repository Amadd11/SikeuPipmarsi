<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarTarif extends Model
{
    //

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'file',
    ];
}
