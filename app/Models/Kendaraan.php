<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function pemegangs(): HasMany
    {
        return $this->hasMany(KendaraanPemegang::class, 'kendaraan_id');
    }

    public function pemegangAktif(): HasOne
    {
        return $this->hasOne(KendaraanPemegang::class, 'kendaraan_id')->where('is_active', true);
    }

    public function aktivitas()
    {
        return $this->hasMany(KendaraanAktivitas::class, 'kendaraan_id')->orderBy('tanggal_aktivitas', 'desc')->orderBy('created_at', 'desc');
    }
}
