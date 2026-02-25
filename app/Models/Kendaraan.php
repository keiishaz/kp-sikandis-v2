<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kendaraan extends Model
{
    // Belum dispesifikasi kolom fillablenya, biarkan model eksisting tapi kita tambahkan relasinya.

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }
}
