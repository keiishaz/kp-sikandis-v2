<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubUnit extends Model
{
    protected $fillable = ['unit_id', 'nama_sub_unit'];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
