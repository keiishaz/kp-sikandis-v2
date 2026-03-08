<?php

namespace App\Services;

use App\Models\QrKendaraan;
use Illuminate\Support\Str;

class QrGeneratorService
{
    /**
     * Generate 9 uppercase characters unique token.
     * Guaranteed to be unique in qr_kendaraans table.
     *
     * @return string
     */
    public function generateUniqueToken(): string
    {
        do {
            // Generate 9 random letters and numbers, then uppercase it
            $token = strtoupper(Str::random(9));
            
            // Periksa apakah token sudah ada didalam DB
            $exists = QrKendaraan::where('token', $token)->exists();
        } while ($exists);

        return $token;
    }
}
