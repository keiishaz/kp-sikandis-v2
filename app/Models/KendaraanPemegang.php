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

    /**
     * Display Helpers for names, position, and unit
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->pegawai ? $this->pegawai->nama : ($this->nama_pegawai ?? '-');
    }

    public function getDisplayJabatanAttribute(): string
    {
        return $this->pegawai ? $this->pegawai->jabatan : ($this->jabatan_pegawai ?? '-');
    }

    public function getDisplayUnitAttribute(): string
    {
        if ($this->pegawai) {
            $unit = $this->pegawai->unit?->nama_unit;
            $subUnit = $this->pegawai->subUnit?->nama_sub_unit;
            return $subUnit ? "{$unit} — {$subUnit}" : ($unit ?? '-');
        }
        return $this->unit_pegawai ?? '-';
    }

    public function getDisplayOpdAttribute(): string
    {
        if ($this->pegawai) {
            return $this->pegawai->unit?->nama_unit ?? 'Lainnya/Manual';
        }
        return $this->unit_pegawai ?? 'Lainnya/API';
    }
}
