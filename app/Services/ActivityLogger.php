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
        $month = now()->format('Y_m');
        $timestamp = now()->format('Y-m-d H:i:s');
        $userName  = Auth::check() ? Auth::user()->name : 'System';

        // Bersihkan ID dari keterangan (bawaan lama controller mem-passing ID manual di $keterangan)
        $keteranganClean = preg_replace('/ID:\s*\d+\s*\|?\s*/i', '', $keterangan);
        $keteranganClean = str_replace([' | ', '|'], ', ', $keteranganClean);

        $aksiLower = strtolower($aksi);
        $kalimat = "";
        
        if (str_contains($aksiLower, 'edit') || str_contains($aksiLower, 'ubah')) {
            // contoh: Dari: A -> B
            $kalimat = "Mengubah data {$entitas} " . ($keteranganClean ?: '');
        } elseif (str_contains($aksiLower, 'tambah')) {
            $kalimat = "Menambahkan data {$entitas} baru" . ($keteranganClean ? " dengan rincian: {$keteranganClean}" : "");
        } elseif (str_contains($aksiLower, 'hapus')) {
            $kalimat = "Menghapus data {$entitas}" . ($keteranganClean ? " dengan rincian: {$keteranganClean}" : "");
        } elseif (str_contains($aksiLower, 'aktivasi') || str_contains($aksiLower, 'deaktivasi')) {
            $kalimat = "Melakukan {$aksi}" . ($keteranganClean ? " pada {$keteranganClean}" : "");
        } else {
            $kalimat = "Melakukan {$aksi} pada {$entitas}" . ($keteranganClean ? ". Rincian: {$keteranganClean}" : "");
        }
        
        $kalimat = rtrim(trim($kalimat), '.') . '.';

        $line = "[{$timestamp}] {$aksi} — User: {$userName} — Entitas: {$entitas} — {$kalimat}";

        $path = storage_path("logs/aktivitas/aktivitas_{$month}.txt");
        
        // Ensure directory exists
        $directory = dirname($path);
        if (!\Illuminate\Support\Facades\File::exists($directory)) {
            \Illuminate\Support\Facades\File::makeDirectory($directory, 0755, true);
        }

        file_put_contents($path, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
