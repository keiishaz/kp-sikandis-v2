<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrKendaraan extends Model
{
    protected $fillable = [
        'kendaraan_id',
        'token',
        'scan_count',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    /** Atomically increment scan counter */
    public function incrementScan(): void
    {
        $this->increment('scan_count');
    }
}
