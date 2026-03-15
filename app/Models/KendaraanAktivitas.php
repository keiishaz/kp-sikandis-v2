<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KendaraanAktivitas extends Model
{
    protected $table = 'kendaraan_aktivitas';

    protected $fillable = [
        'kendaraan_id',
        'judul_aktivitas',
        'deskripsi',
        'tanggal_aktivitas',
        'created_by',
        'biaya_terpakai',
    ];

    protected $casts = [
        'tanggal_aktivitas' => 'date',
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
