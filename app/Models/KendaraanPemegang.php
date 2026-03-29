<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KendaraanPemegang extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'source_system',
        'nip',
        'nama_pegawai',
        'jabatan_pegawai',
        'unit_pegawai',
        'pegawai_id',
        'nomor_sk',
        'tanggal_sk',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_sk'      => 'date',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'is_active'       => 'boolean',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }
}
