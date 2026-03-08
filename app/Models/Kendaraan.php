<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kendaraan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_kendaraan',
        'no_polisi',
        'tahun',
        'no_rangka',
        'no_mesin',
        'pajak',
        'jenis_penggunaan',
        'lokasi_operasional',
        'kategori_id',
        'status',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function qrKendaraan(): HasOne
    {
        return $this->hasOne(QrKendaraan::class);
    }
}
