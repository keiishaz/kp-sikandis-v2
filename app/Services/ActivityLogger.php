<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log an activity to storage/logs/aktivitas.txt.
     *
     * Format: [YYYY-MM-DD HH:MM:SS] AKSI — User: {nama} — Entitas: {entitas} — ID: {id} — Keterangan
     */
    public static function log(string $aksi, string $entitas, int|string $id, string $keterangan = ''): void
    {
        $timestamp = now()->format('Y-m-d H:i:s');
        $userName  = Auth::check() ? Auth::user()->name : 'System';

        $line = "[{$timestamp}] {$aksi} — User: {$userName} — Entitas: {$entitas} — ID: {$id}";

        if ($keterangan !== '') {
            $line .= " — {$keterangan}";
        }

        $path = storage_path('logs/aktivitas.txt');
        file_put_contents($path, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
